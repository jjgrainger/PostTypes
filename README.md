# PostTypes v2.0.1

[![Build Status](https://travis-ci.org/jjgrainger/PostTypes.svg?branch=master)](https://travis-ci.org/jjgrainger/PostTypes) [![Total Downloads](https://poser.pugx.org/jjgrainger/posttypes/downloads)](https://packagist.org/packages/jjgrainger/posttypes) [![Latest Stable Version](https://poser.pugx.org/jjgrainger/posttypes/v/stable)](https://packagist.org/packages/jjgrainger/posttypes) [![License](https://poser.pugx.org/jjgrainger/posttypes/license)](https://packagist.org/packages/jjgrainger/posttypes)

> Simple WordPress custom post types.

## Installation

#### Install with composer

Run the following in your terminal to install PostTypes with [Composer](https://getcomposer.org/).

```
$ composer require jjgrainger/posttypes
```

As PostTypes uses [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading you will need to use Composers autoloader. Below is a basic example of getting started with the class, though your setup maybe different depending on how you are using composer.

```php
require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;

$books = new PostType('book');

$books->register();
```

See Composers [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working Composer and autoloading.

## Post Types

#### Create a new Post Type

A new post type can be created by simply passing the post types name to the class constructor. To register the post type to WordPress you must call the `register()` method.

```php
// Create a book post type
$books = new PostType('book');

// Register the post type to WordPress
$books->register();
```

#### Defining names

The post type labels and slugs are automatically generated from the post type name, however, if you need to define these manually pass an array of names to the post types constructor.

```php
$names = [
    'name' => 'book',
    'singular' => 'Book',
    'plural' => 'Books',
    'slug' => 'books'
];

$books = new PostType($names);

$books->register();
```

It can accept the following names:

* `name` is the post type name (*required*, singular, lowercase, underscores)
* `singular` is the singular label for the post type (Book, Person)
* `plural` is the plural label for the post type (Books, People)
* `slug` is the post type slug used in the permalinks (plural, lowercase, hyphens)

The only required name is the post types `name`.

#### Adding options

You can define the options for the post type by passing an array as the second argument in the class constructor.

```php
$options = [
    'has_archive' => false
];

$books = new PostType('book', $options);

$books->register();
```

All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type)

#### Adding labels

You can define the labels for the post type by passing an array as the third argument in the class constructor.

```php
$labels = [
    'featured_image' => __( 'Book Cover Image' ),
];

$books = new PostType('book', $options, $labels);

$books->register();
```

Alternatively, you can use the `labels()` method to set the labels for the post type.

```php
$books = new PostType('books');

$books->labels([
    'add_new_item' => __('Add new Book')
]);

$books->register();
```

All available labels are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type)

#### Exisiting Post Types

To work with exisiting post types simple pass the post type name into the object. Be careful using global variables (i.e `$post`) which can lead to unwanted results.

```php
// Create a PostType object for an existing post type in WordPress
$blog = new PostType('post');

// Make changes to the post type...

// You still need to register the changes to WordPress
$blog->register();
```

## Taxonomies

Taxonomies are created using the `Taxonomy` class. This works indetically to the `PostType` class and holds similar methods.

#### Create new taxonomy

To create a new taxonomy simply pass the taxonomy name to the `Taxonomy` class constructor. Labels and the taxonomy slug are generated from the taxonomy name.

```php
// Create a new taxonomy
$genres = new Taxonomy('genre');

// Register the taxonomy to WordPress
$genres->register();
```

#### Defining names

You can define names by passing an array as the first argument. Only the `name` is required.

* `name` is the post type name
* `singular` is the singular label for the post type
* `plural` is the plural label for the post type
* `slug` is the post type slug used in the permalinks

```php
$names = [
    'name' => 'genre',
    'singular' => 'Genre',
    'plural' => 'Genres',
    'slug' => 'genres'
];

$genres = new Taxonomy($names);

$genres->register();
```

#### Adding options

You can further customise taxonomies by passing an array of options as the second argument to the method.

```php
$options = [
    'hierarchical' => false,
];

$genres = new Taxonomy('genre', $options);

$genres->register();
```

All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_taxonomy)

#### Adding labels

You can define the labels for a Taxonomy by passing an array as the third argument in the class constructor.

```php
$labels = [
    'add_new_item' => __('Add new Genre'),
];

$genres = new Taxonomy('genres', $options, $labels);

$genres->register();
```

Alternatively, you can use the `labels()` method to set the labels for the post type.

```php
$genres = new Taxonomy('genre');

$genres->labels([
    'add_new_item' => __('Add new Genre')
]);

$genres->register();
```

All available labels are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_taxonomy)

#### Exisiting Taxonomies

You can work with existing taxonomies by passing the taxonomy name to the Taxonoy constructor. Once you have made your changes you need to register them to WordPress using the `register()` method.

```php
// Create a new Taxonomy object for an existing taxonomy
$tags = new Taxonomy('post_tags');

// Modify the taxonomy...

// Regsiter changes to WordPress
$tags->register();
```

## Link Taxonomies and PostTypes

Depending on the object type (Taxonomy/PostType) you can link the two together with the respective methods.

For registering a Taxonomy to a PostType use the `taxonomy()` method.

For regsitering a PostType to a Taxonomy use the `posttype()` method.

```php
// Create a books post type
$books = new PostType('book');

// Add the genre taxonomy to the book post type
$books->taxonomy('genre');

// Register the post type to WordPress
$books->register();

// Create the genre taxonomy
$genres = new Taxonomy('genre');

// Use this method instead of the PostTypes taxonomy() method
$genres->posttype('book');

// register the genre taxonomy to WordPress
$genres->register();
```

## Admin Edit Screen

#### Filters

Set the taxonomy filters on the post type admin edit screen by passing an array to the `filters()` method

```php
$books->filters(['genres', 'category']);
```

The order of the filters are set by the order of the items in the array. An empty array will remove all dropdown filters.

#### Columns

You can now modify a `Taxonomy` columns using exactly the same methods listed below. For example:

```php
// Create a taxonomy
$genres = new Taxonomy('genre');

// Add a column to the taxonomy admin table
$genres->columns()->add([
    'popularity' => __('Popularity')
]);

// Register the taxonomy to WordPress
$genres->register();
```

###### Adding Columns

You can add columns to the admin edit screen by passing an array of slugs and labels to the `add()` method.

```php
// add multiple columns and set their labels
$books->columns()->add([
    'rating' => __('Rating'),
    'price' => __('Price')
]);
```

###### Hiding Columns

You can hide columns by passing the column slug to the `hide()` method. You can hide multiple columns by passing an array of column slugs.

```php
$books->columns()->hide('author');

$books->columns()->hide(['author', 'date']);
```

###### Set Columns

You can force set all the columns to display on the admin page with the `set()` method by passing an array of the column slugs and labels.

```php
$books->columns()->set([
    'cb' => '<input type="checkbox" />',
    'title' => __("Title"),
    'genre' => __("Genres"),
    'rating' => __("Rating"),
    'date' => __("Date")
]);
```

###### Column Order

After hiding and adding columns you may want to rearrange the column order. To do this use the `order()` method. You only have to pass through an array of the columns you want to reposition, not all of them. Their positions are based on a zero based index.

```php
$books->columns()->order([
    'rating' => 2,
    'genre' => 4
]);
```

###### Populating Columns

You can populate any column using the `populate()` method and passing the column slug and function.

```php
$books->columns()->populate('rating', function($column, $post_id) {
    echo get_post_meta($post_id, 'rating', true) . '/10';
});
```

###### Sorting Columns

You can choose which custom columns are sortable with the `sortable()` method. This method accepts an array of column slugs and an array of sorting options.

The first option is the `meta_key` to sort the colums by.

The second option is whether to order the items numerically (`true`) or alphabetically (`false`) by default.

```php
// will make both the price and rating columns sortable and ordered numerically
$books->columns()->sortable([
    'price' => ['price', true],
    'rating' => ['rating', true]
]);
```

#### Menu Icons

With WordPress 3.8 comes [Dashicons](https://developer.wordpress.org/resource/dashicons/) an icon font you can use with your custom post types.

```php
$books->icon('dashicons-book-alt');
```

### Flush Rewrite Rules

You can programmatically recreate the sites rewrite rules with the `flush()` method.
This is an expensive operation and should be used with caution, see [codex](https://codex.wordpress.org/Function_Reference/flush_rewrite_rules) for more.

```php
$books->flush();
```

### Translation

Since 2.0 the `translation()` method has been removed. You can translate any labels and names when you assign them to the PostType or Taxonomy. It was removed to provide more control to the developer while encouraging best practices around internationalizing plugins and themes set out by [WordPress](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/).

```php
// Translating the PostType plural and singular names
$books = new PostType([
    'name' => 'book',
    'singular' => __('Book', 'YOUR_TEXTDOMAIN'),
    'plural' => __('Books', 'YOUR_TEXTDOMAIN'),
    'slug' => 'books'
]);

// Translating Labels
$books->labels([
    'add_new_item' => __('Add new Book', 'YOUR_TEXTDOMAIN')
]);
```

## Notes

* The class has no methods for making custom fields for post types, use [Advanced Custom Fields](http://advancedcustomfields.com)
* The books example used in the README.md can be found in the [examples/books.php](examples/books.php)
* Licensed under the [MIT License](https://github.com/jjgrainger/wp-posttypes/blob/master/LICENSE)
* Maintained under the [Semantic Versioning Guide](http://semver.org)

## Author

**Joe Grainger**

* [http://jjgrainger.co.uk](http://jjgrainger.co.uk)
* [http://twitter.com/jjgrainger](http://twitter.com/jjgrainger)
