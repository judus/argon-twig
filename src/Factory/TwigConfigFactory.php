<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Factory;

use InvalidArgumentException;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Twig\Config\TwigConfig;
use Maduser\Argon\Twig\Config\TwigParameter;

/**
 * @psalm-api
 */
final class TwigConfigFactory
{
    public function create(ArgonContainer $container): TwigConfig
    {
        $parameters = $container->getParameters();

        return new TwigConfig(
            debug: $this->boolParameter($parameters->get(TwigParameter::DEBUG, false), TwigParameter::DEBUG),
            autoReload: $this->boolParameter(
                $parameters->get(TwigParameter::AUTO_RELOAD, false),
                TwigParameter::AUTO_RELOAD
            ),
            strictVariables: $this->boolParameter(
                $parameters->get(TwigParameter::STRICT_VARIABLES, false),
                TwigParameter::STRICT_VARIABLES
            ),
            cache: $this->cacheParameter($parameters->get(TwigParameter::CACHE, false)),
        );
    }

    private function boolParameter(mixed $value, string $name): bool
    {
        if (!is_bool($value)) {
            throw new InvalidArgumentException(sprintf('Parameter "%s" must be a boolean.', $name));
        }

        return $value;
    }

    private function cacheParameter(mixed $value): string|false
    {
        if ($value === false || is_string($value)) {
            return $value;
        }

        throw new InvalidArgumentException(sprintf(
            'Parameter "%s" must be false or a string.',
            TwigParameter::CACHE
        ));
    }
}
