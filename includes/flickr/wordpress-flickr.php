<?php
/**
 * Simple WordPress Flickr API Wrapper
 *
 * @version 1.0.0
 * @author Arif Widipratomo <arif.widip@gmail.com>
 */
class Colabs_WP_Flickr {

  var $api_key;
  var $api_secret;
  var $user_id;
  var $photo_type;
  var $feed_url;
  var $rest_endpoint = 'https://api.flickr.com/services/rest/';

  /**
   * Constructor
   */
  public function __construct( $api_key, $api_secret ) {
    $this->api_key = $api_key;
    $this->api_secret = $api_secret;
  }

  /**
   * Request to Flickr REST API
   */
  public function request( $method, $args = array() ) {
    $return_response = array();
    $defaults = array(
      'method' => $method,
      'format' => 'json',
      'nojsoncallback' => 1,
      'api_key' => $this->api_key
    );
    $args = wp_parse_args( $args, $defaults );
    $api_url = add_query_arg( $args, $this->rest_endpoint );

    $response = wp_remote_get( $api_url, array(
      'timeout' => 30,
      'sslverify' => false
    ));

    if( !is_wp_error( $response ) ) {
      $return_response = json_decode( wp_remote_retrieve_body( $response ) );
    } else {
      $return_response = false;
    }

    return $return_response;
  }

  /**
   * Get User Photos
   * @param String $user_id User ID
   * @param Integer $per_page How many photo to fetch
   * @return Mixed Array if succesfully fetched, false if failed to fetch the photos
   */
  public function get_user_photos( $user_id, $per_page = 20, $page = 1 ) {
    $cache_key = md5( "colabs_flickr_people_getPublicPhotos_{$user_id}_{$per_page}_{$page}" );
    $flickr_photos = get_transient( $cache_key );

    // if transient not exist
    if( !$flickr_photos ) {
      $user_photos = $this->request( 'flickr.people.getPublicPhotos', array( 
        'user_id' => $user_id,
        'per_page' => $per_page,
        'page' => $page
      ));

      // Make sure data is successfully fetched
      if( $user_photos !== false ) {
        $flickr_photos = array(
          'perpage' => $user_photos->photos->perpage,
          'page' => $user_photos->photos->page,
          'total_pages' => $user_photos->photos->pages,
          'photos' => array()
        );

        foreach( $user_photos->photos->photo as $index => $item ) {
          $single_photo = $this->request( 'flickr.photos.getInfo', array(
            'photo_id' => $item->id
          ) );

          $flickr_photos['photos'][ $index ] = array(
            'id' => $single_photo->photo->id,
            'title' => $single_photo->photo->title->_content,
            'page_url' => $single_photo->photo->urls->url[0]->_content,
            'owner_data' => $single_photo->photo->owner,
            'views' => $single_photo->photo->views,
            'dateuploaded' => $single_photo->photo->dateuploaded,
            'image' => array(
              'm' => $this->build_photo_url( $single_photo->photo ),
              'o' => $this->build_photo_url( $single_photo->photo, 'original' )
            )
          );
        }

      } else {
        return false;
      }

      // Write the cache
      set_transient( $cache_key, $flickr_photos, WEEK_IN_SECONDS );
    }

    return $flickr_photos;
  }

  /**
   * Build Photo URL
   *
   * @param Object $photo Photo object returned from Flickr API
   * @param String $size Specify size of the photo 
   */
  public function build_photo_url( $photo, $size = 'medium' ) {
    $sizes = array(
      "square" => "_s",
      "square_75" => "_s",
      "square_150" => "_q",
      "thumbnail" => "_t",
      "small" => "_m",
      "small_240" => "_m",
      "small_320" => "_n",
      "medium" => "",
      "medium_500" => "",
      "medium_640" => "_z",
      "medium_800" => "_c",
      "large" => "_b",
      "large_1024" => "_b",
      "large_1600" => "_h",
      "large_2048" => "_k",
      "original" => "_o",
    );

    $size = strtolower($size);
    if (!array_key_exists($size, $sizes)) {
      $size = "medium";
    }

    // If photo doesn't provide original image size
    if( !isset( $photo->originalsecret ) ) {
      $size = 'medium';
    }

    if ($size == "original") {
      $url = "https://farm" . $photo->farm . ".static.flickr.com/" . $photo->server . "/" . $photo->id . "_" . $photo->originalsecret . "_o" . "." . $photo->originalformat;
    } else {
      $url = "https://farm" . $photo->farm . ".static.flickr.com/" . $photo->server . "/" . $photo->id . "_" . $photo->secret . $sizes[$size] . ".jpg";
    }
    return $url;
  }

}