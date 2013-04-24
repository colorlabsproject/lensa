<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Custom fields for WP write panel - colabsthemes_metabox_create
- colabsthemes_uploader_custom_fields
- colabsthemes_metabox_handle
- colabsthemes_metabox_add
- colabsthemes_metabox_header

-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
// Custom fields for WP write panel
/*-----------------------------------------------------------------------------------*/

function colabsthemes_metabox_create($post,$callback) {
    global $post;

    $template_to_show = $callback['args'];

    $colabs_metaboxes = get_option( 'colabs_custom_template' );

    $seo_metaboxes = get_option( 'colabs_custom_seo_template' );

    if(empty($seo_metaboxes) AND 'seo' == $template_to_show){
        return;
    }
      
    if(get_option( 'seo_colabs_hide_fields') != 'true' AND 'seo' == $template_to_show){
        $colabs_metaboxes = $seo_metaboxes;
    }
        
    $output = '';
    $output .= '<table class="colabs_metaboxes_table">'."\n";
    foreach ($colabs_metaboxes as $colabs_metabox) {
        $colabs_id = "colabsthemes_" . $colabs_metabox["name"];
        $colabs_name = $colabs_metabox["name"];
        $colabs_class ='';
        
        if (isset($colabs_metabox["class"]) && $colabs_metabox["class"]!='')
        $colabs_class = "class='" .$colabs_metabox["class"]."'";

        if ('seo' == $template_to_show) {
            $metabox_post_type_restriction = 'undefined';
        } elseif (function_exists( 'colabsthemes_content_builder_menu')) {
            $metabox_post_type_restriction = $colabs_metabox['cpt'][$post->post_type];
        } else {
            $metabox_post_type_restriction = 'undefined';
        }

        if ( ($metabox_post_type_restriction != '') && ('true' == $metabox_post_type_restriction) ) {
            $type_selector = true;
        } elseif ('undefined' == $metabox_post_type_restriction) {
            $type_selector = true;
        } else {
            $type_selector = false;
        }

        $colabs_metaboxvalue = '';

        if ($type_selector) {

            if(
                    'text' == $colabs_metabox['type']
            OR      'info2' == $colabs_metabox['type']                    
            OR      'select' == $colabs_metabox['type']
            OR      'select2' == $colabs_metabox['type']
            OR      'checkbox' == $colabs_metabox['type']
            OR      'multicheck' == $colabs_metabox['type']
            OR      'textarea' == $colabs_metabox['type']
            OR      'calendar' == $colabs_metabox['type']
            OR      'time' == $colabs_metabox['type']
            OR      'radio' == $colabs_metabox['type']
            OR      'images' == $colabs_metabox['type']) {

                    $colabs_metaboxvalue = get_post_meta($post->ID,$colabs_name,true);

                }

                if ( '' == $colabs_metaboxvalue && isset( $colabs_metabox['std'] ) ) {

                    $colabs_metaboxvalue = $colabs_metabox['std'];
                }
                
                if('info' == $colabs_metabox['type']){

                    $output .= "\t".'<tr style=" border-bottom: 1px solid #DFDFDF; line-height: 1.5em;" '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'. esc_attr( $colabs_id ) .'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td>'.$colabs_metabox['desc'].'</td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }
                elseif('info2' == $colabs_metabox['type']){

                    $output .= "\t".'<tr style=" border-bottom: 1px solid #DFDFDF; line-height: 1.5em;" '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'. esc_attr( $colabs_id ) .'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td>'.esc_attr( $colabs_metaboxvalue ).'</td>'."\n";
                    $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'] .'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }
                elseif('text' == $colabs_metabox['type']){

                    $add_class = ''; $add_counter = '';
                    if('seo' == $template_to_show){$add_class = 'words-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><input class="colabs_input_text '.$add_class.'" type="'.$colabs_metabox['type'].'" value="'.esc_attr( $colabs_metaboxvalue ).'" name="'.$colabs_name.'" id="'.esc_attr( $colabs_id ).'"/>';
                    $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'] .' '. $add_counter .'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }

                elseif ('textarea' == $colabs_metabox['type']){

                    $add_class = ''; $add_counter = '';
                    if( 'seo' == $template_to_show ){ $add_class = 'words-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>'; }
                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.$colabs_metabox.'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><textarea class="colabs_input_textarea '.$add_class.'" name="'.$colabs_name.'" id="'.esc_attr( $colabs_id ).'">' . esc_textarea(stripslashes($colabs_metaboxvalue)) . '</textarea>';
                    $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'] .' '. $add_counter.'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }

                elseif ('calendar' == $colabs_metabox['type']){

                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.$colabs_metabox.'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><input class="colabs_input_calendar" type="text" name="'.$colabs_name.'" id="'.esc_attr( $colabs_id ).'" value="'.esc_attr( $colabs_metaboxvalue ).'">';
                    $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'].'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }

                elseif ('time' == $colabs_metabox['type']){

                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><input class="colabs_input_time" type="'.$colabs_metabox['type'].'" value="'.esc_attr( $colabs_metaboxvalue ).'" name="'.$colabs_name.'" id="'.esc_attr( $colabs_id ).'"/>';
                    $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'].'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";

                }

                elseif ('select' == $colabs_metabox['type']){

                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><select class="colabs_input_select" id="'.esc_attr( $colabs_id ).'" name="'. esc_attr( $colabs_name ) .'">';
                    $output .= '<option value="">Select to return to default</option>';

                    $array = $colabs_metabox['options'];

                    if($array){

                        foreach ( $array as $id => $option ) {
                            $selected = '';

                            if(isset($colabs_metabox['default']))  {
                                if($colabs_metabox['default'] == $option && empty($colabs_metaboxvalue)){$selected = 'selected="selected"';}
                                else  {$selected = '';}
                            }

                            if($colabs_metaboxvalue == $option){$selected = 'selected="selected"';}
                            else  {$selected = '';}

                            $output .= '<option value="'. esc_attr( $option ) .'" '. $selected .'>' . $option .'</option>';
                        }
                    }

                    $output .= '</select><span class="colabs_metabox_desc description">'.$colabs_metabox['desc'].'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";
                }
                elseif ('select2' == $colabs_metabox['type']){

                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><select class="colabs_input_select" id="'.esc_attr( $colabs_id ).'" name="'. esc_attr( $colabs_name ) .'">';
                    $output .= '<option value="">Select to return to default</option>';

                    $array = $colabs_metabox['options'];

                    if($array){

                        foreach ( $array as $id => $option ) {
                            $selected = '';

                            if(isset($colabs_metabox['default']))  {
                                if($colabs_metabox['default'] == $id && empty($colabs_metaboxvalue)){$selected = 'selected="selected"';}
                                else  {$selected = '';}
                            }

                            if($colabs_metaboxvalue == $id){$selected = 'selected="selected"';}
                            else  {$selected = '';}

                            $output .= '<option value="'. esc_attr( $id ) .'" '. $selected .'>' . $option .'</option>';
                        }
                    }

                    $output .= '</select><span class="colabs_metabox_desc description">'.$colabs_metabox['desc'].'</span></td>'."\n";
                    $output .= "\t".'</tr>'."\n";
                }

                elseif ('checkbox' == $colabs_metabox['type']){

                    if('true' == $colabs_metaboxvalue) { $checked = ' checked="checked"';} else {$checked='';}

                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td><input type="checkbox" '.$checked.' class="colabs_input_checkbox" value="true"  id="'.esc_attr( $colabs_id ).'" name="'. esc_attr( $colabs_name ) .'" />';
                    $output .= '<label class="colabs_metabox_desc" style="display:inline">&nbsp;'.$colabs_metabox['desc'].'</label></td>'."\n";
                    $output .= "\t".'</tr>'."\n";
                }
                elseif ('multicheck' == $colabs_metabox['type']){

                     $array = $colabs_metabox['options'];

                    if($array){
                    $output .= "\t".'<tr '.$colabs_class.'>';
                    $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                    $output .= "\t\t".'<td class='.$colabs_name.' multicheck>';
                    $colabs_metaboxvalue_arr = explode(",", $colabs_metaboxvalue);

                    foreach ( $array as $id => $option ) {
                    
                        if( in_array( $id,$colabs_metaboxvalue_arr ) ) { $checked = ' checked="checked"';} else {$checked='';}

                            $output .= '<input type="checkbox" '.$checked.' value="' . $id . '" class="colabs_input_multicheck"  name="'.$id.'" />';
                            $output .= '<span class="colabs_input_multicheck_desc" style="display:inline">&nbsp;'. $option .'</span><div class="colabs_spacer"></div>';
                        }

                        $output .= '<input style="display:none" class="colabs_input_multicheck" type="text" value="'.esc_attr( $colabs_metaboxvalue ).'" name="'.$colabs_name.'" id="'.esc_attr( $colabs_id ).'"/>';
                        $output .= "</td>\t".'</tr>'."\n";
                     }
                    
                }
                elseif ('radio' == $colabs_metabox['type']){

                $array = $colabs_metabox['options'];

                if($array){

                $output .= "\t".'<tr '.$colabs_class.'>';
                $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td>';

                    foreach ( $array as $id => $option ) {

                        if($colabs_metaboxvalue == $id) { $checked = ' checked';} else {$checked='';}

                            $output .= '<input type="radio" '.$checked.' value="' . $id . '" class="colabs_input_radio"  name="'. esc_attr( $colabs_name ) .'" />';
                            $output .= '<span class="colabs_input_radio_desc" style="display:inline">&nbsp;'. $option .'</span><div class="colabs_spacer"></div>';
                        }
                        $output .= "\t".'</tr>'."\n";
                     }
                }
                elseif ('images' == $colabs_metabox['type'])
                {

                $i = 0;
                $select_value = '';
                $layout = '';

                foreach ($colabs_metabox['options'] as $key => $option)
                     {
                     $i++;

                     $checked = '';
                     $selected = '';
                     if($colabs_metaboxvalue != '') {
                        if ($colabs_metaboxvalue == $key) { $checked = ' checked'; $selected = 'colabs-meta-radio-img-selected'; }
                     }
                     else {
                        if ( isset($option['std']) && $option['std'] == $key) { $checked = ' checked'; }
                        elseif ($i == 1) { $checked = ' checked'; $selected = 'colabs-meta-radio-img-selected'; }
                        else { $checked=''; }

                     }

                        $layout .= '<div class="colabs-meta-radio-img-label">';
                        $layout .= '<input type="radio" id="colabs-meta-radio-img-' . $colabs_name . $i . '" class="checkbox colabs-meta-radio-img-radio" value="'.esc_attr($key).'" name="'. $colabs_name.'" '.$checked.' />';
                        $layout .= '&nbsp;' . esc_html($key) .'<div class="colabs_spacer"></div></div>';
                        $layout .= '<img src="'.esc_url( $option ).'" alt="" class="colabs-meta-radio-img-img '. $selected .'" onClick="document.getElementById(\'colabs-meta-radio-img-'. esc_js($colabs_metabox["name"] . $i).'\').checked = true;" />';
                    }

                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td class="colabs_metabox_fields">';
                $output .= $layout;
                $output .= '<span class="colabs_metabox_desc description">'.$colabs_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'</tr>'."\n";

                }

                elseif('upload' == $colabs_metabox['type'])
                {
                    if(isset($colabs_metabox["default"])) $default = $colabs_metabox["default"];
                    else $default = '';

                    // Add support for the ColorLabs Media Library-driven Uploader Module // 2010-11-09.
                    if ( function_exists( 'colabsthemes_medialibrary_uploader' ) ) {

                        $_value = $default;

                        $_value = get_post_meta( $post->ID, $colabs_metabox["name"], true );

                        $output .= "\t".'<tr '.$colabs_class.'>';
                        $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.$colabs_metabox["name"].'">'.$colabs_metabox['label'].'</label></th>'."\n";
                        $output .= "\t\t".'<td class="colabs_metabox_fields">'. colabsthemes_medialibrary_uploader( $colabs_metabox["name"], $_value, 'postmeta', $colabs_metabox["desc"], $post->ID );
                        $output .= '</td>'."\n";
                        $output .= "\t".'</tr>'."\n";

                    } else {

                        $output .= "\t".'<tr '.$colabs_class.'>';
                        $output .= "\t\t".'<th class="colabs_metabox_names"><label for="'.esc_attr( $colabs_id ).'">'.$colabs_metabox['label'].'</label></th>'."\n";
                        $output .= "\t\t".'<td class="colabs_metabox_fields">'. colabsthemes_uploader_custom_fields($post->ID,$colabs_name,$default,$colabs_metabox["desc"]);
                        $output .= '</td>'."\n";
                        $output .= "\t".'</tr>'."\n";

                    } // End IF Statement

                }
        }   // End IF Statement
    }

    $output .= '</table>'."\n\n";
    echo $output;
}



/*-----------------------------------------------------------------------------------*/
// colabsthemes_uploader_custom_fields
/*-----------------------------------------------------------------------------------*/

function colabsthemes_uploader_custom_fields($pID,$id,$std,$desc){

    // Start Uploader
    $upload = get_post_meta( $pID, $id, true);
    $href = cleanSource($upload);
    $uploader = '';
    $uploader .= '<input class="colabs_input_text" name="'.$id.'" type="text" value="'.esc_attr($upload).'" />';
    $uploader .= '<div class="clear"></div>'."\n";
    $uploader .= '<input type="file" name="attachement_'.$id.'" />';
    $uploader .= '<input type="submit" class="button button-highlighted" value="Save" name="save"/>';
    if ( $href )
        $uploader .= '<span class="colabs_metabox_desc description">'.$desc.'</span></td>'."\n".'<td class="colabs_metabox_image"><a href="'. $upload .'"><img src="'.get_template_directory_uri().'/functions/timthumb.php?src='.$href.'&w=150&h=80&zc=1" alt="" /></a>';

return $uploader;
}



/*-----------------------------------------------------------------------------------*/
// colabsthemes_metabox_handle
/*-----------------------------------------------------------------------------------*/

function colabsthemes_metabox_handle(){

    $pID = '';
    global $globals, $post;

    $colabs_metaboxes = get_option( 'colabs_custom_template' );
    
    $seo_metaboxes = get_option( 'colabs_custom_seo_template' );

    if(!empty($seo_metaboxes) AND get_option( 'seo_colabs_hide_fields') != 'true'){
        $colabs_metaboxes = array_merge($colabs_metaboxes,$seo_metaboxes);
    }

    // Sanitize post ID.

    if( isset( $_POST['post_ID'] ) ) {

        $pID = intval( $_POST['post_ID'] );

    } // End IF Statement

    // Don't continue if we don't have a valid post ID.

    if ( $pID == 0 ) {

        return;

    } // End IF Statement

    $upload_tracking = array();

    if ( isset( $_POST['action'] ) && 'editpost' == $_POST['action'] ) {

        foreach ($colabs_metaboxes as $colabs_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
            if('text' == $colabs_metabox['type']
            OR 'calendar' == $colabs_metabox['type']
            OR 'time' == $colabs_metabox['type']
            OR 'select' == $colabs_metabox['type']
            OR 'select2' == $colabs_metabox['type']
            OR 'radio' == $colabs_metabox['type']
            OR 'checkbox' == $colabs_metabox['type']
            OR 'multicheck' == $colabs_metabox['type']
            OR 'textarea' == $colabs_metabox['type']
            OR 'images' == $colabs_metabox['type'] ) // Normal Type Things...
            {

                $var = $colabs_metabox["name"];

                if ( isset( $_POST[$var] ) ) {

                    // Sanitize the input.
                    $posted_value = '';
                    $posted_value = $_POST[$var];

                    // Get the current value for checking in the script.
                    $current_value = '';
                    $current_value = get_post_meta( $pID, $var, true );

                     // If it doesn't exist, add the post meta.
                    if(get_post_meta( $pID, $var ) == "") {

                        add_post_meta( $pID, $var, $posted_value, true );

                    }
                    // Otherwise, if it's different, update the post meta.
                    elseif( $posted_value != get_post_meta( $pID, $var, true ) ) {

                        update_post_meta( $pID, $var, $posted_value );

                    }
                    // Otherwise, if no value is set, delete the post meta.
                    elseif($posted_value == "") {

                        delete_post_meta( $pID, $var, get_post_meta( $pID, $var, true ) );

                    } // End IF Statement


                } elseif ( ! isset( $_POST[$var] ) && 'checkbox' == $colabs_metabox['type'] ) {

                    update_post_meta( $pID, $var, 'false' );

                } else {

                    delete_post_meta( $pID, $var, $current_value ); // Deletes check boxes OR no $_POST

                } // End IF Statement

            } elseif( 'upload' == $colabs_metabox['type'] ) { // So, the upload inputs will do this rather

                $id = $colabs_metabox['name'];
                $override['action'] = 'editpost';

                if(!empty($_FILES['attachement_'.$id]['name'])){ //New upload
                $_FILES['attachement_'.$id]['name'] = preg_replace( '/[^a-zA-Z0-9._\-]/', '', $_FILES['attachement_'.$id]['name']);
                       $uploaded_file = wp_handle_upload($_FILES['attachement_' . $id ],$override);
                       $uploaded_file['option_name']  = $colabs_metabox['label'];
                       $upload_tracking[] = $uploaded_file;
                       update_post_meta( $pID, $id, $uploaded_file['url'] );

                } elseif ( empty( $_FILES['attachement_'.$id]['name'] ) && isset( $_POST[ $id ] ) ) {

                    // Sanitize the input.
                    $posted_value = '';
                    $posted_value = $_POST[$id];

                    update_post_meta($pID, $id, $posted_value);

                } elseif ( '' == $_POST[ $id ] )  {

                    delete_post_meta( $pID, $id, get_post_meta( $pID, $id, true ) );

                } // End IF Statement

            } // End IF Statement

               // Error Tracking - File upload was not an Image
               update_option( 'colabs_custom_upload_tracking', $upload_tracking );

            } // End FOREACH Loop

        } // End IF Statement

} // End colabsthemes_metabox_handle()

/*-----------------------------------------------------------------------------------*/
// colabsthemes_metabox_add
/*-----------------------------------------------------------------------------------*/

function colabsthemes_metabox_add() {
    $seo_metaboxes = get_option( 'colabs_custom_seo_template' );
    $seo_post_types = array( 'post','page' );
    if(defined( 'SEOPOSTTYPES')){
        $seo_post_types_update = unserialize( constant( 'SEOPOSTTYPES') );
    }

    if(!empty($seo_post_types_update)){
        $seo_post_types = $seo_post_types_update;
    }

    $colabs_metabox_settings = get_option('colabs_metabox_settings');
    $colabs_metaboxes = get_option( 'colabs_custom_template' );
    
    if ( function_exists( 'add_meta_box') ) {

        if ( function_exists( 'get_post_types') ) {
            $custom_post_list = get_post_types();

            // Get the theme name for use in multiple meta boxes.
            $theme_name = get_option( 'colabs_themename' );
            
            foreach ($custom_post_list as $type){
            
            if ( !empty($colabs_metabox_settings[$type]) && $type != 'attachment' && $type != 'revision' && $type != 'nav_menu_item' && $type != 'colabsframework'){//wordpress.com
					$settings = $colabs_metabox_settings[$type];
            }else{
                $settings = array(
                                    'id' => 'colabsthemes-settings',
                                    'title' => 'ColorLabs' . __( ' Custom Settings', 'colabsthemes' ),
                                    'callback' => 'colabsthemes_metabox_create',
                                    'page' => $type,
                                    'context' => 'normal',
                                    'priority' => 'high',
                                    'callback_args' => ''
                                );
                }
                
                // Allow child themes/plugins to filter these settings.
				if(isset($settings['id']))
                $settings = apply_filters( 'colabsthemes_metabox_settings', $settings, $type, $settings['id'] );

                if ( ! empty( $colabs_metabox_settings[$type] ) ) {
                    add_meta_box( $settings['id'], $settings['title'], $settings['callback'], $settings['page'], $settings['context'], $settings['priority'], $settings['callback_args'] );
                }
                
                //if(!empty($colabs_metaboxes)) Temporarily Removed
                if(array_search($type, $seo_post_types) !== false){
                    if(get_option( 'seo_colabs_hide_fields') != 'true'){
                        add_meta_box( 'colabsthemes-seo', 'ColorLabs' . ' SEO Settings','colabsthemes_metabox_create',$type,'normal','high','seo' );
                    }
                }
            }
        } else {
            add_meta_box( 'colabsthemes-settings', $theme_name . ' Custom Settings','colabsthemes_metabox_create','post','normal' );
            add_meta_box( 'colabsthemes-settings', $theme_name . ' Custom Settings','colabsthemes_metabox_create','page','normal' );
            if(get_option( 'seo_colabs_hide_fields') != 'true'){
                add_meta_box( 'colabsthemes-seo', $theme_name . ' SEO Settings','colabsthemes_metabox_create','post','normal','high','seo' );
                add_meta_box( 'colabsthemes-seo', $theme_name . ' SEO Settings','colabsthemes_metabox_create','page','normal','high','seo' );
            }
        }

    }
}

/*-----------------------------------------------------------------------------------*/
// colabsthemes_metabox_header
/*-----------------------------------------------------------------------------------*/

function colabsthemes_metabox_header(){
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery( 'form#post').attr( 'enctype','multipart/form-data' );
        jQuery( 'form#post').attr( 'encoding','multipart/form-data' );

         //JQUERY DATEPICKER
        jQuery( '.colabs_input_calendar').each(function (){
            jQuery( '#' + jQuery(this).attr( 'id')).datepicker({showOn: 'button', buttonImage: '<?php echo get_template_directory_uri(); ?>/functions/images/calendar.gif', buttonImageOnly: true});
        });

        //JQUERY TIME INPUT MASK
        jQuery( '.colabs_input_time').each(function (){
            jQuery( '#' + jQuery(this).attr( 'id')).mask( "99:99" );
        });

        //JQUERY CHARACTER COUNTER
        jQuery( '.words-count').each(function(){
            var s = ''; var s2 = '';
            var length = jQuery(this).val().length;
            var w_length = jQuery(this).val().split(/\b[\s,\.-:;]*/).length;
            
            if(length != 1) { s = 's';}
            if(w_length != 1){ s2 = 's';}
            if('' == jQuery(this).val()){ s2 = 's'; w_length = '0';}

            jQuery(this).parent().find( '.counter').html( length + ' character'+ s + ', ' + w_length + ' word' + s2);

            jQuery(this).keyup(function(){
            var s = ''; var s2 = '';
                var new_length = jQuery(this).val().length;
                var word_length = jQuery(this).val().split(/\b[\s,\.-:;]*/).length;

                if(new_length != 1) { s = 's';}
                if(word_length != 1){ s2 = 's'}
                if('' == jQuery(this).val()){ s2 == 's'; word_length = '0';}

                jQuery(this).parent().find( '.counter').html( new_length + ' character' + s + ', ' + word_length + ' word' + s2);
            });
        });

        // Jquery radio button
        jQuery('.colabs_metaboxes_table').each(function(){
            var $this       = jQuery(this),
                radio       = $this.find(':radio'),
                checkedRadio= $this.find(':radio:checked'),
                radioWrap   = radio.parents('tr'),
                radioVal    = checkedRadio.val();
                radioVal    = '' == radioVal ? 'dummy' : radioVal;
                
                function hideRadioBox(radioBtn){
                    
                    if( typeof radioBtn == "undefined" ) {
                        radioBtn = radio;
                    } else {
                        radioBtn = radioBtn;
                    }

                    radioBtn.parents('tr')
                        // .siblings('tr[class]').hide().end()
                        .nextUntil(':not([class])').hide().end()
                        .siblings('.'+radioVal).show();
                }
                hideRadioBox();

                radio.click(function(){
                    radioVal = jQuery(this).val();
                    hideRadioBox( jQuery(this) );
                });
        });
        
        // Jquery multicheck
        jQuery('.multicheck').each(function(){
            var $el = jQuery(this);
            $el.find('input').change(function(){
                var values =  $el.find('input:checked').map(function(){
                    return this.value;
                }).get();
                $el.find(':text').val( values.join(', ') );
            });
        });
        
        jQuery( '.colabs_metaboxes_table th:last, .colabs_metaboxes_table td:last').css( 'border','0' );
        var val = jQuery( 'input#title').attr( 'value' );
        if('' == val){
        jQuery( '.colabs_metabox_fields .button-highlighted').after( "<em class='colabs_red_note'>Please add a Title before uploading a file</em>" );
        };
        jQuery( '.colabs-meta-radio-img-img').click(function(){
                jQuery(this).parent().find( '.colabs-meta-radio-img-img').removeClass( 'colabs-meta-radio-img-selected' );
                jQuery(this).addClass( 'colabs-meta-radio-img-selected' );

            });
            jQuery( '.colabs-meta-radio-img-label').hide();
            jQuery( '.colabs-meta-radio-img-img').show();
            jQuery( '.colabs-meta-radio-img-radio').hide();
        <?php //Errors
        $error_occurred = false;
        $upload_tracking = get_option( 'colabs_custom_upload_tracking' );
        if(!empty($upload_tracking)){
        $output = '<div style="clear:both;height:20px;"></div><div class="errors"><ul>' . "\n";
            $error_shown == false;
            foreach($upload_tracking as $array )
            {
                 if(array_key_exists( 'error', $array)){
                        $error_occurred = true;
                        ?>
                        jQuery( 'form#post').before( '<div class="updated fade"><p>ColorLabs Upload Error: <strong><?php echo $array['option_name'] ?></strong> - <?php echo $array['error'] ?></p></div>' );
                        <?php
                }
            }
        }

        delete_option( 'colabs_upload_custom_errors' );
        ?>
    });

</script>
<style type="text/css">
#colabsthemes-seo.postbox div.inside,
#colabsthemes-settings.postbox div.inside,
#colabsthemes-settings-video.postbox div.inside{ padding:0 }
.colabs_input_text { margin:0 0 10px 0; color:#444; width:80%; font-size:11px; padding: 5px;}
.colabs_input_select { margin:0 0 10px 0; color:#444; width:60%; font-size:11px; padding: 5px;}
.colabs_input_checkbox, .colabs_input_multicheck { margin:0 10px 0 0; }
.colabs_input_radio { margin:0 10px 0 0; }
.colabs_input_radio_desc, .colabs_input_multicheck_desc { font-size: 12px; color: #666 ; }
.colabs_input_calendar { margin:0 0 10px 0; }
.colabs_spacer { display: block; height:5px}
.colabs_metabox_desc { display:block}
.colabs_metaboxes_table{ border-collapse:collapse; width:100%}
.colabs_metaboxes_table tr:first-child { border-top:none;box-shadow:none; }
.colabs_metaboxes_table tr{ -moz-box-shadow: 0 1px 0 #FFFFFF; -webkit-box-shadow: 0 1px 0 #FFFFFF; box-shadow: 0 1px 0 #FFFFFF; border-top: 1px solid #DDDDDD;}
.colabs_metaboxes_table tr:last-child{ -moz-box-shadow: 0 0 0 #FFFFFF; -webkit-box-shadow: 0 0 0 #FFFFFF; box-shadow: 0 0 0 #FFFFFF; border-bottom: none; }
.colabs_metaboxes_table th{ padding:12px 10px 10px;text-align: left; }
.colabs_metaboxes_table td{ padding:10px;text-align: left; }
.colabs_metabox_names { width:20%; vertical-align: top; }
.colabs_metabox_fields { width:auto}
.colabs_metabox_image { text-align: right;}
.colabs_red_note { margin-left: 5px; color: #c77; font-size: 10px;}
.colabs_input_textarea { width:80%; height:120px;margin:0 0 10px 0; color:#444;font-size:11px;padding: 5px;}
.colabs-meta-radio-img-img { border:3px solid #dedede; margin:0 5px 10px 0; display:none; cursor:pointer; border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px;}
.colabs-meta-radio-img-img:hover, .colabs-meta-radio-img-selected { border:3px solid #aaa; border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; }
.colabs-meta-radio-img-label { font-size:12px}
.colabs_metabox_desc span.counter { color:green!important }
.colabs_metabox_fields .controls input.upload { width:280px; padding-bottom:6px; }
.colabs_metabox_fields .controls input.upload_button{ float:right; width:auto; border-color:#BBBBBB; cursor:pointer; height:16px; }
.colabs_metabox_fields .controls input.upload_button:hover { width:auto; border-color:#666666; color:#000; }
.colabs_metabox_fields .screenshot{margin:10px 0;float:left;margin-left:1px;position:relative;width:344px;}
.colabs_metabox_fields .screenshot img{-moz-border-radius:4px;-webkit-border-radius:4px;-border-radius:4px;background:#FAFAFA;float:left;max-width:334px;border-color:#CCC #EEE #EEE #CCC;border-style:solid;border-width:1px;padding:4px;}
.colabs_metabox_fields .screenshot .mlu_remove{background:url( "<?php echo get_template_directory_uri(); ?>/functions/images/ico-delete.png") no-repeat scroll 0 0 transparent;border:medium none;bottom:-4px;display:block;float:left;height:16px;position:absolute;left:-4px;text-indent:-9999px;width:16px;padding:0;}
.colabs_metabox_fields .upload { color:#444444;font-size:11px;margin:0 0 10px;padding:5px;width:70%; }
.colabs_metabox_fields .upload_button {-moz-border-radius:4px; -webkit-border-radius:4px;-border-radius:4px;}
.colabs_metabox_fields .screenshot .no_image .file_link {margin-left: 20px;}
.colabs_metabox_fields .screenshot .no_image .mlu_remove {bottom: 0px;}
</style>
<?php
 echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/css/jquery-ui-datepicker.css" />';
}


function colabs_custom_enqueue($hook) {
    if ('post.php' == $hook OR 'post-new.php' == $hook OR 'page-new.php' == $hook OR 'page.php'== $hook) {
        add_action( 'admin_head', 'colabsthemes_metabox_header' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_register_script( 'jquery-ui-datepicker', get_template_directory_uri() . '/functions/js/ui.datepicker.js', array( 'jquery-ui-core' ));
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_register_script( 'jquery-input-mask', get_template_directory_uri() . '/functions/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
        wp_enqueue_script( 'jquery-input-mask' );
    }
}

add_action( 'admin_enqueue_scripts', 'colabs_custom_enqueue', 10, 1 );
add_action( 'edit_post', 'colabsthemes_metabox_handle' );
add_action( 'admin_menu', 'colabsthemes_metabox_add' ); // Triggers colabsthemes_metabox_create
?>