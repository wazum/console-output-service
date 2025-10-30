<?php

declare(strict_types=1);

namespace Wazum\ConsoleOutputService\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Wazum\ConsoleOutputService\Console\ConsoleOutputFactory;

final class DependencyInjectionTest extends TestCase
{
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();

        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../../Configuration'));
        $loader->load('Services.yaml');

        $this->container->compile();
    }

    /**
     * @test
     */
    public function consoleOutputCanBeInjectedAsService(): void
    {
        self::assertTrue($this->container->has(ConsoleOutput::class));

        $consoleOutput = $this->container->get(ConsoleOutput::class);

        self::assertInstanceOf(ConsoleOutput::class, $consoleOutput);
    }

    /**
     * @test
     */
    public function factoryServiceIsRegistered(): void
    {
        self::assertTrue($this->container->has(ConsoleOutputFactory::class));
    }

    /**
     * @test
     */
    public function consoleOutputIsCreatedByFactory(): void
    {
        $definition = $this->container->getDefinition(ConsoleOutput::class);

        self::assertNotNull($definition->getFactory());
        self::assertEquals(ConsoleOutputFactory::class, $definition->getFactory()[0]->__toString());
        self::assertEquals('create', $definition->getFactory()[1]);
    }

    /**
     * @test
     */
    public function injectedConsoleOutputWorksInEventListenerScenario(): void
    {
        /** @var ConsoleOutput $consoleOutput */
        $consoleOutput = $this->container->get(ConsoleOutput::class);

        $eventListener = new class ($consoleOutput) {
            public function __construct(private readonly ConsoleOutput $output)
            {
            }

            public function handleEvent(): string
            {
                ob_start();
                $this->output->writeln('<info>Event handled with injected ConsoleOutput!</info>');
                $content = ob_get_clean();
                return $content !== false ? $content : '';
            }
        };

        $result = $eventListener->handleEvent();

        self::assertIsString($result);
    }
}
