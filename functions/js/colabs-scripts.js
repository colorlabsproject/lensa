/**
 * Detect mobile phone
 * 
 */
var mobileDetector = {
	engine: {
		webkit: "webkit"
	},
	device: {
		iphone: "iphone",
		ipad: "ipad",
		android: "android"
	},
	uagent: function() {
		if (navigator && navigator.userAgent)
    		return navigator.userAgent.toLowerCase();
	},
	detectIphone: function() {
		if (mobileDetector.uagent().search(mobileDetector.device.iphone) > -1)
			return true;
		else
			return false;
	},
	detectIpad: function() {
		if (mobileDetector.uagent().search(mobileDetector.device.ipad) > -1  && mobileDetector.detectWebkit())
			return true;
		else
			return false;
	},
	detectWebkit: function() {
		if (mobileDetector.uagent().search(mobileDetector.engine.webkit) > -1)
			return true;
		else
			return false;
	},
	detectAndroid: function() {
		if (mobileDetector.uagent().search(mobileDetector.device.android) > -1)
			return true;
		else
			return false;
	}
};



(function($){
/**
 * Equal Height plugin
 */
(function(a){a.fn.equalHeight=function(){var c=0,b=a(window).width();this.addClass("equal-height").each(function(){var d=a(this).outerHeight();if(d>c){c=d}});a(this).outerHeight(c);return this}})(jQuery);

/**
 *
 * Style Select
 *
 * Replace Select text
 * Dependencies: jQuery
 *
 */
colabsAdmin = {
	styleSelect: function () {
		$( '.select_wrapper').each(function () {
			$(this).prepend( '<span>' + $(this).find( '.colabs-input option:selected').text() + '</span>' );
		});
		$( 'select.colabs-input').live( 'change', function () {
			$(this).prev( 'span').replaceWith( '<span>' + $(this).find( 'option:selected').text() + '</span>' );
		});
		$( 'select.colabs-input').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev( 'span').replaceWith( '<span>' + $(this).find( 'option:selected').text() + '</span>' );
		}); 
	},
	equalHeight: function() {
		$('#sidebar-nav, #panel-content').height('auto').equalHeight();
	},
	showGroup: function(target) {
		// Hide all panel except panel target
		$('#panel-content .group').hide();
		$(target).fadeIn(500);

		// Add current class to menu
		$('#sidebar-nav li').removeClass('current');
		$('a[href='+target+']').parent().addClass('current');

		// Set hash
		window.location.hash = target;

		// Set height
		// colabsAdmin.equalHeight();

		// Scroll content to top
		$('body, html').animate({
			scrollTop: $('#panel-content').position().top
		});
	}
};

$(window).load(function(){
	// colabsAdmin.equalHeight();
	
	// Load panel according to hash
	var hash = window.location.hash;
	if( hash !== '#' && hash !== '' ) {
		colabsAdmin.showGroup( hash );
	}

}).resize(function(){
	setTimeout(function(){
		// colabsAdmin.equalHeight();
	}, 500);
});

$(document).ready(function(){
	colabsAdmin.styleSelect();

	/**
	 * Drag and drop for sidebar menu
	 */
	function sidebarMenu() {
		var dropped = false,
			draggable_sibling;
		
		$('#sidebar-nav a').click(function(e){
			e.preventDefault();
		});

		if( mobileDetector.detectAndroid() || mobileDetector.detectIpad() || mobileDetector.detectIphone() ) {
			var menuLink = $("#sidebar-nav li a");
			menuLink.css('cursor','pointer');
			$('.help-block p').hide();
			menuLink.click(function(e){
				e.preventDefault();
				var $this = $(this);
				colabsAdmin.showGroup($this.attr('href'));
			});
		} else {
			if( jQuery.fn.sortable !== undefined ) {
				$("#sidebar-nav li").draggable({
					revert: 'invalid',
					stack: '#sidebar-nav li'
				});

				$('#panel-content').droppable({
					drop: function(event, ui) {
						var dragEl = $(ui.draggable),
							target = dragEl.find('a').attr('href'),
							$this = $(this);
							dropped = true;

						if( target.length ) {
							colabsAdmin.showGroup(target);

							// Animate draggable element
							dragEl.fadeOut(function(){
								$(this).css({
									top: 0,
									left: 0
								}).addClass('current').fadeIn();
							});
						}

					}
				});
			}		
		}
	};
	sidebarMenu();


	// Sidebar Manager Menu
	var sidebarManager = $('.colabs-sbm-menu');
	sidebarManager.find('> ul > li > a').click(function(e){
		e.preventDefault();
		var $this = $(this);

		// Set sidebar height to auto to fix equal height
		sidebarManager.height('auto');

		// Click event on navigation
		$(this).next().slideToggle(500, function(){
			var el          = $this,
				elIndicator = el.find('span');
				elText = ( elIndicator.text() == '[-]' ) ? '[+]' : '[-]';
				elIndicator.text( elText );
				// colabsAdmin.equalHeight();
		}).end().toggleClass('collapsed');
	});

	// Fix equal height block when input select changed
	$('#panel-content').find(':radio, :checkbox, select').change(function(){
		setTimeout(function(){
			// $('#sidebar-nav, #panel-content').height('auto').equalHeight();
		}, 10);
	});

});

})(jQuery);
