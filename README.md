# Magical flash notifications in Laravel 5.* & 6.*

[![Build Status](https://travis-ci.org/lukebro/flash.svg?branch=master)](https://travis-ci.org/lukebro/flash)

A simple magical API for flashing notifications to a session inside Laravel 5.*.

```php
flash()->success('Signed in successfully.');
```

## Installation

Requires [PHP 5.5.9](http://php.net), [Laravel 5.* & 6.*](http://github.com/laravel/laravel) and [Composer](http://getcomposer.org).

Add the following line to the require block of your `composer.json` file: 
```js
	"lukebro/flash": "~0.3"
```

Run `composer install` or `composer update`.

Include the service provider within `\config\app.php`:
```php
	'providers': [
			Lukebro\Flash\FlashServiceProvider::class,
	];
```

Add the facade alias at the bottom of the same file:
```php
	'aliases' => [
	    'Flash' => Lukebro\Flash\FlashFacade::class
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

### Flashing multiple notifications

You can chain your flashes like so:

```php
flash()->warning('Max file size is 5MB.')
	   ->warning('Username is required.')
	   ->danger('There was an error validating your information.');
```

Inside your views `flash()->level` and `flash()->message` attributes will return the level and message of the **last** message flashed.  To get a `Collection` of all messages flashed you have access to `flash()->all()`.

So for example to iterate through all messages flashed inside your view:

```php
@foreach (flash()->all() as $flash)
	<div class="alert alert-{{ $flash->level }}">{{ $flash->message }}</div>
@endforeach
```

`flash()->all()` returns Laravel's Collection class, so you have access to all methods Collection contains such as `groupBy()`, `chunk()`, `toJson()`, etc.  It could also just be treated as an array.

### Detecting flash notifications

To detect if any flash messages exist in the session, use `flash()->exists()`.

If you want to detect a specific level, use the `has()` method.  E.g. `flash()->has('error')`.  This works for single or multiple flashed messages.

It's best use to detect if a certain level was flashed and then display only the notifications of that level using the `get()` method.

```php
@if (flash()->has('error'))
	<ul class="errors">
		@foreach (flash()->get('error') as $flash)
			<li>{{ $flash->message }}</li>
		@endforeach
	</ul>
@endif
```

### Reflashing notifications

To reflash notification or all notifications to the next request use the function `again()`.

```php
flash()->again();
```

You're able to flash more messages before, or after calling the `flash()->again()` method.

## Helper function

You may also just pass a level and a message into the `flash()` helper function.

```php
flash('error', 'There was an error processing your request.');
```

## Single vs Multiple Flashes

Depending on what your application needs, set up your views to display flashes using a loop or just the attributes.  For example if your application is only going to flash one notification at a time, using `flash()->level` and `flash()->message` directly in your views is fine.

However, if your application will flash multiple notifications it's best to set up a `foreach` loop and iterate over `flash()->all()`.  Again you'll still have direct access to the `flash()->level` and `flash()->message` attributes even if multiple notifications were flashed, but there values will be of the last notification flashed.

## Examples

Below are some basic blade templates/examples.


Single flash example
```php
@if (flash()->exists())
	<div class="alert alert-{{ flash()->level }}">
		{{ flash()->message }}
	</div>
@endif
```

Multiple flash example
```php
@if (flash()->exists())
	@foreach (flash()->all() as $flash)
		<div class="alert alert-{{ $flash->level }}">
			{{ $flash->message }}
		</div>
	@endforeach
@endif
```

Detecting a specific level
```php
@if (flash()->has('success'))
	<script>
		launchFireworks();
	</script>
@endif
```

Detecting a specific level and displaying multiple messages
```php
@if (flash()->has('error'))
	<ul class="errors">
		@foreach (flash()->get('error') as $flash)
			<li>{{ $flash->message }}</li>
		@endforeach
	</ul>
@endif
```

## Details

The data is stored inside Laravel's session under the key `flashes` in the following format:

```php
[
	'flashes' => [
		['level' => '', 'message' => ],
		['level' => '', 'message' => ]
	]
]
```

`flash()->all()` returns a Collection of Flash object.  A flash object contains two attributes: `level`, and `message` which are publicly accessable.  You may also access them via array syntax as such: `$flash['level']` and `$flash['message']`.  A `Flash` object has a `toArray()` and `toJson()` method.

## Contributions

Feel free to [create an issue](https://github.com/lukebro/flash/issues) with a feature request or bug report.  If you would rather implement the feature or fix the bug yourself, create a pull request.  I'm not sure how far I want to take this package, as of right now I'm pretty happy where it stands.

Contributions can be made to the documentation as well, not just code.  If you think you can explain the API better than I can, please do and I'll be happy to merge it in.

I'd love to hear your feedback in how the package feels and what you don't like about it.

## Credits

The inspiration for this extremely small package comes from Jeffrey Way's Laracast video: [Elegant Flash Messaging](https://laracasts.com/series/build-project-flyer-with-me/episodes/9).
Definitely check it out.  Jeffrey mentions that you could write the API in a "magical" way, so I thought it would be cool to implement.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
