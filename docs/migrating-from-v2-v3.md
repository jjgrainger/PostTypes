# Migrating from PostTypes v2.x to v3.0

This guide highlights the key changes and migration steps for upgrading from PostTypes v2.x to v3.0. The v3.0 release introduces significant architectural improvements, stricter typing, and new extension points. Review and update your custom post types, taxonomies, and integrations as described below.

v3.0 shifts PostTypes to a declarative, class-based architecture to improve readability, testability, and long-term extensibility.

> **Important:** v3.0 is a breaking release. Existing v2.x post type and taxonomy definitions will not work without modification.

---

## Major Changes

### 1. **Abstract Base Classes & Contracts**
- `PostType` and `Taxonomy` are now **abstract classes** and implement new PSR-4 contracts in `src/Contracts/`.
- You must implement all required abstract methods (e.g., `name()`, `labels()`, `options()`, etc.) in your custom classes.
- The base classes no longer provide magic property population or dynamic label/option generation.

#### Previous PostTypes API

Previously, post types were instantiated and the object methods used to configure the post type programatically.

```php
<?php

// Import PostTypes.
use PostTypes\PostType;

// Create a book post type.
$books = new PostType( 'book' );

// Hide the date and author columns.
$books->columns()->hide( [ 'date', 'author' ] );

// Set the Books menu icon.
$books->icon( 'dashicons-book-alt' );

// Register the post type to WordPress.
$books->register();
```
#### New PostType API

PostType is an abstract class and methods are used to configure the post type declaratively.

```php
<?php

namespace App\PostTypes;

use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType {
    public function name(): string {
        return 'book';
    }

    public function columns( Columns $columns ): Columns {
        $columns->remove( [ 'date', 'author' ] );

        return $columns;
    }

    public function icon(): string {
        return 'dashicons-book-alt';
    }
}
```

Registration remains the same by instantiating class and calling the `register()` method.

```php
// inside functions.php or plugin file.

$books = new App\PostTypes\Books;
$books->register();
```

---

### 2. **Options, Labels, and Taxonomies**
All configuration (labels, options, taxonomies, supports, filters, columns, icon) must be provided via explicit methods. Only `name()` is strictly required; all other methods are optional and return sensible defaults.


```php
class Books extends PostType {
    public function name(): string {
        return 'book';
    }

    public function slug(): string {
        return 'books';
    }

    public function labels(): array {
        return [
            'name'          => __( 'Book', 'post-types' ),
            'singular_name' => __( 'Book', 'post-types' ),
            'plural_name'   => __( 'Book', 'post-types' ),
        ];
    }

    public function options(): array {
        return [
            'public' => true,
        ];
    }

    public function taxonomies(): array {
        return [ 'genres' ];
    }

    public function supports(): array {
        return [ 'title', 'editor' ];
    }

    public function filters(): array {
        return [ 'genres' ];
    }

    public function columns( Columns $columns ): Columns {
        $columns->remove( [ 'date', 'author' ] );

        return $columns;
    }

    public function icon(): string {
        return 'dashicons-book-alt';
    }
}
```

---

### 3. **Columns API**
The columns system is now managed via the `Columns` class, passed as a parameter to the PostType `columns()` method.


```php
class Books extends PostType {

    //...

    public function columns( Columns $columns ): Columns {

        $columns->label( 'rating', __( 'Rating', 'text-domain' ) );

        $columns->populate( 'rating', function( $post_id ) {
            echo get_post_meta( $post_id, 'rating', true );
        } );

        return $columns;
    }
}
```

Some methods on the `Columns` have changed or been replaced.

- `add` has been replaced with `label`.
- add()` and `modify()` now return a Column Builder instance for fluent column configuration.
- `order` has been removed and replaced with a `position` API.
- A new `column` method allows passing `Column` classes for creating complex columns.

The low-level `Columns` API is still available to use. For simple changes, you can call methods directly on the `Columns` instance. For more complex or fluent definitions, use the Column Builder via `add()` or `modify()`.

```php
class Books extends PostType {

    //...

    public function columns( Columns $columns ): Columns {

        // Column Builder usage.
        $columns->add( 'rating' )
            ->after( 'title' )
            ->label(__( 'Rating', 'text-domain' ) )
            ->populate( function ( $post_id ) {
                echo get_post_meta( $post_id, 'rating', true ) );
            } );

        return $columns;
    }
}
```

## Migration Steps

1. **Update all custom PostType and Taxonomy classes:**
    - Extend the new abstract base classes.
    - Implement required methods (at minimum `name()`).
    - Move configuration into explicit methods (labels, options, supports, etc).
2. **Update columns logic:**
    - Use the new `Columns` API in your `columns()` method.
3. **Update registration:**
    - Continue to call `register()` on your custom classes.
4. **Test thoroughly:**
    - Run your test suite and verify admin UI behavior.

---

## Example v2 vs v3

**v2.x:**
```php
// Import PostTypes.
use PostTypes\PostType;

// Create a book post type.
$books = new PostType( 'book' );

// Attach the genre taxonomy (which is created below).
$books->taxonomy( 'genre' );

// Hide the date and author columns.
$books->columns()->hide( [ 'date', 'author' ] );

// Set the Books menu icon.
$books->icon( 'dashicons-book-alt' );
```

**v3.0:**
```php
class Book extends PostType {
    public function name(): string {
        return 'book';
    }

    public function taxonomies(): array {
        return [ 'genre' ];
    }

    public function columns(Columns $columns): Columns {
        $columns->remove( [ 'date', 'author' ] );

        return $columns;
    }

    public function icon(): string {
        return 'dashicons-book-alt';
    }
}
```

---

## Additional Notes
- See the updated README and docs for more examples and details.
- Review the new `src/Contracts/` interfaces for extension points.
- If you encounter issues, check the test suite and consult the [documentation](https://posttypes.jjgrainger.co.uk)
