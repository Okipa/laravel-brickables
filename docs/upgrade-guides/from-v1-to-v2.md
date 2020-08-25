# Upgrade from v1 to v2

Follow the steps below to upgrade the package.

## API changes

The signature of the following methods have changed. If you use those methods, you should report these changes in your code:
* `getBricks(?string $brickableClass = null): Collection` has been changed into `getBricks(?array $brickableClasses = []): Collection`.
* `clearBricks(?string $brickableClass = null): void` has been changed into `clearBricks(?array $brickableClasses = []): void`.
* `clearBricksExcept(string $brickableClass, Collection $excludeBricks): void` has been changed into `clearBricksExcept(Collection $excludeBricks): void`.

The following methods have been transferred from the `Brickables` helper to the `HasBrickablesTrait`. As so, you should update your code accordingly:
* `{{ Brickables::displayBricks(HasBrickables $model, ?string $brickableClass = null) }}` should now be called as following: `{!! $model->displayBricks(?array $brickableClasses) !!}`
* `{{ Brickables::displayAdminPanel(HasBrickables $model) }}` should now be called as following: `{!! $model->displayAdminPanel() !!}`
* `Brickables::getAdditionableTo(HasBrickables $model)` should now be called as following: `$model->getAdditionableBrickables()`
* `Brickables::getAll()` should now be called as following: `$model->getRegisteredBrickables()`

## New brickables resources smart management

Check how to benefit from the brand new [brickables resources smart management](../../README.md#define-brickable-css-and-js-resources).

## New brick inputs validation workflow

To give package users more latitude to execute their own validation workflow, the way the brick inputs are validated has been updated.

As so, the mandatory brickables `protected function setStoreValidationRules(): array` method does not exist anymore and has been replaced by the `public function validateStoreInputs(): array` one.

Same story for the `protected function setUpdateValidationRules(): array`, that has been replaced by the `public function validateUpdateInputs(): array` one.

Make sure to update each brickable with these changes.

You will then be able to validate your form inputs as you wish with:
* Direct request validation: `return request()->validate([...])`
* FormRequest validation `return (new BrickableStoreFormRequest)->validated()`
* Custom validation workflow with validators: https://laravel.com/docs/validation#manually-creating-validators

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-brickables/compare/1.1.0...2.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
