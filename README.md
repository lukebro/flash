# Magical flash notifications in Laravel 5.1

A simple magical API for flashing notifications to a session inside Laravel 5.1.

```php
flash()->success('Signed in successfully.');
```

## Installation

Requires [PHP 5.5.9](http://php.net), [Laravel 5.*](http://github.com/laravel/laravel) and [Composer](http://getcomposer.org).

Add the following line to the require block of your `composer.json` file: 
```js
	"lukebro/flash": "~0.1"
```

Run `composer install` or `composer update`.

Include the service provider within `app/config/app.php`:
```php
	"providers": [
			Lukebro/Flash/FlashServiceProvider::class,
	];
```

Add the facade alias at the bottom of the same file:
```php
	'aliases' => [
	    'Flash' => Lukebro/Flash/FlashFacade::class
	];
```

## Usage

### Flashing notifications

Before redirecting, flash a message to the screen:

```php
flash()->warning('Max file size is 5MB.');
```

Inside your view's you have access to a `flash()->level` and `flash()->message` attribute.

The `level` attribute is the name of the function you called, which could be anything.
The `message` attribute is the string you passed into the magical function.

So for the example above, `flash()->level` and `flash()->message` would equal `warning` and `Max file size is 5MB.` respectively.

A flash message is not required, calling just a method will result in just the level being flashed.

You also have access to a `Flash` facade which you could use in place of the `flash()` helper function.

### Detecting flash notifications

To detect if a flash message exists in the session, use `flash()->exists()`.

If you want to detect a specific level, use the `has()` method.  E.g. `flash()->has('error')`

## Examples

Below are some basic blade templates/examples.


Basic example
```php
@if(flash()->exists())
	<div class="alert alert-{{ flash()->level }}">
		{{ flash()->message }}
	</div>
@endif
```

Detecting a specific level
```php
@if(flash()->has('success'))
	<script>
		launchFireworks();
	</script>
@endif
```

## Credits

The inspiration for this extremely small package comes from Jeffrey Way's Laracast video: [Elegant Flash Messaging](https://laracasts.com/series/build-project-flyer-with-me/episodes/9).
Definitely check it out.  Jeffrey mentions that you could write the API in a "magical" way, so I thought it would be cool to implement.
