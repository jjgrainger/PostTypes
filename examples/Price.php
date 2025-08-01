<?php

use PostTypes\Column;

class Price extends Column
{
    public function name(): string {
        return 'price';
    }

    public function label(): string {
        return __( 'Price', 'post-types' );
    }

    public function order(): int {
        return 2;
    }

    public function populate( int $post_id ): void {
        echo '£' . get_post_meta( $post_id, 'price', true );
    }

    public function isSortable(): bool {
        return true;
    }

    public function sort( $query ): void {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'price');
    }
}
