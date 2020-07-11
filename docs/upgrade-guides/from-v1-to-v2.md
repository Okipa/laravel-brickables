# Upgrade from v1 to v2

Follow the steps below to upgrade the package.

## New brickables resources smart management

Check how to benefit from the brand new [brickables resources smart management](../../README.md#define-brickable-css-and-js-resources).

## New brick inputs validation workflow

To give package users more latitude to execute their own validation workflow, the way the brick inputs were validated has been updated.

As so, the mandatory brickables `protected function setStoreValidationRules(): array` method does not exist anymore and has been replaced by the `public function validateStoreInputs(): array` one.

Same story for the `protected function setUpdateValidationRules(): array`, that has been replaced by the `public function validateUpdateInputs(): array` one.

You will ben able to validate your form inputs as you wish to:
* Direct request validation: `return request()->validate([...])`
* FormRequest validation `return (new BrickableStoreFormRequest)->validated()`
* Custom validation workflow with validators: https://laravel.com/docs/validation#manually-creating-validators

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-brickables/compare/1.1.0...2.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
