<?php
namespace O365Integration;

require 'vendor/autoload.php';
// require 'config.php';

use TheNetworg\OAuth2\Client\Provider\Azure;

class O365Auth {
    private $provider;
    
    public function __construct() {
        $this->provider = new Azure([
            'clientId' => Config::CLIENT_ID,
            'clientSecret' => Config::CLIENT_SECRET,
            'redirectUri' => Config::REDIRECT_URI,
            'tenant' => Config::TENANT_ID,
            'defaultEndPointVersion' => '2.0'
        ]);
        
        $this->provider->defaultEndPointVersion = '2.0';
    }
    
    public function getAuthorizationUrl() {
        return $this->provider->getAuthorizationUrl([
            'scope' => Config::SCOPES
        ]);
    }
    
    public function getAccessToken($code) {
        return $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
    }
    
    public function refreshToken($refreshToken) {
        return $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }
}