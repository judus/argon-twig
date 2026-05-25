<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Factory;

use InvalidArgumentException;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Twig\Config\TwigParameter;
use Maduser\Argon\Twig\TemplatePathRegistry;

/**
 * @psalm-api
 */
final class TwigTemplatePathRegistryFactory
{
    public function create(ArgonContainer $container): TemplatePathRegistry
    {
        $registry = new TemplatePathRegistry();
        $parameters = $container->getParameters();

        if ($parameters->has(TwigParameter::DEFAULT_PATH)) {
            $registry->addPath(
                $this->stringParameter($parameters->get(TwigParameter::DEFAULT_PATH), TwigParameter::DEFAULT_PATH)
            );

            return $registry;
        }

        $basePath = $this->optionalStringParameter($parameters->get('basePath', null), 'basePath');

        if ($basePath !== null && $basePath !== '') {
            $registry->addPath(rtrim($basePath, '/') . '/resources/templates');
        }

        return $registry;
    }

    private function stringParameter(mixed $value, string $name): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Parameter "%s" must be a string.', $name));
        }

        return $value;
    }

    private function optionalStringParameter(mixed $value, string $name): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Parameter "%s" must be a string.', $name));
        }

        return $value;
    }
}
