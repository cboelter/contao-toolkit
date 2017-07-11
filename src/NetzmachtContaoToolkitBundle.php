<?php

/**
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit;

use Netzmacht\Contao\Toolkit\DependencyInjection\CompilerPass\ComponentFactoryCompilePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NetzmachtContaoToolkitBundle.
 *
 * @package Netzmacht\Contao\Toolkit
 */
class NetzmachtContaoToolkitBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new ComponentFactoryCompilePass(
                'netzmacht.toolkit.component.content_element_factory',
                'netzmacht.toolkit.component.content_element_factory'
            )
        );

        $container->addCompilerPass(
            new ComponentFactoryCompilePass(
                'netzmacht.toolkit.component.frontend_module_factory',
                'netzmacht.toolkit.component.frontend_module_factory'
            )
        );
    }
}