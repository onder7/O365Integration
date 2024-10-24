<?php
// get_mail_content.php
require 'vendor/autoload.php';
require 'config.php';
require 'mail_reader.php';

use O365Integration\O365MailReader;

session_start();

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Message ID required');
    }
    
    $reader = new O365MailReader($_SESSION['access_token']);
    $message = $reader->readMessage($_GET['id']);
    
    echo json_encode([
        'subject' => $message['subject'],
        'body' => $message['body']['content'],
        'from' => $message['from']['emailAddress']['address'],
        'date' => $message['receivedDateTime']
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>