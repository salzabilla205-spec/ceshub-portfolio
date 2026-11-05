<?php

return [
    // Transitional reads while existing devices continue posting to cespv.com.
    'url' => env('CES_MONITORING_SOURCE_URL'),
    // These projects receive samples in ceshub.id; others keep their source.
    'pv_local' => true,
    'hess_local' => true,
    'routes' => [
        'dashboard/smart-water-pump', 'dashboard/smart-water-pump/monitoring',
        'dashboard/smart-water-pump/readings', 'dashboard/data',
        'dashboard/chart/{data}', 'dashboard/heatmap',
        'dashboard/lab-scale', 'dashboard/lab-scale/monitoring',
        'dashboard/lab-scale/readings', 'dashboard/lab-scale/history',
        'dashboard/lab-scale/data', 'dashboard/lab-scale/chart/{data}',
        'dashboard/lab-scale/heatmap', 'dashboard/jamur', 'riset/jamur',
        'dashboard/jamur/monitoring', 'dashboard/jamur/readings',
        'dashboard/jamur/data', 'dashboard/jamur/chart/{data}', 'dashboard/jamur/export',
        'dashboard/hess', 'riset/hess', 'dashboard/hess/data',
        'dashboard/hess/chart/all', 'dashboard/hess/export',
        'riset/hess/data', 'riset/hess/chart/all', 'riset/hess/export',
        'dashboard/digital-twin', 'riset/digital-twin', 'dashboard/digital-twin/chart',
        'dashboard/{project}/readings-history', 'export/{scale}',
    ],
];
