# argon-twig

[![PHP](https://img.shields.io/badge/php-8.2+-blue)](https://www.php.net/)
[![Build](https://github.com/judus/argon-twig/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/judus/argon-twig/actions)
[![codecov](https://codecov.io/gh/judus/argon-twig/branch/main/graph/badge.svg)](https://codecov.io/gh/judus/argon-twig)
[![Psalm Level](https://shepherd.dev/github/judus/argon-twig/coverage.svg)](https://shepherd.dev/github/judus/argon-twig)
[![Latest Version](https://img.shields.io/packagist/v/maduser/argon-twig.svg)](https://packagist.org/packages/maduser/argon-twig)
[![Total Downloads](https://img.shields.io/packagist/dt/maduser/argon-twig.svg?color=blue)](https://packagist.org/packages/maduser/argon-twig)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Twig integration for the Argon runtime stack.

## Installation

```bash
composer require maduser/argon-twig
```

## Registration

Register the service provider in your application composition layer:

```php
use Maduser\Argon\Twig\Provider\TwigServiceProvider;

$container->register(TwigServiceProvider::class);
```

The provider registers:

- `Twig\Environment`
- `Twig\Loader\FilesystemLoader`
- `Maduser\Argon\Twig\TemplatePathRegistry`
- `Maduser\Argon\Twig\Config\TwigConfig`

Existing container definitions are not replaced.

## Template Paths

By convention, the provider uses `resources/templates` below the `basePath` container parameter:

```php
$container->getParameters()->set('basePath', dirname(__DIR__));
```

You can override the default path explicitly:

```php
use Maduser\Argon\Twig\Config\TwigParameter;

$container->getParameters()->set(TwigParameter::DEFAULT_PATH, __DIR__ . '/../templates');
```

Module providers can add namespaced template paths:

```php
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Twig\TemplatePathRegistry;

final class AuthServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $registry = $container->get(TemplatePathRegistry::class);

        $registry->addPath(__DIR__ . '/resources/templates', 'auth');
    }
}
```

Templates in that folder can then be rendered with Twig's namespace syntax:

```php
$html = $twig->render('@auth/login.html.twig');
```

## Configuration

All configuration is optional and uses container parameters:

```php
use Maduser\Argon\Twig\Config\TwigParameter;

$parameters = $container->getParameters();
$parameters->set(TwigParameter::DEBUG, true);
$parameters->set(TwigParameter::AUTO_RELOAD, true);
$parameters->set(TwigParameter::STRICT_VARIABLES, true);
$parameters->set(TwigParameter::CACHE, dirname(__DIR__) . '/storage/cache/twig');
```

`TwigParameter::CACHE` accepts a string path or `false`.

## Boundaries

This package only wires Twig into an Argon container. It does not define view responses, controller conventions, template inheritance rules, asset handling, or module discovery.

## Quality Gates

```bash
composer check
composer test:coverage
composer psalm
composer phpcs
```
