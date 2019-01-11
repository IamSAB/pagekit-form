<?php

namespace SAB\Form\Plugin;


use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use PHPHtmlParser\Dom;
use Pagekit\Util\Arr;

class FormPlugin implements EventSubscriberInterface
{

    const ID_PREFIX = 'sab-form';

    // /**
    //  * Content plugins callback.
    //  *
    //  * @param ContentEvent $event
    //  */
    // public function beforeSimpleContentPlugin(ContentEvent $event)
    // {
    //     $event->addPlugin('form', [$this, 'applyPlugin']);
    // }

    /**
     * Defines the plugins callback.
     *
     * @param  array $options
     * @return string
     */
    public function applyPlugin(array $options)
    {
        if (!isset($options['name'])) {
            return;
        }
        // FEATURE allow using predefined forms via plugin shortcut
    }
    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function afterMarkdownPlugin(ContentEvent $event)
    {
        $dom = new Dom;
        $dom->load($event->getContent());

        $forms = [];

        $validators = [
            'required' => true,
            'numeric' => true,
            'integer' => true,
            'float' => true,
            'alpha' => true,
            'alphanum' => true,
            'email' => true,
            'url' => true,
            'minlength' => 0,
            'maxlength' => 200,
            'length' => 50,
            'min' => 0,
            'max' => 1000,
            'pattern' => '/(.*)/'
        ];

        foreach ($dom->find('form') as $i => $form) {

            if ($send = $form->find('send', 0)) {

                if (!$send->{':to'} && !$send->to) continue;

                $send->tag->setAttributes(
                    array_merge([
                        ':status' => 'status',
                    ], $send->getAttributes())
                );

                $form->tag->setAttributes([
                    'id' => self::ID_PREFIX.$i,
                    'v-validator' => 'form',
                    '@submit.prevent' => '$broadcast(\'prepare\') | valid',
                    'v-cloak' => true
                ]);

                foreach($form->find('input[name], select[name], textarea[name]') as $j => $input) {

                    // get inputs and set default values
                    if ($input->tag == 'select' && $input->multiple) { // TODO currently setting multiple times default
                        $forms[$i][$input->name] = [];
                    }
                    else if ($input->type == 'checkbox' && isset($forms[$i][$input->name])) {
                        $forms[$i][$input->name] = [];
                    }
                    else if ($input->type != 'file') {
                        $forms[$i][$input->name] = '';
                    }

                    // set validators via attributes
                    foreach ($input->getAttributes() as $name => $value) {
                        if (isset($validators[$name])) {
                            $input->setAttribute('v-validate:'.$name, $value ? $value : $validators[$name]);
                        }
                    }

                    // set validators via type
                    foreach (['email' => 'email', 'url' => 'url'] as $type => $validator) {
                        if ($input->type == $type) {
                            $input->setAttribute('v-validate:'.$validator, true);
                        }
                    }

                    // input as model
                    if ($input->type != 'file') {
                        $input->tag->setAttributes([
                            'v-model' => 'values.'.$input->name,
                            ':disabled' => 'status > 0'
                        ]);
                    }
                    else {
                        $input->tag->setAttributes([
                            "v-el:$input->name" => '',
                            '@change' => "addFiles('$input->name')",
                            ':disabled' => 'status > 0'
                        ]);
                    }
                }

                // FEATURE add custom inputs (vue components)
                // foreach ($form->find('*[input]') as $j => $component) {
                //     $forms[$i][$component->input] = $component->array ? [] : '';
                //     $component->tag->setAttributes([
                //         "v-el:$component->name" => '',
                //         '@input' => "values[$component->input] == \$event.target.value",
                //         ':value' => "values[$component->input]",
                //         ':disabled' => 'status > 0'
                //     ]);
                // }
            }
        }

        if (count($forms)) {

            // add page to captcha routes
            App::request()->attributes->add(['_captcha_routes' => [App::request()->attributes->get('_route')]]);

            App::view()->data('$sabform', [
                'prefix' => self::ID_PREFIX,
                'node' => App::node()->id,
                'forms' => $forms
            ]);
            App::scripts('forms', 'sab/form:app/bundle/forms.js', ['vue', 'uikit-notify']);
            App::styles('uikit-notify', 'app/assets/uikit/css/components/notify.min.css', ['uikit']);

            $event->setContent($dom);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => [
                // ['beforeSimpleContentPlugin', 11],
                ['afterMarkdownPlugin', 4]
            ]
        ];
    }
}
