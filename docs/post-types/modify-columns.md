# Modify columns

To modify a post types admin columns use the `column()` method. This method accepts the `PostTypes\Columns` manager which has a variety of methods to help fine tune admin table columns.

## Add Columns

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
    public function columns( Columns $columns ): Columns
    {
        // Add a new price column.
        $columns->add( 'price', __( 'Price', 'my-text-domain' ) );

        // Populate the price column with post meta.
        $columns->populate( 'price', function( $post_id ) {
            echo '$' . get_post_meta( $post_id, '_price', true );
        } );

        // Make the price column sortable.
        $columns->sortable( 'price', function( WP_Query $query ) {
            $query->set( 'meta_key', 'price' );
            $query->set( 'orderby', 'meta_value_num' );
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
    public function columns( Columns $columns ): Columns
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
    public function columns( Columns $columns ): Columns
    {
        // Make the rating column sortable.
        $columns->sortable( 'rating', function( WP_Query $query ) {
            $query->set( 'meta_key', 'rating' );
            $query->set( 'orderby', 'meta_value_num' );
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
    public function columns( Columns $columns ): Columns
    {
        // Hide the Author and Date columns
        $columns->hide( [ 'author', 'date' ] );

        return $columns;
    }
}
```

## Position Columns

To rearrange columns use the `position` method to set a columns position before or after another.


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
    public function columns( Columns $columns ): Columns
    {
        // Position the rating column after the title column.
        $columns->position( 'rating', 'after', 'title' );

        return $columns;
    }
}
```


