<?php

namespace App\Services;

class InventoryRack
{
    public static function normalize(?string $location): string
    {
        $location = strtoupper(trim($location ?? ''));
        $location = preg_replace('/^RAK\s*/', '', $location);
        $location = preg_replace('/^BOX\s*/', 'BX', $location);

        return preg_replace('/[\s._-]+/', '', $location);
    }

    public static function valid(string $rack): bool
    {
        return in_array($rack, config('inventory.racks', []), true);
    }
}
