<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3de1c322df5e7f626d92b445c4039721
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3de1c322df5e7f626d92b445c4039721::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3de1c322df5e7f626d92b445c4039721::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3de1c322df5e7f626d92b445c4039721::$classMap;

        }, null, ClassLoader::class);
    }
}
