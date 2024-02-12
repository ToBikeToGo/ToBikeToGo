<?php

namespace App\Service;

use GuzzleHttp\Client;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Api\TransactionalEmailsApi;

class Emailing
{
    public function sendEmailingTemplate(array $emailUser, int $idTemplate, string $token, int $userId) :bool
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_API_KEY']);
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $sendSmtpEmail = new SendSmtpEmail();
        $sendSmtpEmail['to'] = array(array('email' => $emailUser[0], 'name' => 'John Doe'));
        $sendSmtpEmail['templateId'] = $idTemplate;
        $sendSmtpEmail['params'] = array('url' => $token, 'user' => $userId);
        try {
            $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
        return true;
    }
    public function sendNotificationEmailing(array $emailUsers, int $idTemplate, string $text): bool
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_API_KEY']);
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $to = [];

        // Boucle sur chaque utilisateur et crée une instance de SendSmtpEmailTo pour chaque utilisateur
        foreach ($emailUsers as $user) {
            $to[] = new \SendinBlue\Client\Model\SendSmtpEmailTo(['email' => $user->getEmail(), 'name' => $user->getLastname() . ' ' . $user->getFirstname()]);
        }

        $sendSmtpEmail->setTo($to);
        $sendSmtpEmail->setTemplateId($idTemplate);
        $sendSmtpEmail->setParams([
            'text' => $text
        ]);

        try {
            $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            return false;
        }

        return true;
    }



    public function sendEmailingHtml(array $emailUser, string $html, $token,int $userId) :bool
    {       $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_API_KEY']);
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $sendSmtpEmail['to'] = array(array('email' => $emailUser[0], 'name' => 'John Doe'));
        $sendSmtpEmail['htmlContent'] = $html;
        $sendSmtpEmail['params'] = array('url' => $token, 'user' => $userId);
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
        return true;

    }
}
