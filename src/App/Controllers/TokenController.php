<?php
namespace App\Controllers;

use App\Services\GoogleAuthService;

class TokenController extends Controller {

    /**
     * 顯示 tokens 建立時間
     */
    public function showTokensInfo() {
        $tokenPath = GoogleAuthService::TOKEN_PATH;
        $content = file_get_contents($tokenPath);
        $tokens = json_decode($content, true);
        echo json_encode($tokens);
    }

    /**
     * 發出 oauth2 請求
     */
    public function requestAuth() {
        
//        curl_init();
//        var_dump(CURLOPT_CAINFO);
//        die();
        
        // 導向驗證頁
        $client = GoogleAuthService::oauth2Client();
        $authUrl = GoogleAuthService::getAuthUrl($client);
        header("Location: $authUrl");
    }

    /**
     * 接收驗證碼, 將 token 保存起來
     */
    public function redirectHandler() {
        // 通過驗證成功後, 保存 token
        if (!isset($_GET['code'])) {
            die('No code URL paramete present.');
        }
        $code = $_GET['code'];
        $client = GoogleAuthService::oauth2Client();
        $client->authenticate($code);
        $accessToken = GoogleAuthService::getAccessToken($client);
        $msg = '';
        if (!isset($accessToken->refresh_token)) {
            $msg .= "Google did not respond with a refresh token. You can still use the Google Contacts API, but you may to re-authorise your application in the future. \n";
            $msg .= "Access token response:\n";
            echo $msg;
            var_dump($accessToken);
            die();
        } else {
            // 保存 token
            GoogleAuthService::saveAccessToken($accessToken->refresh_token, $accessToken->created);
            $msg .= "Refresh token is: $accessToken->refresh_token \n";
            echo $msg;
            die();
        }
    }
}
