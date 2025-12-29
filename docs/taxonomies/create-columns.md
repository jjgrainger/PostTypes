# Create Columns

The `Column` class allows developers to create reusable, self-contained columns for the taxonomy and post listing table in the WordPress admin. These custom columns can display any custom data related to the taxonomy.

Columns are defined by extending the abstract `PostTypes\Column` class and implementing the required `name()` method, along with any optional logic such as rendering, sorting, or changing the label.

## Creating a Custom Column

To create a custom column, extend the base `Column` class and implement the methods you need. Here's an example of a `PopularityColumn` that pulls a `_popularity` meta field from the term and displays it in the admin table:

```php
use PostTypes\Column;

class PopularityColumn extends Column
{
    /**
     * Defines the column key used internally.
     *
     * @return string.
     */
    public function name(): string
    {
        return 'populariy';
    }

    /**
     * Define the column label.
     *
     * @return string
     */
    public function label(): string
    {
        return __( 'Popularity', 'my-text-domain' );
    }

    /**
     * Position a column before/after another.
     *
     * @return array
     */
    public function position(): array
    {
        return $this->after( 'title' );
    }

    /**
     * Populate column callback.
     *
     * @return callable
     */
    public function populate(): callable
    {
        return function( int $term_id ) {
            echo get_term_meta( $term_id, '_popularity', true );
        };
    }

    /**
     * Handle sorting the column by modifying the admin query.
     *
     * @return callable
     */
    public function sort(): callable
    {
        return function( \WP_Term_Query $query ) {
            $query->query_vars['meta_key'] = '_popularity';
            $query->query_vars['orderby'] = 'meta_value_num';
        };
    }
}
```

## Adding the Column to a Taxonomy

Once you’ve defined your custom column, you can add it to a PostType using the `$columns->column()` method inside your `Taxonomy` class:

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    public function columns( Columns $columns ): Columns
    {
        $columns->column( new PopularityColumn );

        return $columns;
    }
}
```
