<?php

namespace SAB\Form\Plugin;


use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use Sunra\PhpSimple\HtmlDomParser;
use Pagekit\Util\Arr;

class FormPlugin implements EventSubscriberInterface
{

    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        $content = HtmlDomParser::str_get_html($event->getContent());

        foreach ($content->find('form[to][subject]') as $index => $form) { // find forms with to attribute

            $id = 'form'.($index+1);

            $attrs = $form->getAllAttributes();

            $adresses = Arr::extract($attrs,['to','cc','bbc','replyto']);
            $mail = Arr::extract($attrs, ['title', 'subject', 'desc', 'priority']);
            $values = [];

            foreach($form->find('input, select, textarea') as $index => $input) {

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

            $form->find('button[type!=button]',0)->setAttribute('v-show','status == 0');
            $form->find('*[success]',0)->setAttribute('v-show', 'status == 2');
            $form->find('*[error]',0)->setAttribute('v-show', 'status == 3');

            $html = HtmlDomParser::file_get_html(App::locator()->get('form:views/form.html'));
            $html->find('div', 0)->setAttribute('id', $id);
            $html->find('form',0)->setAttribute('class', $form->getAttribute('class'));
            $html->find('fieldset',0)->innertext = $form->innertext;
            $form->outertext = $html;
        }

        $event->setContent($content->save());
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