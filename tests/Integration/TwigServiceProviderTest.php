<?php

declare(strict_types=1);

namespace Tests\Integration;

use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Container\Exceptions\ContainerException;
use Maduser\Argon\Container\Exceptions\NotFoundException;
use Maduser\Argon\Twig\Provider\TwigServiceProvider;
use Maduser\Argon\Twig\TemplatePathRegistry;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProviderTest extends TestCase
{
    private string $basePath;

    #[\Override]
    protected function setUp(): void
    {
        $path = sys_get_temp_dir() . '/argon-twig-' . bin2hex(random_bytes(6));

        mkdir($path . '/resources/templates', 0777, true);
        mkdir($path . '/src/auth/resources/templates', 0777, true);

        file_put_contents($path . '/resources/templates/home.html.twig', 'Hello {{ name }}');
        file_put_contents($path . '/src/auth/resources/templates/login.html.twig', 'Auth {{ name }}');

        $this->basePath = $path;
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->removeDirectory($this->basePath);
    }

    /**
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function testRegistersTwigEnvironmentWithDefaultApplicationTemplatePath(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set('basePath', $this->basePath);
        $container->register(TwigServiceProvider::class);

        $twig = $container->get(Environment::class);

        self::assertInstanceOf(Environment::class, $twig);
        self::assertSame('Hello Julien', $twig->render('home.html.twig', ['name' => 'Julien']));
    }

    /**
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function testModuleServiceProviderCanAddNamespacedTemplatePath(): void
    {
        $container = new ArgonContainer();
        $container->getParameters()->set('basePath', $this->basePath);
        $container->register(TwigServiceProvider::class);

        $paths = $container->get(TemplatePathRegistry::class);
        self::assertInstanceOf(TemplatePathRegistry::class, $paths);

        $paths->addPath($this->basePath . '/src/auth/resources/templates', 'auth');

        $twig = $container->get(Environment::class);

        self::assertSame('Auth Ada', $twig->render('@auth/login.html.twig', ['name' => 'Ada']));
    }

    /**
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function testProviderDoesNotReplaceExistingBindings(): void
    {
        $container = new ArgonContainer();
        $loader = new FilesystemLoader($this->basePath . '/resources/templates');
        $twig = new Environment($loader);

        $container->set(FilesystemLoader::class, static fn(): FilesystemLoader => $loader);
        $container->set(Environment::class, static fn(): Environment => $twig);
        $container->register(TwigServiceProvider::class);

        self::assertSame($loader, $container->get(FilesystemLoader::class));
        self::assertSame($twig, $container->get(Environment::class));
    }

    private function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $items = scandir($path);
        self::assertIsArray($items);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $child = $path . '/' . $item;

            if (is_dir($child)) {
                $this->removeDirectory($child);

                continue;
            }

            unlink($child);
        }

        rmdir($path);
    }
}
