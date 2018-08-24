# PostTypes

## Create a new PostType

A new post type can be created by simply passing the post types name to the class constructor. To register the post type to WordPress you must call the `register()` method.

```php
use PostTypes\PostType;

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


## Working with exisiting PostTypes

To work with exisiting post types simple pass the post type name into the object. Be careful using global variables (i.e `$post`) which can lead to unwanted results.

```php
// Create a PostType object for an existing post type in WordPress
$blog = new PostType('post');

// Make changes to the post type...

// You still need to register the changes to WordPress
$blog->register();
```

## Add Taxonomies

New and existing Taxonomies can be added to a PostType using its `taxonomy()` method by passing the taxonomy name.

```php
// Create a books post type
$books = new PostType('book');

// Add the genre taxonomy to the book post type
$books->taxonomy('genre');

// Register the post type to WordPress
$books->register();
```
See the [documentation](#) on creating a new Taxonomy. Taxonomies and PostTypes can be created in any order.

## Filters

Set the taxonomy filters on the post type admin edit screen by passing an array to the `filters()` method

```php
$books->filters(['genres', 'category']);
```

The order of the filters are set by the order of the items in the array. An empty array will remove all dropdown filters.

## Columns

You can now modify a PostTypes admin columns using the `column()` manager.

#### Adding Columns

You can add columns to the admin edit screen by passing an array of slugs and labels to the `add()` method.

```php
// add multiple columns and set their labels
$books->columns()->add([
    'rating' => __('Rating'),
    'price' => __('Price')
]);
```

#### Hiding Columns

You can hide columns by passing the column slug to the `hide()` method. You can hide multiple columns by passing an array of column slugs.

```php
$books->columns()->hide('author');

$books->columns()->hide(['author', 'date']);
```

#### Set Columns

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

#### Column Order

After hiding and adding columns you may want to rearrange the column order. To do this use the `order()` method. You only have to pass through an array of the columns you want to reposition, not all of them. Their positions are based on a zero based index.

```php
$books->columns()->order([
    'rating' => 2,
    'genre' => 4
]);
```

#### Populating Columns

You can populate any column using the `populate()` method and passing the column slug and function.

```php
$books->columns()->populate('rating', function($column, $post_id) {
    echo get_post_meta($post_id, 'rating', true) . '/10';
});
```

#### Sorting Columns

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

## Menu Icons

With WordPress 3.8 comes [Dashicons](https://developer.wordpress.org/resource/dashicons/) an icon font you can use with your custom post types.

```php
$books->icon('dashicons-book-alt');
```

## Flush Rewrite Rules

You can programmatically recreate the sites rewrite rules with the `flush()` method.
This is an expensive operation and should be used with caution, see [codex](https://codex.wordpress.org/Function_Reference/flush_rewrite_rules) for more.

```php
$books->flush();
```
