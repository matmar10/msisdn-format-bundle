<?php

include __DIR__ . '/../../../../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';
include __DIR__ . '/../../../../vendor/doctrine/common/lib/Doctrine/Common/Annotations/AnnotationRegistry.php';
include __DIR__ . '/../../../../vendor/autoload.php';



use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                          => array(__DIR__.'/../../../../vendor/symfony/symfony/src', __DIR__.'/../../../../vendor/bundles'),
    'Sensio'                           => __DIR__.'/../vendor/bundles',
    'Doctrine\\Common'                 => __DIR__.'/../../../../vendor/doctrine-common/lib',
    'Doctrine'                         => __DIR__.'/../../../../vendor/doctrine/lib',
    'Lmh\\Bundle\\MsisdnBundle'        => __DIR__.'/../../../../',
));

$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../../../..',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
// AnnotationRegistry::registerFile(__DIR__.'/../../../../vendor/doctrine/common/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// require __DIR__.'/../vendor/swiftmailer/lib/swift_required.php';
