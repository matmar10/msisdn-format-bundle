<?php

namespace Matmar10\Bundle\MsisdnFormatBundle;

use Matmar10\Bundle\MsisdnFormatBundle\DependencyInjection\Matmar10MsisdnFormatExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Matmar10MsisdnFormatBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        $container->registerExtension(new Matmar10MsisdnFormatExtension());
    }
}
