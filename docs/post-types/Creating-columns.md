# Custom Columns

The `Column` class allows developers to create reusable, self-contained columns for the post listing table in the WordPress admin. These custom columns can display post meta, taxonomy values, or any custom data related to the post.

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
     * @return void
     */
    public function populate( int $post_id ): void
    {
        echo '$' . get_post_meta( $post_id, '_price', true );
    }

    /**
     * Set the column can be sorted.
     *
     * @return boolean
     */
    public function isSortable(): bool
    {
        return true;
    }

    /**
     * Handle sorting the column by modifying the admin query.
     *
     * @param $query \WP_Query
     * @return void
     */
    public function sort(\WP_Query $query): void
    {
        $query->set( 'meta_key', '_price' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
```

## Adding the Column to a Post Type

Once you’ve defined your custom column, you can add it to a PostType using the `$columns->column()` method inside your `PostType` class:

```php
use PostTypes\PostType;

class Book extends PostType
{
    //...

    public function columns( Columns $columns ): Columns
    {
        $columns->add( new PriceColumn );
        return $columns;
    }
}
```
