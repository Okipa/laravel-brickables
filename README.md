# Page content bricks management made easy

[![Source Code](https://img.shields.io/badge/source-okipa/laravel--brickable-blue.svg)](https://github.com/Okipa/laravel-brickable)
[![Latest Version](https://img.shields.io/github/release/okipa/laravel-brickable.svg?style=flat-square)](https://github.com/Okipa/laravel-brickable/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/okipa/laravel-brickable.svg?style=flat-square)](https://packagist.org/packages/okipa/laravel-brickable)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/Okipa/laravel-brickable.svg?branch=master)](https://travis-ci.org/Okipa/laravel-brickable)
[![Coverage Status](https://coveralls.io/repos/github/Okipa/laravel-brickable/badge.svg?branch=master)](https://coveralls.io/github/Okipa/laravel-brickable?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/Okipa/laravel-brickable.svg?style=flat-square)](https://scrutinizer-ci.com/g/Okipa/laravel-brickable/?branch=master)

:warning: PACKAGE IN DEVELOPMENT :warning:

This package allows you to associate content bricks to Eloquent models and gives ability to easily manage them from an admin panel.

This package is shipped with a few `Bootstrap 4.*` pre-built content bricks. You can use them as is, but you definitely should consider them as examples : customizing them or create new ones has been designed to be simple as hell ! :fire:

## Compatibility

| Laravel version | PHP version | Package version |
|---|---|---|
| ^5.5 | ^7.1 | ^1.0 |

## Usage

Associate content bricks to an Eloquent model :

```php
$page = Page::find(1);

// associate one brick
$page->addBrick(OneTextColumn::class, ['content' => 'Text content']);

// or associate several bricks at once
$page->addBricks([
    [OneTextColumn::class, ['content' => 'Text']],
    [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']]
]);
```

And display them in your view :

```blade
{{-- automatically --}}
{{ $page->displayBricks() }}

{{-- or manually --}}
<h3>Title<h3>
<p>Paragraph</p>
{{ $page->getFirstBrick(OneTextColumn::class) }}
<p>Other paragraph</p>
{{ $page->getFirstBrick(TwoTextColumns::class) }}
```

## Table of contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Views](#views)
* [API documentation](#api-documentation)
  * [Add content bricks](#add-content-bricks)
  * [Update a content brick](#update-a-content-brick)
  * [Delete a content brick](#delete-a-content-brick)
  * [Set content bricks order](#set-content-bricks-order)
  * [Retrieve content bricks](#retrieve-content-bricks)
  * [Query bricks](#query-bricks)
  * [Display bricks in you views](#display-bricks-in-you-views)
  * [Create your own content brick](#create-your-own-content-brick)
* [Testing](#testing)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Security](#security)
* [Credits](#credits)
* [Licence](#license)

## Installation

You can install the package via composer :

```bash
composer require okipa/laravel-brickable
```

Then, add the `Okipa\LaravelBrickable\Traits\HasBrickables` trait to any Eloquent model that you want to be able to manage content bricks to.

```php
class Page extends Model
{
	use HasBrickables;

	// ...
}
```

## Configuration

Publish the package configuration file to customize it if necessary : 

```bash
php artisan vendor:publish --tag=laravel-brickable:config
```

## Views

Publish the package views to customize them if necessary : 

```bash
php artisan vendor:publish --tag=laravel-brickable:views
```

## API documentation

### Add content bricks

Associate a single content brick to an Eloquent model :

```php
$page = Page::find(1);
$brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text']);
```

You also can associate several content bricks at once :

```php
$page = Page::find(1);
$bricks = $page->addBricks([
    [OneTextColumn::class, ['content' => 'Text']],
    [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']]
]);
```

### Update a content brick

Just update your content brick as you would fo for any other Eloquent model instance :

```php
$brick->update(['content', 'Another text']);

// or
$brick->content = 'Another text';
$brick->save();
```

### Delete a content brick

Just delete your content brick as you would fo for any other Eloquent model instance :

```php
$brick->delete();
```

### Set content bricks order

By default all inserted media items are ordered by their creation order (from the oldest to the newest).

The `Brick` model uses the `spatie/eloquent-sortable` package to handle the content bricks positioning.

This third party package documentation is available here : https://github.com/spatie/eloquent-sortable.

### Retrieve content bricks

Retrieve the content bricks associated to an Eloquent model :

```php
$page = Page::find(1);
$bricks = $page->getBricks();
```

You also can find the first typed brick associated to the model :

```php
$page = Page::find(1);
$brick = $page->getFirstBrick(OneTextColumn::class);
```

### Query bricks

You can query content bricks as for any Eloquent model :

```php
Brick::where('brick_type', OneTextColumn::class)->first();
```

### Display bricks in you views

Display a single content brick in your view :

```blade
{{ $page->getFirstBrick(OneTextColumn::class) }}
```

Or display all the model related content bricks :
```blade
{{ $page->displayBricks() }}
```

### Create your own content brick

Create your own content brick by following these steps :

#### 1. Create a new brick type

Create a new brick type in the `app/vendor/LaravelBrickable/Brickables` directory (this location is optional).

You new brick type have to extends the `Okipa\LaravelBrickable\Abstracts\BrickableAbstract` class and should look like this : 

```php
<?php

namespace App\Vendor\LaravelBrickable\Brickables;

use Okipa\LaravelBrickable\Abstracts\BrickableAbstract;

class MyBrickType extends BrickableAbstract
{
    /**
     * @inheritDoc
     */
    protected function setViewPath(): string
    {
        return 'laravel-brickable::my-brick-type';
    }
}
```

#### 2. Create your brick type view

Create a view that will host your brick type HTML.

If you published the package views, you can create it in the `ressources/views/vendor/laravel-brickable` directory.

If not, put them wherever you wan (without forgetting to change the path return by the `setViewPath()` method from you brick type class).

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email arthur.lorent@gmail.com instead of using the issue tracker.

## Credits

- [Arthur LORENT](https://github.com/okipa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
