# PHP - Google Calendar API 串接實作

* 以 OAuth 2.0 方式，驗證 user 身分，將 auth token 儲存在 local 端
* 利用 auth token 存取 Google Calendar API

## 環境
* Apache 2.4.* (需要 mod_rewrite 模組)
* php 5.6.* (需要 php_curl & php_openssl 模組)

> Windows 環境 solution: [wamp](http://www.wampserver.com/en/)

## 部屬
1. 將 repo clone 回來
2. 安裝套件 `composer install` && `bower install`
 
## 開啟 API 存取
1. 至 [Google Clound Platform Console](https://console.cloud.google.com/) 建立 Project
2. 在 API 管理員中，啟用 `Google Calendar API`
3. 建立 OAuth 2.0 Client ID 憑證，設定接收授權碼的**已授權的重新導向 URI**
4. 下載憑證 JSON，將內容替換至 repo 目錄的 `src/config/client_secret.json`
 
## 取得 auth token
* 開啟瀏覽器，存取 `token/auth` 的 URI，例：
```
http://localhost/php-google-calendar/public/token/auth
```

## URI
 URI | Method | 敘述
 ---|---|---
`/token` | GET | 顯示 tokens 資訊
`/token/auth` | GET | 驗證取得 token 
`/calendar` | GET | 顯示 user 擁有的 calendars (view)
`/calendar/{calendarId}` | GET | 顯示 calendar 的 events (view)
`/calendar/event/create` | GET | 新增 calendar event (view)
`/calendar/event/create` | POST | 新增 calendar event (action)
`/calendar/{calendarId}/event/{eventId}/delete` | GET | 刪除 calendar event (action)

> base path 從 `public` 資料夾開始
