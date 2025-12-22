# Create Columns

The `Column` class allows developers to create reusable, self-contained columns for the post listing table in the WordPress admin. These custom columns can display post meta, taxonomy values, or any custom data related to the post or taxonomy.

Columns are defined by extending the abstract `PostTypes\Column` class and implementing the required `name()` method, along with any optional logic such as rendering, sorting, or changing the label.

## Creating a Custom Column

To create a custom column, extend the base `Column` class and implement the methods you need. Here's an example of a `PriceColumn` that pulls a `_price` meta field from the post and displays it in the admin list table:

```php
use PostTypes\Column;

class PriceColumn extends Column
{
    /**
     * Defines the column key used internally.
     *
     * @return string.
     */
    public function name(): string
    {
        return 'price';
    }

    /**
     * Define the column label.
     *
     * @return string
     */
    public function label(): string
    {
        return __( 'Price', 'my-text-domain' );
    }

    /**
     * Populate column callback.
     *
     * @return callable
     */
    public function populate(): callable
    {
        return function( int $post_id ) {
            echo '$' . get_post_meta( $post_id, '_price', true );
        }
    }

    /**
     * Handle sorting the column by modifying the admin query.
     *
     * @return callable
     */
    public function sort(): callable
    {
        return function( \WP_Query $query ) {
            $query->set( 'meta_key', '_price' );
            $query->set( 'orderby', 'meta_value_num' );
        };
    }
}
```

## Adding the Column to a Post Type

Once you’ve defined your custom column, you can add it to a PostType using the `$columns->column()` method inside your `PostType` or `Taxonomy` class:

```php
use PostTypes\PostType;

class Book extends PostType
{
    //...

    public function columns( Columns $columns ): Columns
    {
        $columns->column( new PriceColumn );

        return $columns;
    }
}
```
