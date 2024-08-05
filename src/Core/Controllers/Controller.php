<?php

namespace Core\Controllers;

use Core\Views\View;
use App\Helpers\MailingHelper;

abstract class Controller
{
    protected function render($view, $data = [])
    {
        $view = new View($view);
        return $view->render($data);
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