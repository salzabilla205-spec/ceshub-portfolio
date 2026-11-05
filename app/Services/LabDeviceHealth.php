<?php
namespace App\Services;

final class LabDeviceHealth
{
    public static function validate(array $input): array
    {
        $limits = ['firmware'=>[1,2147483647], 'uptime_s'=>[0,4294967295],
            'reset_reason'=>[0,32], 'rssi'=>[-127,0], 'heap'=>[0,4194304],
            'modbus'=>[0,255], 'failed_cycles'=>[0,4294967295], 'http'=>[-2000,599]];
        $out = [];
        foreach ($limits as $key => [$min,$max]) {
            $value = $input[$key] ?? null;
            if (!is_int($value) || $value < $min || $value > $max) throw new \InvalidArgumentException($key);
            $out[$key] = $value;
        }
        foreach (['sampled','pzem','light','temperature'] as $key) {
            if (!isset($input[$key]) || !is_bool($input[$key])) throw new \InvalidArgumentException($key);
            $out[$key] = $input[$key];
        }
        if (!in_array($input['mode'] ?? '', ['awake','sleep'], true)) throw new \InvalidArgumentException('mode');
        $out['mode'] = $input['mode'];
        foreach (['voltage'=>300,'current'=>1000,'power'=>300000,'irradiance'=>1000000,'temperature_c'=>100] as $key=>$max) {
            $value = $input[$key] ?? null;
            $min = $key === 'temperature_c' ? -50 : 0;
            if ($value !== null && ((!is_int($value) && !is_float($value)) || !is_finite((float)$value) || $value < $min || $value > $max)) throw new \InvalidArgumentException($key);
            $valid = $out['sampled'] && $out[match($key) {'irradiance'=>'light','temperature_c'=>'temperature',default=>'pzem'}];
            $out[$key] = $valid ? $value : null;
        }
        return $out;
    }

    public static function read(string $path): ?array
    {
        $handle = @fopen($path, 'rb');
        if (!$handle) return null;
        try {
            if (!flock($handle, LOCK_SH)) return null;
            $data = json_decode(stream_get_contents($handle, 32768), true);
            return is_array($data) && isset($data['latest'], $data['events']) ? $data : null;
        } finally { flock($handle, LOCK_UN); fclose($handle); }
    }

    public static function store(string $path, array $input): void
    {
        $latest = self::validate($input);
        $handle = fopen($path, 'c+b');
        if (!$handle) throw new \RuntimeException('Storage unavailable');
        try {
            if (!flock($handle, LOCK_EX)) throw new \RuntimeException('Storage busy');
            $old = json_decode(stream_get_contents($handle, 32768), true) ?: [];
            $events = is_array($old['events'] ?? null) ? $old['events'] : [];
            $latest['received_at'] = gmdate('c');
            $state = fn($r) => array_intersect_key($r, array_flip(['firmware','reset_reason','sampled','pzem','light','temperature','mode','http','modbus']));
            $restarted = isset($old['latest']['uptime_s']) && $latest['uptime_s'] < $old['latest']['uptime_s'];
            if (!$old || $restarted || $state($latest) !== $state($old['latest'] ?? [])) {
                array_unshift($events, array_merge($state($latest), ['received_at'=>$latest['received_at'],'uptime_s'=>$latest['uptime_s']]));
            }
            $body = json_encode(['latest'=>$latest,'events'=>array_slice($events,0,16)], JSON_THROW_ON_ERROR);
            rewind($handle);
            if (!ftruncate($handle, 0) || fwrite($handle, $body) !== strlen($body)) throw new \RuntimeException('Storage write failed');
            fflush($handle);
        } finally { flock($handle, LOCK_UN); fclose($handle); }
    }
}
