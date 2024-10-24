<?php

require 'vendor/autoload.php';
require 'config.php';
require 'auth.php';
require 'mail_reader.php';

use O365Integration\Config;
use O365Integration\O365Auth;
use O365Integration\O365MailReader;

session_start();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Onder Monder ::. Office 365 Mail Okuyucu</title>
    <style>
        .mail-box { margin: 20px; padding: 20px; }
        .mail-item { border: 1px solid #ddd; margin: 10px 0; padding: 15px; }
        .nav-bar { background: #f8f9fa; padding: 10px; margin-bottom: 20px; }
        .button { padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .logout { background: #dc3545; }
    </style>
</head>
<body>
    <div class="nav-bar">
        <h2 style="margin:0; display:inline;">Onder Monder ::. Office 365 Mail Okuyucu</h2>
        <?php if (isset($_SESSION['access_token'])): ?>
            <a href="logout.php" class="button logout" style="float:right;">Çıkış Yap</a>
        <?php endif; ?>
    </div>

    <div class="mail-box">
    <?php
    try {
        $auth = new O365Auth();
        
        if (!isset($_SESSION['access_token'])) {
            echo '<p>Lütfen Office 365 hesabınıza giriş yapın</p>';
            echo '<a href="' . $auth->getAuthorizationUrl() . '" class="button">Office 365 ile Giriş Yap</a>';
            exit;
        }

        // Graph API için yeni scope'ları ekleyelim
        $reader = new O365MailReader($_SESSION['access_token']);
        
        try {
            $messages = $reader->getInboxMessages(20); // Son 20 mail
            
            if (isset($messages['value']) && count($messages['value']) > 0) {
                foreach ($messages['value'] as $message) {
                    echo "<div class='mail-item'>";
                    echo "<h3>Konu: " . htmlspecialchars($message['subject']) . "</h3>";
                    echo "<p><strong>Kimden:</strong> " . htmlspecialchars($message['from']['emailAddress']['address']) . "</p>";
                    echo "<p><strong>Tarih:</strong> " . date('d.m.Y H:i', strtotime($message['receivedDateTime'])) . "</p>";
                    
                    // Mail içeriği
                    if (isset($message['bodyPreview'])) {
                        echo "<p><strong>İçerik:</strong> " . htmlspecialchars(substr($message['bodyPreview'], 0, 200)) . "...</p>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<p>Hiç mail bulunamadı.</p>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>Mail okuma hatası: " . $e->getMessage() . "</div>";
            
            // Token süresi dolmuşsa yeniden login
            if (strpos($e->getMessage(), 'token') !== false) {
                echo "<p>Oturum süresi dolmuş. Lütfen tekrar giriş yapın.</p>";
                echo '<a href="logout.php" class="button">Yeniden Giriş Yap</a>';
            }
        }
        
    } catch (Exception $e) {
        echo "Hata: " . $e->getMessage();
    }
    ?>
    </div>
</body>
</html>