<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Config;

final readonly class TwigConfig
{
    public function __construct(
        public bool $debug = false,
        public bool $autoReload = false,
        public bool $strictVariables = false,
        public string|false $cache = false,
    ) {
    }
}
