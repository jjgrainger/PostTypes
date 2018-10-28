# Taxonomies

Taxonomies are created using the `Taxonomy` class. This works identically to the `PostType` class and holds similar methods.

## Create a new taxonomy

To create a new taxonomy pass the taxonomy name to the class constructor. Labels and the slug are generated from the taxonomy name.

```php
use PostTypes\Taxonomy;

// Create a new taxonomy
$genres = new Taxonomy('genre');

// Register the taxonomy to WordPress
$genres->register();
```

#### Set names

You can define names by passing an array as the first argument. Only `name` is required.

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

The following names are accepted.

| Key | Description | Example |
| --- | --- | --- |
| `name` | is the taxonomy name | *required*, singular, lowercase, underscores |
| `singular` | is the singular label for the taxonomy | e.g 'Genre', 'Category' |
| `plural` | is the plural label for the taxonomy | e.g 'Genres', 'Categories' |
| `slug` | is the taxonomy slug used in the permalinks | plural, lowercase, hyphens |

#### Add options

You can further customise taxonomies by passing an array of options as the second argument in the constructor.

```php
$options = [
    'hierarchical' => false,
];

$genres = new Taxonomy('genre', $options);

$genres->register();
```

Alternatively, you can set options using the `options()` method.

```php
$genres = new Taxonomy('genre');

$genres->options([
    'hierarchical' => false,
]);

$genres->register();
```

The options match the arguments passed to the `register_taxonomy()` WordPress function. All available options are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments).

#### Add labels

You can define the labels for a taxonomy by passing an array as the third argument in the class constructor.

```php
$labels = [
    'add_new_item' => __('Add new Genre'),
];

$genres = new Taxonomy('genres', $options, $labels);

$genres->register();
```

Alternatively, you can use the `labels()` method to set the labels for the taxonomy.

```php
$genres = new Taxonomy( 'genre' );

$genres->labels( [
	'add_new_item' => __( 'Add new Genre' ),
] );

$genres->register();
```

All available labels are on the [WordPress Codex](https://codex.wordpress.org/Function_Reference/register_taxonomy)

## Work with existing Taxonomies

You can work with existing taxonomies by passing the taxonomy name to the Taxonoy constructor. Once you have made your changes you need to register them to WordPress using the `register()` method.

```php
// Create a new Taxonomy object for an existing taxonomy.
$tags = new Taxonomy( 'post_tag' );

// Modify the taxonomy...

// Regsiter changes to WordPress.
$tags->register();
```
