<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6fe4f83bc0654a6068e53f95db0bb777
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Paymongo\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Paymongo\\' => 
        array (
            0 => __DIR__ . '/..' . '/paymongo/paymongo-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6fe4f83bc0654a6068e53f95db0bb777::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6fe4f83bc0654a6068e53f95db0bb777::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6fe4f83bc0654a6068e53f95db0bb777::$classMap;

        }, null, ClassLoader::class);
    }
}