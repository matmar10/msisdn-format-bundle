<?php

namespace Matmar10\Bundle\BitcoinAddressValidatorBundle;

use Matmar10\Bundle\BitcoinAddressValidatorBundle\DependencyInjection\Matmar10BitcoinAddressValidatorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Matmar10BitcoinAddressValidatorBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerExtension(new Matmar10BitcoinAddressValidatorExtension());
    }
}
