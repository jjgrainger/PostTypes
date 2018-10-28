# Filters

You can set what dropdown filters appear on the post type admin edit screen by passing an array of taxonomy names to the `filters()` method.

```php
$books->filters( [ 'genre', 'category' ] );
```

The order the filters appear are set by the order of the items in the array.

```php
// Display the category dropdown first.
$books->filters( [ 'category', 'genre' ] );
```

An empty array will remove all dropdown filters from the admin edit screen.

```php
// Don't display filters on the admin edit screen.
$books->filters( [] );
```
