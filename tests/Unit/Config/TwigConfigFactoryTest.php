<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use InvalidArgumentException;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Twig\Config\TwigParameter;
use Maduser\Argon\Twig\Factory\TwigConfigFactory;
use PHPUnit\Framework\TestCase;

final class TwigConfigFactoryTest extends TestCase
{
    public function testCreatesDefaultConfig(): void
    {
        $config = (new TwigConfigFactory())->create(new ArgonContainer());

        self::assertFalse($config->debug);
        self::assertFalse($config->autoReload);
        self::assertFalse($config->strictVariables);
        self::assertFalse($config->cache);
    }

    public function testCreatesConfigFromParameters(): void
    {
        $container = new ArgonContainer();
        $parameters = $container->getParameters();
        $parameters->set(TwigParameter::DEBUG, true);
        $parameters->set(TwigParameter::AUTO_RELOAD, true);
        $parameters->set(TwigParameter::STRICT_VARIABLES, true);
        $parameters->set(TwigParameter::CACHE, '/tmp/twig-cache');

        $config = (new TwigConfigFactory())->create($container);

        self::assertTrue($config->debug);
        self::assertTrue($config->autoReload);
        self::assertTrue($config->strictVariables);
        self::assertSame('/tmp/twig-cache', $config->cache);
    }

    public function testRejectsNonBooleanDebugParameter(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set(TwigParameter::DEBUG, 'yes');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "twig.debug" must be a boolean.');

        (new TwigConfigFactory())->create($container);
    }

    public function testRejectsInvalidCacheParameter(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set(TwigParameter::CACHE, true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "twig.cache" must be false or a string.');

        (new TwigConfigFactory())->create($container);
    }
}
