# Changelog

## [3.2.0](https://github.com/Okipa/laravel-brickables/compare/3.1.0...3.2.0)

2023-01-22

* Added Laravel 10 support
* Dropped Laravel 8 support
* Locked PHP versions to 8.1 and 8.2

## [3.1.0](https://github.com/Okipa/laravel-brickables/compare/3.0.1...3.1.0)

2022-12-23

* Added PHP 8.2 support
* Dropped PHP 8.0 support

## [3.0.1](https://github.com/Okipa/laravel-brickables/compare/3.0.0...3.0.1)

2022-10-27

* Improved CI

## [3.0.0](https://github.com/Okipa/laravel-brickables/compare/2.3.1...3.0.0)

2022-09-21

* Added support for Laravel 9
* Dropped support for Laravel 7
* Added support for PHP 8.1
* Dropped support for PHP 7.4 and earlier versions
* Package is now using Fontawesome 6 icons
* Views have upgraded to Bootstrap 5
* Replaced `phpcs/phpcbf` by `laravel/pint`

:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v2-to-v3.md)

## [2.3.1](https://github.com/Okipa/laravel-brickables/compare/2.3.0...2.3.1)

2021-06-07

* Handled forgotten URLs on previous 2.2.1 fix.

## [2.3.0](https://github.com/Okipa/laravel-brickables/compare/2.2.1...2.3.0)

2021-06-02

* Optimizing queries with eager loading is now possible: just set the relationships you want to load in the `$with` attribute of your custom brick models, the relations will be correctly loaded.

## [2.2.1](https://github.com/Okipa/laravel-brickables/compare/2.2.0...2.2.1)

2021-05-12

* Fixed URLs in action forms to allow brick models to embed other bricks (e.g., a container that contains tiles bricks).

## [2.2.0](https://github.com/Okipa/laravel-brickables/compare/2.1.0...2.2.0)

2021-03-28

* You can now disabled a brick management for an Eloquent model by setting the max number of bricks to `0`. This allows you to only disable one brick or more without having to list all allowed bricks in the `can_only_handle` model configuration.
* Trying to (programmatically) add a brick to a model when the max number of bricks is already reached will now throw a `ModelHasReachedMaxNumberOfBricksException`. This behavior replaces the previous one, which automatically removed the oldest brick when a new brick was programmatically added to an Eloquent model which had reached the max number for this type of brick.

## [2.1.0](https://github.com/Okipa/laravel-brickables/compare/2.0.1...2.1.0)

2020-11-25

* Added PHP 8 support
* Removed Scrutinizer analysis
* Updated PHPCS checker and fixer norm to PSR-12

## [2.0.1](https://github.com/Okipa/laravel-brickables/compare/2.0.0...2.0.1)

2020-09-30

* Removed useless `type="text/javascript"` for script declarations in favor of W3C rules

## [2.0.0](https://github.com/Okipa/laravel-brickables/compare/1.1.0...2.0.0)

2020-09-10

* Added Laravel 8 support
* Dropped Laravel 5.8 and 6 support
* Added PHP 7.4 support
* Dropped PHP 7.2 and 7.3 support
* Added brickables CSS and JS resources smart management
* Changed brick inputs validation workflow (breaking changes)
* Other API changes (breaking changes)

:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v1-to-v2.md)

## [1.1.0](https://github.com/Okipa/laravel-brickables/compare/1.0.2...1.1.0)

2020-03-16

* Added Laravel 7 support

## [1.0.2](https://github.com/Okipa/laravel-brickables/compare/1.0.1...1.0.2)

2020-02-24

* Applied an additional fix for the bricks order problem returned by the `Brickables::castBricks()` method

## [1.0.1](https://github.com/Okipa/laravel-brickables/compare/1.0.0...1.0.1)

2020-02-20

* Fixed the wrong bricks order returned by the `Brickables::castBricks()` method

## [1.0.0](https://github.com/Okipa/laravel-brickables/compare/1.0.0...1.0.0)

2020-02-14

* First release
