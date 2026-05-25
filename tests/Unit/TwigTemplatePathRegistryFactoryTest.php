<?php

declare(strict_types=1);

namespace Tests\Unit;

use InvalidArgumentException;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Twig\Config\TwigParameter;
use Maduser\Argon\Twig\Factory\TwigTemplatePathRegistryFactory;
use PHPUnit\Framework\TestCase;

final class TwigTemplatePathRegistryFactoryTest extends TestCase
{
    public function testUsesExplicitDefaultPathParameter(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set(TwigParameter::DEFAULT_PATH, '/custom/templates');

        $registry = (new TwigTemplatePathRegistryFactory())->create($container);
        $paths = $registry->all();

        self::assertCount(1, $paths);
        self::assertSame('/custom/templates', $paths[0]->path);
    }

    public function testUsesBasePathConventionWhenDefaultPathIsMissing(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set('basePath', '/app');

        $registry = (new TwigTemplatePathRegistryFactory())->create($container);
        $paths = $registry->all();

        self::assertCount(1, $paths);
        self::assertSame('/app/resources/templates', $paths[0]->path);
    }

    public function testAllowsNoDefaultPathWhenNoBasePathIsAvailable(): void
    {
        $registry = (new TwigTemplatePathRegistryFactory())->create(new ArgonContainer());

        self::assertSame([], $registry->all());
    }

    public function testRejectsNonStringDefaultPath(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set(TwigParameter::DEFAULT_PATH, true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "twig.defaultPath" must be a string.');

        (new TwigTemplatePathRegistryFactory())->create($container);
    }

    public function testRejectsNonStringBasePath(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set('basePath', true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "basePath" must be a string.');

        (new TwigTemplatePathRegistryFactory())->create($container);
    }
}
