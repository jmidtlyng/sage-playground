<site-navigation>
  <nav>
    @if (has_nav_menu($nav))
        {!! wp_nav_menu(['theme_location' => $nav, 'walker'=> new \App\walker_site_navigation(), 'menu_class'=>$class]) !!}
    @endif
  </nav>
</site-navigation>
