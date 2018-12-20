<?php

namespace SAB\Form\Plugin;


use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Util\Arr;
use PHPHtmlParser\Dom;

class FormPlugin implements EventSubscriberInterface
{

    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        $dom = new Dom;
        $dom->load($event->getContent());

        foreach ($dom->find('form[to][subject]') as $i => &$form) { // find forms with to attribute

            $id = 'form'.($i+1);

            $attrs = $form->getAttributes();

            $adresses = Arr::extract($attrs,['to','cc','bbc','replyto']);
            $mail = Arr::extract($attrs, ['title', 'subject', 'desc', 'priority']);
            $values = [];

            foreach($form->find('input[name], select[name], textarea[name]') as $j => $input) {

                if ($input->tag == 'select' && $input->multiple) { // TODO currently setting multiple times default
                    $values[$input->name] = [];
                }
                else if ($input->type == 'checkbox' && array_key_exists($input->name, $values)) {
                    $values[$input->name] = [];
                }
                else {
                    $values[$input->name] = null;
                }

                if ($input->type == 'file') {
                    $input->setAttribute("v-el:$input->name", '');
                    $input->setAttribute('@change', "files('$input->name')");
                }
                else {
                    $input->setAttribute('v-model', 'values.'.$input->name);
                }
            }

            App::view()->script('forms');
            App::view()->style('form');

            App::view()->data('$forms', [
                $id => compact('mail', 'values', 'adresses')
            ]);

            // google recaptcha
            if (App::config('form')->get('recaptcha.sitekey')) {
                App::view()->script('g-recaptcha');
            }

            // submission handling
            if($form->find('input[type=submit]')->count()) {
                $form->find('input[type=submit]',0)->setAttribute('v-show','status == 0');
            }

            if ($form->find('*[success]')->count()) {
                $form->find('*[success]',0)->setAttribute('v-show', 'status == 2');
            }
            if ($form->find('*[error]')->count()) {
                $form->find('*[error]')->setAttribute('v-show', 'status == 3');
            }
            $tmpl = new Dom;
            $tmpl->load(App::locator()->get('form:views/form.html'));

            $tmpl->find('div', 0)->setAttribute('id', $id);
            $tmpl->find('form',0)->setAttribute('class', $form->getAttribute('class'));
            $tmpl->find('fieldset',0)->addChild($form);
            var_dump($tmpl->root->children[$tmpl->root->getChildren()[0]->id()]['prev']);
            // var_dump($form->id());
            // $form->getParent()->replaceChild($form->id(), $tmpl->root);
            // var_dump($form->getParent()->getTag()->name());
            // var_dump($form->countChildren());
            // $form->getChild($tmpl->root->id());
        }

        $event->setContent((string) $dom);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => ['onContentPlugins', 25],
        ];
    }
}