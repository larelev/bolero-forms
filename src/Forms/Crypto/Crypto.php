<?php

namespace Bolero\Forms\Crypto;

class Crypto
{
    public static function createToken(string $key = ''): string
    {
        if ($key != '') {
            $token = uniqid($key, true);
        } else {
            $token = uniqid(rand());
        }
        return base64_encode($token);
    }

    public static function createUID(): string
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

    public static function createOID(): string
    {
        return str_replace('-', '', self::createUID());
    }
}
