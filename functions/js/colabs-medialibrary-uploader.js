/*-----------------------------------------------------------------------------------*/
/* colabsthemes Media Library-driven AJAX File Uploader Module - JavaScript Functions */
/* 2010-11-05. */
/*
/* The code below is designed to work as a part of the colabsthemes Media Library-driven
/* AJAX File Uploader Module. It is included only on screens where this module is used.
/*-----------------------------------------------------------------------------------*/

(function ($) {

  colabsthemesMLU = {
  
/*-----------------------------------------------------------------------------------*/
/* Remove file when the "remove" button is clicked.
/*-----------------------------------------------------------------------------------*/
  
    removeFile: function () {
     
     $( '.mlu_remove').live( 'click', function(event) { 
        $(this).hide();
        $(this).parents().parents().children( '.upload').attr( 'value', '' );
        $(this).parents( '.screenshot').slideUp();
        
        return false;
      });
      
      // Hide the delete button on the first row 
      $( 'a.delete-inline', '#option-1' ).hide();
      
    }, // End removeFile
    
/*-----------------------------------------------------------------------------------*/
/* Replace the default file upload field with a customised version.
/*-----------------------------------------------------------------------------------*/

    recreateFileField: function () {
    
      $( 'input.file').each(function(){
        var uploadbutton = '<input class="upload_file_button" type="button" value="Upload" />';
        $(this).wrap( '<div class="file_wrap" />' );
        $(this).addClass( 'file').css( 'opacity', 0); //set to invisible
        $(this).parent().append($( '<div class="fake_file" />').append($( '<input type="text" class="upload" />').attr( 'id',$(this).attr( 'id')+'_file')).val( $(this).val() ).append(uploadbutton));
 
        $(this).bind( 'change', function() {
          $( '#'+$(this).attr( 'id')+'_file').val($(this).val());
        });
        $(this).bind( 'mouseout', function() {
          $( '#'+$(this).attr( 'id')+'_file').val($(this).val());
        });
      });
      
    }, // End recreateFileField

/*-----------------------------------------------------------------------------------*/
/* Use a custom function when working with the Media Uploads popup.
/* Requires jQuery, Media Upload and Thickbox JavaScripts.
/*-----------------------------------------------------------------------------------*/

	mediaUpload: function () {
	
  var _self = this;
	jQuery.noConflict();
	
	$( 'input.upload_button' ).removeAttr( 'style' );
	
      var formfield,
          formID,
          btnContent = true;

      // On Click
      $( 'input.upload_button').live( "click", function () {
        formfield = $(this).prev( 'input').attr( 'name' );
        formID = $(this).attr( 'rel' );
        
        // Display a custom title for each Thickbox popup.
        var colabsthemes_title = '';
        
        if ( $(this).parents( '.section' ).find( '.heading' ) ) { colabsthemes_title = $(this).parents( '.section' ).find( '.heading' ).text(); } // End IF Statement
       
        if ( colabsthemes_title == '' && $(this).parents( '.colabsthemes_metabox_fields' ).prev( 'th' ).find( 'label' ) ) { colabsthemes_title = $(this).parents( '.colabsthemes_metabox_fields' ).prev( 'th' ).find( 'label' ).text(); } // End IF Statement
        
        // For WP 3.5+
        if( typeof wp !== "undefined") {
          _self.wp_media_uploader.apply( _self, [formfield, formID, colabsthemes_title] );
        } else {
          tb_show( colabsthemes_title, 'media-upload.php?post_id='+formID+'&amp;title=' + colabsthemes_title + '&amp;is_colabsthemes=yes&amp;TB_iframe=1' );
        }

        return false;
      });
            
      window.original_send_to_editor = window.send_to_editor;
      window.send_to_editor = function(html) {
        
        if (formfield) {
        	
          // itemurl = $(html).attr( 'href' ); // Use the URL to the main image.
          
          if ( $(html).html(html).find( 'img').length > 0 ) {
          
          	itemurl = $(html).html(html).find( 'img').attr( 'src' ); // Use the URL to the size selected.
          	
          } else {
          
          // It's not an image. Get the URL to the file instead.
          	
          	var htmlBits = html.split( "'" ); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
          
          	itemurl = htmlBits[1]; // Use the URL to the file.
          	
          	var itemtitle = htmlBits[2];
          	
          	itemtitle = itemtitle.replace( '>', '' );
          	itemtitle = itemtitle.replace( '</a>', '' );
          
          } // End IF Statement
                   
          var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
          var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
          var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
          var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
          
          if (itemurl.match(image)) {
            btnContent = '<img src="'+itemurl+'" alt="" /><a href="#" class="mlu_remove button">Remove Image</a>';
          } else {
          	
          	// No output preview if it's not an image.
            // btnContent = '';
            
            // Standard generic output if it's not an image.
            html = '<a href="'+itemurl+'" target="_blank" rel="external">View File</a>';
            
            btnContent = '<div class="no_image"><span class="file_link">'+html+'</span><a href="#" class="mlu_remove button">Remove</a></div>';
          }
          
          $( '#' + formfield).val(itemurl);
          $( '#' + formfield).siblings( '.screenshot').slideDown().html(btnContent);
          tb_remove();
          
        } else {
          window.original_send_to_editor(html);
        }
        
        // Clear the formfield value so the other media library popups can work as they are meant to. - 2010-11-11.
        formfield = '';
        
      }
      
  }, // End mediaUpload


  /**
   * =================================================================
   * WordPress 3.5+ Uploader
   * =================================================================
   */
  file_frame: {},
  wp_media_uploader: function( formfield, formID, colabsthemes_title ) {
    var _self = this,
        image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi,
        document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi,
        audio = /(^.*\.mp3|m4a|ogg|wav*)/gi,
        video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;

    // If the media frame already exists, reopen it.
    if( _self.file_frame[ formfield ] ) {
      _self.file_frame[ formfield ].open();
      return;
    }

    // Create the media frame.
    _self.file_frame[formfield] = wp.media.frames[formfield] = wp.media({
      title: colabsthemes_title,
      button: {
        text: 'Use this media'
      },
      multiple: false
    });

    // When an image is selected, run a callback
    _self.file_frame[formfield].on( 'select', function() {
      var attachment = _self.file_frame[formfield].state().get('selection').first().toJSON(),
          btnContent, html;

      if (attachment.url.match(image)) {
        btnContent = '<img src="'+attachment.url+'" alt="" /><a href="#" class="mlu_remove button">Remove Image</a>';        
      } else {
        // No output preview if it's not an image.
        // Standard generic output if it's not an image.
        html = '<a href="'+attachment.url+'" target="_blank" rel="external">View File</a>';
        
        btnContent = '<div class="no_image"><span class="file_link">'+html+'</span><a href="#" class="mlu_remove button">Remove</a></div>';
      }

      $( '#' + formfield ).val( attachment.url );
      $( '#' + formfield).siblings( '.screenshot').slideDown().html(btnContent);

    });

    // Open the modal
    _self.file_frame[formfield].open();
  }
   
}; // End colabsthemesMLU Object // Don't remove this, or the sky will fall on your head.

/*-----------------------------------------------------------------------------------*/
/* Execute the above methods in the colabsthemesMLU object.
/*-----------------------------------------------------------------------------------*/
  
	$(document).ready(function () {

		colabsthemesMLU.removeFile();
		colabsthemesMLU.recreateFileField();
		colabsthemesMLU.mediaUpload();
	
	});
  
})(jQuery);