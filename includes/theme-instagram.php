<?php

	define('colabs_instagram_version', '1.0');
	
	$colabsIncludePath = get_include_path().PATH_SEPARATOR.
							get_stylesheet_directory().'/includes/instagram-php-api/'.PATH_SEPARATOR.
							get_stylesheet_directory().'/includes/PowerHour_Geocoder/';
		
	if(!set_include_path($colabsIncludePath)) 
		ini_set('include_path',	$colabsIncludePath);

	require_once 'Instagram_XAuth.php';

	function getLatLng($address){
				
		require_once(STYLESHEETPATH . '/includes/PowerHour_Geocoder/Geocoder.php');			
				
		if(preg_match('/\d+\.d+,\d+\.\d+/', $address) > 0)
			{
					$result = explode(',', $address);
			}
		else if(strlen($address) > 0){
			$result = array();
			
			try
			{
						
			$geocoder = new PowerHour_Geocoder();
						
			$geocoder->mapFromAddress($address);
						
			$result[0] = $geocoder->getLatitude();
			$result[1] = $geocoder->getLongitude();
			}
			catch(Exception $ex){}
		}
				
				return $result;
	} 
	
	class ColabsInstagram
	{
				
		var $dbOptionKey = 'ColabsInstagram_Options';
		
		var $cachePath = '';
		var $token;
		
		/**
		 * Constructor
		 */
		function ColabsInstagram()
		{
			$this->token = 'colabsthemes-instagram';

			add_action( 'admin_menu', array( &$this, 'register_instagram_menu' ), 20 );
			
			add_shortcode('instacolabs', array(&$this, 'shortcode'));
			
			$this->cachePath = ABSPATH.'wp-content/cache/';
			
			add_action('wp_ajax_colabs_paging', array(&$this, 'ajax_colabs_paging'));
			add_action('wp_ajax_nopriv_colabs_paging', array(&$this, 'ajax_colabs_paging'));
			
			
		}
		
		function register_instagram_admin_head(){
        
			echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/admin-style.css" media="screen" />';
			echo '<style type="text/css">'
				.'#panel-content .section .description { float:none; width:35% }'
				.'</style>';
			
		}

		function getInstance()
		{
			global $ColabsInstagram;
			if(!isset($ColabsInstagram))
			{
				$ColabsInstagram = new ColabsInstagram();
			}
			
			return $ColabsInstagram;
		}
		
		function getAPIInstance()
		{
			$config = ColabsInstagram::getConfiguration();
			        
			$instagram = new Instagram_XAuth($config);
			
			$instagram->setAccessToken(ColabsInstagram::getAccessToken());
			
			return $instagram;
		}
		
		function install()
		{
			$this->getOptions();
		}
		
		function shortcode($params)
		{
			$values = shortcode_atts(array
									(
										'userid' => '',
										'size' => 85,
										'piccount' => 9,
										'effect' => false,
										'url' => false,
										'title' => 0,
										'paging' => 0,
										'max_id' => '',
										'like' => 0,
										'tag' => ''
									), 
									$params);

			
			// Default-size 150x150
			$picSize = (intval($values['size']) > 0) ? intval($values['size']) : 150;

			$page = intval($values['paging']);
			
			$beforeImage = '<div class="colabs-shortcode-image %1$s" id="colabs-shortcode-image-%2$d">';
			$imageHtml = '<img src="%1$s" ';

			if(!$this->imageAttributesDisabled())
			{
				$imageHtml .= 'width="%2$d" height="%2$d" ';
			}
			$imageHtml .= 'border="0" /></a></div>';

			$paginatorHtml = '<div class="colabs-shortcode-pager">%s</div>';
			
			$buttonNextHtml = '<a href="'.get_bloginfo( 'wpurl' ).'" class="next-page-colabs" rel="%d">'.__('Next', 'colabsthemes').' &gt;&gt;</a>';
			
			$buttonPrevHtml = '<a href="'.get_bloginfo( 'wpurl' ).'" class="prev-page-colabs" rel="%d">&lt;&lt; '.__('Previous', 'colabsthemes').'</a>';
			
			$result = '<div class="colabs-shortcode version-'.ColabsInstagram::getVersion().($page ? ' colabs-shortcode-page' : '').'" id="colabs-shortcode-page-'.$page.'">';
			
			if(!$values['url']) 
			{
				$result .= $this->getFeed($values, $imageHtml, $beforeImage, $picSize, $values['max_id']);
								
				
				if($page && strlen($values['max_id']) == 0)
				{
					$buttons = '';
					if($page > 1)$buttons .= sprintf($buttonPrevHtml, $page-1);
					
					$buttons .= sprintf($buttonNextHtml, $page+1);
					
					$paginator = sprintf($paginatorHtml, $buttons);
					
					
					$result = 	'<script type="text/javascript">var colabsConfig = '.json_encode($values).';</script>'.
								$paginator.
								'<div class="colabs-gallery">'.
								$result;
				}
			}
			else 
			{
				$oEmbed = $this->getOEmbedImage($values['url']);
				
				$result .= sprintf($beforeImage, 'oembed', 0);
				
				if($values['effect']==true)
				{
					$result .= '<a href="'.$oEmbed->url.'" rel="fancybox" title="'.htmlentities($oEmbed->title).'">';
				}else{
					$result .= '<a href="'.$values['url'].'" target="_blank">';
				}				
				$result .= sprintf($imageHtml, $oEmbed->url, $picSize);
			}
			
			$result .= '</div>';
						
			if($page)
			{
				$result .= '</div>'.$paginator;
			}
			
			return $result;
		}
		
		function ajax_colabs_paging()
		{
			$values = $_POST['config'];
			foreach($values as $key=>$value)
			{
				if(is_numeric($value))
				{
					$values[$key] = intval($value);
				}
			}
			$values['url'] = false;
			$values['max_id'] = $_POST['nextMaxId'];
			
			echo $this->shortcode($values);
			die(); 
		}
		
		function getFeed($values, $imageHtml, $beforeImage, $picSize, $nextMaxId = '')
		{
			$tagFeed = (!empty($values['tag']));
			$result = "";
			if(!$tagFeed)
			{
				$userid = $values['userid'];
				if(!is_numeric($values['userid']) && $values['userid'] != 'self' && $values['userid'] != 'myfeed' && strlen($values['userid']))
					$userid = ColabsInstagram::getUserIdByName($values['userid']);
			}
				
			$piccounter = 1;
		
			$odd = true;
			
			$lastShownId = $nextMaxId;
			
			do
			{
				$max_id = $nextMaxId;
				
				if(!$tagFeed)
					$data = ColabsInstagram::getFeedByUserId($userid, $max_id, $nextMaxId, intval($values['piccount']));
				else 
					$data = ColabsInstagram::getFeedByTag($values['tag'], $max_id, $nextMaxId, intval($values['piccount']));

				
				if(count($data) > 0)
				{
					foreach($data as $obj)
					{
						
						if(intval($values['piccount']) > 0 && $piccounter > $values['piccount'])
							break;
							
						
						$title = (intval($values['title']) == 1) ? $obj->caption->text : "";
						
					
						$result .= sprintf($beforeImage, (($odd) ? 'odd' : 'even'), $piccounter++);
						
						$odd = !$odd;
						
						
						$imageKey = ColabsInstagram::getImageKey($picSize);
						
						$result .= '<a href="'.$obj->link.'" target="_blank">';
						
						$result .= sprintf($imageHtml, $obj->images->$imageKey->url, $picSize);
												
						if($nextMaxId)
							$lastShownId = $obj->id;
						else
							$lastShownId = '';
					}
				}
				else
				{
					break;
				}
			}
			while($nextMaxId && ($piccounter <= $values['piccount'] || intval($values['piccount']) == 0));
			
			$result .= '<input type="hidden" id="colabs-next-max-id-'.(intval($values['paging'])+1).'" value="'.$nextMaxId.'" />';
			
			return $result;
		}
		
		function getOEmbedImage($url)
		{
			$json = @file_get_contents('http://api.instagram.com/oembed?url='.$url);
			return json_decode($json);
		}
		
		
		function getFeedByUserId($userid, $max_id = '', $nextMaxId = 0, $count = 0)
		{	
			$writeToCache = true;
									
			$cacheid = $userid.($max_id ? "_".$max_id : "");
			
			if(ColabsInstagram::getInstance()->getFeedFromCache($cacheid))
			{
				$json = ColabsInstagram::getInstance()->getFeedFromCache($cacheid);
				$writeToCache = false;
			}
			
			else if(intval($userid) != 0 || $userid == 'self')
			{
				$json = ColabsInstagram::getAPIInstance()->getUserRecent($userid, $max_id, $count);
			}
			
			else if($userid == 'myfeed')
			{
				$json = ColabsInstagram::getAPIInstance()->getUserFeed($max_id);
			}
			
			else
			{
				$json = ColabsInstagram::getAPIInstance()->getPopularMedia();
			}
						
			$response = json_decode($json);
			
			if($writeToCache && $response->data)
				ColabsInstagram::getInstance()->writeFeedToCache($cacheid, $json);
				
			
			if($response->pagination)
				$nextMaxId = $response->pagination->next_max_id; 
			else 
				$nextMaxId = null;
				
				
			return $response->data;
		}
		
		function getFeedByTag($tag, $max_id = '', $nextMaxId = 0, $count = 0)
		{	
			$writeToCache = true;
									
			$cacheid = $tag.($max_id ? "_".$max_id : "");
			
			if(ColabsInstagram::getInstance()->getFeedFromCache($cacheid))
			{
				$json = ColabsInstagram::getInstance()->getFeedFromCache($cacheid);
				$writeToCache = false;
			}
			else
			{
				$json = ColabsInstagram::getAPIInstance()->getRecentTags($tag, $max_id);
			}
						
			$response = json_decode($json);
			
			if($writeToCache && $response->data)
				ColabsInstagram::getInstance()->writeFeedToCache($cacheid, $json);
				
			
			if($response->pagination)
				$nextMaxId = $response->pagination->next_max_id; // max_id für nächsten Request setzen
			else 
				$nextMaxId = null;
				
			return $response->data;
		}
		
		function getCacheFilename($cachename)
		{
			if(!$cachename)
				$cachename = 'popular-media';
			return $this->cachePath.'cache-'.$cachename.'.json';
		}
		
		function getDataFromCache($cachename)
		{
			
			$cacheFile = $this->getCacheFilename($cachename);
			
			
			if(is_readable($cacheFile) && filemtime($cacheFile) > strtotime('- '.$this->getOption('app_cache_time').' Minutes', time()))
			{
				return @file_get_contents($cacheFile);	
			}
			
			return false;	
		}
		
		function writeDataToCache($cachename, $json)
		{
			
			$cacheFile = $this->getCacheFilename($cachename);
			
			
			if($this->cacheIsEnabled())
			{
				@file_put_contents($cacheFile, $json);
				return true;
			}
			
			return false;
		}
		
		function cacheIsEnabled()
		{
			
			if(!is_dir($this->cachePath) && is_writable(ABSPATH.'wp-content/'))
			{
				
				return @mkdir($this->cachePath, 0755);
			}
			
		
			return is_writable($this->cachePath);
		}
		
		function getFeedFromCache($cachename)
		{
			return $this->getDataFromCache($cachename);
		}
		
		function writeFeedToCache($cachename, $json)
		{
			return $this->writeDataToCache($cachename, $json);
		}
		
		function getMediaFromCache($mediaId)
		{
			return $this->getDataFromCache('media-'.$mediaId);
		}
		
		function writeMediaToCache($mediaId, $json)
		{
			return $this->writeDataToCache('media-'.$mediaId, $json);
		}
		
		function getLocationBasedFeed($coordinates)
		{
			
			if(!empty($coordinates))
			{
				
				$cachename = implode('-', $coordinates);
				$cachename = str_replace('.', '_', $cachename);
				
				
				if(ColabsInstagram::getInstance()->getFeedFromCache($cachename))
				{
					$json = ColabsInstagram::getInstance()->getFeedFromCache($cachename);
				}
				else 
				{
					$json = ColabsInstagram::getAPIInstance()->mediaSearch($coordinates[0], $coordinates[1], null, null, 250);
					ColabsInstagram::getInstance()->writeFeedToCache($cachename, $json);
				}
				
				$response = json_decode($json);
				
				return $response->data;
			}
			
			return array();
		}
		
		function getImageTitle($imageId)
		{
	
			$json = $this->getMediaFromCache($imageId);
			
			
			if(!$json)
			{
				$json = $this->getAPIInstance()->getMedia($imageId);
				$writeToCache = true;
			}
			
			$media = json_decode($json);
			
			if($writeToCache && $media->data)
				ColabsInstagram::getInstance()->writeMediaToCache($imageId, $json);
			
			return $media->data->caption->text;
		}
		
		function getUserIdByName($name)
		{			
			if($name && $name != 'self')
			{
				$json = ColabsInstagram::getAPIInstance()->searchUser($name);
				
				$response = json_decode($json);
								
				$data = $response->data;
				
				if(count($data) > 0)
				{
					return $data[0]->id;
				}
			}
			else if($name == 'self')
			{
				return $name;
			}
			return 0;
		}
		
		function getOptions()
		{
		
			$options = array
			(
				'app_access_token' => '',
				'app_cache_time' => 30
			);
			
			
			$saved = get_option($this->dbOptionKey);
			
			
			if(!empty($saved))
			{
				
				foreach($saved as  $key => $option)
				{
					$options[$key] = $option;
				}
			}
			
			
			if($saved != $options)
				update_option($this->dbOptionKey, $options);
				
			return $options;
		}
		
		function getPluginUrl()
		{
			return get_admin_url(null, 'options-general.php?page=instagram.php');
		}
		
		function getPluginDirUrl()
		{
			return trailingslashit(plugins_url('', __FILE__));
		}
		
		function getPluginDirPath()
		{
			return trailingslashit(plugin_dir_path(__FILE__));
		}
		
		function getOption($key)
		{
			$options = $this->getOptions();
			
			return $options[$key];
		}
		
		function handleOptions()
		{
			$options = $this->getOptions();
			
			
			if(isset($_POST['instagram-update-auth-settings']))
			{			
				$options = array();
				$options['app_user_username'] = trim($_POST['instagram-app-user-username']);
				$options['app_user_password'] = trim($_POST['instagram-app-user-password']);
				
				
				update_option($this->dbOptionKey, $options);
				
				$instagram = ColabsInstagram::getAPIInstance();
				
				if(!$options['app_access_token'])
				{
					
					$errorMessage = "";
					
					$token = $instagram->getAccessToken($errorMessage);
				
					
					if($token)
					{
						
						$options['app_access_token'] = $token;
						
						update_option($this->dbOptionKey, $options);
						
						echo '<div class="updated"><p>'.__('Settings saved.', 'colabsthemes').'</p></div>';
					}
					else if($errorMessage) 
					{
						echo '<div class="error"><p>'.__('Instagram API reported the following error', 'colabsthemes').': <b>';
						echo $errorMessage;
						echo '</b></p></div>';
					}
				}
			}
			
			else if(isset($_POST['instagram-reset-settings']))
			{
				
				delete_option($this->dbOptionKey);
			}
			
		
			if(isset($_POST['instagram-update-settings']))
			{
				
				$cacheTime = intval($_POST['instagram-cache-time']);
				if($cacheTime > 0)
				{
					$options['app_cache_time'] = $cacheTime;
				}
				$options['app_disable_effects'] = isset($_POST['instagram-disable-fancybox']);
				$options['app_disable_image_attributes'] = isset($_POST['instagram-disable-image-attr']);
				
				update_option($this->dbOptionKey, $options);
			}
			
			
			$authorizeUrl = $this->getOAuthRedirectUrl();
			
			include('theme-instagram-options.php');
		}
		
		
		function getConfiguration()
		{
			$options = ColabsInstagram::getInstance()->getOptions();
			return array(
							'site_url' 		=> 'https://api.instagram.com/oauth/access_token',
				            'client_id' 	=> '0a344b64448b43e5bb8e1c22acffc0ef',
				            'client_secret' => 'ff62e43965be4a48b83a32261cd540bc',
							'username' 		=> $options['app_user_username'],
							'password' 		=> $options['app_user_password'],
				            'grant_type' 	=> 'password',
				            'redirect_uri'	=> ColabsInstagram::getOAuthRedirectUrl()
				        );
		}
		
		
		function register_instagram_menu()
		{
			
			$this->admin_page = add_submenu_page('colabsthemes', __( 'Instagram Settings', 'colabsthemes' ), __( 'Instagram Settings', 'colabsthemes' ), 'manage_options', $this->token, array( &$this, 'handleOptions' ) );
			add_action( 'admin_print_styles-'.$this->admin_page, array( &$this, 'register_instagram_admin_head' ) );
		}

		function getPluginName()
		{
			return plugin_basename(__FILE__);
		}
		
		
		function imageAttributesDisabled()
		{
			return $this->getOption('app_disable_image_attributes');	
		}
		

		function getOAuthRedirectUrl()
		{
			return get_admin_url().'admin.php?page=colabsthemes-instagram';//.'instagram/oauth.php';
		}
		
		function getAccessToken()
		{
			$options = ColabsInstagram::getInstance()->getOptions();
			
			return $options['app_access_token'];
		}
		
		function getVersion()
		{
			return colabs_instagram_version;
		}
		
		function getImageKey($size)
		{
			if($size <= 150)
				return 'thumbnail';
			if($size <= 306)
				return 'low_resolution';
			
			return 'standard_resolution';
		}
				
		function isCurlInstalled() 
		{
			return in_array('curl', get_loaded_extensions());
		}
		
		function getErrors()
		{
			$errors = array();
			if(!ColabsInstagram::getInstance()->cacheIsEnabled())
				$errors[] = sprintf(__('To improve performance of this plugin, it is highly recommended to make the directory wp-content or wp-content/cache writable. For further information click <a target="_blank" href="%s">here</a>' , 'colabsthemes'), 'http://codex.wordpress.org/Changing_File_Permissions');
			if(!ColabsInstagram::getInstance()->isCurlInstalled())
				$errors[] = __('Instagram requires <a href="http://php.net/manual/en/book.curl.php" target="_blank">PHP cURL</a> extension to work properly', 'colabsthemes');
			if(!function_exists('mb_detect_encoding'))
				$errors[] = __('Geocoding won\'t work unless <a href="http://www.php.net/manual/en/mbstring.installation.php" target="_blank">mbstring</a> is activated', 'colabsthemes');
				
			return (count($errors) > 0 ? $errors : false);
		}
		
	}
		
	if (class_exists('ColabsInstagram')): 
		$ColabsInstagram = ColabsInstagram::getInstance();
		if (isset($ColabsInstagram)) 
		{
			register_activation_hook(__FILE__, array(&$ColabsInstagram, 'install'));
		}
	endif;
	
