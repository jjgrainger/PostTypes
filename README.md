# PostTypes v2.0.1

[![Build Status](https://flat.badgen.net/travis/jjgrainger/PostTypes?label=build)](https://travis-ci.org/jjgrainger/PostTypes) [![Latest Stable Version](https://flat.badgen.net/github/release/jjgrainger/PostTypes/stable)](https://packagist.org/packages/jjgrainger/posttypes) [![Total Downloads](https://flat.badgen.net/packagist/dt/jjgrainger/PostTypes)](https://packagist.org/packages/jjgrainger/posttypes) [![License](https://flat.badgen.net/github/license/jjgrainger/PostTypes)](https://packagist.org/packages/jjgrainger/posttypes)

> Simple WordPress custom post types.

## Requirements

* PHP >=5.6
* [Composer](https://getcomposer.org/)
* [WordPress](https://wordpress.org) >=3.8

## Installation

#### Install with composer

Run the following in your terminal to install PostTypes with [Composer](https://getcomposer.org/).

```
$ composer require jjgrainger/posttypes
```

PostTypes uses [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading and cand be used with Composers autoloader. Below is a basic example of getting started, though your setup maybe different depending on how you are using Composer.

```php
require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;

$books = new PostType('book');

$books->register();
```

See Composers [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working Composer and autoloading.

## Basic Usage

Below is a basic example of setting up a simple books PostType with a genre Taxonomy. For more information, check out the [online documentation here](https://posttypes.jjgrainger.co.uk).

```php
// Require the Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Import PostTypes
use PostTypes\PostType;

// Create a books PostType
$books = new PostType('book');

// Attach the genre taxonomy, this is created below
$books->taxonomy('genre');

// Hide the date and author columns
$books->columns()->hide(['date', 'author']);

// Set the Books menu icon
$books->icon('dashicons-book-alt');

// Register the PostType to WordPress
$books->register();

// Create a genre Taxonomy
$genres = new Taxonomy('genre');

// Set options for the Taxonomy
$genres->options([
    'hierarchical' => false,
]);

// Register the Taxonomy to WordPress
$genres->register();
```

## Notes

* The full documentation can be found online at [posttypes.jjgrainger.co.uk](https://posttypes.jjgrainger.co.uk)
* The class has no methods for making custom fields for post types, use [Advanced Custom Fields](http://advancedcustomfields.com)
* The books example used in the README.md can be found in the [examples/books.php](examples/books.php)
* Licensed under the [MIT License](https://github.com/jjgrainger/wp-posttypes/blob/master/LICENSE)
* Maintained under the [Semantic Versioning Guide](http://semver.org)

## Author

**Joe Grainger**

* [http://jjgrainger.co.uk](http://jjgrainger.co.uk)
* [http://twitter.com/jjgrainger](http://twitter.com/jjgrainger)
