<?php
namespace O365Integration;

class O365MailReader {
    private $accessToken;
    
    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }
    
    public function getInboxMessages($limit = 20) {
        // Microsoft Graph API endpoint'i
        $graph_url = 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages';
        
        // Query parametreleri
        $params = [
            '$top' => $limit,
            '$orderby' => 'receivedDateTime DESC',
            '$select' => 'subject,from,receivedDateTime,bodyPreview'
        ];
        
        $url = $graph_url . '?' . http_build_query($params);
        
        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
            'Prefer: outlook.body-content-type="text"'
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode === 401) {
            throw new \Exception('Başarılı bir giriş yaptınız, gerekli izin olmadığı için mail okuyamazsınız');
        }
        
        if ($httpCode !== 200) {
            throw new \Exception('API hatası: HTTP ' . $httpCode);
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decode hatası: ' . json_last_error_msg());
        }
        
        return $data;
    }
}