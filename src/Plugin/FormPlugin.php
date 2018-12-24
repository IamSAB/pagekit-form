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

        foreach ($content->find('form[to]') as $i => $form) { // find forms with to attribute

            $attrs = $form->getAllAttributes();

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
            App::view()->style('uikit-notify');

            $id = 'sab-form-'.($i+1);

            App::view()->data('$forms', [
                $id => compact('mail', 'values', 'adresses', 'i')
            ]);

            // google reCAPTCHA
            if (App::config('form')->get('recaptcha.sitekey')) {
                App::view()->script('g-recaptcha');
            }

            // submission handling

            if ($submit = $form->find('input[type=submit]',0)) {
                $submit->setAttribute('v-show','status == 0');
            }
            else {
                App::log()->warning(sprintf('No submit button found for %s', $id));
            }

            if ($success = $form->find('*[success]',0)) {
                $success->setAttribute('v-show', 'status == 2');
            }
            if ($error = $form->find('*[error]',0)) {
                $error->setAttribute('v-show', 'status == 3');
            }

            $form->outertext = App::view('sab/form/form.php', [
                'id' => $id,
                'class' => $form->getAttribute('class'),
                'content' => $form->innertext,
            ]);
        }

        $event->setContent($content->save());
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