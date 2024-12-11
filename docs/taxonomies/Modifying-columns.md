# Modifying columns

To modify a taxonomies admin columns use the `column()` method. This method accepts the `PostTypes\Columns` manager which has a variety of methods to help fine tune admin table columns.

## Adding Columns

To add columns to the admin list table pass an array of column slugs and labels to the `add()` method.

```php
use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Add a new Popularity column.
        $columns->add( 'popularity', __( 'Popularity', 'my-text-domain' ) );

        // Populate the popularity column with term meta.
        $columns->populate( 'popularity', function( $term_id ) {
            echo '$' . get_term_meta( $term_id, '_popularity', true );
        } );

        // Make the popularity column sortable.
        $columns->sortable( 'popularity', function( WP_Term_Query $query ) {
            $query->query_vars['orderby'] = 'meta_value_num';
            $query->query_vars['meta_key'] = 'popularity';
        } );

        return $columns;
    }
}
```

## Populate Columns

To populate any column use the `populate()` method, by passing the column slug and a callback function.

```php
use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        $columns->populate( 'popularity', function( $term_id ) {
            echo '$' . get_term_meta( $term_id, '_popularity', true );
        } );


        return $columns;
    }
}
```

## Sortable Columns

To define which custom columns are sortable use the `sortable()` method.

```php
use PostTypes\Taxonomy;
use PostTypes\Columns;
use WP_Term_Query;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Make the popularity column sortable.
        $columns->sortable( 'popularity', function( WP_Term_Query $query ) {
            $query->query_vars['orderby'] = 'meta_value_num';
            $query->query_vars['meta_key'] = 'popularity';
        } );

        return $columns;
    }
}
```

## Hide Columns

To hide columns pass the column slug to the `hide()` method. For multiple columns pass an array of column slugs.

```php
use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Hide the Description column.
        $columns->hide( [ 'description' ] );

        return $columns;
    }
}
```

## Column Order

To rearrange columns pass an array of column slugs and position to the `order()` method. Only olumns you want to reorder need to be set, not all columns.


```php
use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Order the new Popularity column.
        $columns->order( [
            'popularity' => 2,
        ] );

        return $columns;
    }
}
```


