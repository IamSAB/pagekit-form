<?php

use SAB\Form\Plugin\FormPlugin;
use Pagekit\View\Event\ViewEvent;
use Pagekit\View\View;
use Pagekit\View\Asset\AssetManager;
use Pagekit\Event\Event;

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

    'config' => [
        'recaptcha' => [
            'sitekey' => '',
            'secret' => ''
        ]
    ],

    'events' => [

        'view.scripts' => function (Event $event, AssetManager $scripts) use ($app) {
            $scripts->register('g-recaptcha', 'https://www.google.com/recaptcha/api.js');
            $dps = ['uikit-notify', 'vue'];
            if ($app['config']->get('form')->get('recaptcha.sitekey')) $dps[] = 'g-recaptcha';
            $scripts->register('forms', 'sab/form:app/bundle/forms.js', $dps);
        },

        'view.styles' => function (Event $event, AssetManager $styles) {
            $styles->register('form', 'sab/form:css/form.css');
            $styles->register('uikit-notify', '../../../app/assets/uikit/css/components/notify.min.css');
        }

    ]

];