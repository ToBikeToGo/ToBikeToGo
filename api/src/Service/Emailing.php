<?php

namespace App\Service;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

class Emailing
{
    public function sendEmailing(array $emailUser, int $idTemplate, $token, $userId) :bool
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_API_KEY']);
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $sendSmtpEmail['to'] = array(array('email' => $emailUser[0], 'name' => 'John Doe'));
        $sendSmtpEmail['templateId'] = $idTemplate;
        $sendSmtpEmail['params'] = array('url' => $token, 'user' => $userId);
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
        return true;
    }
}