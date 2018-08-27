# Create a PostType

You can use PostTypes to create a new post type, or work with an existing post type.

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
