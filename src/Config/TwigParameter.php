<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Config;

/**
 * @psalm-api
 */
final class TwigParameter
{
    public const DEFAULT_PATH = 'twig.defaultPath';
    public const DEBUG = 'twig.debug';
    public const AUTO_RELOAD = 'twig.autoReload';
    public const STRICT_VARIABLES = 'twig.strictVariables';
    public const CACHE = 'twig.cache';

    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }
}
