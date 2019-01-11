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

    ],

    'events' => [

        // captcha bug fix (occurs in Pagekit v1.0.15)
        'request' => [function ($event, $request) use ($app) {
            if ($request->attributes->has('_captcha_verify') && 0 < count(Arr::filter($app->config('system/captcha')->toArray(), function ($val) { return !$val; }))) {
                $request->attributes->set('_captcha_verify', false);
            }
        }, -90],

    ]

];