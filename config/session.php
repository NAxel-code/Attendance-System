<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | Menentukan driver penyimpanan sesi pengguna: "file", "cookie",
    | "database", "apc", "memcached", "redis", "dynamodb", atau "array".
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Menentukan berapa lama (dalam menit) sesi dapat menganggur (idle)
    | sebelum kedaluwarsa secara otomatis.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | Enkripsi data sesi sebelum disimpan untuk lapisan keamanan ekstra.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | Lokasi file penyimpanan jika menggunakan driver 'file'.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection & Table
    |--------------------------------------------------------------------------
    |
    | Konfigurasi jika menggunakan driver 'database'.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Nama cookie sesi yang dikirimkan ke browser.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path & Domain
    |--------------------------------------------------------------------------
    |
    | Path dan domain berlakunya cookie sesi.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies (Secure)
    |--------------------------------------------------------------------------
    |
    | Jika true, cookie hanya akan dikirimkan melalui koneksi aman HTTPS.
    | Sangat disarankan bernilai true di environment production.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | HTTP-Only Cookies
    |--------------------------------------------------------------------------
    |
    | Mencegah cookie diakses oleh skrip sisi klien seperti JavaScript.
    | Membantu secara signifikan mengurangi risiko pencurian sesi akibat serangan XSS.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Menentukan kebijakan pengiriman cookie pada request lintas situs.
    | Pilihan: "lax", "strict", "none", atau null.
    | Melindungi pengguna dari serangan CSRF.
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
