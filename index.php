<?php

use SAB\Form\Plugin\FormPlugin;
use Pagekit\View\Event\ViewEvent;
use Pagekit\View\View;
use Pagekit\View\Asset\AssetManager;
use Pagekit\Event\Event;
use Pagekit\Util\Arr;

return [

    'name' => 'sab/form',

    'main' => function ($app) {

        $app->subscribe(
            new FormPlugin
        );

    },

    'autoload' => [
        'SAB\\Form\\' => 'src'
    ],

    'routes' => [

        '/api' => [
            'name' => '@api/form',
            'controller' => [
                'SAB\\Form\\Controller\\FormApiController'
            ]
        ]

    ]

];