# Page content bricks management made easy

[![Source Code](https://img.shields.io/badge/source-okipa/laravel--brickable-blue.svg)](https://github.com/Okipa/laravel-brickable)
[![Latest Version](https://img.shields.io/github/release/okipa/laravel-brickable.svg?style=flat-square)](https://github.com/Okipa/laravel-brickable/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/okipa/laravel-brickable.svg?style=flat-square)](https://packagist.org/packages/okipa/laravel-brickable)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/Okipa/laravel-brickable.svg?branch=master)](https://travis-ci.org/Okipa/laravel-brickable)
[![Coverage Status](https://coveralls.io/repos/github/Okipa/laravel-brickable/badge.svg?branch=master)](https://coveralls.io/github/Okipa/laravel-brickable?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/Okipa/laravel-brickable.svg?style=flat-square)](https://scrutinizer-ci.com/g/Okipa/laravel-brickable/?branch=master)

:warning: PACKAGE IN DEVELOPMENT :warning:

This package allows you to associate bricks of content with Eloquent models and gives ability to easily manage pages content from an admin panel.

This package is shipped with `Bootstrap 4.*` and `FontAwesome 5` pre-built content bricks. However, customizing them or create new ones has been designed to be simple as hell ! :fire:

## Compatibility

| Laravel version | PHP version | Package version |
|---|---|---|
| ^5.5 | ^7.1 | ^1.0 |

## Usage

Associate a content bricks to an Eloquent model :

``` php
$page = Page::find(1);
$page->addBrick($brickId);
// or
$page->addBricks([$brickId, $anotherBrickId]);
```

And render the content bricks in your view :

```blade
{{-- automatically --}}
@foreach ($page->bricks as $brick)
    {{ $brick }}
@endforeach

{{-- or manually --}}
<h3>Static title<h3>
<p>Static content</p>
{{ $page->bricks->find($brickId)->first() }}
```

## Table of contents

* [Installation](#installation)
* [Configuration](#configuration)
* [API documentation](#api-documentation)
* [Testing](#testing)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Credits](#credits)
* [Licence](#license)

## Installation

You can install the package via composer:

```bash
composer require okipa/laravel-brickable
```

## Configuration

Publish the package configuration and override the available config values :

```bash
php artisan vendor:publish --tag=laravel-brickable:config
```

## API documentation

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email arthur.lorent@gmail.com instead of using the issue tracker.

## Credits

- [Arthur LORENT](https://github.com/okipa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
