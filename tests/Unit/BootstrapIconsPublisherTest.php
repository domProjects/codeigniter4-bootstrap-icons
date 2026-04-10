<?php

declare(strict_types=1);

/**
 * This file is part of domprojects/codeigniter4-bootstrap-icons.
 *
 * (c) domProjects
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace domProjects\CodeIgniterBootstrapIcons\Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use domProjects\CodeIgniterBootstrapIcons\Publishers\BootstrapIconsPublisher;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BootstrapIconsPublisherTest extends CIUnitTestCase
{
    /**
     * @var list<string>
     */
    private array $cleanupPaths = [];

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanupPaths) as $path) {
            $this->deletePath($path);
        }

        $this->cleanupPaths = [];

        parent::tearDown();
    }

    public function testConstructorCreatesDestinationDirectory(): void
    {
        $source      = $this->createSourceFontDirectory();
        $destination = FCPATH . 'publisher-create-' . bin2hex(random_bytes(4));

        $this->cleanupPaths[] = $destination;

        $this->assertDirectoryDoesNotExist($destination);

        $publisher = new BootstrapIconsPublisher($source, $destination);

        $this->assertSame($destination . DIRECTORY_SEPARATOR, $publisher->getDestination());
        $this->assertDirectoryExists($destination);
    }

    public function testPublishCopiesBootstrapIconsFiles(): void
    {
        $source      = $this->createSourceFontDirectory();
        $destination = $this->createDestinationDirectory('publisher-copy');
        $publisher   = new BootstrapIconsPublisher($source, $destination);

        $this->assertTrue($publisher->publish());
        $this->assertSame([], $publisher->getErrors());
        $this->assertCount(3, $publisher->getPublished());
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'bootstrap-icons.min.css');
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'bootstrap-icons.woff');
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'bootstrap-icons.woff2');
    }

    public function testPublishDoesNotOverwriteExistingFilesByDefault(): void
    {
        $source      = $this->createSourceFontDirectory();
        $destination = $this->createDestinationDirectory('publisher-no-replace');
        $existingCss = $destination . DIRECTORY_SEPARATOR . 'bootstrap-icons.min.css';

        file_put_contents($existingCss, 'old-css');

        $publisher = new BootstrapIconsPublisher($source, $destination);

        $this->assertTrue($publisher->publish());
        $this->assertSame('old-css', file_get_contents($existingCss));
    }

    private function createSourceFontDirectory(): string
    {
        $root = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'test-runtime' . DIRECTORY_SEPARATOR . 'source-' . bin2hex(random_bytes(4));

        $fontsDir = $root . DIRECTORY_SEPARATOR . 'fonts';

        mkdir($fontsDir, 0775, true);

        file_put_contents($root . DIRECTORY_SEPARATOR . 'bootstrap-icons.min.css', 'icons-css');
        file_put_contents($fontsDir . DIRECTORY_SEPARATOR . 'bootstrap-icons.woff', 'woff-data');
        file_put_contents($fontsDir . DIRECTORY_SEPARATOR . 'bootstrap-icons.woff2', 'woff2-data');

        $this->cleanupPaths[] = $root;

        return $root;
    }

    private function createDestinationDirectory(string $prefix): string
    {
        $destination = FCPATH . $prefix . '-' . bin2hex(random_bytes(4));

        mkdir($destination, 0775, true);
        $this->cleanupPaths[] = $destination;

        return $destination;
    }

    private function deletePath(string $path): void
    {
        if (is_file($path) || is_link($path)) {
            @unlink($path);

            return;
        }

        if (! is_dir($path)) {
            return;
        }

        $items = scandir($path);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $this->deletePath($path . DIRECTORY_SEPARATOR . $item);
        }

        @rmdir($path);
    }
}
