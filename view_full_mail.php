<?php
// view_full_mail.php
require 'vendor/autoload.php';
require 'config.php';
require 'mail_reader.php';

use O365Integration\O365MailReader;

session_start();

if (!isset($_GET['id'])) {
    die('Mail ID gerekli');
}

try {
    $reader = new O365MailReader($_SESSION['access_token']);
    $message = $reader->readMessage($_GET['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mail Detayı</title>
    <style>
        .mail-container { margin: 20px; padding: 20px; border: 1px solid #ddd; }
        .mail-header { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .mail-body { padding: 10px; }
        .attachment { margin: 5px; padding: 5px; background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="mail-header">
            <h2><?= htmlspecialchars($message['subject']) ?></h2>
            <p><strong>Kimden:</strong> <?= htmlspecialchars($message['from']['emailAddress']['address']) ?></p>
            <p><strong>Tarih:</strong> <?= date('d.m.Y H:i', strtotime($message['receivedDateTime'])) ?></p>
            
            <?php if (!empty($message['attachments'])): ?>
                <div class="attachments">
                    <h3>Ekler:</h3>
                    <?php foreach ($message['attachments'] as $attachment): ?>
                        <div class="attachment">
                            <?= htmlspecialchars($attachment['name']) ?>
                            (<?= number_format($attachment['size'] / 1024, 2) ?> KB)
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mail-body">
            <?php 
            // HTML içerik varsa
            if ($message['body']['contentType'] === 'html') {
                // Güvenli HTML içeriği göster
                echo strip_tags($message['body']['content'], '<p><br><b><i><u><strong><em>');
            } else {
                // Düz metin içeriği
                echo nl2br(htmlspecialchars($message['body']['content']));
            }
            ?>
        </div>
    </div>
    
    <p><a href="read_mails.php">← Listeye Geri Dön</a></p>
</body>
</html>
<?php
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>