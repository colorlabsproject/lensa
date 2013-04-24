<?php
/**
 * Add Syntax Highlighting for Theme Editor page
 */

function colabs_editor_scripts() {
	if( isset( $_REQUEST['file'] ) ) {
		$file = $_REQUEST['file'];
	} else {
		$file = 'style.css';
	}
	$file_ext = substr( strchr( $file, '.' ), 1 );
	$mime_type = 'text/css';
	if( 'php' == $file_ext ) {
		$mime_type = 'application/x-httpd-php';
	}

	wp_enqueue_script( 'colabs-codemirror', get_template_directory_uri() . '/functions/js/codemirror.js', array('jquery') );
	wp_enqueue_style( 'colabs-codemirror', get_template_directory_uri() . '/functions/css/codemirror.css' );
	wp_localize_script( 'colabs-codemirror', 'colabs_editor_mime', $mime_type );
}
add_action( 'admin_print_scripts-theme-editor.php', 'colabs_editor_scripts' );


/** Print Javascript for initiating codemirror **/
function colabs_editor_print_scripts() { ?>
	<style type="text/css">
		#template .CodeMirror {
			line-height: 16px;
			margin-right: 20px;
			border: 1px solid #dfdfdf;
		}
		#template .CodeMirror div {
			margin-right: 0;
		}
		.activeline {
			background: #000
		}
		.CodeMirror-activeline-background {
			background: #E8F2FF !important;
		}
	</style>

	<script type="text/javascript">
		(function(global, $){
			$(document).ready(function(){

				var editor = CodeMirror.fromTextArea( document.getElementById('newcontent'), {
					mode: colabs_editor_mime,
					styleActiveLine: true,
					lineNumbers: true,
					lineWrapping: false
				} );

			});
		})(this, jQuery);
	</script>
<?php }
add_action('admin_footer-theme-editor.php', 'colabs_editor_print_scripts');