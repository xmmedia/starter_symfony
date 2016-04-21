<?php

// from: http://makingco.de/symfony2-assetic-cache-busting/
// and: http://stackoverflow.com/questions/17795200/symfony2-assets-versioning-by-file
// use like, where 'css' is the package name: <link href="{{ asset('css/public.css', 'css') }}" rel="stylesheet">
$container->loadFromExtension('framework', [
    'assets' => [
        'version' => time(),
        'packages' => [
            'css' => [
                // 'version' => time(),
            ],
            'js' => [
                // 'version' => time(),
            ],
            'image' => [
                // 'version' => time(),
            ],
        ],
    ],
]);