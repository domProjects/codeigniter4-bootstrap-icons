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

namespace domProjects\CodeIgniterBootstrapIcons\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use domProjects\CodeIgniterBootstrapIcons\Publishers\BootstrapIconsPublisher;
use Throwable;

/**
 * Spark command used to publish Bootstrap Icons assets.
 */
class PublishBootstrapIcons extends BaseCommand
{
    /**
     * Command group shown in the Spark command list.
     *
     * @var string
     */
    protected $group = 'Bootstrap Icons';

    /**
     * Command name used from the CLI.
     *
     * @var string
     */
    protected $name = 'assets:publish-bootstrap-icons';

    /**
     * Short description displayed in Spark help.
     *
     * @var string
     */
    protected $description = 'Publishes Bootstrap Icons assets to public/assets/bootstrap-icons.';

    /**
     * Usage string displayed in Spark help.
     *
     * @var string
     */
    protected $usage = 'assets:publish-bootstrap-icons [--force]';

    /**
     * Supported command options.
     *
     * @var array<string, string>
     */
    protected $options = [
        '--force' => 'Overwrite existing files.',
        '-f'      => 'Alias of --force.',
    ];

    /**
     * Publishes the Bootstrap Icons assets to the configured public destination.
     *
     * When `--force` is provided, existing files are overwritten.
     *
     * @param array<int|string, string|null> $params
     *
     * @return int CLI exit status code.
     */
    public function run(array $params): int
    {
        $force = array_key_exists('force', $params)
            || array_key_exists('f', $params)
            || CLI::getOption('force') !== null
            || CLI::getOption('f') !== null;

        $publisher = (new BootstrapIconsPublisher())->setReplace($force);

        CLI::write(
            'Publishing Bootstrap Icons assets to ' . $publisher->getDestination() . ($force ? ' with overwrite...' : '...'),
            'yellow',
        );

        try {
            if (! $publisher->publish()) {
                CLI::error('Bootstrap Icons publish failed.');

                foreach ($publisher->getErrors() as $file => $exception) {
                    CLI::write($file);
                    CLI::error($exception->getMessage());
                    CLI::newLine();
                }

                return EXIT_ERROR;
            }
        } catch (Throwable $e) {
            $this->showError($e);

            return EXIT_ERROR;
        }

        CLI::write('Bootstrap Icons assets published successfully.', 'green');

        return EXIT_SUCCESS;
    }
}
