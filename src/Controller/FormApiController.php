<?php

namespace SAB\Form\Controller;


use Pagekit\Application as App;
use Pagekit\Util\Arr;
use ReCaptcha\ReCaptcha;

/**
 * @Route("/form")
 */
class FormApiController
{
    /**
     * @Route(methods="POST")
     * @Request({"data"}, csrf=true)
     * @Captcha(verify="true")
     */
    public function indexAction(string $data)
    {
        // try {

            list($index, $node, $mail, $values) = array_values(json_decode($data, true));

            $node = Node::find($node);

            if (!isset($mail['subject'])) $mail['subject'] = sprintf('%s - form #%u at %s ', App::config('system/site')->get('title'), $mail['i']+1, App::url()->previous());

            $msg = App::mailer()->create();
            $msg
                ->setTo($mail['to'])
                ->setSubject($mail['subject']);

            foreach ($mail as $key => $value) {
                $msg->{'set'.ucfirst($key)}($value);
            }

            if (isset($mail['cc'])) $msg->setCc($mail['cc']);
            if (isset($mail['bcc'])) $msg->setBcc($mail['bcc']);
            if (isset($mail['replyto'])) $msg->setReplyTo($mail['replyTo']);
            if (isset($mail['priority'])) $msg->setPriority($mail['priority']);

            if ($params = App::request()->files->all()) {
                foreach ($params as $key => $files) {
                    foreach ($files as $file) {
                        $msg->attachFile($file, strtoupper($key).'_'.$file->getClientOriginalName(), $file->getClientMimeType());
                        $values[$key][] = $file->getClientOriginalName();
                    }
                }
            }

            $msg->setBody(App::view('sab/form/mail.php', compact('values', 'mail', 'form')), 'text/html');

            $msg->send();

            return compact('values', 'mail', 'adresses');

        // } catch (\Exception $e) {
        //     throw new \Exception(__('Unable to send mail.'));
        // }
    }
}
