<site-navigation>
  <nav>
    <button data-toggle>Open</button>
    <div data-drawer>
      @if (has_nav_menu($nav))
        {!! wp_nav_menu(['theme_location' => $nav]) !!}
      @endif
    </div>
  </nav>
</site-navigation>
