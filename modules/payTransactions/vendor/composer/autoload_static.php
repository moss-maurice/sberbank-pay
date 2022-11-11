<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbbbd011089e65718a77f59c9da57a6c7
{
    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'module\\' => 7,
            'mmaurice\\qurl\\' => 14,
            'mmaurice\\modx\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'module\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'mmaurice\\qurl\\' => 
        array (
            0 => __DIR__ . '/..' . '/mmaurice/qurl/src',
        ),
        'mmaurice\\modx\\' => 
        array (
            0 => __DIR__ . '/..' . '/mmaurice/modx-injector/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbbbd011089e65718a77f59c9da57a6c7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbbbd011089e65718a77f59c9da57a6c7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbbbd011089e65718a77f59c9da57a6c7::$classMap;

        }, null, ClassLoader::class);
    }
}