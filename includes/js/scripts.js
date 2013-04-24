(function($){

$(document).ready(function(){

/* Show the slideshow
------------------------------------------------------------------- */
$('body').on('click', '.page-heading', function(e){
  var $el = $(this),
      $body = $('body'),
      $footer = $('.footer-section'),
      $progress = $('.progress-wrapper'),
      headingHeight = $el.outerHeight(),
      headerHeight = $('.header-section').height(),
      windowHeight = $(window).height(),
      footerHeight = 45,
      position = windowHeight - headerHeight - headingHeight - footerHeight - 60,
      state;

    // Pause the slideshow
    if( $el.hasClass('active') ) {
      position = 0;
      footerPos = -40;
      progressPos = 0;
      state = 'pause';
      $el.removeClass('active');
      $body.removeClass('slider-active');
    // Play the slideshow
    } else {
      footerPos = 0;
      progressPos = 38;
      state = 'play';
      $el.addClass('active');
      $body.addClass('slider-active');
    }

    $el.animate({ 'margin-top': position });

    if( $footer.hasClass('static') ) {
      $footer.animate({ 'bottom': footerPos });
      $progress.animate({ 'bottom': progressPos });
    }

    if( typeof api !== "undefined" ) {
      api.playToggle(state);
    }
});

/* Responsive Video
------------------------------------------------------------------- */
$('.entry-post iframe, .entry-post object').parent().fitVids(); // Responsive video

/* Set footer position
 * Set fixed when main content height is lower than window height
------------------------------------------------------------------- */
function setFooterPos() {
  var $footer = $('.footer-section'),
      $main = $('.main-container'),
      viewport = $(window).height() - $('.header-section').height() - $('.footer-section').height();

  if( $main.height() > viewport ) {
    $footer.addClass('static');
    $('.progress-wrapper').addClass('bottom');
  }
};
// setFooterPos();
$('.main-container').imagesLoaded(function(){
  setFooterPos();
});



/* Fullscreen Slider
------------------------------------------------------------------- */
if( typeof slides !== "undefined" ) {
  var defaults = {
    slideshow: 1,
    autoplay: 0,
    slide_interval: 7 * 1000,
    transition: 1,
    slide_links: 0,
    thumb_links: 0,
    min_width: 0,
    min_height: 0,
    vertical_center: 1,
    horizontal_center: 1,
    fit_always: 0,
    fit_portrait: 1,
    fit_landscape: 0,
    slides: slides
  },
  options = $.extend( defaults, slider_config );

  $.supersized( options );
}


/* Superfish
------------------------------------------------------------------- */
$('.top-nav ul:first').addClass('sf-menu').superfish({
  delay: 300,
  animation: { opacity: 'show' },
  speed: 'fast',
  dropShadows: false,
  onInit: function() {
    var $el = $(this);
    // Replace submenu indicator
    $el.find('.sf-sub-indicator').html('<i class="icon-caret-down"></i>');
    $el.find('ul .sf-sub-indicator').html('<i class="icon-caret-right"></i>');
  }
});

/* Mobile nav collapse
------------------------------------------------------------------- */
$('.btn-navbar').click(function(e){
  e.preventDefault();
  var $el = $(this),
      $navCollapse = $el.next('.nav-collapse');
      console.log($el);

  // If collapsed
  if( $el.hasClass('collapsed') ) {
    $navCollapse.height( $navCollapse.children().outerHeight(true) );
    $el.removeClass('collapsed');
  } else {
    $navCollapse.height(0);
    $el.addClass('collapsed');
  }
});

/* ===================================================================
  #Post List - Masonry
=================================================================== */
var $listMasonry = $('.gallery-list');

$listMasonry.css('opacity', 0);
$listMasonry.imagesLoaded(function(){
  $(this).masonry({
    itemSelector: '.gallery-item',
     isAnimated: true
  });
  $listMasonry.delay(200).fadeTo(500, 1);
});

/* ===================================================================
  #Fancybox
=================================================================== */
function fancyInit( $fancy ) {

  // Add index to fancy link
  $fancy.each(function(i){
    $fancy.attr('data-index', i);
  });

  // Initiate Fancybox
  $fancy.fancybox({
    transitionIn  : 'elastic',
    transitionOut : 'elastic',
    titlePosition: 'inside',
    cyclic: true,
    onComplete: function(el, i) {
      var $el = $fancy.eq(i),
          $like = $el.parent().find('.like'),
          $time = $el.parent().find('.time'),
          wrapper = '';

      // Create wrapper
      wrapper += '<div class="fancy-wrapper">';
      if( $like.length > 0 )
        wrapper += '<div class="fancy-like">'+ $like.html() +'</div>';
      if( $time.length > 0)
        wrapper += '<div class="fancy-time">'+ $time.html() +'</div>';
      wrapper += '</div>';

      if( $el.attr('title') ) {
        $('#fancybox-wrap').removeClass('no-title');
      } else {
        $('#fancybox-wrap').addClass('no-title');
      }

      $(wrapper).insertAfter('#fancybox-title');

      // Resize Fancybox
      // $('#fancybox-content').height()
    },
    onCleanup: function() {
      $('.fancy-wrapper').remove();
    }
  });
}
fancyInit( $('a.grouped, a[rel^=lightbox]') );

/*
Plugin Placeholder
*/
$('input, textarea').placeholder();


/* Ajax Likes
------------------------------------------------------------------- */
// Jquery Likes
var ajaxMessage = 'Loading...';
$('.entry-likes').live('click', function(e){
  e.preventDefault();
  var 
    likeData  = $(this).data('like'),
    likeData  = likeData.split('_'),
    likeId    = likeData[1],
    liked     = ( $.cookie('like_' + likeId) == 'true' ) ? true : false,
    disable   = ( $(this).hasClass('disabled') || liked ) ? true : false;
    
    if( !disable ) {
      likethis( likeId, $(this) );
    } else {
      ajaxMessage = 'You have already liked this post';
      $('.loading-box').trigger('ajaxSend').trigger('ajaxComplete');
      return;
    }
});

function likethis(likeId, el) {
  $.ajax({
    url: config.ajaxurl,
    //cache: true,
    type: 'post',
    data: {
      action: 'like',
      id: likeId
    },
    beforeSend: function(){
      ajaxMessage = 'Loading...';
    },
    success: function(response, event) {
      $.cookie('like_' + likeId,'true');
      ajaxMessage = 'Liked!';
      
    var likeNumber  = el.find('span'),
      before      = parseInt(likeNumber.text());
      likeNumber.text( before + 1 );
    }
  });

} // end function like this yo!;

/* --- Append loading box for ajax request loading ---*/
var loadingBox = $('<div class="loading-box">').text('Loading...').appendTo('body');
loadingBox.bind( 'ajaxSend', function(res, req){
  $(this).text(ajaxMessage).delay(100).animate({ top: -1 });
})
.bind( 'ajaxComplete', function(res, req){
  var boxHeight = $(this).outerHeight();
  $(this).text( ajaxMessage ).delay(1000).animate({ top: -boxHeight-5 });
});


/* Infinite Scroll
------------------------------------------------------------------- */
$.extend($.infinitescroll.prototype,{

  /* --- Show Done Message --- */
  _showdonemsg_manual_trigger: function infscr_showdonemsg_manual_trigger() {
    var opts = this.options;

    ajaxMessage = "No More Post";
    $(this.element).infinitescroll('destroy');
  }
});


(function(){
  var $container = $('.gallery-list');

  /*$container.infinitescroll({
    behaviour: 'manual_trigger',
    navSelector: '.nav-previous',
    nextSelector: '.nav-previous a',
    itemSelector: '.gallery-item',
    loading: {
        msg: ""
      }
    },
    // Trigger Masonry as a callback
    function( newElements ) {
      // hide new items while they are loading
      var $newElems = $( newElements ).css({ opacity: 0 });
      // ensure that images load before adding to masonry layout
      $newElems.imagesLoaded(function(){
        // show elems now they're ready
        $newElems.animate({ opacity: 1 });
        $container.masonry( 'appended', $newElems, true );
        setFooterPos();
        fancyInit( $newElems.find('a[rel^=lightbox]') );
      });
    }
  );*/
})();

});
})(jQuery);