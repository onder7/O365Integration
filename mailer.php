<?php
namespace O365Integration;

require 'vendor/autoload.php';
// require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TheNetworg\OAuth2\Client\Provider\Azure;

class O365Mailer {
    private $mail;
    private $accessToken;
    
    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
        $this->mail = new PHPMailer(true);
        
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.office365.com';
        $this->mail->SMTPAuth = true;
        $this->mail->AuthType = 'XOAUTH2';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }
    
    public function sendMail($to, $subject, $body, $from, $isHtml = false) {
        try {
            $this->mail->setOAuth(
                new OAuth(
                    [
                        'provider' => new Azure([
                            'clientId' => Config::CLIENT_ID,
                            'clientSecret' => Config::CLIENT_SECRET
                        ]),
                        'clientId' => Config::CLIENT_ID,
                        'clientSecret' => Config::CLIENT_SECRET,
                        'refreshToken' => $this->refreshToken,
                        'userName' => $from
                    ]
                )
            );
            
            $this->mail->setFrom($from);
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->isHTML($isHtml);
            
            return $this->mail->send();
        } catch (Exception $e) {
            throw new Exception('Mail gönderme hatası: ' . $this->mail->ErrorInfo);
        }
    }
}