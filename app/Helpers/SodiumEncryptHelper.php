<?php

namespace App\Helpers;

class SodiumEncryptHelper {
    public static function encryptString($value) {
        
        $key = base64_decode(env('SODIUM_KEY'));
        $nonce = env('SODIUM_CRYPTO_SECRETBOX_NONCEBYTES');
        // $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($value, $nonce, $key);

        return rtrim(strtr(base64_encode($nonce . $ciphertext), '+/', '-_'), '=');
    }

    public static function decryptString($value) {
        $key = base64_decode(env('SODIUM_KEY'));

        $decoded = base64_decode(strtr($value, '-_', '+/'));
        $nonce = substr($decoded, 0, env('SODIUM_CRYPTO_SECRETBOX_NONCEBYTES'));
        $ciphertext = substr($decoded, env('SODIUM_CRYPTO_SECRETBOX_NONCEBYTES'));

        return sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
    }
}