<header class="banner">
  <div class="container">
    <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
    <nav class="nav-primary">
      @include('partials.header-nav-murmur', ['nav'=>'primary_navigation'])
    </nav>
  </div>
</header>
