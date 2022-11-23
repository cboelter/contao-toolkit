<?php

declare(strict_types=1);

namespace spec\Netzmacht\Contao\Toolkit\Bundle\DependencyInjection\Compiler;

use Netzmacht\Contao\Toolkit\Bundle\DependencyInjection\Compiler\RepositoriesPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RepositoriesPassSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RepositoriesPass::class);
    }

    public function it_is_a_compiler_pass(): void
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    public function it_registeres_tagged_services_to_the_repository_manager(
        ContainerBuilder $container,
        Definition $definition
    ): void {
        $taggedServices = [
            'foo' => [
                ['model' => 'FooModel'],
            ],
            'bar' => [
                ['model' => 'BarModel'],
            ],
        ];

        $definition->getArgument(1)->shouldBeCalled();

        $container
            ->hasDefinition('netzmacht.contao_toolkit.repository_manager')
            ->shouldBeCalled()
            ->willReturn(true);

        $container
            ->getDefinition('netzmacht.contao_toolkit.repository_manager')
            ->willReturn($definition);

        $container
            ->findTaggedServiceIds('netzmacht.contao_toolkit.repository')
            ->shouldBeCalled()
            ->willReturn($taggedServices);

        $definition->setArgument(1, Argument::type('array'))->willReturn($definition)->shouldBeCalled();

        $this->process($container);
    }
}
