
<?php
require 'vendor/autoload.php';
require 'config.php';
require 'auth.php';
require 'mailer.php';
require 'mail_reader.php';

use O365Integration\Config;
use O365Integration\O365Auth;
use O365Integration\O365Mailer;
use O365Integration\O365MailReader;

session_start();

try {
    $auth = new O365Auth();
    
    if (!isset($_SESSION['access_token']) || 
        (isset($_SESSION['token_expires']) && $_SESSION['token_expires'] <= time())) {
        
        if (isset($_SESSION['refresh_token'])) {
            $token = $auth->refreshToken($_SESSION['refresh_token']);
            $_SESSION['access_token'] = $token->getToken();
            $_SESSION['refresh_token'] = $token->getRefreshToken();
            $_SESSION['token_expires'] = $token->getExpires();
        } else {
            header('Location: ' . $auth->getAuthorizationUrl());
            exit;
        }
    }
    
    $accessToken = $_SESSION['access_token'];
    
    // Mail gönderme örneği
    $mailer = new O365Mailer($accessToken);
    $mailer->sendMail(
        'moakoz@corendonairlines.com',
        'Test Konusu',
        '<h1>Test mesajı içeriği</h1>',
        'moakoz@corendonairlines.com',
        true
    );
    
    // Mail okuma örneği
    $reader = new O365MailReader($accessToken);
    $messages = $reader->getInboxMessages(5);
    
    foreach ($messages['value'] as $message) {
        echo "Konu: " . $message['subject'] . "<br>";
        echo "Gönderen: " . $message['from']['emailAddress']['address'] . "<br>";
        echo "Tarih: " . $message['receivedDateTime'] . "<br><br>";
    }
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}