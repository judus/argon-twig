<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig;

final readonly class TemplatePath
{
    public function __construct(
        public string $path,
        public ?string $namespace = null,
    ) {
    }
}
