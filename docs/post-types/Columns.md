# Columns

To modify a post types admin columns use the `column()` manager. It has a variety of methods to help fine tune admin table columns.

#### Add Columns

To add columns to the admin edit screen pass an array of column slugs and labels to the `add()` method.

```php
// Add multiple columns and set their labels.
$books->columns()->add( [
	'rating' => __( 'Rating' ),
	'price'  => __( 'Price' ),
] );
```

#### Hide Columns

To hide columns pass the column slug to the `hide()` method. For multiple columns pass an array of column slugs.

```php
$books->columns()->hide( 'author' );

$books->columns()->hide( [ 'author', 'date' ] );
```

#### Column Order

To rearrange columns pass an array of column slugs and position to the `order()` method. Only columns you want to reorder need to be set, not all columns. Positions are based on a zero based index.

```php
$books->columns()->order( [
	'rating' => 2,
	'genre'  => 4,
] );
```

#### Set Columns

To set all columns to display pass an array of the column slugs and labels to the `set()` method. This overrides any other configuration set by the methods above.

```php
$books->columns()->set( [
	'cb'     => '<input type="checkbox" />',
	'title'  => __( 'Title' ),
	'genre'  => __( 'Genres' ),
	'rating' => __( 'Rating' ),
	'date'   => __( 'Date' ),
] );
```

#### Populate Columns

To populate any column use the `populate()` method, by passing the column slug and a callback function.

```php
$books->columns()->populate( 'rating', function ( $column, $post_id ) {
	echo get_post_meta( $post_id, 'rating', true ) . '/10';
} );
```

#### Sortable Columns

To define which custom columns are sortable use the `sortable()` method. This method accepts an array of column slugs and an array of sorting options.

The first option is the `meta_key` to sort the columns by.

The second option is how the items are ordered, either numerically (`true`) or alphabetically (`false`) by default.

```php
// Make both the price and rating columns sortable and ordered numerically.
$books->columns()->sortable( [
	'price'  => [ 'price', true ],
	'rating' => [ 'rating', true ],
] );
```
