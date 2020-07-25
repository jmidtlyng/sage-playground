<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment', 'embed'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    collect(['get_header', 'wp_head'])->each(function ($tag) {
        ob_start();
        do_action($tag);
        $output = ob_get_clean();
        remove_all_actions($tag);
        add_action($tag, function () use ($output) {
            echo $output;
        });
    });
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory().'/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Render comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );

    $data = collect(get_body_class())->reduce(function ($data, $class) use ($comments_template) {
        return apply_filters("sage/template/{$class}/data", $data, $comments_template);
    }, []);

    $theme_template = locate_template(["views/{$comments_template}", $comments_template]);

    if ($theme_template) {
        echo template($theme_template, $data);
        return get_stylesheet_directory().'/index.php';
    }

    return $comments_template;
}, 100);

/**
 * Add two ACF fields; header_image and header_content to all pages
 */
add_filter('sage/template/page/data', function(array $data){
    $data['header_image'] = get_field('header_image');
    $data['header_content'] = get_field('header_content');
    return $data;
});

add_action('the_post', function(){
   sage('blade')->share('links', [
       'facebook' => 'https://facebook.com/rootswp',
       'twitter' => 'https://twitter.com/rootswp'
   ]);
});

add_shortcode('reviews', function($atts){
    return \App\template('partials.reviews');
});

add_action('woocommerce_after_single_product', function(){
    echo \App\template('partials.product-bottom');
}, 10);

add_filter('get_the_archive_title', function($title){
    // Get title for Team post type archive from ACF option
    if (get_post_type('team')) {
        $title = get_field('team_title', 'option');
    }

    return $title;
});
add_filter('sage/display_sidebar', function($display){
    static $display;

    isset($display) || $display = in_array(true, [
        is_single(),
        is_404(),
        is_search()
    ]);
    return $display;
});
/**
 * Browsersync reload on post save
 */
add_action('save_post', function(){
    // WP_ENV must be set on your development environment in order for this to work
    // This is already defined if you are using Bedrock
    if (WP_ENV === 'development') {
        $args = ['blocking' => false];
        wp_remote_get('http://localhost:3000/__browser_sync__?method=reload', $args);
    }
});

/**
 * Add icons to parent nav menu items
 */
add_filter('walker_nav_menu_start_el', function($output, $item, $depth, $args){
    if ($args->theme_location === 'primary_navigation' && in_array('menu-item-has-children', $item->classes)) {
        $output = str_replace('</a>',
          ' <span class="menu-toggle"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></a>',
          $output);
    }
    return $output;
}, 10, 4);

/**
 * Use Lozad (lazy loading) for attachments/featured images
 */
add_filter('wp_get_attachment_image_attributes', function($attr, $attachment){
    // Bail on admin, bail on about page since Isotope and lazy loading conflict
    if (is_admin() || is_page('about')) {
        return $attr;
    }
    $attr['data-src'] = $attr['src'];
    $attr['class'] .= ' lozad';
    unset($attr['src']);
    return $attr;
}, 10, 2);

/**
 * Remove 'Category:' prefix from category titles
 */
add_filter('get_the_archive_title', function($title){
    if (is_category()) {
        $title = single_cat_title('', false);
    }
    return $title;
});
