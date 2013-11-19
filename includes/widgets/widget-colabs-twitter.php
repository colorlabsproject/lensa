<?php
/* ===================================================================
 * Twitter Widgets
 * ===================================================================
 */
class CoLabs_Twitter extends WP_Widget {
  var $settings = array( 'title', 'limit', 'username' );
  var $consumer_key = 'tZC2RgSO04T7ctQQDIFw';
  var $consumer_secret = 'xB8YWcEYkzqnqGAgHia84YVWlGSZqRnZn0otis2Ho';

  /**
   * Constructor
   */
  function __construct() {
    $widget_ops = array( 'description' => 'Show your Twitter feed.' );
    parent::WP_Widget( false, __('ColorLabs - Twitter Stream', 'colabsthemes'), $widget_ops );
  }

  /**
   * Render Widget
   */
  function widget( $args, $instance ) {
    $instance = $this->colabs_enforce_defaults( $instance );
    extract( $args, EXTR_SKIP );
    extract( $instance, EXTR_SKIP );
    $unique_id = $args['widget_id'];

    echo $before_widget;
    if( $title ) {
      echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
    }
    $user_timeline = $this->get_user_timeline( $username, $number ); ?>
    <?php if( isset( $user_timeline['error'] ) ) : ?>
      <p><?php echo $user_timeline['error']; ?></p>
    <?php else : ?>
      <?php $this->build_twitter_markup( $user_timeline ); ?>
    <?php endif; ?>
    <p><?php _e('Follow','colabsthemes'); ?> <a href="http://twitter.com/<?php echo $username; ?>"><strong>@<?php echo $username; ?></strong></a> <?php _e('on Twitter','colabsthemes'); ?></p>
    <?php
    echo $after_widget;
  }

  /**
   * Save Widget
   */
  function update( $new_instance, $old_instance ) {
    $new_instance = $this->colabs_enforce_defaults( $new_instance );
    return $new_instance;
  }

  /**
   * Set default settings
   */
  function colabs_enforce_defaults( $instance ) {
    $defaults = $this->colabs_get_settings();
    $instance = wp_parse_args( $instance, $defaults );
    $instance['title'] = strip_tags( $instance['title'] );
    $instance['username'] = $instance['username'] ? $instance['username'] : 'colorlabs';
    return $instance;
  }

  /**
   * Provides an array of the settings with the setting name as 
   * the key and the default value as the value
   */
  function colabs_get_settings() {
    // Set the default to a blank string
    $settings = array_fill_keys( $this->settings, '' );
    // Now set the more specific defaults
    $settings['number'] = 3;
    return $settings;
  }

  /**
   * Widget Form Settings
   */
  function form( $instance ) {
    $instance = $this->colabs_enforce_defaults( $instance );
    extract( $instance, EXTR_SKIP ); ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
        <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:','colabsthemes'); ?></label>
        <input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo esc_attr( $username ); ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of tweet:','colabsthemes'); ?></label>
        <input type="text" name="<?php echo $this->get_field_name('number'); ?>"  value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
      </p>
    <?php
  }

  /**
   * Linkify Twitter Text
   * 
   * @param string s Tweet
   * 
   * @return string a Tweet with the links, mentions and hashtags wrapped in <a> tags 
   */
  function linkify_twitter_text($tweet){
    $url_regex = '/((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d:#@%\/\;$()~_?\+-=\\\.&]*)/';
    $tweet = preg_replace($url_regex, '<a href="$1" target="_blank">'. "$1" .'</a>', $tweet);
    $tweet = preg_replace( array(
      '/\@([a-zA-Z0-9_]+)/', # Twitter Usernames
      '/\#([a-zA-Z0-9_]+)/' # Hash Tags
    ), array(
      '<a href="http://twitter.com/$1" target="_blank">@$1</a>',
      '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>'
    ), $tweet );
    
    return $tweet;
  }

  /**
   * Get User Timeline
   * 
   */
  function get_user_timeline( $username = '', $limit = 5 ) {
    $key = "twitter_user_timeline_{$username}_{$limit}";

    // Check if cache exists
    $timeline = get_transient( $key );
    if ($timeline !== false) {
      return $timeline;
    } else {
      $headers = array( 'Authorization' => 'Bearer ' . $this->get_access_token() );
      $response = wp_remote_get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name={$username}&count={$limit}", array( 'headers' => $headers ));
      if ( is_wp_error($response) ) {
        // In case Twitter is down we return error
        return array('error' => __('There is problem fetching twitter timeline', 'colabsthemes'));
      } else {
        // If everything's okay, parse the body and json_decode it
        $json = json_decode(wp_remote_retrieve_body($response));

        // Check for error
        if( !count( $json ) ) {
          return array('error' => __('There is problem fetching twitter timeline', 'colabsthemes'));
        } elseif( isset( $json->errors ) ) {
          return array('error' => $json->errors[0]->message);
        } else {
          set_transient( $key, $json, 60 * 60 );
          return $json;
        }
      }
    }
  }

  /**
   * Get Twitter application-only access token
   * @return string Access token
   */
  function get_access_token() {
    $consumer_key = urlencode( $this->consumer_key );
    $consumer_secret = urlencode( $this->consumer_secret );
    $bearer_token = base64_encode( $consumer_key . ':' . $consumer_secret );

    $oauth_url = 'https://api.twitter.com/oauth2/token';

    $headers = array( 'Authorization' => 'Basic ' . $bearer_token );
    $body = array( 'grant_type' => 'client_credentials' );

    $response = wp_remote_post( $oauth_url, array(
      'headers' => $headers,
      'body' => $body
    ) );

    $response_json = json_decode( $response['body'] );

    return $response_json->access_token;
  }

  /**
   * Builder Twitter timeline HTML markup
   */
  function build_twitter_markup( $timelines ) { ?>
    <ul>
    <?php foreach( $timelines as $item ) : ?>
      <?php 
        $screen_name = $item->user->screen_name;
        $profile_link = "http://twitter.com/{$screen_name}";
        $status_url = "http://twitter.com/{$screen_name}/status/{$item->id}";
      ?>
      <li>
        <span class="content">
          <?php echo $this->linkify_twitter_text( $item->text ); ?>
          <a href="<?php echo $status_url; ?>" style="font-size:85%" class="time" target="_blank">
            <?php echo date('M j, Y', strtotime($item->created_at)); ?>
          </a>
        </span>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php }

}

register_widget( 'CoLabs_Twitter' );