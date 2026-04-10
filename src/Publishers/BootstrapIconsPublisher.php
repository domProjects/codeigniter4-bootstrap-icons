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

namespace domProjects\CodeIgniterBootstrapIcons\Publishers;

use CodeIgniter\Publisher\Publisher;
use RuntimeException;

/**
 * Publishes the Bootstrap Icons runtime assets from the Composer package to `public/`.
 */
class BootstrapIconsPublisher extends Publisher
{
    /**
     * Default source directory inside the Bootstrap Icons Composer package.
     *
     * @var string
     */
    protected $source = VENDORPATH . 'twbs/bootstrap-icons/font';

    /**
     * Default public destination for the published Bootstrap Icons assets.
     *
     * @var string
     */
    protected $destination = FCPATH . 'assets/bootstrap-icons';

    /**
     * Whether existing destination files should be overwritten.
     */
    protected bool $replace = false;

    /**
     * @param string|null $source      Optional custom Bootstrap Icons source directory.
     * @param string|null $destination Optional custom destination directory under the public path.
     */
    public function __construct(?string $source = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        if (! is_dir($destination) && ! mkdir($destination, 0775, true) && ! is_dir($destination)) {
            throw new RuntimeException('Unable to create Bootstrap Icons asset destination: ' . $destination);
        }

        parent::__construct($source ?? $this->source, $destination);
    }

    /**
     * Enables or disables overwriting existing files during publication.
     */
    public function setReplace(bool $replace): self
    {
        $this->replace = $replace;

        return $this;
    }

    /**
     * Publishes the minified Bootstrap Icons stylesheet and font files.
     */
    public function publish(): bool
    {
        return $this->addPaths([
            'bootstrap-icons.min.css',
            'fonts',
        ])->merge($this->replace);
    }
}
