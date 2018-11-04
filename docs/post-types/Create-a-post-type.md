# Create a Post Type

You can use PostTypes to create a new post type, or work with an [existing post type](#work-with-existing-posttypes). PostTypes can be included in your theme or plugins.

## Create a new Post Type

To create a new post type pass the post types name to the class constructor. In order to apply changes to WordPress you must call the `register()` method.

```php
use PostTypes\PostType;

// Create a book post type.
$books = new PostType( 'book' );

// Register the post type to WordPress.
$books->register();
```

{% hint style="info" %}
The `register()` method hooks into WordPress and sets up the different actions and filters to create your custom post type. You do not need to add any of your PostTypes code in actions/filters. Doing so may lead to unexpected results.
{% endhint %}

### Set names

The post type labels and slugs are automatically generated from the post type name, however, you can set these manually by passing an array of names to the class constructor.

```php
$names = [
	'name'     => 'book',
	'singular' => 'Book',
	'plural'   => 'Books',
	'slug'     => 'books',
];

$books = new PostType( $names );

$books->register();
```

The following names are accepted.

| Key | Description | Example |
| --- | --- | --- |
| `name` | is the post type name | *required*, singular, lowercase, underscores |
| `singular` | is the singular label for the post type | e.g 'Book', 'Person' |
| `plural` | is the plural label for the post type | e.g 'Books', 'People' |
| `slug` | is the post type slug used in the permalinks | plural, lowercase, hyphens |

The only required field is the post type's `name`, all others are optional.

### Set options

Options for the post type are set by passing an array as the second argument in the class constructor.

```php
$options = [
	'has_archive' => false,
];

$books = new PostType( 'book', $options );

$books->register();
```

Alternatively, you can set options using the `options()` method.

```php
$books = new PostType( 'book' );

$books->options( [
	'has_archive' => false,
] );

$books->register();
```

The options match the arguments passed to the `register_post_type()` WordPress function. All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type#Parameters)

### Set labels

You can set the labels for the post type by passing an array as the third argument in the class constructor.

```php
$labels = [
	'add_new_item' => __( 'Add new Book' ),
];

$books = new PostType( 'book', $options, $labels );

$books->register();
```

Alternatively, you can use the `labels()` method to set the labels for the post type.

```php
$books = new PostType( 'books' );

$books->labels( [
	'add_new_item' => __( 'Add new Book' ),
] );

$books->register();
```

All available labels are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_post_type#labels)

## Work with existing Post Types

To work with existing post types pass the post type name into the constructor. Be careful and avoid using global variables (e.g `$post`) which can lead to unwanted results.

```php
// Create a PostType object for an existing post type in WordPress.
$blog = new PostType( 'post' );

// Make changes to the post type...

// You still need to register the changes to WordPress.
$blog->register();
```
