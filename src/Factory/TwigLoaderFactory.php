<?php

declare(strict_types=1);

namespace Maduser\Argon\Twig\Factory;

use Maduser\Argon\Twig\TemplatePathRegistry;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

/**
 * @psalm-api
 */
final class TwigLoaderFactory
{
    /**
     * @throws LoaderError
     */
    public function create(TemplatePathRegistry $paths): FilesystemLoader
    {
        $loader = new FilesystemLoader();

        foreach ($paths->all() as $path) {
            if ($path->namespace === null) {
                $loader->addPath($path->path);

                continue;
            }

            $loader->addPath($path->path, $path->namespace);
        }

        return $loader;
    }
}
