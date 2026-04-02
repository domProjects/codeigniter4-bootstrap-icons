<?php

namespace domProjects\CodeIgniterBootstrapIcons\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use domProjects\CodeIgniterBootstrapIcons\Publishers\BootstrapIconsPublisher;
use Throwable;

class PublishBootstrapIcons extends BaseCommand
{
    protected $group = 'Bootstrap Icons';
    protected $name = 'assets:publish-bootstrap-icons';
    protected $description = 'Publishes Bootstrap Icons assets to public/assets/bootstrap-icons.';
    protected $usage = 'assets:publish-bootstrap-icons [--force]';
    protected $options = [
        '--force' => 'Overwrite existing files.',
        '-f'      => 'Alias of --force.',
    ];

    public function run(array $params)
    {
        $force = array_key_exists('force', $params)
            || array_key_exists('f', $params)
            || CLI::getOption('force') !== null
            || CLI::getOption('f') !== null;

        $publisher = (new BootstrapIconsPublisher())->setReplace($force);

        CLI::write(
            'Publishing Bootstrap Icons assets to ' . $publisher->getDestination() . ($force ? ' with overwrite...' : '...'),
            'yellow'
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
