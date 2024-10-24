<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'config.php';
require 'auth.php';

use O365Integration\Config;
use O365Integration\O365Auth;

session_start();

try {
    $auth = new O365Auth();
    
    if (isset($_GET['code'])) {
        // Authorization code'u al
        $token = $auth->getAccessToken($_GET['code']);
        
        // Token bilgilerini session'a kaydet
        $_SESSION['access_token'] = $token->getToken();
        $_SESSION['refresh_token'] = $token->getRefreshToken();
        $_SESSION['token_expires'] = $token->getExpires();
        
        // Ana sayfaya yönlendir
        header('Location: read_mails.php');
        exit;
    }
} catch (Exception $e) {
    echo "Hata oluştu: " . $e->getMessage();
}