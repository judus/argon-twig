<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Factory;

use Maduser\Argon\Twig\Config\TwigConfig;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @psalm-api
 */
final class TwigEnvironmentFactory
{
    public function create(FilesystemLoader $loader, TwigConfig $config): Environment
    {
        return new Environment($loader, [
            'debug' => $config->debug,
            'auto_reload' => $config->autoReload,
            'strict_variables' => $config->strictVariables,
            'cache' => $config->cache,
        ]);
    }
}
