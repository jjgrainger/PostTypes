# Modify columns

To modify a taxonomies admin columns use the `column()` method. This method accepts the `PostTypes\Columns` manager which has a variety of methods to help fine tune admin table columns.

## Add Columns

Use the `add` method to create a column and initiate the fluent column builder API. The column builder provides useful methods for defining a number of column attributes.

```php
use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    //...

    /**
     * Set the Taxonomy admin columns.
     *
     * @return Columns
     */
    public function columns( Columns $columns ): Columns
    {
        // Add a new Popularity column.
        $columns->add( 'popularity' )
            // Set the label.
            ->label( __( 'Popularity', 'my-text-domain' ) );
            // Position the column after the title column.
            ->after( 'title' )
            // Populate the popularity column with term meta.
            >populate( 'popularity', function( $term_id ) {
                echo get_term_meta( $term_id, '_popularity', true );
            } );
            // Make the popularity column sortable.
            ->sortable( 'popularity', function( WP_Term_Query $query ) {
                $query->query_vars['meta_key'] = '_popularity';
                $query->query_vars['orderby'] = 'meta_value_num';
            } );

        return $columns;
    }
}
```

## Populate Columns

To populate any column use the `populate()` method and passing a callback function.

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
    public function columns( Columns $columns ): Columns
    {
        $columns->add( 'popularity' )->populate( function( $term_id ) {
            echo get_term_meta( $term_id, '_popularity', true );
        } );

        return $columns;
    }
}
```

## Sortable Columns

To define which columns are sortable use the `sort()` method.

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
    public function columns( Columns $columns ): Columns
    {
        // Make the popularity column sortable.
        $columns->add( 'popularity' )->sort( function( WP_Term_Query $query ) {
            $query->query_vars['meta_key'] = '_popularity';
            $query->query_vars['orderby'] = 'meta_value_num';
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
    public function columns( Columns $columns ): Columns
    {
        // Hide the Description column.
        $columns->hide( [ 'description' ] );

        return $columns;
    }
}
```

## Column Positioning

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
    public function columns( Columns $columns ): Columns
    {
        // Position the new Popularity column.
        $columns->position( 'popularity', 'after', 'title' );

        return $columns;
    }
}
```
