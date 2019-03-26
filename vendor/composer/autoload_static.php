<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7757877f34eb496fb1662e5553e01f00
{
    public static $files = array (
        'e8aa6e4b5a1db2f56ae794f1505391a8' => __DIR__ . '/..' . '/amphp/amp/lib/functions.php',
        '76cd0796156622033397994f25b0d8fc' => __DIR__ . '/..' . '/amphp/amp/lib/Internal/functions.php',
        '6cd5651c4fef5ed6b63e8d8b8ffbf3cc' => __DIR__ . '/..' . '/amphp/byte-stream/lib/functions.php',
        '8dc56fe697ca93c4b40d876df1c94584' => __DIR__ . '/..' . '/amphp/process/lib/functions.php',
        '445532134d762b3cbc25500cac266092' => __DIR__ . '/..' . '/daverandom/libdns/src/functions.php',
        '7863f327e247feb05e3be59a4fe77a6b' => __DIR__ . '/..' . '/amphp/uri/src/functions.php',
        '7ebf029ad4b246f1e3f66192b40a932f' => __DIR__ . '/..' . '/amphp/dns/lib/functions.php',
        'd4e415514e4352172d58f02433fa50e4' => __DIR__ . '/..' . '/amphp/socket/src/functions.php',
        '1c2dcb9d6851a7abaae89f9586ddd460' => __DIR__ . '/..' . '/amphp/socket/src/Internal/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spiral\\Goridge\\' => 15,
        ),
        'P' => 
        array (
            'PHPinnacle\\Buffer\\' => 18,
            'PHPinnacle\\Amridge\\' => 19,
        ),
        'L' => 
        array (
            'LibDNS\\' => 7,
        ),
        'A' => 
        array (
            'Amp\\WindowsRegistry\\' => 20,
            'Amp\\Uri\\' => 8,
            'Amp\\Socket\\' => 11,
            'Amp\\Process\\' => 12,
            'Amp\\Parser\\' => 11,
            'Amp\\Dns\\' => 8,
            'Amp\\Cache\\' => 10,
            'Amp\\ByteStream\\' => 15,
            'Amp\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spiral\\Goridge\\' => 
        array (
            0 => __DIR__ . '/..' . '/spiral/goridge/php-src',
        ),
        'PHPinnacle\\Buffer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpinnacle/buffer/src',
        ),
        'PHPinnacle\\Amridge\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'LibDNS\\' => 
        array (
            0 => __DIR__ . '/..' . '/daverandom/libdns/src',
        ),
        'Amp\\WindowsRegistry\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/windows-registry/lib',
        ),
        'Amp\\Uri\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/uri/src',
        ),
        'Amp\\Socket\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/socket/src',
        ),
        'Amp\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/process/lib',
        ),
        'Amp\\Parser\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/parser/lib',
        ),
        'Amp\\Dns\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/dns/lib',
        ),
        'Amp\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/cache/lib',
        ),
        'Amp\\ByteStream\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/byte-stream/lib',
        ),
        'Amp\\' => 
        array (
            0 => __DIR__ . '/..' . '/amphp/amp/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7757877f34eb496fb1662e5553e01f00::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7757877f34eb496fb1662e5553e01f00::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
