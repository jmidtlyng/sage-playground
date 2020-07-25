<?php

namespace App\Controllers\Partials;

trait RecentPosts
{
    public function recent_posts()
    {
        $args = [
            'post_type'           => 'post',
            'posts_per_page'      => 3,
            'orderby'             => 'date',
            'order'               => 'DESC',
        ];

        return $query = new \WP_Query($args);
    }
}
