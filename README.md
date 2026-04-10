# Bootstrap Icons for CodeIgniter 4

Bootstrap Icons asset publisher for CodeIgniter 4 projects.

## Features

- Adds a Spark command: `assets:publish-bootstrap-icons`
- Publishes Bootstrap Icons assets to `public/assets/bootstrap-icons`
- Publishes the minified CSS file and font files
- Supports `--force` to overwrite existing files
- Compatible with CodeIgniter 4.7.2+

## Requirements

- PHP 8.2+
- CodeIgniter 4.7.2+
- `twbs/bootstrap-icons`

## Installation

Install the package with Composer:

```bash
composer require domprojects/codeigniter4-bootstrap-icons
```

## Usage

Publish the assets manually:

```bash
php spark assets:publish-bootstrap-icons
```

Overwrite existing files:

```bash
php spark assets:publish-bootstrap-icons --force
```

Published files:

- `public/assets/bootstrap-icons/bootstrap-icons.min.css`
- `public/assets/bootstrap-icons/fonts/*`

## Automation

If you want automatic publishing after `composer install` and `composer update`, use the companion package:

```bash
composer require domprojects/codeigniter4-bootstrap-icons-plugin
```

## Package Structure

```text
src/
  Commands/
    PublishBootstrapIcons.php
  Publishers/
    BootstrapIconsPublisher.php
```

## License

MIT
