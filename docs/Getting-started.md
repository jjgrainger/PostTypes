# Getting Started

## Requirements

* PHP >=7.2
* [Composer](https://getcomposer.org/)
* [WordPress](https://wordpress.org) >=5.1

## Installation

#### Install with composer

Run the following in your terminal to install PostTypes with [Composer](https://getcomposer.org/).

```
$ composer require jjgrainger/posttypes
```

PostTypes uses [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading and can be used with the Composer's autoloader. Below is a basic example of getting started, though your setup may be different depending on how you are using Composer.

```php
require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;

$books = new PostType('book');

$books->register();
```

See Composer's [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working with Composer and autoloading.

## Basic Usage

Below is a basic example of setting up a simple book post type with a genre taxonomy.

```php
// Require the Composer autoloader.
require __DIR__ . '/vendor/autoload.php';

// Import PostTypes.
use PostTypes\PostType;

// Create a book post type.
$books = new PostType( 'book' );

// Attach the genre taxonomy (which is created below).
$books->taxonomy( 'genre' );

// Hide the date and author columns.
$books->columns()->hide( [ 'date', 'author' ] );

// Set the Books menu icon.
$books->icon( 'dashicons-book-alt' );

// Register the post type to WordPress.
$books->register();

// Create a genre taxonomy.
$genres = new Taxonomy( 'genre' );

// Set options for the taxonomy.
$genres->options( [
    'hierarchical' => false,
] );

// Register the taxonomy to WordPress.
$genres->register();
```
