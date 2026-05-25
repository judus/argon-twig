<?php

declare(strict_types=1);

namespace Tests\Unit;

use InvalidArgumentException;
use Maduser\Argon\Twig\TemplatePathRegistry;
use PHPUnit\Framework\TestCase;

final class TemplatePathRegistryTest extends TestCase
{
    public function testAddsDefaultAndNamespacedPaths(): void
    {
        $registry = new TemplatePathRegistry();

        $registry
            ->addPath('/app/resources/templates/')
            ->addPath('/app/src/auth/resources/templates', ' auth ');

        $paths = $registry->all();

        self::assertCount(2, $paths);
        self::assertSame('/app/resources/templates', $paths[0]->path);
        self::assertNull($paths[0]->namespace);
        self::assertSame('/app/src/auth/resources/templates', $paths[1]->path);
        self::assertSame('auth', $paths[1]->namespace);
    }

    public function testRejectsEmptyPath(): void
    {
        $registry = new TemplatePathRegistry();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('template path must not be empty');

        $registry->addPath('');
    }

    public function testRejectsEmptyNamespace(): void
    {
        $registry = new TemplatePathRegistry();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('namespace must not be empty');

        $registry->addPath('/app/resources/templates', ' ');
    }
}
