<?php

namespace domProjects\CodeIgniterBootstrapIcons\Publishers;

use CodeIgniter\Publisher\Publisher;
use RuntimeException;

class BootstrapIconsPublisher extends Publisher
{
    protected $source = VENDORPATH . 'twbs/bootstrap-icons/font';
    protected $destination = FCPATH . 'assets/bootstrap-icons';
    protected bool $replace = false;

    public function __construct(?string $source = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        if (! is_dir($destination) && ! mkdir($destination, 0775, true) && ! is_dir($destination)) {
            throw new RuntimeException('Unable to create Bootstrap Icons asset destination: ' . $destination);
        }

        parent::__construct($source ?? $this->source, $destination);
    }

    public function setReplace(bool $replace): self
    {
        $this->replace = $replace;

        return $this;
    }

    public function publish(): bool
    {
        return $this->addPaths([
            'bootstrap-icons.min.css',
            'fonts',
        ])->merge($this->replace);
    }
}
