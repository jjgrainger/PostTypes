# PostTypes v1.1.2

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
```

See Composers [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working Composer and autoloading.

## Post Types

#### Create a new Post Type

A new post type can be created by simply passing the post types name to the class constructor.

```php
$books = new PostType('book');
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
```

All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type)

#### Adding labels

You can define the labels for the post type by passing an array as the third argument in the class constructor.

```php
$labels = [
    'featured_image' => __( 'Book Cover Image' ),
];

$books = new PostType('book', $options, $labels);
```

All available labels are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type)

#### Exisiting Post Types

To work with exisiting post types simple pass the post type name into the object. Be careful using global variables (i.e `$post`) which can lead to unwanted results.

```php
$blog = new PostType('post');
```

## Add Taxonomies

Adding taxonomies to a post type is easily achieved by using the `taxonomy()` method.

#### Create new taxonomy

To create a new taxonomy simply pass the taxonomy name to the `taxonomy()` method. Labels and the taxonomy slug are generated from the taxonomy name.

```php
$books->taxonomy('genre');
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

$books->taxonomy($names);
```

#### Adding options

You can further customise taxonomies by passing an array of options as the second argument to the method.

```php
$options = [
	'hierarchical' => false,
];

$books->taxonomy('genre', $options);
```

All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_taxonomy)

#### Adding Exisiting Taxonomies

You can add existing taxonomies by passing the taxonomy name to the `taxonomy()` method. This works with custom taxonomies too. You only need to pass the options/names for the taxonomy **once**, afterwards you only need to pass the taxonomy name.

```php
$books->taxonomy('post_tag');
```

## Admin Edit Screen

#### Filters

Set the taxonomy filters on the admin edit screen by passing an array to the `filters()` method

```php
$books->filters(['genres', 'category']);
```

The order of the filters are set by the order of the items in the array. An empty array will remove all dropdown filters.

#### Columns

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

Columns that are automatically populated with correct slug

* `post_id` - the post id
* `title` - the posts title with edit links
* `author` - the posts author
* `date` - the posts dates
* `{taxonomy_name}` - a list of the taxonomy terms attached to the post
* `thumbnail` - the post featured image
* `meta_{meta_key}` - the post meta for that key

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

The class is setup for translation, but if you need to set your own textdomain to work with your theme or plugin use the `translation()` method:

```php
$books->translation('your-textdomain');
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
