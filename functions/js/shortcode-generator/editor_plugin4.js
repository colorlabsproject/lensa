/**
 * Add TinyMCE Plugins
 */
(function($){

  // Get the URL to this script file (as JavaScript is loaded in order)
  // (http://stackoverflow.com/questions/2255689/how-to-get-the-file-path-of-the-currenctly-executing-javascript-code)
  var scripts = document.getElementsByTagName( "script"),
      src = scripts[scripts.length-1].src;

  if ( scripts.length ) {
    for ( i in scripts ) {
      var scriptSrc = '';

      if ( typeof scripts[i].src != 'undefined' ) { scriptSrc = scripts[i].src; } // End IF Statement
      var txt = scriptSrc.search( 'shortcode-generator' );
      
      if ( txt != -1 ) {
      
        src = scripts[i].src;
      
      } // End IF Statement
    
    } // End FOR Loop
  
  } // End IF Statement

  var framework_url = src.split( '/js/' ),
      icon_url = framework_url[0] + '/images/shortcode-icon.png';

  /**
   * Add Colabs Shortcode Plugin
   */
  tinymce.create( 'tinymce.plugins.CoLabsThemesShortcodes', {
    menuItem: [],

    /**
     * Create Control Button
     */
    createControl: function( editor, url ) {
      var self = this;

      // Add menu button
      editor.addButton('colabsthemes_shortcodes_button', {
        icon: 'icon icon-colabs-shortcode',
        image: icon_url,
        tooltip: 'Colorlabs Shortcode',
        type: 'menubutton',
        menu: self.menuItem,
        onSelect: function( event ) {

          // Make sure value is set
          if( typeof event.control.settings.value != 'undefined' ) {
            // Menu when clicked opened a dialog
            if( event.control.settings.menutype == 'dialog' ) {
              tinymce.activeEditor.execCommand( 'colabsOpenDialog', false, {
                title: event.control.settings.text,
                identifier: event.control.settings.value
              });
            }

            else {
              tinymce.activeEditor.execCommand( 'mceInsertContent', false, event.control.settings.value );
            }  
          }
        
        }
      });
    },

    /**
     * Initialization
     */
    init: function( editor, url ) {
      // Open dialog
      editor.addCommand( 'colabsOpenDialog', function( ui, value ) {
        var text = '';

        selectedText = editor.selection.getContent(),
        colabsSelectedShortcodeType = value.identifier;
        colabsSelectedShortcodeTitle = value.title;

        $.get( url + '/dialog.php', function( response ) {

          $( '#colabs-options').addClass( 'shortcode-' + colabsSelectedShortcodeType );
          $( '#colabs-preview').addClass( 'shortcode-' + colabsSelectedShortcodeType );

          // Skip the popup on certain shortcodes.
          switch ( colabsSelectedShortcodeType ) {
            // Pulled Quote
            case 'pulledquote':
              text = '[pulledquote]'+selectedText+'[/pulledquote]';
              tinyMCE.activeEditor.execCommand( "mceInsertContent", false, text);
              break;

            // Highlight
            case 'highlight':
              text = '[highlight]'+selectedText+'[/highlight]';
              tinyMCE.activeEditor.execCommand( "mceInsertContent", false, text);
              break;

            // Dropcap
            case 'dropcap':
              text = '[dropcap]'+selectedText+'[/dropcap]';
              tinyMCE.activeEditor.execCommand( "mceInsertContent", false, text);
              break;

            // Otherwise
            default:
              $( "#colabs-dialog").remove();
              $( "body").append( response );
              $( "#colabs-dialog").hide();
              var windowWidth = $(window).width(),
                  windowHeight = jQuery(window).height();
              
              windowWidth = 720 < windowWidth ? 720 : windowWidth;
              windowWidth -= 80;
              windowHeight -= 120;

              tb_show( 
                "Insert CoLabsThemes "+ colabsSelectedShortcodeTitle +" Shortcode", 
                "#TB_inline?width="+windowWidth+"&height="+windowHeight+"&inlineId=colabs-dialog"
              );
              $( "#colabs-options h3:first").text( "Customize the "+ value.title +" Shortcode" );
              break;
          }
        });
      });

      this.createControl( editor, url );

      // Populate menu item
      this.populateMenu( editor, url );
    },

    /**
     * Populate Menu
     */
    populateMenu: function( editor, url ) {
      this.addMenubuttonItem( this.menuItem, 'Button', 'button' );
      this.addMenubuttonItem( this.menuItem, 'Icon Link', 'ilink' );
      this.addMenubuttonItem( this.menuItem, 'Info Box', 'box' );

      var typography = this.addMenubuttonItem( this.menuItem, 'Typography', '', '', true );
        this.addMenubuttonItem( typography, 'Dropcap', 'dropcap' );
        this.addMenubuttonItem( typography, "Quote", "quote" );
        this.addMenubuttonItem( typography, "Highlight", "highlight" );
        this.addMenubuttonItem( typography, "Custom Typography", "typography" );
        this.addMenubuttonItem( typography, "Abbreviation", "abbr" );
        this.addMenubuttonItem( typography, "Pulled Quote", "pulledquote" );

      this.addMenubuttonItem( this.menuItem, "Content Toggle", "toggle" );
      this.addMenubuttonItem( this.menuItem, "Related Posts", "related" );
      this.addMenubuttonItem( this.menuItem, "Contact Form", "contactform" );

      this.addMenubuttonItem( this.menuItem, "Column Layout", "column" );
      this.addMenubuttonItem( this.menuItem, "Tab Layout", "tab" );

      var list = this.addMenubuttonItem( this.menuItem, 'List Generator', '', '', true );
        this.addMenubuttonItem( list, "Unordered List", "unordered_list" );
        this.addMenubuttonItem( list, "Ordered List", "ordered_list" );

      var divider = this.addMenubuttonItem( this.menuItem, 'Dividers', '', '', true );
        this.addMenubuttonItem( divider, "Horizontal Rule", "[hr] ", 'immediate' );
        this.addMenubuttonItem( divider, "Divider", "[divider] ", 'immediate' );
        this.addMenubuttonItem( divider, "Flat Divider", "[divider_flat] ", 'immediate' );

      var social = this.addMenubuttonItem( this.menuItem, 'Social Buttons', '', '', true );
        this.addMenubuttonItem( social, "Social Profile Icon", "social_icon" );
        this.addMenubuttonItem( social, "Twitter", "twitter" );
        this.addMenubuttonItem( social, "Twitter Follow Button", "twitter_follow" );
        this.addMenubuttonItem( social, "Tweetmeme", "tweetmeme" );
        this.addMenubuttonItem( social, "Digg", "digg" );
        this.addMenubuttonItem( social, "Like on Facebook", "fblike" );
        this.addMenubuttonItem( social, "Share on Facebook", "fbshare" );
        this.addMenubuttonItem( social, "Share on LinkedIn", "linkedin_share" );
        this.addMenubuttonItem( social, "Google +1 Button", "google_plusone" );
    },

    /**
     * Add menu item which open dialog when clicked    
     */
    addMenubuttonItem: function( array_target, text, value, type, has_child ) {
      if( typeof array_target != 'undefined' ) {
        if( typeof has_child == 'undefined' ) {
          has_child = false;
        }

        if( typeof type == 'undefined' ) {
          type = 'dialog';
        }

        var menu = {
          text: text,
          value: value,
          menutype: type
        };

        if( has_child ) {
          menu.menu = [];
        }

        array_target.push( menu );

        // Return the menu options, so child menu can be appended
        if( has_child ) {
          return array_target[ array_target.length - 1 ].menu;
        }
      }
    }
  });

  tinymce.PluginManager.add('CoLabsThemesShortcodes', tinymce.plugins.CoLabsThemesShortcodes);

})(jQuery);