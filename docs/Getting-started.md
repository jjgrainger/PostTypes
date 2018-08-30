# Getting Started

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
```

See Composers [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working Composer and autoloading.

## Basic Usage

Below is a basic example of setting up a simple books PostType.

```php
// Require the Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Import PostTypes
use PostTypes\PostType;

// Create a books PostType
$books = new PostType('book');

// Hide the date and author columns
$books->columns()->hide(['date', 'author']);

// Set the Books menu icon
$books->icon('dashicons-book-alt');

// Register the PostType to WordPRess
$books->register();
```
