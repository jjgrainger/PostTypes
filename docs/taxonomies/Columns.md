## Columns

You can now modify `Taxonomy` columns using the same methods as you would for a `PostType`. For example:

```php
// Create a taxonomy.
$genres = new Taxonomy( 'genre' );

// Add a column to the taxonomy admin table.
$genres->columns()->add( [
	'popularity' => __( 'Popularity' ),
] );

// Register the taxonomy to WordPress.
$genres->register();
```

#### Add Columns

To add columns to the admin edit screen pass an array of column slugs and labels to the `add()` method.

```php
// Add columns and set their labels.
$genres->columns()->add( [
    'popularity' => __( 'Popularity' ),
] );
```

#### Hide Columns

To hide columns pass the column slug to the `hide()` method. For multiple columns pass an array of column slugs.

```php
$genres->columns()->hide( 'description' );
```

#### Column Order

To rearrange columns pass an array of column slugs and position to the `order()` method. Only columns you want to reorder need to be set, not all columns. Positions are based on a zero based index.

```php
$genres->columns()->order( [
    'popularity' => 2,
] );
```

#### Set Columns

To set all columns to display pass an array of the column slugs and labels to the `set()` method. This overrides any other configuration set by the methods above.

```php
$genres->columns()->set( [
    'cb'          => '<input type="checkbox" />',
    'name'        => __( 'Name' ),
    'description' => __( 'Description' ),
    'slug'        => __( 'Slug' ),
    'popularity'  => __( 'Popularity' ),
] );
```

#### Populate Columns

To populate any column use the `populate()` method, by passing the column slug and a callback function.

Taxonomy columns work differently to post type columns. The callback receives 3 arguments, the columns content, the column name and the term ID. Also, the [hook used](https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/) is a filter, so the column value must be returned.

```php
$genres->columns()->populate( 'popularity', function ( $content, $column, $term_id ) {
    return get_term_meta( $term_id, 'popularity', true );
} );
```

#### Sortable Columns

To define which custom columns are sortable use the `sortable()` method. This method accepts an array of column slugs and an array of sorting options.

The first option is the term `meta_key` to sort the columns by.

The second option is how the items are ordered, either numerically (`true`) or alphabetically (`false`) by default.

```php
// Make both the price and rating columns sortable and ordered numerically.
$genres->columns()->sortable( [
    'popularity'  => [ 'popularity', true ],
] );
```

