<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Config;

/**
 * @psalm-api
 */
final class TwigParameter
{
    public const string DEFAULT_PATH = 'twig.defaultPath';
    public const string DEBUG = 'twig.debug';
    public const string AUTO_RELOAD = 'twig.autoReload';
    public const string STRICT_VARIABLES = 'twig.strictVariables';
    public const string CACHE = 'twig.cache';

    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }
}
