# Taxonomies

Taxonomies are created using the `Taxonomy` class. This works indetically to the `PostType` class and holds similar methods.

## Create a new taxonomy

To create a new taxonomy simply pass the taxonomy name to the `Taxonomy` class constructor. Labels and the taxonomy slug are generated from the taxonomy name.

```php
use PostTypes\Taxonomy;

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

## Working with exisiting Taxonomies

You can work with existing taxonomies by passing the taxonomy name to the Taxonoy constructor. Once you have made your changes you need to register them to WordPress using the `register()` method.

```php
// Create a new Taxonomy object for an existing taxonomy
$tags = new Taxonomy('post_tags');

// Modify the taxonomy...

// Regsiter changes to WordPress
$tags->register();
```

## Add to PostType

```php
$genres = new Taxonomy('genre');

$genres->posttype('books');

$genres->register();
```

## Columns

You can now modify a `Taxonomy` columns using the same methods as you would for a `PostType`. For example:

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
Please refer to the [PostTypes column documentation](02-posttypes.md#columns) for more on how to work with the column manager.
