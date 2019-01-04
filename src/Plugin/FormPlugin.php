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
    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
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

            if ($submit = $form->find('send', 0)) {

                if (!$submit->{':to'} && !$submit->to) continue;

                $submit->tag->setAttributes(
                    array_merge([
                        ':status' => 'status',
                    ], $submit->getAttributes())
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
                    else if ($input->type == 'checkbox' && array_key_exists($input->name, $values)) {
                        $forms[$i][$input->name] = [];
                    }
                    else {
                        $forms[$i][$input->name] = '';
                    }

                    // set validators
                    foreach ($input->getAttributes() as $key => $value) {
                        if (isset($validators[$key])) {
                            $input->setAttribute('v-validate:'.$key, $value ? $value : $validators[$key]);
                        }
                    }

                    // adapt inputs
                    if ($input->type != 'file') {
                        $input->tag->setAttributes([
                            'v-model' => 'values.'.$input->name,
                            ':disabled' => 'status > 0'
                        ]);
                    }
                    else {
                        $input->tag->setAttributes([
                            "v-el:$input->name" => '',
                            '@change' => "files('$input->name')",
                            ':disabled' => 'status > 0'
                        ]);
                    }
                }
            }
        }

        if (count($forms)) {

            App::request()->attributes->add(['_captcha_routes' => [App::request()->attributes->get('_route')]]);

            App::view()->data('$sabform', [
                'prefix' => self::ID_PREFIX,
                'node' => App::node()->id,
                'forms' => $forms
            ]);

            App::view()->script('forms');
            App::view()->style('form');
            App::view()->style('uikit-notify');

            $event->setContent($dom);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => 'onContentPlugins',
        ];
    }
}