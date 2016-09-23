<?php

namespace App\Services;

use Google_Client;

class GoogleAuthService {

    const GOOGLE_API_SCOPES = [
        'https://www.googleapis.com/auth/calendar', // read/write access to Calendars
    ];
    const TOKEN_PATH = __DIR__ . './../../config/token.json';
    const CONFIG_PATH = __DIR__ . './../../config/client_secret.json';

    /**
     * 讀取 Google API configs
     * @return type
     */
    private static function loadConfig() {
        $contents = file_get_contents(self::CONFIG_PATH);
        $config = json_decode($contents);
        return $config;
    }

    /**
     * 讀取 token
     * @return type
     */
    private static function loadToken() {
        $content = file_get_contents(self::TOKEN_PATH);
        $tokenInfo = json_decode($content);
        $token = [
            'refreshToken' => property_exists($tokenInfo, 'token') ? $tokenInfo->token : ''
        ];
        return $token;
    }

    /**
     * Google OAUTH2 Client
     * @return Google_Client
     */
    public static function oauth2Client() {
        $config = self::loadConfig();
        $token = self::loadToken();
        $client = new Google_Client();
        $client->setScopes([self::GOOGLE_API_SCOPES]);
        $client->setClientId($config->web->client_id);
        $client->setClientSecret($config->web->client_secret);
        $client->setRedirectUri($config->web->redirect_uris[0]);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        if (isset($token['refreshToken']) && $token['refreshToken']) {
            try {
                $client->refreshToken($token['refreshToken']);
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'invalid_grant') !== FALSE) {
                    // 若 token 為 invalid_grant , 則清空 token
                    self::saveAccessToken('', '');
                    die('Error refreshing the OAuth2 token');
                }
                // 若不是 invalid_grant 的情況, 則丟出原始訊息
                die($e->getMessage());
            }
        }
        return $client;
    }

    /**
     * 取得 token
     * @param Google_Client $client
     * @return mixed
     */
    public static function getAccessToken(Google_Client $client) {
        return json_decode($client->getAccessToken());
    }

    /**
     * 將 token 保存在本地端
     * @param $string
     */
    public static function saveAccessToken($string, $timestamp) {
        $content = file_get_contents(self::TOKEN_PATH);
        $tokens = json_decode($content);
        $tokens->token = $string;
        $tokens->created = $timestamp;
        file_put_contents(self::TOKEN_PATH, json_encode($tokens));
    }

    /**
     * 傳回 oauth2 驗證網址
     * @param Google_Client $client
     * @return string
     */
    public static function getAuthUrl(Google_Client $client) {
        return $client->createAuthUrl();
    }

}
