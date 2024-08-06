<?php

namespace Core\Controllers;

use Core\Views\View;
use Helpers\MailingHelper;

abstract class Controller
{
    protected function render($view, $data = [])
    {
        echo "rendering $view";
        $view = new View($view);
        echo $view->render($data);  // Echo the result
    }

    protected function sendEmail($to, $subject, $message, $from = null)
    {
        $mailer = new MailingHelper();
        $mailer->to($to)
               ->subject($subject)
               ->message($message);

        if ($from) {
            $mailer->from($from);
        }

        return $mailer->send();
    }
}