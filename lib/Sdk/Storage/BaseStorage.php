<?php

namespace Kinde\KindeSDK\Sdk\Storage;

use Kinde\KindeSDK\Sdk\Enums\StorageEnums;

class BaseStorage
{
    static $prefix = 'kinde';
    static $storage;

    static function getStorage()
    {
        if (empty(self::$storage)) {
            self::$storage = $_COOKIE['kinde'];
        }
        return self::$storage;
    }

    public static function getItem(string $key)
    {
        return request()->cookies->get(self::getKey($key));
    }

    public static function setItem(
        string $key,
        string $value,
        int $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool $secure = true,
        bool $httpOnly = false
    ) {
        $newKey = self::getKey($key);

        cookie()->queue($newKey, $value, $expires_or_options, $path, $domain, $secure, $httpOnly);
    }

    public static function removeItem(string $key)
    {
        $newKey = self::getKey($key);
        cookie()->forget($newKey);
    }

    public static function clear()
    {
        self::removeItem(StorageEnums::TOKEN);
        self::removeItem(StorageEnums::STATE);
        self::removeItem(StorageEnums::CODE_VERIFIER);
        self::removeItem(StorageEnums::USER_PROFILE);
    }

    private static function getKey($key)
    {
        return self::$prefix . '_' . $key;
    }
}
