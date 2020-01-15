# Page content bricks management made easy

[![Source Code](https://img.shields.io/badge/source-okipa/laravel--brickables-blue.svg)](https://github.com/Okipa/laravel-brickables)
[![Latest Version](https://img.shields.io/github/release/okipa/laravel-brickables.svg?style=flat-square)](https://github.com/Okipa/laravel-brickables/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/okipa/laravel-brickables.svg?style=flat-square)](https://packagist.org/packages/okipa/laravel-brickables)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/Okipa/laravel-brickables.svg?branch=master)](https://travis-ci.org/Okipa/laravel-brickables)
[![Coverage Status](https://coveralls.io/repos/github/Okipa/laravel-brickables/badge.svg?branch=master)](https://coveralls.io/github/Okipa/laravel-brickables?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/Okipa/laravel-brickables.svg?style=flat-square)](https://scrutinizer-ci.com/g/Okipa/laravel-brickables/?branch=master)

:warning: PACKAGE IN DEVELOPMENT :warning:

This package allows you to associate content bricks to Eloquent models and provides a full and customizable admin panel to manage them.

This package is shipped with pre-built brickables. You can use them as is, but you definitely should consider them as examples: customizing them or create new ones has been designed to be simple as hell ! :fire:

## Compatibility

| Laravel version | PHP version | Package version |
|---|---|---|
| ^5.5 | ^7.1 | ^1.0 |

## Usage

Associate content bricks to Eloquent models:

```php
$page = Page::find(1);

// associate one content brick
$page->addBrick(OneTextColumn::class, ['content' => 'Text']);

// or associate several content bricks at once
$page->addBricks([
    [OneTextColumn::class, ['content' => 'Text']],
    [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']]
]);
```

Display bricks in your views:

```blade
{{-- all at once --}}
{{ Brickables::bricks($page) }}

{{-- or one by one --}}
{{ $page->getFirstBrick(OneTextColumn::class) }}
```

Display the model-related bricks admin panel in your views:

```blade
{{ Brickables::adminPanel($page) }}
```

## Table of contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Views](#views)
* [Implementation](#implementation)
  * [Models](#models)
  * [Routes](#routes)
* [How to](#how-to)
  * [Add content bricks](#add-content-bricks)
  * [Update a content brick](#update-a-content-brick)
  * [Delete a content brick](#delete-a-content-brick)
  * [Set content bricks order](#set-content-bricks-order)
  * [Retrieve content bricks](#retrieve-content-bricks)
  * [Query content bricks](#query-content-bricks)
  * [Display content bricks](#display-content-bricks)
  * [Retrieve brickables](#retrieve-brickables)
  * [Create your own brickable](#create-your-own-brickable)
  * [Manage model content bricks](#manage-model-content-bricks)
  * [Empower bricks with extra abilities](#empower-bricks-with-extra-abilities)
* [Testing](#testing)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Security](#security)
* [Credits](#credits)
* [Licence](#license)

## Installation

Install the package via composer:

```bash
composer require okipa/laravel-brickables
```

Then, publish and run the database migrations: 

```bash
php artisan vendor:publish --provider="Okipa\LaravelBrickables\BrickablesServiceProvider" --tag=migrations
php artisan migrate
```

## Configuration

Publish the package configuration file to customize it if necessary: 

```bash
php artisan vendor:publish --provider="Okipa\LaravelBrickables\BrickablesServiceProvider" --tag=config
```

## Views

Publish the package views to customize them if necessary: 

```bash
php artisan vendor:publish --provider="Okipa\LaravelBrickables\BrickablesServiceProvider" --tag=views
```

## Implementation

### Models

Implement the `HasBrickables` interface and use the `HasBrickablesTrait` trait to any Eloquent model that you want to be able to be associated to content bricks to.

```php

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class Page extends Model implements HasBrickables
{
	use HasBrickablesTrait;

	// ...
}
```

### Routes

Add the `web` routes that will be required by the content bricks admin panel:

```php
Brickables::routes();
```

These routes are consuming the `Okipa\LaravelBrickables\Controllers\BricksController` controller.

To customize the admin panel treatments, create your own `BricksController`, extending the package one. You will also have to create your own routes.

### Translations

All displayed labels or sentences are translatables.

See how to translate them on the Laravel official documentation : https://laravel.com/docs/localization#using-translation-strings-as-keys

## How to

### Add content bricks

Associate a single content brick to an Eloquent model:

```php
$page = Page::find(1);
$brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text']);
```

You also can associate several content bricks at once:

```php
$page = Page::find(1);
$bricks = $page->addBricks([
    [OneTextColumn::class, ['content' => 'Text']],
    [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']]
]);
```

### Update a content brick

Just update your content brick as you would fo for any other Eloquent model instance:

```php
// as data are store in json, you'll have to process this way
$brick->data = ['content', 'Another text'];
$brick->save();
```

### Delete a content brick

Just delete your content brick as you would fo for any other Eloquent model instance:

```php
$brick->delete();
```

### Set content bricks order

By default all bricks are ordered by their creation order (from the oldest to the newest).

The `Brick` model uses the `spatie/eloquent-sortable` package to handle the content bricks positioning.

To see how to use this third party package, check its documentation here: https://github.com/spatie/eloquent-sortable.

### Retrieve content bricks

Retrieve the content bricks associated to an Eloquent model:

```php
$page = Page::find(1);
$bricks = $page->getBricks();
```

You also can get the first content brick which :

```php
$page = Page::find(1);
$brick = $page->getFirstBrick(OneTextColumn::class);
```

### Query content bricks

Query content bricks as for any Eloquent model:

```php
Brick::where('brickable_type', OneTextColumn::class)->first();
```

### Display content bricks

Display a single content brick in your view:

```blade
{{ $page->getFirstBrick(OneTextColumn::class) }}
```

Or display all the model-related content bricks:

```blade
{{ Brickables::bricks($page) }}
```

### Retrieve brickables

Get all the registered brickables that can be associated to Eloquent models:

```php
$registeredBrickables = Brickables::getAll();
```

Retrieve a brickable from a brick instance:

```php
$page = Page::find(1);
$brick = $page->getFirstBrick(OneTextColumn::class);
$brickable = $brick->brickable;
```

### Create your own brickable

Create a new class that extends class in your `app/vendor/Brickables` directory:

Override any method from the `Brickable` abstract it extends to customize the brickable behaviour. 

```php
<?php

namespace App\Vendor\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class MyBrickable extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setLabel(): string
    {
        return __('My brickable');
    }

    /**
     * @inheritDoc
     */
    public function setViewPath(): string
    {
        return 'laravel-brickables::brickables.my-brickable';
    }
}
```

Then, register it in your `config('brickables.registered')` array:

```php
<?php

return [

    'registered' => [
        // other brickables declarations ...
        App\Vendor\LaravelBrickables\Brickables\MyBrickable::class,
    ],
];

```

Finally, create a view that will host your brickable HTML. If you published the package views, you should place it in the `ressources/views/vendor/laravel-brickables` directory.

Your brickable is now ready to associate to Eloquent models.

### Manage model content bricks

Use the ready-to-use admin panel to manage related-model content bricks:

```blade
{{ Brickables::adminPanel($page) }}
```

Customize the admin panel views by [publishing them](#views).

You also can customize the admin panel operations (CRUD, moving, ...) by [defining your own routes and controller](#routes).

**:bulb: Tips**

* Add a javascript confirmation request to intercept the content bricks delete action, otherwise, the removal action will be directly executed without asking the user agreement.
* In case of views customization, delete the untouched ones after the views publication in order to get them automatically updated in case of package upgrade.

### Empower bricks with extra abilities

Add abilities to your bricks, like adding image management for example.

Create your own `Brick` class extending the `Okipa\LaravelBrickables\Models\Brick` one.

```php
<?php

namespace App;

class Brick extends Okipa\LaravelBrickables\Models\Brick implements Spatie\MediaLibrary\HasMedia\HasMedia
{
    // example of image management feature addition with the spatie/laravel-medialibrary package
    use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
    
    // ...
}
```

Then, set your custom `Brick` model namespace in the package config file.

```php
<?php

return [

    /*
     * The fully qualified class name of the brick model.
     */
    'brickModel' => App\Brick::class,
  
    // ...  
];
```

That's it, your `Brick` model will now be used by the package.

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
