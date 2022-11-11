<?php

namespace module\helpers;

use \mmaurice\modx\Search;
use \mmaurice\qurl\Client;
use \module\helpers\RenderHelper;

class MailerHelper
{
    static public function send($address, $subject, $body, $copyToAdmin = false)
    {
        $search = new Search;
        $modx = $search->modx;

        $modx->loadExtension('MODxMailer');

        $modx->mail->IsHTML(true);
        $modx->mail->From = $modx->config['client_siteEmail'];
        $modx->mail->FromName = $modx->config['client_siteName'];
        $modx->mail->SMTPSecure = 'ssl';
        $modx->mail->Subject = $subject;
        $modx->mail->msgHTML($body);
        $modx->mail->ClearAllRecipients();
        $modx->mail->AddAddress($address);
        $modx->mail->SMTPDebug = 1;

        if ($copyToAdmin and isset($modx->config['client_siteEmail']) and !empty($modx->config['client_siteEmail'])) {
            $modx->mail->AddCC($modx->config['client_siteEmail'], isset($modx->config['client_siteName']) ? $modx->config['client_siteName'] : '');
        }

        if (!$modx->mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $modx->mail->ErrorInfo;

            return false;
        }

        return true;
    }

    static public function renderTemplate($view, $properties = [])
    {
        $templatePath = realpath(dirname(__FILE__) . "/../views/mails/{$view}.php");

        return RenderHelper::renderTemplate($templatePath, $properties);
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
