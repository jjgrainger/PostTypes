# Modifying columns

To modify a post types admin columns use the `column()` method. This method accepts the `PostTypes\Columns` manager which has a variety of methods to help fine tune admin table columns.

## Adding Columns

To add columns to the admin edit screen pass an array of column slugs and labels to the `add()` method.

```php
use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType
{
    //...

    /**
     * Set the PostTypes admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Add a new price column.
        $columns->add( 'price', __( 'Price', 'my-text-domain' ) );

        // Populate the price column with post meta.
        $columns->populate( 'price', function( $post_id ) {
            echo '$' . get_post_meta( $post_id, '_price', true );
        } );

        // Make the price column sortable.
        $columns->sortable( 'price', function( WP_Query $query ) {
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'meta_key', 'price' );
        } );

        return $columns;
    }
}
```

## Populate Columns

To populate any column use the `populate()` method, by passing the column slug and a callback function.

```php
use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType
{
    //...

    /**
     * Set the PostTypes admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        $columns->populate( 'rating', function( $post_id ) {
            echo get_post_meta( $post_id, 'rating', true ) . '/10';
        } );

        return $columns;
    }
}
```

## Sortable Columns

To define which custom columns are sortable use the `sortable()` method.

```php
use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType
{
    //...

    /**
     * Set the PostTypes admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Make the rating column sortable.
        $columns->sortable( 'rating', function( WP_Query $query ) {
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'meta_key', 'rating' );
        } );

        return $columns;
    }
}
```

## Hide Columns

To hide columns pass the column slug to the `hide()` method. For multiple columns pass an array of column slugs.

```php
use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType
{
    //...

    /**
     * Set the PostTypes admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Hide the Author and Date columns
        $columns->hide( [ 'author', 'date' ] );

        return $columns;
    }
}
```

## Column Order

To rearrange columns pass an array of column slugs and position to the `order()` method. Only olumns you want to reorder need to be set, not all columns.


```php
use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType
{
    //...

    /**
     * Set the PostTypes admin columns.
     *
     * @return array
     */
    public function columns( Columns $column ): Columns
    {
        // Order the new Rating and Genre columns.
        $columns->order( [
            'rating' => 2,
            'genre'  => 4,
        ] );

        return $columns;
    }
}
```


