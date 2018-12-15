<?php

namespace SAB\Form\Controller;


use Pagekit\Application as App;
use Pagekit\Util\Arr;

/**
 * @Route("/form")
 */
class FormApiController
{
    /**
     * @Route(methods="POST")
     * @Request({"data"}, csrf=true)
     */
    public function indexAction(string $data)
    {
        // try {

            list($mail, $adresses, $values) = array_values(json_decode($data, true));

            foreach($adresses as $key => $value) {
                $parts = explode(' ', trim($value)); // value can have multiple email seperated by whitespace
                $adresses[$key] = [];
                foreach ($parts as $index => $part) {
                    if (preg_match('/^\$([A-Za-z]+)/', $part, $matches)) {
                        $part = (array) $values[$matches[1]]; // mostly string; can be array (multiselect/checkboxes with emails)
                    }
                    $adresses[$key] = Arr::merge($adresses[$key], (array) $part);
                }
            }

            var_dump($adresses);

            foreach ($adresses as $type => $arr) {
                foreach ($arr as $i => $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        unset($adresses[$type][$i]);
                        App::log()->warning(sprintf('%s is not a valid email!', $email));
                    }
                }
            }

            $msg = App::mailer()->create();
            $msg
                ->setTo($adresses['to'])
                ->setSubject($mail['subject']);

            if (isset($adresses['cc'])) $msg->setCc($adresses['cc']);
            if (isset($adresses['bcc'])) $msg->setBcc($adresses['bcc']);
            if (isset($adresses['replyto'])) $msg->setReplyTo($adresses['replyto']);

            if ($params = App::request()->files->all()) {
                foreach ($params as $key => $files) {
                    foreach ($files as $file) {
                        $msg->attachFile($file, strtoupper($key).'_'.$file->getClientOriginalName(), $file->getClientMimeType());
                        $values[$key][] = $file->getClientOriginalName();
                    }
                }
            }

            $msg->setBody(App::view('form/mail.php', compact('values', 'mail')), 'text/html');


            $msg->send();

            return compact('values', 'mail', 'adresses');

        // } catch (\Exception $e) {
        //     throw new \Exception(__('Unable to send mail.'));
        // }
    }
}
