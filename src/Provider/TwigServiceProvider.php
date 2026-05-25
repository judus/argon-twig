<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Provider;

use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Container\Exceptions\ContainerException;
use Maduser\Argon\Twig\Config\TwigConfig;
use Maduser\Argon\Twig\Factory\TwigConfigFactory;
use Maduser\Argon\Twig\Factory\TwigEnvironmentFactory;
use Maduser\Argon\Twig\Factory\TwigLoaderFactory;
use Maduser\Argon\Twig\Factory\TwigTemplatePathRegistryFactory;
use Maduser\Argon\Twig\TemplatePathRegistry;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @psalm-api
 */
final class TwigServiceProvider extends AbstractServiceProvider
{
    /**
     * @throws ContainerException
     */
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        if (!$container->has(TwigConfig::class)) {
            $container->set(TwigConfig::class)
                ->factory(TwigConfigFactory::class, 'create')
                ->shared();
        }

        if (!$container->has(TemplatePathRegistry::class)) {
            $container->set(TemplatePathRegistry::class)
                ->factory(TwigTemplatePathRegistryFactory::class, 'create')
                ->shared();
        }

        if (!$container->has(FilesystemLoader::class)) {
            $container->set(FilesystemLoader::class)
                ->factory(TwigLoaderFactory::class, 'create')
                ->shared();
        }

        if (!$container->has(Environment::class)) {
            $container->set(Environment::class)
                ->factory(TwigEnvironmentFactory::class, 'create')
                ->shared();
        }
    }
}
