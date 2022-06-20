<?php

namespace Diadal\Kuda;

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

class KudaEncyption
{

    public function __construct()
    {
    }

    /**
     * RSAEncrypt
     *
     * @param  string $data
     * @param  string $publicKey
     * @return string
     */
    public function RSAEncrypt($data, $publicKey)
    {
        $private_key = PublicKeyLoader::load($publicKey)->toString('PKCS1');
        $puk_key = PublicKeyLoader::loadPrivateKey($private_key)->getPublicKey()->toString('PKCS1');
        $key = RSA::loadFormat('PKCS1', $puk_key)->withPadding(RSA::ENCRYPTION_PKCS1);
        $encryptedData = $key->encrypt($data);
        $encodedData = base64_encode($encryptedData);
        return $encodedData;
    }

    /**
     * RSADecrypt
     *
     * @param  string $data
     * @param  string $privateKey
     * @return mixed
     */
    public function RSADecrypt($data, $privateKey)
    {
        $decodedData = base64_decode($data);
        $private_key = PublicKeyLoader::load($privateKey)->toString('PKCS1');
        $key = RSA::loadFormat('PKCS1', $private_key)->withPadding(RSA::ENCRYPTION_PKCS1);
        $decryptedData = $key->decrypt($decodedData);
        return $decryptedData;
    }

    /**
     * AESEncrypt
     *
     * @param  string $text
     * @param  string $password
     * @param  string $salt
     * @return string
     */
    public static function AESEncrypt($text, $password, $salt)
    {
        $derivedKey = openssl_pbkdf2($password, $salt, 32, 1000);
        $aes = new AES('cbc');
        $aes->setPassword($password, 'pbkdf2', 'sha1', $salt, 1000, 32);
        $aes->setIV(substr($derivedKey, 0, 16));
        $result = $aes->encrypt($text);
        return base64_encode($result);
    }

    /**
     * AESDecrypt
     *
     * @param  string $text
     * @param  string $password
     * @return string
     */
    public function AESDecrypt(string $text, string $password, string $salt)
    {
        $derivedKey = openssl_pbkdf2($password, $salt, 32, 1000);
        $aes = new AES('cbc');
        $aes->setPassword($password, 'pbkdf2', 'sha1', $salt, 1000, 32);
        $aes->setIV(substr($derivedKey, 0, 16));
        $result = $aes->decrypt(base64_decode($text));
        return $result;
    }
}