<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig;

use InvalidArgumentException;

final class TemplatePathRegistry
{
    /**
     * @var list<TemplatePath>
     */
    private array $paths = [];

    public function addPath(string $path, ?string $namespace = null): self
    {
        $path = rtrim($path, '/');
        $namespace = $namespace !== null ? trim($namespace) : null;

        if ($path === '') {
            throw new InvalidArgumentException('Twig template path must not be empty.');
        }

        if ($namespace === '') {
            throw new InvalidArgumentException('Twig template path namespace must not be empty.');
        }

        $this->paths[] = new TemplatePath($path, $namespace);

        return $this;
    }

    /**
     * @return list<TemplatePath>
     */
    public function all(): array
    {
        return $this->paths;
    }
}
