<?php
namespace O365Integration;

class Config {
    const CLIENT_ID = '';
    const CLIENT_SECRET = '';
    const TENANT_ID = '';
    const REDIRECT_URI = 'https://ondernet.net/callback.php';
    
    const SCOPES = [
        'openid',
        'profile',
        'offline_access',
        'Mail.Read',
        'Mail.ReadWrite',
        'User.Read'
    ];
    
    // Scope'ları birleştiren fonksiyon
    public static function getScopeString() {
        return implode(' ', self::SCOPES);
    }
}


