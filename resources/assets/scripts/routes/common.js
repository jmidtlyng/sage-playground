export default {
  init() {
    // JavaScript to be fired on all pages
    var $el_siteNav = $('site-navigation');
    $el_siteNav.bind('drawer-state-change', (e)=>{
      var $el_expandCollapse = $(e.detail.el.previousSibling).find('.nav-expand');

      if($el_expandCollapse){
        if(e.detail.action == 'open'){
          $el_expandCollapse.html('(close)');
        } else if(e.detail.action == 'close'){
          $el_expandCollapse.html('(expand)');
        }
      }
    });
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
