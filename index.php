<?php

use SAB\Form\Plugin\FormPlugin;

return [

    'name' => 'form',

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

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('forms', 'form:app/bundle/forms.js', ['vue']);
        },

        'view.styles' => function ($event, $styles) {
            $styles->register('form', 'form:css/form.css');
        }

    ]

];