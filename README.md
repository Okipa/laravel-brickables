# Page content bricks management made easy

[![Source Code](https://img.shields.io/badge/source-okipa/laravel--brickables-blue.svg)](https://github.com/Okipa/laravel-brickables)
[![Latest Version](https://img.shields.io/github/release/okipa/laravel-brickables.svg?style=flat-square)](https://github.com/Okipa/laravel-brickables/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/okipa/laravel-brickables.svg?style=flat-square)](https://packagist.org/packages/okipa/laravel-brickables)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build status](https://github.com/Okipa/laravel-brickables/workflows/CI/badge.svg)](https://github.com/Okipa/laravel-brickables/actions)
[![Coverage Status](https://coveralls.io/repos/github/Okipa/laravel-brickables/badge.svg?branch=master)](https://coveralls.io/github/Okipa/laravel-brickables?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/Okipa/laravel-brickables.svg?style=flat-square)](https://scrutinizer-ci.com/g/Okipa/laravel-brickables/?branch=master)

This package allows you to associate content bricks to Eloquent models and provides a full and customizable admin panel to manage them.

This package is shipped with pre-built brickables. You can use them as is, but you definitely should consider them as examples: customizing them or create new ones has been designed to be simple as hell ! :fire:

## Compatibility

| Laravel | PHP | Package |
|---|---|---|
| ^5.8 | ^7.2 | ^1.0 |

## Usage

Associate content bricks to Eloquent models:

```php
$page = Page::find(1);

// associate one content brick
$page->addBrick(OneTextColumn::class, ['text' => 'Text']);

// or associate several content bricks at once
$page->addBricks([
    [OneTextColumn::class, ['text' => 'Text']],
    [TwoTextColumns::class, ['text_left' => 'Left text', 'text_right' => 'Right text']]
]);
```

Display bricks in your views:

```blade
{{-- all at once --}}
{{ Brickables::displayBricks($page) }}

{{-- or one by one --}}
{{ $page->getFirstBrick(OneTextColumn::class) }}
```

Display the model-related bricks admin panel in your views:

```blade
{{ Brickables::displayAdminPanel($page) }}
```

## Table of contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Views](#views)
* [Translations](#translations)
* [Implementation](#implementation)
  * [Models](#models)
  * [Routes](#routes)
* [How to](#how-to)
  * [Define brickable contraints](#define-brickables-constraints)
  * [Add content bricks](#add-content-bricks)
  * [Update a content brick](#update-a-content-brick)
  * [Remove content bricks](#remove-content-bricks)
  * [Set content bricks order](#set-content-bricks-order)
  * [Retrieve content bricks](#retrieve-content-bricks)
  * [Query content bricks](#query-content-bricks)
  * [Display content bricks](#display-content-bricks)
  * [Retrieve brickables](#retrieve-brickables)
  * [Manage model content bricks](#manage-model-content-bricks)
  * [Create your own brickable](#create-your-own-brickable)
  * [Empower bricks with extra abilities](#empower-brickables-with-extra-abilities)
  * [Get Eloquent model from Request](#get-eloquent-model-from-request)
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

:warning: You may have to run a `composer dump-autoload` after changing a path in your configuration file.

## Views

Publish the package views to customize them if necessary: 

```bash
php artisan vendor:publish --provider="Okipa\LaravelBrickables\BrickablesServiceProvider" --tag=views
```

## Translations

All displayed labels or sentences are translatable.

See how to translate them on the Laravel official documentation : https://laravel.com/docs/localization#using-translation-strings-as-keys.

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

These routes are consuming the `Okipa\LaravelBrickables\Controllers\BricksController` controller by default.

To customize the admin panel actions, you can add routes inside or outside from the brickables route group.

```php
Brickables::routes(function(){
    // inside the routes group: will benefit from the CRUDBrickable middleware.
});
// outside the route group: will not benefit from the CRUDBrickable middleware.
```

Check the [Empower bricks with extra abilities](#empower-brickables-with-extra-abilities) part to get more information about the customization possibilities.

## How to

### Define brickables constraints

In your Eloquent model, define constraints:

* The brickables list that your model can handle.
* Make sure to always keep a min number of bricks for a brickable.
* Make sure to only hold a max number of bricks for a brickable.

```php

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class Page extends Model implements HasBrickables
{
	use HasBrickablesTrait;

    public $brickables = [
        'canOnlyHandle' => [OneTextColumn::class], // by default all registered brickables can be handled.
        'numberOfBricks' => [OneTextColumn::class => ['min' => 1, 'max' => 3]], // by default, there are no number restrictions.
    ];

	// ...
}
```

In this example:

* The model will only be allowed to handle the `OneTextColumn` brickable.
* Clearing all bricks from this brickable type for this model will keep the one with the highest position.
* Adding a 4th brick from this brickable type will remove the one with the lowest position.

### Add content bricks

Associate a single content brick to an Eloquent model:

```php
$brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
```

You also can associate several content bricks at once:

```php
$bricks = $page->addBricks([
    [OneTextColumn::class, ['text' => 'Text']],
    [TwoTextColumns::class, ['text_left' => 'Left text', 'text_right' => 'Right text']]
]);
```

### Update a content brick

Just update your content brick as you would fo for any other Eloquent model instance:

```php
// as data are store in json, so you will have to process this way
$brick->data = ['text' => 'Another text'];
$brick->save();
```

### Remove content bricks

Just delete your content brick as you would fo for any other Eloquent model instance:

```php
$brick->delete();
```

Clear all the content bricks associated to an Eloquent model, or only those with a specific brickable type:

```php
$page->clearBricks();

$page->clearBricks(OneTextColumn::class);
```

Clear all the content bricks from a given brickable type except some bricks:

```php
$page->clearBricksExcept(OneTextColumn::class, $bricksCollection);
```

**Note**

* According to the [number of bricks constraints](#define-brickables-constraints) defined in the Eloquent model, these methods could be brought to keep the min number of bricks instead of removing the targeted brick(s).

### Set content bricks order

By default all bricks are ordered by their creation order (last created at the end).

The `Brick` model uses the `spatie/eloquent-sortable` package to handle the content bricks positioning.

To see how to use this third party package, check its documentation here: https://github.com/spatie/eloquent-sortable.

You may note that the bricks order management is already handled in the provided [admin panel](#manage-model-content-bricks) and that you can use it as is.

### Retrieve content bricks

Retrieve all the content bricks associated to an Eloquent model, or only those with a specific brickable type:

```php
$bricks = $page->getBricks();

$bricks = $page->getBricks(OneTextColumn::class);
```

Get the first content brick associated to an Eloquent model, or the one with a specific brickable type:

```php
$brick = $page->getFirstBrick();

$brick = $page->getFirstBrick(OneTextColumn::class);
```

### Query content bricks

As brickables can specify the model they use, you should query content bricks and then cast them to their respective models:

```php
$bricks = Brickables::castBricks(Brick::all());
```

### Display content bricks

Display a single content brick in your view:

```blade
{{ $page->getFirstBrick(OneTextColumn::class) }}
```

Or display all the content bricks associated to an Eloquent model, with a brickable constraint or not:

```blade
{{ Brickables::displayBricks($page) }}

{{ Brickables::displayBricks($page, OneTextColumn::class) }}
```

### Retrieve brickables

Get all the registered brickables:

```php
$registeredBrickables = Brickables::getAll();
```

Get all the brickables that can be added to an Eloquent model:

```php
$additionableBrickables = Brickables::getAdditionableTo($page);
```

Retrieve a brickable from a brick instance:

```php
$brickable = $page->getFirstBrick(OneTextColumn::class)->brickable;
```

### Manage model content bricks

Use the ready-to-use admin panel to manage related-model content bricks:

```blade
{{ Brickables::displayAdminPanel($page) }}
```

Customize the admin panel views by [publishing them](#views).

**:bulb: Tips**

* Add a javascript confirmation request to intercept the content bricks delete action, otherwise, the removal action will be directly executed without asking the user agreement.

### Create your own brickable

Create a new brickable class that extends class in your `app/vendor/Brickables` directory.

In your brickable class, you can override any method from the extended abstract `Brickable` to customize the brickable behaviour. 

```php
<?php

namespace App\Vendor\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class MyNewBrickable extends Brickable
{
    /** @inheritDoc */
    protected function setStoreValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }

    /** @inheritDoc */
    protected function setUpdateValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }
}
```

Then, register it in your `config('brickables.registered')` array:

```php
<?php

return [

    'registered' => [
        // other brickables declarations ...
        App\Vendor\LaravelBrickables\Brickables\MyNewBrickable::class,
    ],
];

```

Finally, create the brick view in the `ressources/views/vendor/laravel-brickables/my-new-brickable` directory (you can customize the views paths in your `MyNewBrickable` class if you want):

* the `brick` view will be used to display your brickable.
* the `form` view will contain the form inputs that will be used to CRUD your brickable.

Check the existing brickables to see how they are implemented.

Your brickable is now ready to be associated to Eloquent models.

### Empower brickables with extra abilities

To add abilities to your brickables, you will have to implement the additional treatments in the brickable-related brick model and bricks controller.

Let's add the ability to manage images in our `MyNewBrickable` from the previous example.

First create a `MyNewBrickableBrick` model that will extend the `Okipa\LaravelBrickables\Models\Brick` one.

```php
<?php

namespace App;

use Okipa\LaravelBrickables\Models\Brick;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class MyNewBrickableBrick extends Brick implements HasMedia
{
    // image management example with the spatie/laravel-medialibrary package
    use HasMediaTrait;
    
    // ...
}
```

Then, create a `MyNewBrickableBricksController` model that will extend the `Okipa\LaravelBrickables\Controllers\BricksController` one.

```php
<?php

namespace App\Http\Controllers;

use Okipa\LaravelBrickables\Controllers\BricksController;

class MyNewBrickableBricksController extends BricksController
{

    // ...    

    /** @inheritDoc */
    protected function stored(Request $request, Brick $brick): void
    {
        // image management example with the spatie/laravel-medialibrary package
        $brick->addMediaFromRequest('image')->toMediaCollection('bricks');
    }

    /** @inheritDoc */
    protected function updated(Request $request, Brick $brick): void
    {
        // image management example with the spatie/laravel-medialibrary package
        if ($request->file('image')) {
            $brick->addMediaFromRequest('image')->toMediaCollection('bricks');
        }
    }

    // ...

}
```

Finally, set your `MyNewBrickableBrick` model and your `MyNewBrickableBricksController` namespaces in your `MyNewBrickable` brickable class.

```php
<?php

namespace App\Vendor\LaravelBrickables\Brickables;

use App\MyNewBrickableBrick;
use App\Http\Controllers\MyNewBrickableBricksController;
use Okipa\LaravelBrickables\Abstracts\Brickable;

class MyNewBrickable extends Brickable
{

    // ...
    
    /** @inheritDoc */
    protected function setBrickModelClass(): string
    {
        return MyNewBrickableBrick::class;
    }

    /** @inheritDoc */
    protected function setBricksControllerClass(): string
    {
        return MyNewBrickableBricksController::class;
    }

    // ...
}
```

That's it, your custom model and controller will now be used by the brickable.

### Get Eloquent model from request

It can be useful to retrieve the Eloquent model from the request, for navigation concerns, for example.

This helper will be able to return the related model when navigating on the brickables form views (bricks creation and edition requests).

```php
// you can pass a custom request in the parameters. If none is given, the current request is used.
$model = Brickables::getModelFromRequest();
```

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
