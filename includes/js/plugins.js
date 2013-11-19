
/* --- Cookie --- */
jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};


/*!
 * jQuery imagesLoaded plugin v2.1.0
 * http://github.com/desandro/imagesloaded
 *
 * MIT License. by Paul Irish et al.
 */
(function(c,n){var l="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function m(){var b=c(i),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function j(b,a){b.src===l||-1!==c.inArray(b,k)||(k.push(b),a?h.push(b):i.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),o&&d.notifyWith(c(b),[a,e,c(i),c(h)]),e.length===k.length&&(setTimeout(m),e.unbind(".imagesLoaded")))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():
0,o=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),k=[],i=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",function(b){j(b.target,"error"===b.type)}).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)j(a,e.isBroken);else if(a.complete&&a.naturalWidth!==n)j(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=l,a.src=d}):m();return d?d.promise(g):
g}})(jQuery);


/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 */
(function($){$.fn.superfish=function(op){var sf=$.fn.superfish,c=sf.c,$arrow=$(['<span class="',c.arrowClass,'"> &#187;</span>'].join("")),over=function(){var $$=$(this),menu=getMenu($$);clearTimeout(menu.sfTimer);$$.showSuperfishUl().siblings().hideSuperfishUl()},out=function(){var $$=$(this),menu=getMenu($$),o=sf.op;clearTimeout(menu.sfTimer);menu.sfTimer=setTimeout(function(){o.retainPath=$.inArray($$[0],o.$path)>-1;$$.hideSuperfishUl();if(o.$path.length&&$$.parents(["li.",o.hoverClass].join("")).length<
1)over.call(o.$path)},o.delay)},getMenu=function($menu){var menu=$menu.parents(["ul.",c.menuClass,":first"].join(""))[0];sf.op=sf.o[menu.serial];return menu},addArrow=function($a){$a.addClass(c.anchorClass).append($arrow.clone())};return this.each(function(){var s=this.serial=sf.o.length;var o=$.extend({},sf.defaults,op);o.$path=$("li."+o.pathClass,this).slice(0,o.pathLevels).each(function(){$(this).addClass([o.hoverClass,c.bcClass].join(" ")).filter("li:has(ul)").removeClass(o.pathClass)});sf.o[s]=
sf.op=o;$("li:has(ul)",this)[$.fn.hoverIntent&&!o.disableHI?"hoverIntent":"hover"](over,out).each(function(){if(o.autoArrows)addArrow($(">a:first-child",this))}).not("."+c.bcClass).hideSuperfishUl();var $a=$("a",this);$a.each(function(i){var $li=$a.eq(i).parents("li");$a.eq(i).focus(function(){over.call($li)}).blur(function(){out.call($li)})});o.onInit.call(this)}).each(function(){var menuClasses=[c.menuClass];if(sf.op.dropShadows&&!($.browser.msie&&$.browser.version<7))menuClasses.push(c.shadowClass);
$(this).addClass(menuClasses.join(" "))})};var sf=$.fn.superfish;sf.o=[];sf.op={};sf.IE7fix=function(){var o=sf.op;if($.browser.msie&&$.browser.version>6&&o.dropShadows&&o.animation.opacity!=undefined)this.toggleClass(sf.c.shadowClass+"-off")};sf.c={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",arrowClass:"sf-sub-indicator",shadowClass:"sf-shadow"};sf.defaults={hoverClass:"sfHover",pathClass:"overideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},speed:"normal",
autoArrows:true,dropShadows:true,disableHI:false,onInit:function(){},onBeforeShow:function(){},onShow:function(){},onHide:function(){}};$.fn.extend({hideSuperfishUl:function(){var o=sf.op,not=o.retainPath===true?o.$path:"";o.retainPath=false;var $ul=$(["li.",o.hoverClass].join(""),this).add(this).not(not).removeClass(o.hoverClass).find(">ul").hide().css("visibility","hidden");o.onHide.call($ul);return this},showSuperfishUl:function(){var o=sf.op,sh=sf.c.shadowClass+"-off",$ul=this.addClass(o.hoverClass).find(">ul:hidden").css("visibility",
"visible");sf.IE7fix.call($ul);o.onBeforeShow.call($ul);$ul.animate(o.animation,o.speed,function(){sf.IE7fix.call($ul);o.onShow.call($ul)});return this}})})(jQuery);


/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/
(function($){$.fn.fitVids=function(options){var settings={customSelector:null};var div=document.createElement("div"),ref=document.getElementsByTagName("base")[0]||document.getElementsByTagName("script")[0];div.className="fit-vids-style";div.innerHTML="&shy;<style>               .fluid-width-video-wrapper {                 width: 100%;                              position: relative;                       padding: 0;                            }                                                                                   .fluid-width-video-wrapper iframe,        .fluid-width-video-wrapper object,        .fluid-width-video-wrapper embed {           position: absolute;                       top: 0;                                   left: 0;                                  width: 100%;                              height: 100%;                          }                                       </style>";
ref.parentNode.insertBefore(div,ref);if(options)$.extend(settings,options);return this.each(function(){var selectors=["iframe[src*='player.vimeo.com']","iframe[src*='www.youtube.com']","iframe[src*='www.kickstarter.com']","object","embed"];if(settings.customSelector)selectors.push(settings.customSelector);var $allVideos=$(this).find(selectors.join(","));$allVideos.each(function(){var $this=$(this);if(this.tagName.toLowerCase()==="embed"&&$this.parent("object").length||$this.parent(".fluid-width-video-wrapper").length)return;
var height=this.tagName.toLowerCase()==="object"||$this.attr("height")?parseInt($this.attr("height"),10):$this.height(),width=$this.attr("width")?parseInt($this.attr("width"),10):$this.width(),aspectRatio=height/width;if(!$this.attr("id")){var videoID="fitvid"+Math.floor(Math.random()*999999);$this.attr("id",videoID)}$this.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",aspectRatio*100+"%");$this.removeAttr("height").removeAttr("width")})})}})(jQuery);


/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright å© 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright å© 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */


 /*

  Supersized - Fullscreen Slideshow jQuery Plugin
  Version : 3.2.7
  Site  : www.buildinternet.com/project/supersized
  
  Author  : Sam Dunn
  Company : One Mighty Roar (www.onemightyroar.com)
  License : MIT License / GPL License
  
 */

 (function($){

  /* Place Supersized Elements
  ----------------------------*/
  $(document).ready(function() {
    $('body').append('<ul id="supersized"></ul>');
  });
     
     
     $.supersized = function(options){
      
      /* Variables
    ----------------------------*/
      var el = '#supersized',
          base = this;
         // Access to jQuery and DOM versions of element
         base.$el = $(el);
         base.el = el;
         vars = $.supersized.vars;
         // Add a reverse reference to the DOM object
         base.$el.data("supersized", base);
         api = base.$el.data('supersized');
    
    base.init = function(){
          // Combine options and vars
          $.supersized.vars = $.extend($.supersized.vars, $.supersized.themeVars);
          $.supersized.vars.options = $.extend({},$.supersized.defaultOptions, $.supersized.themeOptions, options);
             base.options = $.supersized.vars.options;
             
             base._build();
         };
         
         
         /* Build Elements
    ----------------------------*/
         base._build = function(){
          // Add in slide markers
          var thisSlide = 0,
            slideSet = '',
        markers = '',
        markerContent,
        thumbMarkers = '',
        thumbImage;

        // Check if Slides is exists
        if( typeof base.options.slides !== 'undefined' ) {
          // Append loader
          $('<div id="supersized-loader"></div>').insertBefore( base.$el );
        }
        
      while(thisSlide <= base.options.slides.length-1){
        //Determine slide link content
        switch(base.options.slide_links){
          case 'num':
            markerContent = thisSlide;
            break;
          case 'name':
            markerContent = base.options.slides[thisSlide].title;
            break;
          case 'blank':
            markerContent = '';
            break;
        }
        
        slideSet = slideSet+'<li class="slide-'+thisSlide+'"></li>';
        
        if(thisSlide == base.options.start_slide-1){
          // Slide links
          if (base.options.slide_links)markers = markers+'<li class="slide-link-'+thisSlide+' current-slide"><a>'+markerContent+'</a></li>';
          // Slide Thumbnail Links
          if (base.options.thumb_links){
            base.options.slides[thisSlide].thumb ? thumbImage = base.options.slides[thisSlide].thumb : thumbImage = base.options.slides[thisSlide].image;
            thumbMarkers = thumbMarkers+'<li class="thumb'+thisSlide+' current-thumb"><img src="'+thumbImage+'"/></li>';
          };
        }else{
          // Slide links
          if (base.options.slide_links) markers = markers+'<li class="slide-link-'+thisSlide+'" ><a>'+markerContent+'</a></li>';
          // Slide Thumbnail Links
          if (base.options.thumb_links){
            base.options.slides[thisSlide].thumb ? thumbImage = base.options.slides[thisSlide].thumb : thumbImage = base.options.slides[thisSlide].image;
            thumbMarkers = thumbMarkers+'<li class="thumb'+thisSlide+'"><img src="'+thumbImage+'"/></li>';
          };
        }
        thisSlide++;
      }
      
      if (base.options.slide_links) $(vars.slide_list).html(markers);
      if (base.options.thumb_links && vars.thumb_tray.length){
        $(vars.thumb_tray).append('<ul id="'+vars.thumb_list.replace('#','')+'">'+thumbMarkers+'</ul>');
      }
      
      $(base.el).append(slideSet);
      
      // Add in thumbnails
      if (base.options.thumbnail_navigation){
        // Load previous thumbnail
        vars.current_slide - 1 < 0  ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
        $(vars.prev_thumb).show().html($("<img/>").attr("src", base.options.slides[prevThumb].image));
        
        // Load next thumbnail
        vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
        $(vars.next_thumb).show().html($("<img/>").attr("src", base.options.slides[nextThumb].image));
      }
      
             base._start(); // Get things started
         };
         
         
         /* Initialize
    ----------------------------*/
      base._start = function(){
      
      // Determine if starting slide random
      if (base.options.start_slide){
        vars.current_slide = base.options.start_slide - 1;
      }else{
        vars.current_slide = Math.floor(Math.random()*base.options.slides.length);  // Generate random slide number
      }
      
      // If links should open in new window
      var linkTarget = base.options.new_window ? ' target="_blank"' : '';
      
      // Set slideshow quality (Supported only in FF and IE, no Webkit)
      if (base.options.performance == 3){
        base.$el.addClass('speed');     // Faster transitions
      } else if ((base.options.performance == 1) || (base.options.performance == 2)){
        base.$el.addClass('quality'); // Higher image quality
      }
            
      // Shuffle slide order if needed    
      if (base.options.random){
        arr = base.options.slides;
        for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x); // Fisher-Yates shuffle algorithm (jsfromhell.com/array/shuffle)
          base.options.slides = arr;
      }
      
      /*-----Load initial set of images-----*/
  
      if (base.options.slides.length > 1){
        if(base.options.slides.length > 2){
          // Set previous image
          // vars.current_slide - 1 < 0  ? loadPrev = base.options.slides.length - 1 : loadPrev = vars.current_slide - 1;  // If slide is 1, load last slide as previous
          loadPrev = ( vars.current_slide - 1 < 0 ) ? base.options.slides.length - 1 : vars.current_slide - 1;
          var imageLink = (base.options.slides[loadPrev].url) ? "href='" + base.options.slides[loadPrev].url + "'" : "";
        
          var imgPrev = $('<img src="'+base.options.slides[loadPrev].image+'"/>');
          var slidePrev = base.el+' li:eq('+loadPrev+')';
          imgPrev.appendTo(slidePrev).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading prevslide');
        
          imgPrev.load(function(){
            $(this).data('origWidth', $(this).width()).data('origHeight', $(this).height());
            base.resizeNow(); // Resize background image
          }); // End Load
        }
      } else {
        // Slideshow turned off if there is only one slide
        base.options.slideshow = 0;
      }
      
      // Set current image
      imageLink = (api.getField('url')) ? "href='" + api.getField('url') + "'" : "";
      var img = $('<img src="'+api.getField('image')+'"/>');
      
      var slideCurrent= base.el+' li:eq('+vars.current_slide+')';
      img.appendTo(slideCurrent).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading activeslide');
      
      img.load(function(){
        base._origDim($(this));
        base.resizeNow(); // Resize background image
        base.launch();
        if( typeof theme != 'undefined' && typeof theme._init == "function" ) theme._init();  // Load Theme
      });
      
      if (base.options.slides.length > 1){
        // Set next image
        vars.current_slide == base.options.slides.length - 1 ? loadNext = 0 : loadNext = vars.current_slide + 1;  // If slide is last, load first slide as next
        imageLink = (base.options.slides[loadNext].url) ? "href='" + base.options.slides[loadNext].url + "'" : "";
        
        var imgNext = $('<img src="'+base.options.slides[loadNext].image+'"/>');
        var slideNext = base.el+' li:eq('+loadNext+')';
        imgNext.appendTo(slideNext).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading');
        
        imgNext.load(function(){
          $(this).data('origWidth', $(this).width()).data('origHeight', $(this).height());
          base.resizeNow(); // Resize background image
        }); // End Load
      }
      /*-----End load initial images-----*/
      
      //  Hide elements to be faded in
      base.$el.css('visibility','hidden');
      $('.load-item').hide();
      
      };
    
    
    /* Launch Supersized
    ----------------------------*/
    base.launch = function(){
    
      base.$el.css('visibility','visible');
      $('#supersized-loader').remove();   //Hide loading animation
      
      // Call theme function for before slide transition
      if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('next');
      $('.load-item').show();
      
      // Keyboard Navigation
      if (base.options.keyboard_nav){
        $(document.documentElement).keyup(function (event) {
        
          if(vars.in_animation) return false;   // Abort if currently animating
          
          // Left Arrow or Down Arrow
          if ((event.keyCode == 37) || (event.keyCode == 40)) {
            clearInterval(vars.slideshow_interval); // Stop slideshow, prevent buildup
            base.prevSlide();
          
          // Right Arrow or Up Arrow
          } else if ((event.keyCode == 39) || (event.keyCode == 38)) {
            clearInterval(vars.slideshow_interval); // Stop slideshow, prevent buildup
            base.nextSlide();
          
          // Spacebar 
          } else if (event.keyCode == 32 && !vars.hover_pause) {
            clearInterval(vars.slideshow_interval); // Stop slideshow, prevent buildup
            base.playToggle();
          }
        
        });
      }
      
      // Pause when hover on image
      if (base.options.slideshow && base.options.pause_hover){
        $(base.el).hover(function() {
          if(vars.in_animation) return false;   // Abort if currently animating
              vars.hover_pause = true;  // Mark slideshow paused from hover
              if(!vars.is_paused){
                vars.hover_pause = 'resume';  // It needs to resume afterwards
                base.playToggle();
              }
          }, function() {
          if(vars.hover_pause == 'resume'){
            base.playToggle();
            vars.hover_pause = false;
          }
          });
      }
      
      if (base.options.slide_links){
        // Slide marker clicked
        $(vars.slide_list+'> li').click(function(){
        
          index = $(vars.slide_list+'> li').index(this);
          targetSlide = index + 1;
          
          base.goTo(targetSlide);
          return false;
          
        });
      }
      
      // Thumb marker clicked
      if (base.options.thumb_links){
        $(vars.thumb_list+'> li').click(function(){
        
          index = $(vars.thumb_list+'> li').index(this);
          targetSlide = index + 1;
          
          api.goTo(targetSlide);
          return false;
          
        });
      }
      
      // Start slideshow if enabled
      if (base.options.slideshow && base.options.slides.length > 1){
          
          // Start slideshow if autoplay enabled
          if (base.options.autoplay && base.options.slides.length > 1){
            vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval); // Initiate slide interval
        }else{
          vars.is_paused = true;  // Mark as paused
        }
        
        //Prevent navigation items from being dragged         
        $('.load-item img').bind("contextmenu mousedown",function(){
          return false;
        });
                
      }
      
      // Adjust image when browser is resized
      $(window).resize(function(){
          base.resizeNow();
      });
        
      };
         
         
         /* Resize Images
    ----------------------------*/
    base.resizeNow = function(){
      
      return base.$el.each(function() {
          //  Resize each image seperately
          $('img', base.el).each(function(){
            
          thisSlide = $(this);
          var ratio = (thisSlide.data('origHeight')/thisSlide.data('origWidth')).toFixed(2);  // Define image ratio
          
          // Gather browser size
          var browserwidth = base.$el.width(),
            browserheight = base.$el.height(),
            offset;
          
          /*-----Resize Image-----*/
          if (base.options.fit_always){ // Fit always is enabled
            if ((browserheight/browserwidth) > ratio){
              resizeWidth();
            } else {
              resizeHeight();
            }
          }else{  // Normal Resize
            if ((browserheight <= base.options.min_height) && (browserwidth <= base.options.min_width)){  // If window smaller than minimum width and height
            
              if ((browserheight/browserwidth) > ratio){
                base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight(true); // If landscapes are set to fit
              } else {
                base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth(true);   // If portraits are set to fit
              }
            
            } else if (browserwidth <= base.options.min_width){   // If window only smaller than minimum width
            
              if ((browserheight/browserwidth) > ratio){
                base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight(); // If landscapes are set to fit
              } else {
                base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth(true);   // If portraits are set to fit
              }
              
            } else if (browserheight <= base.options.min_height){ // If window only smaller than minimum height
            
              if ((browserheight/browserwidth) > ratio){
                base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight(true); // If landscapes are set to fit
              } else {
                base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth();   // If portraits are set to fit
              }
            
            } else {  // If larger than minimums
              
              if ((browserheight/browserwidth) > ratio){
                base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight(); // If landscapes are set to fit
              } else {
                base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth();   // If portraits are set to fit
              }
              
            }
          }
          /*-----End Image Resize-----*/
          
          
          /*-----Resize Functions-----*/
          
          function resizeWidth(minimum){
            if (minimum){ // If minimum height needs to be considered
              if(thisSlide.width() < browserwidth || thisSlide.width() < base.options.min_width ){
                if (thisSlide.width() * ratio >= base.options.min_height){
                  thisSlide.width(base.options.min_width);
                    thisSlide.height(thisSlide.width() * ratio);
                  }else{
                    resizeHeight();
                  }
                }
            }else{
              if (base.options.min_height >= browserheight && !base.options.fit_landscape){ // If minimum height needs to be considered
                if (browserwidth * ratio >= base.options.min_height || (browserwidth * ratio >= base.options.min_height && ratio <= 1)){  // If resizing would push below minimum height or image is a landscape
                  thisSlide.width(browserwidth);
                  thisSlide.height(browserwidth * ratio);
                } else if (ratio > 1){    // Else the image is portrait
                  thisSlide.height(base.options.min_height);
                  thisSlide.width(thisSlide.height() / ratio);
                } else if (thisSlide.width() < browserwidth) {
                  thisSlide.width(browserwidth);
                    thisSlide.height(thisSlide.width() * ratio);
                }
              }else{  // Otherwise, resize as normal
                thisSlide.width(browserwidth);
                thisSlide.height(browserwidth * ratio);
              }
            }
          };
          
          function resizeHeight(minimum){
            if (minimum){ // If minimum height needs to be considered
              if(thisSlide.height() < browserheight){
                if (thisSlide.height() / ratio >= base.options.min_width){
                  thisSlide.height(base.options.min_height);
                  thisSlide.width(thisSlide.height() / ratio);
                }else{
                  resizeWidth(true);
                }
              }
            }else{  // Otherwise, resized as normal
              if (base.options.min_width >= browserwidth){  // If minimum width needs to be considered
                if (browserheight / ratio >= base.options.min_width || ratio > 1){  // If resizing would push below minimum width or image is a portrait
                  thisSlide.height(browserheight);
                  thisSlide.width(browserheight / ratio);
                } else if (ratio <= 1){   // Else the image is landscape
                  thisSlide.width(base.options.min_width);
                    thisSlide.height(thisSlide.width() * ratio);
                }
              }else{  // Otherwise, resize as normal
                thisSlide.height(browserheight);
                thisSlide.width(browserheight / ratio);
              }
            }
          };
          
          /*-----End Resize Functions-----*/
          
          if (thisSlide.parents('li').hasClass('image-loading')){
            $('.image-loading').removeClass('image-loading');
          }
          
          // Horizontally Center
          if (base.options.horizontal_center){
            $(this).css('left', (browserwidth - $(this).width())/2);
          }
          
          // Vertically Center
          if (base.options.vertical_center){
            $(this).css('top', (browserheight - $(this).height())/2);
          }
          
        });
        
        // Basic image drag and right click protection
        if (base.options.image_protect){
          
          $('img', base.el).bind("contextmenu mousedown",function(){
            return false;
          });
        
        }
        
        return false;
        
      });
      
    };
         
         
         /* Next Slide
    ----------------------------*/
    base.nextSlide = function(){
      
      if(vars.in_animation || !api.options.slideshow) return false;   // Abort if currently animating
        else vars.in_animation = true;    // Otherwise set animation marker
        
        clearInterval(vars.slideshow_interval); // Stop slideshow
        
        var slides = base.options.slides,         // Pull in slides array
        liveslide = base.$el.find('.activeslide');    // Find active slide
        $('.prevslide').removeClass('prevslide');
        liveslide.removeClass('activeslide').addClass('prevslide'); // Remove active class & update previous slide
          
      // Get the slide number of new slide
      vars.current_slide + 1 == base.options.slides.length ? vars.current_slide = 0 : vars.current_slide++;
      
        var nextslide = $(base.el+' li:eq('+vars.current_slide+')'),
          prevslide = base.$el.find('.prevslide');
      
      // If hybrid mode is on drop quality for transition
      if (base.options.performance == 1) base.$el.removeClass('quality').addClass('speed'); 
      
      
      /*-----Load Image-----*/
      
      loadSlide = false;

      vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;  // Determine next slide

      var targetList = base.el+' li:eq('+loadSlide+')';
      if (!$(targetList).html()){
        
        // If links should open in new window
        var linkTarget = base.options.new_window ? ' target="_blank"' : '';
        
        imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";  // If link exists, build it
        var img = $('<img src="'+base.options.slides[loadSlide].image+'"/>'); 
        
        img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility','hidden');
        
        img.load(function(){
          base._origDim($(this));
          base.resizeNow();
        }); // End Load
      };
            
      // Update thumbnails (if enabled)
      if (base.options.thumbnail_navigation == 1){
      
        // Load previous thumbnail
        vars.current_slide - 1 < 0  ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
        $(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].image));
      
        // Load next thumbnail
        nextThumb = loadSlide;
        $(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].image));
        
      }
      
      
      
      /*-----End Load Image-----*/
      
      
      // Call theme function for before slide transition
      if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('next');
      
      //Update slide markers
      if (base.options.slide_links){
        $('.current-slide').removeClass('current-slide');
        $(vars.slide_list +'> li' ).eq(vars.current_slide).addClass('current-slide');
      }
        
        nextslide.css('visibility','hidden').addClass('activeslide'); // Update active slide
        
        switch(base.options.transition){
          case 0: case 'none':  // No transition
              nextslide.css('visibility','visible'); vars.in_animation = false; base.afterAnimation();
              break;
          case 1: case 'fade':  // Fade
              nextslide.css({opacity : 0, 'visibility': 'visible'}).animate({opacity : 1, avoidTransforms : false}, base.options.transition_speed, function(){ base.afterAnimation(); });
              break;
          case 2: case 'slideTop':  // Slide Top
              nextslide.css({top : -base.$el.height(), 'visibility': 'visible'}).animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
              break;
          case 3: case 'slideRight':  // Slide Right
            nextslide.css({left : base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 4: case 'slideBottom': // Slide Bottom
            nextslide.css({top : base.$el.height(), 'visibility': 'visible'}).animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 5: case 'slideLeft':  // Slide Left
            nextslide.css({left : -base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 6: case 'carouselRight': // Carousel Right
            nextslide.css({left : base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
          liveslide.animate({ left: -base.$el.width(), avoidTransforms : false }, base.options.transition_speed );
            break;
          case 7: case 'carouselLeft':   // Carousel Left
            nextslide.css({left : -base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
          liveslide.animate({ left: base.$el.width(), avoidTransforms : false }, base.options.transition_speed );
            break;
        }
        return false; 
    };
    
    
    /* Previous Slide
    ----------------------------*/
    base.prevSlide = function(){
    
      if(vars.in_animation || !api.options.slideshow) return false;   // Abort if currently animating
        else vars.in_animation = true;    // Otherwise set animation marker
      
      clearInterval(vars.slideshow_interval); // Stop slideshow
      
      var slides = base.options.slides,         // Pull in slides array
        liveslide = base.$el.find('.activeslide');    // Find active slide
        $('.prevslide').removeClass('prevslide');
        liveslide.removeClass('activeslide').addClass('prevslide');   // Remove active class & update previous slide
      
      // Get current slide number
      vars.current_slide == 0 ?  vars.current_slide = base.options.slides.length - 1 : vars.current_slide-- ;
        
        var nextslide =  $(base.el+' li:eq('+vars.current_slide+')'),
          prevslide =  base.$el.find('.prevslide');
      
      // If hybrid mode is on drop quality for transition
      if (base.options.performance == 1) base.$el.removeClass('quality').addClass('speed'); 
      
      
      /*-----Load Image-----*/
      
      loadSlide = vars.current_slide;
      
      var targetList = base.el+' li:eq('+loadSlide+')';
      if (!$(targetList).html()){
        // If links should open in new window
        var linkTarget = base.options.new_window ? ' target="_blank"' : '';
        imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";  // If link exists, build it
        var img = $('<img src="'+base.options.slides[loadSlide].image+'"/>'); 
        
        img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility','hidden');
        
        img.load(function(){
          base._origDim($(this));
          base.resizeNow();
        }); // End Load
      };
      
      // Update thumbnails (if enabled)
      if (base.options.thumbnail_navigation == 1){
      
        // Load previous thumbnail
        //prevThumb = loadSlide;
        loadSlide == 0 ? prevThumb = base.options.slides.length - 1 : prevThumb = loadSlide - 1;
        $(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].image));
        
        // Load next thumbnail
        vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
        $(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].image));
      }
      
      /*-----End Load Image-----*/
      
      
      // Call theme function for before slide transition
      if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('prev');
      
      //Update slide markers
      if (base.options.slide_links){
        $('.current-slide').removeClass('current-slide');
        $(vars.slide_list +'> li' ).eq(vars.current_slide).addClass('current-slide');
      }
      
        nextslide.css('visibility','hidden').addClass('activeslide'); // Update active slide
        
        switch(base.options.transition){
          case 0: case 'none':  // No transition
              nextslide.css('visibility','visible'); vars.in_animation = false; base.afterAnimation();
              break;
          case 1: case 'fade':  // Fade
              nextslide.css({opacity : 0, 'visibility': 'visible'}).animate({opacity : 1, avoidTransforms : false}, base.options.transition_speed, function(){ base.afterAnimation(); });
              break;
          case 2: case 'slideTop':  // Slide Top (reverse)
              nextslide.css({top : base.$el.height(), 'visibility': 'visible'}).animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
              break;
          case 3: case 'slideRight':  // Slide Right (reverse)
            nextslide.css({left : -base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 4: case 'slideBottom': // Slide Bottom (reverse)
            nextslide.css({top : -base.$el.height(), 'visibility': 'visible'}).animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 5: case 'slideLeft':  // Slide Left (reverse)
            nextslide.css({left : base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
            break;
          case 6: case 'carouselRight': // Carousel Right (reverse)
            nextslide.css({left : -base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
          liveslide.css({left : 0}).animate({ left: base.$el.width(), avoidTransforms : false}, base.options.transition_speed );
            break;
          case 7: case 'carouselLeft':   // Carousel Left (reverse)
            nextslide.css({left : base.$el.width(), 'visibility': 'visible'}).animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
          liveslide.css({left : 0}).animate({ left: -base.$el.width(), avoidTransforms : false }, base.options.transition_speed );
            break;
        }
        return false; 
    };
    
    
    /* Play/Pause Toggle
    ----------------------------*/
    base.playToggle = function(){
    
      if (vars.in_animation || !api.options.slideshow) return false;    // Abort if currently animating
      
      if (vars.is_paused){
        
        vars.is_paused = false;
        
        // Call theme function for play
        if( typeof theme != 'undefined' && typeof theme.playToggle == "function" ) theme.playToggle('play');
        
        // Resume slideshow
            vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
              
          }else{
            
            vars.is_paused = true;
            
            // Call theme function for pause
            if( typeof theme != 'undefined' && typeof theme.playToggle == "function" ) theme.playToggle('pause');
            
            // Stop slideshow
            clearInterval(vars.slideshow_interval); 
            
            }
        
        return false;
        
      };
      
      
      /* Go to specific slide
    ----------------------------*/
      base.goTo = function(targetSlide){
      if (vars.in_animation || !api.options.slideshow) return false;    // Abort if currently animating
      
      var totalSlides = base.options.slides.length;
      
      // If target outside range
      if(targetSlide < 0){
        targetSlide = totalSlides;
      }else if(targetSlide > totalSlides){
        targetSlide = 1;
      }
      targetSlide = totalSlides - targetSlide + 1;
      
      clearInterval(vars.slideshow_interval); // Stop slideshow, prevent buildup
      
      // Call theme function for goTo trigger
      if (typeof theme != 'undefined' && typeof theme.goTo == "function" ) theme.goTo();
      
      if (vars.current_slide == totalSlides - targetSlide){
        if(!(vars.is_paused)){
          vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
        } 
        return false;
      }
      
      // If ahead of current position
      if(totalSlides - targetSlide > vars.current_slide ){
        
        // Adjust for new next slide
        vars.current_slide = totalSlides-targetSlide-1;
        vars.update_images = 'next';
        base._placeSlide(vars.update_images);
        
      //Otherwise it's before current position
      }else if(totalSlides - targetSlide < vars.current_slide){
        
        // Adjust for new prev slide
        vars.current_slide = totalSlides-targetSlide+1;
        vars.update_images = 'prev';
          base._placeSlide(vars.update_images);
          
      }
      
      // set active markers
      if (base.options.slide_links){
        $(vars.slide_list +'> .current-slide').removeClass('current-slide');
        $(vars.slide_list +'> li').eq((totalSlides-targetSlide)).addClass('current-slide');
      }
      
      if (base.options.thumb_links){
        $(vars.thumb_list +'> .current-thumb').removeClass('current-thumb');
        $(vars.thumb_list +'> li').eq((totalSlides-targetSlide)).addClass('current-thumb');
      }
      
    };
         
         
         /* Place Slide
    ----------------------------*/
         base._placeSlide = function(place){
          
      // If links should open in new window
      var linkTarget = base.options.new_window ? ' target="_blank"' : '';
      
      loadSlide = false;
      
      if (place == 'next'){
        
        vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;  // Determine next slide
        
        var targetList = base.el+' li:eq('+loadSlide+')';
        
        if (!$(targetList).html()){
          // If links should open in new window
          var linkTarget = base.options.new_window ? ' target="_blank"' : '';
          
          imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";  // If link exists, build it
          var img = $('<img src="'+base.options.slides[loadSlide].image+'"/>'); 
          
          img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility','hidden');
          
          img.load(function(){
            base._origDim($(this));
            base.resizeNow();
          }); // End Load
        };
        
        base.nextSlide();
        
      }else if (place == 'prev'){
      
        vars.current_slide - 1 < 0  ? loadSlide = base.options.slides.length - 1 : loadSlide = vars.current_slide - 1;  // Determine next slide
        
        var targetList = base.el+' li:eq('+loadSlide+')';
        
        if (!$(targetList).html()){
          // If links should open in new window
          var linkTarget = base.options.new_window ? ' target="_blank"' : '';
          
          imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";  // If link exists, build it
          var img = $('<img src="'+base.options.slides[loadSlide].image+'"/>'); 
          
          img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility','hidden');
          
          img.load(function(){
            base._origDim($(this));
            base.resizeNow();
          }); // End Load
        };
        base.prevSlide();
      }
      
    };
    
    
    /* Get Original Dimensions
    ----------------------------*/
    base._origDim = function(targetSlide){
      targetSlide.data('origWidth', targetSlide.width()).data('origHeight', targetSlide.height());
    };
    
    
    /* After Slide Animation
    ----------------------------*/
    base.afterAnimation = function(){
      
      // If hybrid mode is on swap back to higher image quality
      if (base.options.performance == 1){
          base.$el.removeClass('speed').addClass('quality');
      }
      
      // Update previous slide
      if (vars.update_images){
        vars.current_slide - 1 < 0  ? setPrev = base.options.slides.length - 1 : setPrev = vars.current_slide-1;
        vars.update_images = false;
        $('.prevslide').removeClass('prevslide');
        $(base.el+' li:eq('+setPrev+')').addClass('prevslide');
      }
      
      vars.in_animation = false;
      
      // Resume slideshow
      if (!vars.is_paused && base.options.slideshow){
        vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
        if (base.options.stop_loop && vars.current_slide == base.options.slides.length - 1 ) base.playToggle();
      }
      
      // Call theme function for after slide transition
      if (typeof theme != 'undefined' && typeof theme.afterAnimation == "function" ) theme.afterAnimation();
      
      return false;
    
    };
    
    base.getField = function(field){
      return base.options.slides[vars.current_slide][field];
    };
    
         // Make it go!
         base.init();
  };
  
  
  /* Global Variables
  ----------------------------*/
  $.supersized.vars = {
  
    // Elements             
    thumb_tray      : '#thumb-tray',  // Thumbnail tray
    thumb_list      : '#thumb-list',  // Thumbnail list
    slide_list          :   '#slide-list',  // Slide link list
    
    // Internal variables
    current_slide     : 0,      // Current slide number
    in_animation      : false,    // Prevents animations from stacking
    is_paused         :   false,    // Tracks paused on/off
    hover_pause       : false,    // If slideshow is paused from hover
    slideshow_interval    : false,    // Stores slideshow timer         
    update_images       :   false,    // Trigger to update images after slide jump
    options         : {}      // Stores assembled options list
    
  };
  
  
  /* Default Options
  ----------------------------*/
  $.supersized.defaultOptions = {
     
      // Functionality
    slideshow               :   1,      // Slideshow on/off
    autoplay        : 1,      // Slideshow starts playing automatically
    start_slide             :   1,      // Start slide (0 is random)
    stop_loop       : 0,      // Stops slideshow on last slide
    random          :   0,      // Randomize slide order (Ignores start slide)
    slide_interval          :   5000,   // Length between transitions
    transition              :   1,      // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
    transition_speed    : 750,    // Speed of transition
    new_window        : 1,      // Image links open in new window/tab
    pause_hover             :   0,      // Pause slideshow on hover
    keyboard_nav            :   1,      // Keyboard navigation on/off
    performance       : 1,      // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed //  (Only works for Firefox/IE, not Webkit)
    image_protect     : 1,      // Disables image dragging and right click with Javascript
                           
    // Size & Position
    fit_always        : 0,      // Image will never exceed browser width or height (Ignores min. dimensions)
    fit_landscape     :   0,      // Landscape images will not exceed browser width
    fit_portrait          :   1,      // Portrait images will not exceed browser height          
    min_width           :   0,      // Min width allowed (in pixels)
    min_height            :   0,      // Min height allowed (in pixels)
    horizontal_center       :   1,      // Horizontally center background
    vertical_center         :   1,      // Vertically center background
    
                           
    // Components             
    slide_links       : 1,      // Individual links for each slide (Options: false, 'num', 'name', 'blank')
    thumb_links       : 1,      // Individual thumb links for each slide
    thumbnail_navigation    :   0     // Thumbnail navigation
      
     };
     
     $.fn.supersized = function(options){
         return this.each(function(){
             (new $.supersized(options));
         });
     };
    
 })(jQuery);



/**
 * jQuery Masonry v2.1.05
 * A dynamic layout plugin for jQuery
 * The flip-side of CSS Floats
 * http://masonry.desandro.com
 *
 * Licensed under the MIT license.
 * Copyright 2012 David DeSandro
 */
(function(a,b,c){"use strict";var d=b.event,e;d.special.smartresize={setup:function(){b(this).bind("resize",d.special.smartresize.handler)},teardown:function(){b(this).unbind("resize",d.special.smartresize.handler)},handler:function(a,c){var d=this,f=arguments;a.type="smartresize",e&&clearTimeout(e),e=setTimeout(function(){b.event.handle.apply(d,f)},c==="execAsap"?0:100)}},b.fn.smartresize=function(a){return a?this.bind("smartresize",a):this.trigger("smartresize",["execAsap"])},b.Mason=function(a,c){this.element=b(c),this._create(a),this._init()},b.Mason.settings={isResizable:!0,isAnimated:!1,animationOptions:{queue:!1,duration:500},gutterWidth:0,isRTL:!1,isFitWidth:!1,containerStyle:{position:"relative"}},b.Mason.prototype={_filterFindBricks:function(a){var b=this.options.itemSelector;return b?a.filter(b).add(a.find(b)):a},_getBricks:function(a){var b=this._filterFindBricks(a).css({position:"absolute"}).addClass("masonry-brick");return b},_create:function(c){this.options=b.extend(!0,{},b.Mason.settings,c),this.styleQueue=[];var d=this.element[0].style;this.originalStyle={height:d.height||""};var e=this.options.containerStyle;for(var f in e)this.originalStyle[f]=d[f]||"";this.element.css(e),this.horizontalDirection=this.options.isRTL?"right":"left",this.offset={x:parseInt(this.element.css("padding-"+this.horizontalDirection),10),y:parseInt(this.element.css("padding-top"),10)},this.isFluid=this.options.columnWidth&&typeof this.options.columnWidth=="function";var g=this;setTimeout(function(){g.element.addClass("masonry")},0),this.options.isResizable&&b(a).bind("smartresize.masonry",function(){g.resize()}),this.reloadItems()},_init:function(a){this._getColumns(),this._reLayout(a)},option:function(a,c){b.isPlainObject(a)&&(this.options=b.extend(!0,this.options,a))},layout:function(a,b){for(var c=0,d=a.length;c<d;c++)this._placeBrick(a[c]);var e={};e.height=Math.max.apply(Math,this.colYs);if(this.options.isFitWidth){var f=0;c=this.cols;while(--c){if(this.colYs[c]!==0)break;f++}e.width=(this.cols-f)*this.columnWidth-this.options.gutterWidth}this.styleQueue.push({$el:this.element,style:e});var g=this.isLaidOut?this.options.isAnimated?"animate":"css":"css",h=this.options.animationOptions,i;for(c=0,d=this.styleQueue.length;c<d;c++)i=this.styleQueue[c],i.$el[g](i.style,h);this.styleQueue=[],b&&b.call(a),this.isLaidOut=!0},_getColumns:function(){var a=this.options.isFitWidth?this.element.parent():this.element,b=a.width();this.columnWidth=this.isFluid?this.options.columnWidth(b):this.options.columnWidth||this.$bricks.outerWidth(!0)||b,this.columnWidth+=this.options.gutterWidth,this.cols=Math.floor((b+this.options.gutterWidth)/this.columnWidth),this.cols=Math.max(this.cols,1)},_placeBrick:function(a){var c=b(a),d,e,f,g,h;d=Math.ceil(c.outerWidth(!0)/this.columnWidth),d=Math.min(d,this.cols);if(d===1)f=this.colYs;else{e=this.cols+1-d,f=[];for(h=0;h<e;h++)g=this.colYs.slice(h,h+d),f[h]=Math.max.apply(Math,g)}var i=Math.min.apply(Math,f),j=0;for(var k=0,l=f.length;k<l;k++)if(f[k]===i){j=k;break}var m={top:i+this.offset.y};m[this.horizontalDirection]=this.columnWidth*j+this.offset.x,this.styleQueue.push({$el:c,style:m});var n=i+c.outerHeight(!0),o=this.cols+1-l;for(k=0;k<o;k++)this.colYs[j+k]=n},resize:function(){var a=this.cols;this._getColumns(),(this.isFluid||this.cols!==a)&&this._reLayout()},_reLayout:function(a){var b=this.cols;this.colYs=[];while(b--)this.colYs.push(0);this.layout(this.$bricks,a)},reloadItems:function(){this.$bricks=this._getBricks(this.element.children())},reload:function(a){this.reloadItems(),this._init(a)},appended:function(a,b,c){if(b){this._filterFindBricks(a).css({top:this.element.height()});var d=this;setTimeout(function(){d._appended(a,c)},1)}else this._appended(a,c)},_appended:function(a,b){var c=this._getBricks(a);this.$bricks=this.$bricks.add(c),this.layout(c,b)},remove:function(a){this.$bricks=this.$bricks.not(a),a.remove()},destroy:function(){this.$bricks.removeClass("masonry-brick").each(function(){this.style.position="",this.style.top="",this.style.left=""});var c=this.element[0].style;for(var d in this.originalStyle)c[d]=this.originalStyle[d];this.element.unbind(".masonry").removeClass("masonry").removeData("masonry"),b(a).unbind(".masonry")}},b.fn.imagesLoaded=function(a){function h(){a.call(c,d)}function i(a){var c=a.target;c.src!==f&&b.inArray(c,g)===-1&&(g.push(c),--e<=0&&(setTimeout(h),d.unbind(".imagesLoaded",i)))}var c=this,d=c.find("img").add(c.filter("img")),e=d.length,f="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",g=[];return e||h(),d.bind("load.imagesLoaded error.imagesLoaded",i).each(function(){var a=this.src;this.src=f,this.src=a}),c};var f=function(b){a.console&&a.console.error(b)};b.fn.masonry=function(a){if(typeof a=="string"){var c=Array.prototype.slice.call(arguments,1);this.each(function(){var d=b.data(this,"masonry");if(!d){f("cannot call methods on masonry prior to initialization; attempted to call method '"+a+"'");return}if(!b.isFunction(d[a])||a.charAt(0)==="_"){f("no such method '"+a+"' for masonry instance");return}d[a].apply(d,c)})}else this.each(function(){var c=b.data(this,"masonry");c?(c.option(a||{}),c._init()):b.data(this,"masonry",new b.Mason(a,this))});return this}})(window,jQuery);


/*
 * FancyBox - jQuery Plugin
 * Simple and fancy lightbox alternative
 *
 * Examples and documentation at: http://fancybox.net
 *
 * Copyright (c) 2008 - 2010 Janis Skarnelis
 * That said, it is hardly a one-person project. Many people have submitted bugs, code, and offered their advice freely. Their support is greatly appreciated.
 *
 * Version: 1.3.4 (11/11/2010)
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

;(function($) {
  var tmp, loading, overlay, wrap, outer, content, close, title, nav_left, nav_right,

    selectedIndex = 0, selectedOpts = {}, selectedArray = [], currentIndex = 0, currentOpts = {}, currentArray = [],

    ajaxLoader = null, imgPreloader = new Image(), imgRegExp = /\.(jpg|gif|png|bmp|jpeg)(.*)?$/i, swfRegExp = /[^\.]\.(swf)\s*$/i,

    loadingTimer, loadingFrame = 1,

    titleHeight = 0, titleStr = '', start_pos, final_pos, busy = false, fx = $.extend($('<div/>')[0], { prop: 0 }),

    isIE6 = $.browser.msie && $.browser.version < 7 && !window.XMLHttpRequest,

    /*
     * Private methods 
     */

    _abort = function() {
      loading.hide();

      imgPreloader.onerror = imgPreloader.onload = null;

      if (ajaxLoader) {
        ajaxLoader.abort();
      }

      tmp.empty();
    },

    _error = function() {
      if (false === selectedOpts.onError(selectedArray, selectedIndex, selectedOpts)) {
        loading.hide();
        busy = false;
        return;
      }

      selectedOpts.titleShow = false;

      selectedOpts.width = 'auto';
      selectedOpts.height = 'auto';

      tmp.html( '<p id="fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>' );

      _process_inline();
    },

    _start = function() {
      var obj = selectedArray[ selectedIndex ],
        href, 
        type, 
        title,
        str,
        emb,
        ret;

      _abort();

      selectedOpts = $.extend({}, $.fn.fancybox.defaults, (typeof $(obj).data('fancybox') == 'undefined' ? selectedOpts : $(obj).data('fancybox')));

      ret = selectedOpts.onStart(selectedArray, selectedIndex, selectedOpts);

      if (ret === false) {
        busy = false;
        return;
      } else if (typeof ret == 'object') {
        selectedOpts = $.extend(selectedOpts, ret);
      }

      title = selectedOpts.title || (obj.nodeName ? $(obj).attr('title') : obj.title) || '';

      if (obj.nodeName && !selectedOpts.orig) {
        selectedOpts.orig = $(obj).children("img:first").length ? $(obj).children("img:first") : $(obj);
      }

      if (title === '' && selectedOpts.orig && selectedOpts.titleFromAlt) {
        title = selectedOpts.orig.attr('alt');
      }

      href = selectedOpts.href || (obj.nodeName ? $(obj).attr('href') : obj.href) || null;

      if ((/^(?:javascript)/i).test(href) || href == '#') {
        href = null;
      }

      if (selectedOpts.type) {
        type = selectedOpts.type;

        if (!href) {
          href = selectedOpts.content;
        }

      } else if (selectedOpts.content) {
        type = 'html';

      } else if (href) {
        if (href.match(imgRegExp)) {
          type = 'image';

        } else if (href.match(swfRegExp)) {
          type = 'swf';

        } else if ($(obj).hasClass("iframe")) {
          type = 'iframe';

        } else if (href.indexOf("#") === 0) {
          type = 'inline';

        } else {
          type = 'ajax';
        }
      }

      if (!type) {
        _error();
        return;
      }

      if (type == 'inline') {
        obj = href.substr(href.indexOf("#"));
        type = $(obj).length > 0 ? 'inline' : 'ajax';
      }

      selectedOpts.type = type;
      selectedOpts.href = href;
      selectedOpts.title = title;

      if (selectedOpts.autoDimensions) {
        if (selectedOpts.type == 'html' || selectedOpts.type == 'inline' || selectedOpts.type == 'ajax') {
          selectedOpts.width = 'auto';
          selectedOpts.height = 'auto';
        } else {
          selectedOpts.autoDimensions = false;  
        }
      }

      if (selectedOpts.modal) {
        selectedOpts.overlayShow = true;
        selectedOpts.hideOnOverlayClick = false;
        selectedOpts.hideOnContentClick = false;
        selectedOpts.enableEscapeButton = false;
        selectedOpts.showCloseButton = false;
      }

      selectedOpts.padding = parseInt(selectedOpts.padding, 10);
      selectedOpts.margin = parseInt(selectedOpts.margin, 10);

      tmp.css('padding', (selectedOpts.padding + selectedOpts.margin));

      $('.fancybox-inline-tmp').unbind('fancybox-cancel').bind('fancybox-change', function() {
        $(this).replaceWith(content.children());        
      });

      switch (type) {
        case 'html' :
          tmp.html( selectedOpts.content );
          _process_inline();
        break;

        case 'inline' :
          if ( $(obj).parent().is('#fancybox-content') === true) {
            busy = false;
            return;
          }

          $('<div class="fancybox-inline-tmp" />')
            .hide()
            .insertBefore( $(obj) )
            .bind('fancybox-cleanup', function() {
              $(this).replaceWith(content.children());
            }).bind('fancybox-cancel', function() {
              $(this).replaceWith(tmp.children());
            });

          $(obj).appendTo(tmp);

          _process_inline();
        break;

        case 'image':
          busy = false;

          $.fancybox.showActivity();

          imgPreloader = new Image();

          imgPreloader.onerror = function() {
            _error();
          };

          imgPreloader.onload = function() {
            busy = true;

            imgPreloader.onerror = imgPreloader.onload = null;

            _process_image();
          };

          imgPreloader.src = href;
        break;

        case 'swf':
          selectedOpts.scrolling = 'no';

          str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"><param name="movie" value="' + href + '"></param>';
          emb = '';

          $.each(selectedOpts.swf, function(name, val) {
            str += '<param name="' + name + '" value="' + val + '"></param>';
            emb += ' ' + name + '="' + val + '"';
          });

          str += '<embed src="' + href + '" type="application/x-shockwave-flash" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"' + emb + '></embed></object>';

          tmp.html(str);

          _process_inline();
        break;

        case 'ajax':
          busy = false;

          $.fancybox.showActivity();

          selectedOpts.ajax.win = selectedOpts.ajax.success;

          ajaxLoader = $.ajax($.extend({}, selectedOpts.ajax, {
            url : href,
            data : selectedOpts.ajax.data || {},
            error : function(XMLHttpRequest, textStatus, errorThrown) {
              if ( XMLHttpRequest.status > 0 ) {
                _error();
              }
            },
            success : function(data, textStatus, XMLHttpRequest) {
              var o = typeof XMLHttpRequest == 'object' ? XMLHttpRequest : ajaxLoader;
              if (o.status == 200) {
                if ( typeof selectedOpts.ajax.win == 'function' ) {
                  ret = selectedOpts.ajax.win(href, data, textStatus, XMLHttpRequest);

                  if (ret === false) {
                    loading.hide();
                    return;
                  } else if (typeof ret == 'string' || typeof ret == 'object') {
                    data = ret;
                  }
                }

                tmp.html( data );
                _process_inline();
              }
            }
          }));

        break;

        case 'iframe':
          _show();
        break;
      }
    },

    _process_inline = function() {
      var
        w = selectedOpts.width,
        h = selectedOpts.height;

      if (w.toString().indexOf('%') > -1) {
        w = parseInt( ($(window).width() - (selectedOpts.margin * 2)) * parseFloat(w) / 100, 10) + 'px';

      } else {
        w = w == 'auto' ? 'auto' : w + 'px';  
      }

      if (h.toString().indexOf('%') > -1) {
        h = parseInt( ($(window).height() - (selectedOpts.margin * 2)) * parseFloat(h) / 100, 10) + 'px';

      } else {
        h = h == 'auto' ? 'auto' : h + 'px';  
      }

      tmp.wrapInner('<div style="width:' + w + ';height:' + h + ';overflow: ' + (selectedOpts.scrolling == 'auto' ? 'auto' : (selectedOpts.scrolling == 'yes' ? 'scroll' : 'hidden')) + ';position:relative;"></div>');

      selectedOpts.width = tmp.width();
      selectedOpts.height = tmp.height();

      _show();
    },

    _process_image = function() {
      selectedOpts.width = imgPreloader.width;
      selectedOpts.height = imgPreloader.height;

      $("<img />").attr({
        'id' : 'fancybox-img',
        'src' : imgPreloader.src,
        'alt' : selectedOpts.title
      }).appendTo( tmp );

      _show();
    },

    _show = function() {
      var pos, equal;

      loading.hide();

      if (wrap.is(":visible") && false === currentOpts.onCleanup(currentArray, currentIndex, currentOpts)) {
        $.event.trigger('fancybox-cancel');

        busy = false;
        return;
      }

      busy = true;

      $(content.add( overlay )).unbind();

      $(window).unbind("resize.fb scroll.fb");
      $(document).unbind('keydown.fb');

      if (wrap.is(":visible") && currentOpts.titlePosition !== 'outside') {
        wrap.css('height', wrap.height());
      }

      currentArray = selectedArray;
      currentIndex = selectedIndex;
      currentOpts = selectedOpts;

      if (currentOpts.overlayShow) {
        overlay.css({
          'background-color' : currentOpts.overlayColor,
          'opacity' : currentOpts.overlayOpacity,
          'cursor' : currentOpts.hideOnOverlayClick ? 'pointer' : 'auto',
          'height' : $(document).height()
        });

        if (!overlay.is(':visible')) {
          if (isIE6) {
            $('select:not(#fancybox-tmp select)').filter(function() {
              return this.style.visibility !== 'hidden';
            }).css({'visibility' : 'hidden'}).one('fancybox-cleanup', function() {
              this.style.visibility = 'inherit';
            });
          }

          overlay.show();
        }
      } else {
        overlay.hide();
      }

      final_pos = _get_zoom_to();

      _process_title();

      if (wrap.is(":visible")) {
        $( close.add( nav_left ).add( nav_right ) ).hide();

        pos = wrap.position(),

        start_pos = {
          top  : pos.top,
          left : pos.left,
          width : wrap.width(),
          height : wrap.height()
        };

        equal = (start_pos.width == final_pos.width && start_pos.height == final_pos.height);

        content.fadeTo(currentOpts.changeFade, 0.3, function() {
          var finish_resizing = function() {
            content.html( tmp.contents() ).fadeTo(currentOpts.changeFade, 1, _finish);
          };

          $.event.trigger('fancybox-change');

          content
            .empty()
            .removeAttr('filter')
            .css({
              'border-width' : currentOpts.padding,
              'width' : final_pos.width - currentOpts.padding * 2,
              'height' : selectedOpts.autoDimensions ? 'auto' : final_pos.height - titleHeight - currentOpts.padding * 2
            });

          if (equal) {
            finish_resizing();

          } else {
            fx.prop = 0;

            $(fx).animate({prop: 1}, {
               duration : currentOpts.changeSpeed,
               easing : currentOpts.easingChange,
               step : _draw,
               complete : finish_resizing
            });
          }
        });

        return;
      }

      wrap.removeAttr("style");

      content.css('border-width', currentOpts.padding);

      if (currentOpts.transitionIn == 'elastic') {
        start_pos = _get_zoom_from();

        content.html( tmp.contents() );

        wrap.show();

        if (currentOpts.opacity) {
          final_pos.opacity = 0;
        }

        fx.prop = 0;

        $(fx).animate({prop: 1}, {
           duration : currentOpts.speedIn,
           easing : currentOpts.easingIn,
           step : _draw,
           complete : _finish
        });

        return;
      }

      if (currentOpts.titlePosition == 'inside' && titleHeight > 0) { 
        title.show(); 
      }

      content
        .css({
          'width' : final_pos.width - currentOpts.padding * 2,
          'height' : selectedOpts.autoDimensions ? 'auto' : final_pos.height - titleHeight - currentOpts.padding * 2
        })
        .html( tmp.contents() );

      wrap
        .css(final_pos)
        .fadeIn( currentOpts.transitionIn == 'none' ? 0 : currentOpts.speedIn, _finish );
    },

    _format_title = function(title) {
      if (title && title.length) {
        if (currentOpts.titlePosition == 'float') {
          return '<table id="fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="fancybox-title-float-left"></td><td id="fancybox-title-float-main">' + title + '</td><td id="fancybox-title-float-right"></td></tr></table>';
        }

        return '<div id="fancybox-title-' + currentOpts.titlePosition + '">' + title + '</div>';
      }

      return false;
    },

    _process_title = function() {
      titleStr = currentOpts.title || '';
      titleHeight = 0;

      title
        .empty()
        .removeAttr('style')
        .removeClass();

      if (currentOpts.titleShow === false) {
        title.hide();
        return;
      }

      titleStr = $.isFunction(currentOpts.titleFormat) ? currentOpts.titleFormat(titleStr, currentArray, currentIndex, currentOpts) : _format_title(titleStr);

      if (!titleStr || titleStr === '') {
        title.hide();
        return;
      }

      title
        .addClass('fancybox-title-' + currentOpts.titlePosition)
        .html( titleStr )
        .appendTo( 'body' )
        .show();

      switch (currentOpts.titlePosition) {
        case 'inside':
          title
            .css({
              'width' : final_pos.width - (currentOpts.padding * 2),
              'marginLeft' : currentOpts.padding,
              'marginRight' : currentOpts.padding
            });

          titleHeight = title.outerHeight(true);

          title.appendTo( outer );

          final_pos.height += titleHeight;
        break;

        case 'over':
          title
            .css({
              'marginLeft' : currentOpts.padding,
              'width' : final_pos.width - (currentOpts.padding * 2),
              'bottom' : currentOpts.padding
            })
            .appendTo( outer );
        break;

        case 'float':
          title
            .css('left', parseInt((title.width() - final_pos.width - 40)/ 2, 10) * -1)
            .appendTo( wrap );
        break;

        default:
          title
            .css({
              'width' : final_pos.width - (currentOpts.padding * 2),
              'paddingLeft' : currentOpts.padding,
              'paddingRight' : currentOpts.padding
            })
            .appendTo( wrap );
        break;
      }

      title.hide();
    },

    _set_navigation = function() {
      if (currentOpts.enableEscapeButton || currentOpts.enableKeyboardNav) {
        $(document).bind('keydown.fb', function(e) {
          if (e.keyCode == 27 && currentOpts.enableEscapeButton) {
            e.preventDefault();
            $.fancybox.close();

          } else if ((e.keyCode == 37 || e.keyCode == 39) && currentOpts.enableKeyboardNav && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'SELECT') {
            e.preventDefault();
            $.fancybox[ e.keyCode == 37 ? 'prev' : 'next']();
          }
        });
      }

      if (!currentOpts.showNavArrows) { 
        nav_left.hide();
        nav_right.hide();
        return;
      }

      if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex !== 0) {
        nav_left.show();
      }

      if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex != (currentArray.length -1)) {
        nav_right.show();
      }
    },

    _finish = function () {
      if (!$.support.opacity) {
        content.get(0).style.removeAttribute('filter');
        wrap.get(0).style.removeAttribute('filter');
      }

      if (selectedOpts.autoDimensions) {
        content.css('height', 'auto');
      }

      wrap.css('height', 'auto');

      if (titleStr && titleStr.length) {
        title.show();
      }

      if (currentOpts.showCloseButton) {
        close.show();
      }

      _set_navigation();
  
      if (currentOpts.hideOnContentClick) {
        content.bind('click', $.fancybox.close);
      }

      if (currentOpts.hideOnOverlayClick) {
        overlay.bind('click', $.fancybox.close);
      }

      $(window).bind("resize.fb", $.fancybox.resize);

      if (currentOpts.centerOnScroll) {
        $(window).bind("scroll.fb", $.fancybox.center);
      }

      if (currentOpts.type == 'iframe') {
        $('<iframe id="fancybox-frame" name="fancybox-frame' + new Date().getTime() + '" frameborder="0" hspace="0" ' + ($.browser.msie ? 'allowtransparency="true""' : '') + ' scrolling="' + selectedOpts.scrolling + '" src="' + currentOpts.href + '"></iframe>').appendTo(content);
      }

      wrap.show();

      busy = false;

      $.fancybox.center();

      currentOpts.onComplete(currentArray, currentIndex, currentOpts);

      _preload_images();
    },

    _preload_images = function() {
      var href, 
        objNext;

      if ((currentArray.length -1) > currentIndex) {
        href = currentArray[ currentIndex + 1 ].href;

        if (typeof href !== 'undefined' && href.match(imgRegExp)) {
          objNext = new Image();
          objNext.src = href;
        }
      }

      if (currentIndex > 0) {
        href = currentArray[ currentIndex - 1 ].href;

        if (typeof href !== 'undefined' && href.match(imgRegExp)) {
          objNext = new Image();
          objNext.src = href;
        }
      }
    },

    _draw = function(pos) {
      var dim = {
        width : parseInt(start_pos.width + (final_pos.width - start_pos.width) * pos, 10),
        height : parseInt(start_pos.height + (final_pos.height - start_pos.height) * pos, 10),

        top : parseInt(start_pos.top + (final_pos.top - start_pos.top) * pos, 10),
        left : parseInt(start_pos.left + (final_pos.left - start_pos.left) * pos, 10)
      };

      if (typeof final_pos.opacity !== 'undefined') {
        dim.opacity = pos < 0.5 ? 0.5 : pos;
      }

      wrap.css(dim);
      wrap.css('width', dim.width);

      content.css({
        'width' : (dim.width - currentOpts.padding * 2),
        'height' : (dim.height - (titleHeight * pos) - currentOpts.padding * 2)
      });
    },

    _get_viewport = function() {
      return [
        $(window).width() - (currentOpts.margin * 2),
        $(window).height() - (currentOpts.margin * 2),
        $(document).scrollLeft() + currentOpts.margin,
        $(document).scrollTop() + currentOpts.margin
      ];
    },

    _get_zoom_to = function () {
      var view = _get_viewport(),
        to = {},
        resize = currentOpts.autoScale,
        double_padding = currentOpts.padding * 2,
        ratio;

      if (currentOpts.width.toString().indexOf('%') > -1) {
        to.width = parseInt((view[0] * parseFloat(currentOpts.width)) / 100, 10);
      } else {
        to.width = currentOpts.width + double_padding;
      }

      if (currentOpts.height.toString().indexOf('%') > -1) {
        to.height = parseInt((view[1] * parseFloat(currentOpts.height)) / 100, 10);
      } else {
        to.height = currentOpts.height + double_padding;
      }

      if (resize && (to.width > view[0] || to.height > view[1])) {
        if (selectedOpts.type == 'image' || selectedOpts.type == 'swf') {
          ratio = (currentOpts.width ) / (currentOpts.height );

          if ((to.width ) > view[0]) {
            to.width = view[0];
            to.height = parseInt(((to.width - double_padding) / ratio) + double_padding, 10);
          }

          if ((to.height) > view[1]) {
            to.height = view[1];
            to.width = parseInt(((to.height - double_padding) * ratio) + double_padding, 10);
          }

        } else {
          to.width = Math.min(to.width, view[0]);
          to.height = Math.min(to.height, view[1]);
        }
      }

      to.top = parseInt(Math.max(view[3] - 20, view[3] + ((view[1] - to.height - 40) * 0.5)), 10);
      to.left = parseInt(Math.max(view[2] - 20, view[2] + ((view[0] - to.width - 40) * 0.5)), 10);

      return to;
    },

    _get_obj_pos = function(obj) {
      var pos = obj.offset();

      pos.top += parseInt( obj.css('paddingTop'), 10 ) || 0;
      pos.left += parseInt( obj.css('paddingLeft'), 10 ) || 0;

      pos.top += parseInt( obj.css('border-top-width'), 10 ) || 0;
      pos.left += parseInt( obj.css('border-left-width'), 10 ) || 0;

      pos.width = obj.width();
      pos.height = obj.height();

      return pos;
    },

    _get_zoom_from = function() {
      var orig = selectedOpts.orig ? $(selectedOpts.orig) : false,
        from = {},
        pos,
        view;

      if (orig && orig.length) {
        pos = _get_obj_pos(orig);

        from = {
          width : pos.width + (currentOpts.padding * 2),
          height : pos.height + (currentOpts.padding * 2),
          top : pos.top - currentOpts.padding - 20,
          left : pos.left - currentOpts.padding - 20
        };

      } else {
        view = _get_viewport();

        from = {
          width : currentOpts.padding * 2,
          height : currentOpts.padding * 2,
          top : parseInt(view[3] + view[1] * 0.5, 10),
          left : parseInt(view[2] + view[0] * 0.5, 10)
        };
      }

      return from;
    },

    _animate_loading = function() {
      if (!loading.is(':visible')){
        clearInterval(loadingTimer);
        return;
      }

      $('div', loading).css('top', (loadingFrame * -40) + 'px');

      loadingFrame = (loadingFrame + 1) % 12;
    };

  /*
   * Public methods 
   */

  $.fn.fancybox = function(options) {
    if (!$(this).length) {
      return this;
    }

    $(this)
      .data('fancybox', $.extend({}, options, ($.metadata ? $(this).metadata() : {})))
      .unbind('click.fb')
      .bind('click.fb', function(e) {
        e.preventDefault();

        if (busy) {
          return;
        }

        busy = true;

        $(this).blur();

        selectedArray = [];
        selectedIndex = 0;

        var rel = $(this).attr('rel') || '';

        if (!rel || rel == '' || rel === 'nofollow') {
          selectedArray.push(this);

        } else {
          selectedArray = $("a[rel=" + rel + "], area[rel=" + rel + "]");
          selectedIndex = selectedArray.index( this );
        }

        _start();

        return;
      });

    return this;
  };

  $.fancybox = function(obj) {
    var opts;

    if (busy) {
      return;
    }

    busy = true;
    opts = typeof arguments[1] !== 'undefined' ? arguments[1] : {};

    selectedArray = [];
    selectedIndex = parseInt(opts.index, 10) || 0;

    if ($.isArray(obj)) {
      for (var i = 0, j = obj.length; i < j; i++) {
        if (typeof obj[i] == 'object') {
          $(obj[i]).data('fancybox', $.extend({}, opts, obj[i]));
        } else {
          obj[i] = $({}).data('fancybox', $.extend({content : obj[i]}, opts));
        }
      }

      selectedArray = jQuery.merge(selectedArray, obj);

    } else {
      if (typeof obj == 'object') {
        $(obj).data('fancybox', $.extend({}, opts, obj));
      } else {
        obj = $({}).data('fancybox', $.extend({content : obj}, opts));
      }

      selectedArray.push(obj);
    }

    if (selectedIndex > selectedArray.length || selectedIndex < 0) {
      selectedIndex = 0;
    }

    _start();
  };

  $.fancybox.showActivity = function() {
    clearInterval(loadingTimer);

    loading.show();
    loadingTimer = setInterval(_animate_loading, 66);
  };

  $.fancybox.hideActivity = function() {
    loading.hide();
  };

  $.fancybox.next = function() {
    return $.fancybox.pos( currentIndex + 1);
  };

  $.fancybox.prev = function() {
    return $.fancybox.pos( currentIndex - 1);
  };

  $.fancybox.pos = function(pos) {
    if (busy) {
      return;
    }

    pos = parseInt(pos);

    selectedArray = currentArray;

    if (pos > -1 && pos < currentArray.length) {
      selectedIndex = pos;
      _start();

    } else if (currentOpts.cyclic && currentArray.length > 1) {
      selectedIndex = pos >= currentArray.length ? 0 : currentArray.length - 1;
      _start();
    }

    return;
  };

  $.fancybox.cancel = function() {
    if (busy) {
      return;
    }

    busy = true;

    $.event.trigger('fancybox-cancel');

    _abort();

    selectedOpts.onCancel(selectedArray, selectedIndex, selectedOpts);

    busy = false;
  };

  // Note: within an iframe use - parent.$.fancybox.close();
  $.fancybox.close = function() {
    if (busy || wrap.is(':hidden')) {
      return;
    }

    busy = true;

    if (currentOpts && false === currentOpts.onCleanup(currentArray, currentIndex, currentOpts)) {
      busy = false;
      return;
    }

    _abort();

    $(close.add( nav_left ).add( nav_right )).hide();

    $(content.add( overlay )).unbind();

    $(window).unbind("resize.fb scroll.fb");
    $(document).unbind('keydown.fb');

    content.find('iframe').attr('src', isIE6 && /^https/i.test(window.location.href || '') ? 'javascript:void(false)' : 'about:blank');

    if (currentOpts.titlePosition !== 'inside') {
      title.empty();
    }

    wrap.stop();

    function _cleanup() {
      overlay.fadeOut('fast');

      title.empty().hide();
      wrap.hide();

      $.event.trigger('fancybox-cleanup');

      content.empty();

      currentOpts.onClosed(currentArray, currentIndex, currentOpts);

      currentArray = selectedOpts = [];
      currentIndex = selectedIndex = 0;
      currentOpts = selectedOpts  = {};

      busy = false;
    }

    if (currentOpts.transitionOut == 'elastic') {
      start_pos = _get_zoom_from();

      var pos = wrap.position();

      final_pos = {
        top  : pos.top,
        left : pos.left,
        width : wrap.width(),
        height : wrap.height()
      };

      if (currentOpts.opacity) {
        final_pos.opacity = 1;
      }

      title.empty().hide();

      fx.prop = 1;

      $(fx).animate({ prop: 0 }, {
         duration : currentOpts.speedOut,
         easing : currentOpts.easingOut,
         step : _draw,
         complete : _cleanup
      });

    } else {
      wrap.fadeOut( currentOpts.transitionOut == 'none' ? 0 : currentOpts.speedOut, _cleanup);
    }
  };

  $.fancybox.resize = function() {
    if (overlay.is(':visible')) {
      overlay.css('height', $(document).height());
    }

    $.fancybox.center(true);
  };

  $.fancybox.center = function() {
    var view, align;

    if (busy) {
      return; 
    }

    align = arguments[0] === true ? 1 : 0;
    view = _get_viewport();

    if (!align && (wrap.width() > view[0] || wrap.height() > view[1])) {
      return; 
    }

    wrap
      .stop()
      .animate({
        'top' : parseInt(Math.max(view[3] - 20, view[3] + ((view[1] - content.height() - 40) * 0.5) - currentOpts.padding)),
        'left' : parseInt(Math.max(view[2] - 20, view[2] + ((view[0] - content.width() - 40) * 0.5) - currentOpts.padding))
      }, typeof arguments[0] == 'number' ? arguments[0] : 200);
  };

  $.fancybox.init = function() {
    if ($("#fancybox-wrap").length) {
      return;
    }

    $('body').append(
      tmp = $('<div id="fancybox-tmp"></div>'),
      loading = $('<div id="fancybox-loading"><div></div></div>'),
      overlay = $('<div id="fancybox-overlay"></div>'),
      wrap = $('<div id="fancybox-wrap"></div>')
    );

    outer = $('<div id="fancybox-outer"></div>')
      .append('<div class="fancybox-bg" id="fancybox-bg-n"></div><div class="fancybox-bg" id="fancybox-bg-ne"></div><div class="fancybox-bg" id="fancybox-bg-e"></div><div class="fancybox-bg" id="fancybox-bg-se"></div><div class="fancybox-bg" id="fancybox-bg-s"></div><div class="fancybox-bg" id="fancybox-bg-sw"></div><div class="fancybox-bg" id="fancybox-bg-w"></div><div class="fancybox-bg" id="fancybox-bg-nw"></div>')
      .appendTo( wrap );

    outer.append(
      content = $('<div id="fancybox-content"></div>'),
      close = $('<a id="fancybox-close"></a>'),
      title = $('<div id="fancybox-title"></div>'),

      nav_left = $('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"><i class="icon-arrow-left"></i></span></a>'),
      nav_right = $('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"><i class="icon-arrow-right"></i></span></a>')
    );

    close.click($.fancybox.close);
    loading.click($.fancybox.cancel);

    nav_left.click(function(e) {
      e.preventDefault();
      $.fancybox.prev();
    });

    nav_right.click(function(e) {
      e.preventDefault();
      $.fancybox.next();
    });

    if ($.fn.mousewheel) {
      wrap.bind('mousewheel.fb', function(e, delta) {
        if (busy) {
          e.preventDefault();

        } else if ($(e.target).get(0).clientHeight == 0 || $(e.target).get(0).scrollHeight === $(e.target).get(0).clientHeight) {
          e.preventDefault();
          $.fancybox[ delta > 0 ? 'prev' : 'next']();
        }
      });
    }

    if (!$.support.opacity) {
      wrap.addClass('fancybox-ie');
    }

    if (isIE6) {
      loading.addClass('fancybox-ie6');
      wrap.addClass('fancybox-ie6');

      $('<iframe id="fancybox-hide-sel-frame" src="' + (/^https/i.test(window.location.href || '') ? 'javascript:void(false)' : 'about:blank' ) + '" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(outer);
    }
  };

  $.fn.fancybox.defaults = {
    padding : 10,
    margin : 40,
    opacity : false,
    modal : false,
    cyclic : false,
    scrolling : 'auto', // 'auto', 'yes' or 'no'

    width : 560,
    height : 340,

    autoScale : true,
    autoDimensions : true,
    centerOnScroll : false,

    ajax : {},
    swf : { wmode: 'transparent' },

    hideOnOverlayClick : true,
    hideOnContentClick : false,

    overlayShow : true,
    overlayOpacity : 0.7,
    overlayColor : '#777',

    titleShow : true,
    titlePosition : 'float', // 'float', 'outside', 'inside' or 'over'
    titleFormat : null,
    titleFromAlt : false,

    transitionIn : 'fade', // 'elastic', 'fade' or 'none'
    transitionOut : 'fade', // 'elastic', 'fade' or 'none'

    speedIn : 300,
    speedOut : 300,

    changeSpeed : 300,
    changeFade : 'fast',

    easingIn : 'swing',
    easingOut : 'swing',

    showCloseButton  : true,
    showNavArrows : true,
    enableEscapeButton : true,
    enableKeyboardNav : true,

    onStart : function(){},
    onCancel : function(){},
    onComplete : function(){},
    onCleanup : function(){},
    onClosed : function(){},
    onError : function(){}
  };

  $(document).ready(function() {
    $.fancybox.init();
  });

})(jQuery);
  


/*

  Supersized - Fullscreen Slideshow jQuery Plugin
  Version : 3.2.7
  Theme   : Shutter 1.1
  
  Site  : www.buildinternet.com/project/supersized
  Author  : Sam Dunn
  Company : One Mighty Roar (www.onemightyroar.com)
  License : MIT License / GPL License

*/

(function($){
  
  theme = {
    
    
    /* Initial Placement
    ----------------------------*/
    _init : function(){
      
      // Center Slide Links
      if (api.options.slide_links) $(vars.slide_list).css('margin-left', -$(vars.slide_list).width()/2);
      
      // Start progressbar if autoplay enabled
        if (api.options.autoplay){
          if (api.options.progress_bar) theme.progressBar();
      }else{
        if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "play.png"); // If pause play button is image, swap src
        if (api.options.progress_bar) $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 ); //  Place progress bar
      }
      
      /* Navigation Items
      ----------------------------*/
        $(vars.next_slide).click(function() {
          api.nextSlide();
        });
        
        $(vars.prev_slide).click(function() {
          api.prevSlide();
        });

        $(vars.prev_slide +','+vars.next_slide).css('opacity', 1);
      
      
      /* Window Resize
      ----------------------------*/
      $(window).resize(function(){
        
        // Delay progress bar on resize
        if (api.options.progress_bar && !vars.in_animation){
          if (vars.slideshow_interval) clearInterval(vars.slideshow_interval);
          if (api.options.slides.length - 1 > 0) clearInterval(vars.slideshow_interval);
          
          $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
          
          if (!vars.progressDelay && api.options.slideshow){
            // Delay slideshow from resuming so Chrome can refocus images
            vars.progressDelay = setTimeout(function() {
                if (!vars.is_paused){
                  theme.progressBar();
                  vars.slideshow_interval = setInterval(api.nextSlide, api.options.slide_interval);
                }
                vars.progressDelay = false;
            }, 1000);
          }
        }
      
      });

      // Remove URL link from slider
      $('#supersized a').removeAttr('href');

      // Set Slide Info
      this.setSlideInfo();
      this.setSlideInfoPos();
      $('.slide-info-wrapper').fadeTo(300, 1);

      // Add Pattern overlay
      $('<div class="supersized-pattern"></div>').appendTo('#supersized');

      // Add class that supersized is active in html
      if( !$('.main-container').length ) {
        $('html').addClass('supersized-active');
      }

      // Pause the slideshow when not on homepage
      if( !$('.slide-info-wrapper').length )
        api.playToggle('pause');

      $(window).bind('resize', this.setSlideInfoPos);
    },


    /* Set Title and caption
    ----------------------------*/
    setSlideInfo: function() {
      var title = api.getField('title'),
          url = api.getField('url'),
          caption = api.getField('caption'),
          $slideTitle= $('.slide-title a'),
          $slideCaption= $('.slide-caption');

      $slideTitle.html( title ).prop('href', url);

      // Check if caption exists
      if( caption !== '' ) {
        $slideCaption.css('opacity', 1).html( caption );
      } else {
        $slideCaption.css('opacity', 0);
      }
    },
    
    /* Set Slide Info Position
    ----------------------------*/
    setSlideInfoPos: function() {
      var $infoWrapper = $('.slide-info-wrapper'),
          wrapperHeight = $infoWrapper.height(),
          windowHeight = $(window).height(),
          headerHeight = $('.header-section').height(),
          footerHeight = $('.footer-section').height(),
          infoPos = windowHeight - headerHeight - footerHeight - 350;
      $infoWrapper.css('margin-top', infoPos);
    },

    
    /* Go To Slide
    ----------------------------*/
    goTo : function(){
      if (api.options.progress_bar && !vars.is_paused){
        $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
        theme.progressBar();
      }
    },
    
    /* Play & Pause Toggle
    ----------------------------*/
    playToggle : function(state){
      
      if (state =='play'){
        // If image, swap to pause
        if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "pause.png");
        if (api.options.progress_bar && !vars.is_paused) theme.progressBar();
      }else if (state == 'pause'){
        // If image, swap to play
        if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "play.png");
            if (api.options.progress_bar && vars.is_paused)$(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
      }
      
    },
    
    
    /* Before Slide Transition
    ----------------------------*/
    beforeAnimation : function(direction){

        if (api.options.progress_bar && !vars.is_paused) $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
        
        /* Update Fields
        ----------------------------*/
        // Update slide caption
        if ($(vars.slide_caption).length){
          (api.getField('title')) ? $(vars.slide_caption).html(api.getField('title')) : $(vars.slide_caption).html('');
        }

        // $('.slide-info-wrapper').fadeTo(300, 0);
        this.setSlideInfo();
        this.setSlideInfoPos();

        // Remove URL link from slider
        $('#supersized a').removeAttr('href');
    },
    
    
    /* After Slide Transition
    ----------------------------*/
    afterAnimation : function(){
      if (api.options.progress_bar && !vars.is_paused) theme.progressBar(); //  Start progress bar
      // $('.slide-info-wrapper').fadeTo(300, 1);
    },
    
    
    /* Progress Bar
    ----------------------------*/
    progressBar : function(){
        $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 ).animate({ left:0 }, api.options.slide_interval);
      }
    
   
   };
   
   
   /* Theme Specific Variables
   ----------------------------*/
   $.supersized.themeVars = {
    
    // Internal Variables
    progress_delay    : false,        // Delay after resize before resuming slideshow
    thumb_page      :   false,        // Thumbnail page
    thumb_interval    :   false,        // Thumbnail interval
    image_path      : 'img/',       // Default image path
                          
    // General Elements             
    play_button     : '#pauseplay',   // Play/Pause button
    next_slide      : '.slide-next',   // Next slide button
    prev_slide      : '.slide-prev',   // Prev slide button
    next_thumb      : '#nextthumb',   // Next slide thumb button
    prev_thumb      : '#prevthumb',   // Prev slide thumb button
    
    slide_caption   : '#slidecaption',  // Slide caption
    slide_current   : '.slidenumber',   // Current slide number
    slide_total     : '.totalslides',   // Total Slides
    slide_list      : '#slide-list',    // Slide jump list              
    
    thumb_tray      : '#thumb-tray',    // Thumbnail tray
    thumb_list      : '#thumb-list',    // Thumbnail list
    thumb_forward   : '#thumb-forward', // Cycles forward through thumbnail list
    thumb_back      : '#thumb-back',    // Cycles backwards through thumbnail list
    tray_arrow      : '#tray-arrow',    // Thumbnail tray button arrow
    tray_button     : '#tray-button',   // Thumbnail tray button
    
    progress_bar    : '.progress-bar'   // Progress bar
                          
   };                       
  
   /* Theme Specific Options
   ----------------------------*/                       
   $.supersized.themeOptions = {          
                 
    progress_bar    : 1,    // Timer for each slide                     
    mouse_scrub     : 0   // Thumbnails move with mouse
    
   };
   
})(jQuery);

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(f,h,$){var a='placeholder' in h.createElement('input'),d='placeholder' in h.createElement('textarea'),i=$.fn,c=$.valHooks,k,j;if(a&&d){j=i.placeholder=function(){return this};j.input=j.textarea=true}else{j=i.placeholder=function(){var l=this;l.filter((a?'textarea':':input')+'[placeholder]').not('.placeholder').bind({'focus.placeholder':b,'blur.placeholder':e}).data('placeholder-enabled',true).trigger('blur.placeholder');return l};j.input=a;j.textarea=d;k={get:function(m){var l=$(m);return l.data('placeholder-enabled')&&l.hasClass('placeholder')?'':m.value},set:function(m,n){var l=$(m);if(!l.data('placeholder-enabled')){return m.value=n}if(n==''){m.value=n;if(m!=h.activeElement){e.call(m)}}else{if(l.hasClass('placeholder')){b.call(m,true,n)||(m.value=n)}else{m.value=n}}return l}};a||(c.input=k);d||(c.textarea=k);$(function(){$(h).delegate('form','submit.placeholder',function(){var l=$('.placeholder',this).each(b);setTimeout(function(){l.each(e)},10)})});$(f).bind('beforeunload.placeholder',function(){$('.placeholder').each(function(){this.value=''})})}function g(m){var l={},n=/^jQuery\d+$/;$.each(m.attributes,function(p,o){if(o.specified&&!n.test(o.name)){l[o.name]=o.value}});return l}function b(m,n){var l=this,o=$(l);if(l.value==o.attr('placeholder')&&o.hasClass('placeholder')){if(o.data('placeholder-password')){o=o.hide().next().show().attr('id',o.removeAttr('id').data('placeholder-id'));if(m===true){return o[0].value=n}o.focus()}else{l.value='';o.removeClass('placeholder');l==h.activeElement&&l.select()}}}function e(){var q,l=this,p=$(l),m=p,o=this.id;if(l.value==''){if(l.type=='password'){if(!p.data('placeholder-textinput')){try{q=p.clone().attr({type:'text'})}catch(n){q=$('<input>').attr($.extend(g(this),{type:'text'}))}q.removeAttr('name').data({'placeholder-password':true,'placeholder-id':o}).bind('focus.placeholder',b);p.data({'placeholder-textinput':q,'placeholder-id':o}).before(q)}p=p.removeAttr('id').hide().prev().attr('id',o).show()}p.addClass('placeholder');p[0].value=p.attr('placeholder')}else{p.removeClass('placeholder')}}}(this,document,jQuery));



/*
  --------------------------------
  Infinite Scroll
  --------------------------------
  + https://github.com/paulirish/infinitescroll
  + version 2.0b2.110713
  + Copyright 2011 Paul Irish & Luke Shumard
  + Licensed under the MIT license
  
  + Documentation: http://infinite-scroll.com/
  
*/

(function(window,$,undefined){$.infinitescroll=function infscr(options,callback,element){this.element=$(element);this._create(options,callback);};$.infinitescroll.defaults={loading:{finished:undefined,finishedMsg:"<em>Congratulations, you've reached the end of the internet.</em>",img:"http://www.infinite-scroll.com/loading.gif",msg:null,msgText:"<em>Loading the next set of posts...</em>",selector:null,speed:'fast',start:undefined},state:{isDuringAjax:false,isInvalidPage:false,isDestroyed:false,isDone:false,isPaused:false,currPage:1},callback:undefined,debug:false,behavior:undefined,binder:$(window),nextSelector:"div.navigation a:first",navSelector:"div.navigation",contentSelector:null,extraScrollPx:150,itemSelector:"div.post",animate:false,pathParse:undefined,dataType:'html',appendCallback:true,bufferPx:40,errorCallback:function(){},infid:0,pixelsFromNavToBottom:undefined,path:undefined};$.infinitescroll.prototype={_binding:function infscr_binding(binding){var instance=this,opts=instance.options;if(!!opts.behavior&&this['_binding_'+opts.behavior]!==undefined){this['_binding_'+opts.behavior].call(this);return;}
if(binding!=='bind'&&binding!=='unbind'){this._debug('Binding value  '+binding+' not valid')
return false;}
if(binding=='unbind'){(this.options.binder).unbind('smartscroll.infscr.'+instance.options.infid);}else{(this.options.binder)[binding]('smartscroll.infscr.'+instance.options.infid,function(){instance.scroll();});};this._debug('Binding',binding);},_create:function infscr_create(options,callback){if(!this._validate(options)){return false;}
var opts=this.options=$.extend(true,{},$.infinitescroll.defaults,options),relurl=/(.*?\/\/).*?(\/.*)/,path=$(opts.nextSelector).attr('href');opts.contentSelector=opts.contentSelector||this.element;opts.loading.selector=opts.loading.selector||opts.contentSelector;if(!path){this._debug('Navigation selector not found');return;}
opts.path=this._determinepath(path);opts.loading.msg=$('<div id="infscr-loading"><img alt="Loading..." src="'+opts.loading.img+'" /><div>'+opts.loading.msgText+'</div></div>');(new Image()).src=opts.loading.img;opts.pixelsFromNavToBottom=$(document).height()-$(opts.navSelector).offset().top;opts.loading.start=opts.loading.start||function(){$(opts.navSelector).hide();opts.loading.msg.appendTo(opts.loading.selector).show(opts.loading.speed,function(){beginAjax(opts);});};opts.loading.finished=opts.loading.finished||function(){opts.loading.msg.fadeOut('normal');};opts.callback=function(instance,data){if(!!opts.behavior&&instance['_callback_'+opts.behavior]!==undefined){instance['_callback_'+opts.behavior].call($(opts.contentSelector)[0],data);}
if(callback){callback.call($(opts.contentSelector)[0],data);}};this._setup();},_debug:function infscr_debug(){if(this.options.debug){return window.console&&console.log.call(console,arguments);}},_determinepath:function infscr_determinepath(path){var opts=this.options;if(!!opts.behavior&&this['_determinepath_'+opts.behavior]!==undefined){this['_determinepath_'+opts.behavior].call(this,path);return;}
if(!!opts.pathParse){this._debug('pathParse manual');return opts.pathParse;}else if(path.match(/^(.*?)\b2\b(.*?$)/)){path=path.match(/^(.*?)\b2\b(.*?$)/).slice(1);}else if(path.match(/^(.*?)2(.*?$)/)){if(path.match(/^(.*?page=)2(\/.*|$)/)){path=path.match(/^(.*?page=)2(\/.*|$)/).slice(1);return path;}
path=path.match(/^(.*?)2(.*?$)/).slice(1);}else{if(path.match(/^(.*?page=)1(\/.*|$)/)){path=path.match(/^(.*?page=)1(\/.*|$)/).slice(1);return path;}else{this._debug('Sorry, we couldn\'t parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.');opts.state.isInvalidPage=true;}}
this._debug('determinePath',path);return path;},_error:function infscr_error(xhr){var opts=this.options;if(!!opts.behavior&&this['_error_'+opts.behavior]!==undefined){this['_error_'+opts.behavior].call(this,xhr);return;}
if(xhr!=='destroy'&&xhr!=='end'){xhr='unknown';}
this._debug('Error',xhr);if(xhr=='end'){this._showdonemsg();}
opts.state.isDone=true;opts.state.currPage=1;opts.state.isPaused=false;this._binding('unbind');},_loadcallback:function infscr_loadcallback(box,data){var opts=this.options,callback=this.options.callback,result=(opts.state.isDone)?'done':(!opts.appendCallback)?'no-append':'append',frag;if(!!opts.behavior&&this['_loadcallback_'+opts.behavior]!==undefined){this['_loadcallback_'+opts.behavior].call(this,box,data);return;}
switch(result){case'done':this._showdonemsg();return false;break;case'no-append':if(opts.dataType=='html'){data='<div>'+data+'</div>';data=$(data).find(opts.itemSelector);};break;case'append':var children=box.children();if(children.length==0){return this._error('end');}
frag=document.createDocumentFragment();while(box[0].firstChild){frag.appendChild(box[0].firstChild);}
this._debug('contentSelector',$(opts.contentSelector)[0])
$(opts.contentSelector)[0].appendChild(frag);data=children.get();break;}
opts.loading.finished.call($(opts.contentSelector)[0],opts)
if(opts.animate){var scrollTo=$(window).scrollTop()+$('#infscr-loading').height()+opts.extraScrollPx+'px';$('html,body').animate({scrollTop:scrollTo},800,function(){opts.state.isDuringAjax=false;});}
if(!opts.animate)opts.state.isDuringAjax=false;callback(this,data);},_nearbottom:function infscr_nearbottom(){var opts=this.options,pixelsFromWindowBottomToBottom=0+$(document).height()-(opts.binder.scrollTop())-$(window).height();if(!!opts.behavior&&this['_nearbottom_'+opts.behavior]!==undefined){this['_nearbottom_'+opts.behavior].call(this);return;}
this._debug('math:',pixelsFromWindowBottomToBottom,opts.pixelsFromNavToBottom);return(pixelsFromWindowBottomToBottom-opts.bufferPx<opts.pixelsFromNavToBottom);},_pausing:function infscr_pausing(pause){var opts=this.options;if(!!opts.behavior&&this['_pausing_'+opts.behavior]!==undefined){this['_pausing_'+opts.behavior].call(this,pause);return;}
if(pause!=='pause'&&pause!=='resume'&&pause!==null){this._debug('Invalid argument. Toggling pause value instead');};pause=(pause&&(pause=='pause'||pause=='resume'))?pause:'toggle';switch(pause){case'pause':opts.state.isPaused=true;break;case'resume':opts.state.isPaused=false;break;case'toggle':opts.state.isPaused=!opts.state.isPaused;break;}
this._debug('Paused',opts.state.isPaused);return false;},_setup:function infscr_setup(){var opts=this.options;if(!!opts.behavior&&this['_setup_'+opts.behavior]!==undefined){this['_setup_'+opts.behavior].call(this);return;}
this._binding('bind');return false;},_showdonemsg:function infscr_showdonemsg(){var opts=this.options;if(!!opts.behavior&&this['_showdonemsg_'+opts.behavior]!==undefined){this['_showdonemsg_'+opts.behavior].call(this);return;}
opts.loading.msg.find('img').hide().parent().find('div').html(opts.loading.finishedMsg).animate({opacity:1},2000,function(){$(this).parent().fadeOut('normal');});opts.errorCallback.call($(opts.contentSelector)[0],'done');},_validate:function infscr_validate(opts){for(var key in opts){if(key.indexOf&&key.indexOf('Selector')>-1&&$(opts[key]).length===0){this._debug('Your '+key+' found no elements.');return false;}
return true;}},bind:function infscr_bind(){this._binding('bind');},destroy:function infscr_destroy(){this.options.state.isDestroyed=true;return this._error('destroy');},pause:function infscr_pause(){this._pausing('pause');},resume:function infscr_resume(){this._pausing('resume');},retrieve:function infscr_retrieve(pageNum){var instance=this,opts=instance.options,path=opts.path,box,frag,desturl,method,condition,pageNum=pageNum||null,getPage=(!!pageNum)?pageNum:opts.state.currPage;beginAjax=function infscr_ajax(opts){opts.state.currPage++;instance._debug('heading into ajax',path);box=$(opts.contentSelector).is('table')?$('<tbody/>'):$('<div/>');desturl=path.join(opts.state.currPage);method=(opts.dataType=='html'||opts.dataType=='json')?opts.dataType:'html+callback';if(opts.appendCallback&&opts.dataType=='html')method+='+callback'
switch(method){case'html+callback':instance._debug('Using HTML via .load() method');box.load(desturl+' '+opts.itemSelector,null,function infscr_ajax_callback(responseText){instance._loadcallback(box,responseText);});break;case'html':case'json':instance._debug('Using '+(method.toUpperCase())+' via $.ajax() method');$.ajax({url:desturl,dataType:opts.dataType,complete:function infscr_ajax_callback(jqXHR,textStatus){condition=(typeof(jqXHR.isResolved)!=='undefined')?(jqXHR.isResolved()):(textStatus==="success"||textStatus==="notmodified");(condition)?instance._loadcallback(box,jqXHR.responseText):instance._error('end');}});break;}};if(!!opts.behavior&&this['retrieve_'+opts.behavior]!==undefined){this['retrieve_'+opts.behavior].call(this,pageNum);return;}
if(opts.state.isDestroyed){this._debug('Instance is destroyed');return false;};opts.state.isDuringAjax=true;opts.loading.start.call($(opts.contentSelector)[0],opts);},scroll:function infscr_scroll(){var opts=this.options,state=opts.state;if(!!opts.behavior&&this['scroll_'+opts.behavior]!==undefined){this['scroll_'+opts.behavior].call(this);return;}
if(state.isDuringAjax||state.isInvalidPage||state.isDone||state.isDestroyed||state.isPaused)return;if(!this._nearbottom())return;this.retrieve();},toggle:function infscr_toggle(){this._pausing();},unbind:function infscr_unbind(){this._binding('unbind');},update:function infscr_options(key){if($.isPlainObject(key)){this.options=$.extend(true,this.options,key);}}}
$.fn.infinitescroll=function infscr_init(options,callback){var thisCall=typeof options;switch(thisCall){case'string':var args=Array.prototype.slice.call(arguments,1);this.each(function(){var instance=$.data(this,'infinitescroll');if(!instance){return false;}
if(!$.isFunction(instance[options])||options.charAt(0)==="_"){return false;}
instance[options].apply(instance,args);});break;case'object':this.each(function(){var instance=$.data(this,'infinitescroll');if(instance){instance.update(options);}else{$.data(this,'infinitescroll',new $.infinitescroll(options,callback,this));}});break;}
return this;};var event=$.event,scrollTimeout;event.special.smartscroll={setup:function(){$(this).bind("scroll",event.special.smartscroll.handler);},teardown:function(){$(this).unbind("scroll",event.special.smartscroll.handler);},handler:function(event,execAsap){var context=this,args=arguments;event.type="smartscroll";if(scrollTimeout){clearTimeout(scrollTimeout);}
scrollTimeout=setTimeout(function(){$.event.handle.apply(context,args);},execAsap==="execAsap"?0:100);}};$.fn.smartscroll=function(fn){return fn?this.bind("smartscroll",fn):this.trigger("smartscroll",["execAsap"]);};})(window,jQuery);