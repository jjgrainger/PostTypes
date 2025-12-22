# Modify columns

To modify a post types admin columns use the `column()` method. This method accepts the `PostTypes\Columns` manager that has a variety of methods to help fine tune admin table columns.

## Add Columns

Use the `add` method to create a column and initiate the fluent column builder API. The column builder provides useful methods for defining a number of column attributes.

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
        $columns->add( 'price' )
            // Set the label.
            ->label( __( 'Price', 'my-text-domain' ) )
            // Position the column after the title column.
            ->after( 'title' )
            // Set the populate callback.
            ->populate( function( $post_id ) {
                echo '$' . get_post_meta( $post_id, '_price', true );
            } )
            // Set the sort callback.
            ->sort( function( WP_Query $query ) {
                $query->set( 'meta_key', 'price' );
                $query->set( 'orderby', 'meta_value_num' );
            } );

        return $columns;
    }
}
```

## Modify a column

Any column can be modified using the `modify` method.

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
        // Update the WordPress author column label.
        $columns->modify( 'author' )->label( __( 'Post Author', 'my-text-domain' ) );

        return $columns;
    }
}
```

## Position Columns

To rearrange columns use either the `before` or `after` methods to set a columns position before or after another.


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
        // Position the price column after the title column.
        $columns->add( 'price' )->after( 'title' );

        return $columns;
    }
}
```


## Populate Columns

To populate a column use the `populate()` method passing a callback function.

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
        $columns->add( 'price' )->populate( function( $post_id ) {
            echo '$' . get_post_meta( $post_id, '_price', true );
        } );

        return $columns;
    }
}
```

## Sortable Columns

To make a column sortable use the `sort()` method and pass the sorting callback.

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
        $columns->add( 'price' )->sort( function( WP_Query $query ) {
            $query->set( 'meta_key', 'price' );
            $query->set( 'orderby', 'meta_value_num' );
        } );

        return $columns;
    }
}
```

## Remove Columns

To remove columns pass the column slug to the `remove()` method. For multiple columns pass an array of column slugs.

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
        $columns->remove( [ 'author', 'date' ] );

        return $columns;
    }
}
```

## Whitelist Columns

Use the `only()` method to define what columns should appear by passing an array of column slugs.

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
        // Only show the checkbox, title and price columns.
        $columns->only( [ 'cb', 'title', 'price' ] );

        return $columns;
    }
}
```

## Low-level API

The Columns class has a low-level API that can continue to be used to make and modify columns, however it is recommended to use the column builder API shown above.

Below is an example of how to use the low-level API to create the price column.

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
        $columns->label( 'price', __( 'Price', 'my-text-domain' ) );

        // Position the column after the title column.
        $columns->position( 'price', 'after', 'title' );

        // Set the populate callback.
        $columns->populate( 'price', function( $post_id ) {
            echo '$' . get_post_meta( $post_id, '_price', true );
        } );

        // Set the sort callback.
        $columns->sort( 'price', function( WP_Query $query ) {
            $query->set( 'meta_key', 'price' );
            $query->set( 'orderby', 'meta_value_num' );
        } );

        return $columns;
    }
}
```

