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

    public function position(): array {
        return $this->after( 'title' );
    }

    public function populate(): callable {
        return function( int $post_id ) {
            echo '£' . get_post_meta( $post_id, 'price', true );
        };
    }

    public function sort(): callable {
        return function( $query ) {
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'meta_key', 'price' );
        };
    }
}
