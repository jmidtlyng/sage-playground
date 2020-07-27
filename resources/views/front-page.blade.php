<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    <div class="wrap container" role="document">
      <div class="content">
        <svg class="distortion-filter--hidden">
    			<defs>
    				<filter id="distortionFilter">
    					<feTurbulence type="fractalNoise" baseFrequency="0.15 0.02" numOctaves="3" result="warp" />
    					<feDisplacementMap xChannelSelector="R" yChannelSelector="G" scale="0" in="SourceGraphic" in2="warp" />
    				</filter>
    			</defs>
    		</svg>
        <main class="p-8">
          <h1>Jacob's Site</h1>
          <site-navigation>
            <nav class="menu menu--linethrough">
              @if (has_nav_menu('front_page_navigation'))
                  {!! wp_nav_menu(['theme_location' => 'front_page_navigation', 'walker'=> new \App\walker_front_page(), 'menu_class'=>'front-page-nav menu menu--linethrough']) !!}
              @endif
            </nav>
          </site-navigation>
        </main>
      </div>
    </div>
    @php do_action('get_footer') @endphp
    @include('partials.footer')
    @php wp_footer() @endphp
  </body>
</html>
