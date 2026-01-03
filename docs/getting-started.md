# Getting Started

## Requirements

* PHP >=8.1
* [Composer](https://getcomposer.org/)
* [WordPress](https://wordpress.org) >=6.3

## Installation

#### Install with composer

Run the following in your terminal to install PostTypes with [Composer](https://getcomposer.org/).

```
$ composer require jjgrainger/posttypes
```

PostTypes uses [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading and can be used with the Composer's autoloader. See Composer's [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working with Composer and autoloading.

## Basic Usage

#### Create a custom post type

Custom post types are defined as classes that extend the base `PostType` class. At a minimum, the `name` method must be implemented to define the post type slug. All other methods are optional and allow you to configure labels, options, taxonomies, admin columns, filters, and more as needed.

```php
<?php

use PostTypes\PostType;
use PostTypes\Columns;

class Book extends PostType {
    /**
     * Define the Post Type name.
     */
    public function name(): string {
        return 'book';
    }

    /**
     * Define the Post Type labels.
     */
    public function labels(): array {
        return [
            'name'               => __( 'Book', 'text-domain' ),
            'singular_name'      => __( 'Book', 'text-domain' ),
            'menu_name'          => __( 'Books', 'text-domain' ),
            'all_items'          => __( 'Books', 'text-domain' ),
            'add_new'            => __( 'Add New', 'text-domain' ),
            'add_new_item'       => __( 'Add New Book', 'text-domain' ),
            'edit_item'          => __( 'Edit Book', 'text-domain' ),
            'new_item'           => __( 'New Book', 'text-domain' ),
            'view_item'          => __( 'View Book', 'text-domain' ),
            'search_items'       => __( 'Search Books', 'text-domain' ),
            'not_found'          => __( 'No Books found', 'text-domain' ),
            'not_found_in_trash' => __( 'No Books found in Trash', 'text-domain' ),
            'parent_item_colon'  => __( 'Parent Book', 'text-domain' ),
        ];
    }

    /**
     * Define Post Type feature supports.
     */
    public function supports(): array {
        return [
            'title',
            'editor',
            'thumbnail',
            'custom-fields',
        ];
    }

    /**
     * Define Taxonomies associated with the Post Type.
     */
    public function taxonomies(): array {
        return [
            'genre',
            'category',
        ];
    }

    /**
     * Set the menu icon for the Post Type.
     */
    public function icon(): string {
        return 'dashicons-book';
    }

    /**
     * Set the admin post table filters.
     */
    public function filters(): array {
        return [
            'genre',
            'category',
        ];
    }

    /**
     * Define the columns for the admin post table.
     */
    public function columns(Columns $columns): Columns {
        // Remove the author and date column.
        $columns->remove( [ 'author', 'date' ] );

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

### Register a custom post type

Once the custom post type class is created it can be registered to WordPress by instantiating and call the register method.

```php
// Instantiate the Book PostType class.
$book = new Book;

// Register the Book PostType to WordPress.
$book->register();
```
