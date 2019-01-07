<?php

namespace SAB\Form\Controller;


use Pagekit\Application as App;
use Pagekit\Util\Arr;
use Pagekit\Site\Model\Node;

/**
 * @Route("/form")
 */
class FormApiController
{
    /**
     * @Route(methods="POST")
     * @Request({"index": "int","node": "int","mail": "array", "values": "array"}, csrf=true)
     * @Captcha(verify="true")
     */
    public function indexAction($index, $node, $mail, $values)
    {
        // try {

            $node = Node::find($node);

            if (!isset($mail['subject'])) {
                $mail['subject'] = sprintf('%s - #%u form at %s ', App::config('system/site')->get('title'), $index+1, $node->title);
            }

            $msg = App::mailer()->create();
            $msg->setSubject($mail['subject']);

            // add adresses
            foreach (['to', 'cc', 'bcc', 'replyTo'] as $key) {
                if (Arr::has($mail, $key)) {
                    if (is_array($mail[$key])) { // has multiple adresses
                        foreach ($mail[$key] as $adress) {
                            if (is_array($adress)) $msg->{'add'.ucfirst($key)}($adress[0], $adress[1]); // email with name: $value = ['email', 'name']
                            else $msg->{'add'.ucfirst($key)}($adress); // adress without name
                        }
                    }
                    else $msg->{'add'.ucfirst($key)}($mail[$key]); // single adress without name
                }
            }

            // set priority [ 1 (highest) - 5 (lowest) ]
            if (isset($mail['priority'])) {
                $msg->setPriority( (int) $mail['priority']);
            }

            // attach files
            if ($params = App::request()->files->all()) {
                foreach ($params as $key => $files) {
                    foreach ($files as $file) {
                        $msg->attachFile($file, strtoupper($key).'_'.$file->getClientOriginalName(), $file->getClientMimeType());
                        $values[$key][] = $file->getClientOriginalName();
                    }
                }
            }

            $mail['values'] = $values;
            $mail['index'] = $index;
            $mail['node'] = $node;

            // set email body
            $msgContent = App::view(
                isset($mail['tmpl']) && App::locator()->get($mail['tmpl']) ? $mail['tmpl'] : 'sab/form/content.php', $mail
            );
            $msg->setBody(App::view('sab/form/layout.php', ['content' => $msgContent]), 'text/html');

            $msg->send();

            unset($mail['index']);
            unset($mail['node']);

            return $mail;

        // } catch (\Exception $e) {
        //     throw new \Exception(__('Unable to send mail.'));
        // }
    }
}
