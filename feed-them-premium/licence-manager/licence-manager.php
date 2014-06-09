<?php

/**
 * Class used for actions performed on plugin licence
 * @version 1.0
 */
class licence_manager
{
	// plugin info
	private $plugin_slug,
			$plugin_path,
			$plugin_title,
			$plugin_version,
			$plugin_file,
			$plugin_identifier;

	// licence_validator info
	private $validator_token	= 'licence-validator',
			$validator_prefix	= 'licence_validator_';

    // URL address of API where digital-licencing plugin is installed
    private $api_url;

	// static variables
	private static	$instance	= false,
					$plugins	= array();

	/**
	 * Constructor - takes a plugin name as parameter
	 * @param string $file
	 */
	public function __construct( $file, $plugin_identifier, $api_url )
	{
        $this->api_url              = $api_url;
		$this->plugin_file			= $file;
		$this->plugin_identifier	= $plugin_identifier;
		$this->plugin_path			= plugin_dir_path( $this->plugin_file );
		$this->plugin_slug			= plugin_basename( $this->plugin_file );

		// Running this here rather than the constructor since get_file_data is a bit expensive
		$info = get_file_data( $this->plugin_file, array( 'Title' => 'Plugin Name', 'Version' => 'Version' ), 'plugin' );

		$this->plugin_title		= $info['Title'];
		$this->plugin_version	= $info['Version'];
		
		// Store the plugin to a static variable
		self::$plugins[$this->plugin_identifier] = array(
			'version' => $this->plugin_version,
			'slug'    => $this->plugin_slug,
			'path'    => $this->plugin_path,
			'title'   => $this->plugin_title,
		);

		if ( !self::$instance ) {
			self::$instance = true;

			add_action( 'admin_menu', array( $this, 'register_nav_menu_link' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_assets_admin' ) );
		}
		
		// Running plugin auto-updater
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check' ) );	// define the alternative API for updating checking
		add_filter( 'plugins_api', array( $this, 'update_info' ), 10, 3 );						// Define the alternative response for information checking
	}

	/**
	 * Activate API request
	 * @param string $product_id
	 * @param string $license_key
	 * @param string $email
	 * @return type
	 */
	private function activate( $product_id, $license_key, $email )
	{
		// POST data to send to your API
		$args = array(
			'email'			=> $email,
			'licence_key'	=> $license_key,
			'product_id'	=> $product_id,
			'instance'		=> $this->generate_plugin_instance()
		);

		// Send request for detailed information
		return $this->prepare_request( 'activation', $args );
	}

	/**
	 * Register admin assets
	 */
	public function add_assets_admin()
	{
		// STYLES
		wp_enqueue_style( 'licence_validator_admin_styles', plugins_url( 'assets/css/admin.css', __FILE__ ) );
	}

	/**
	 * Display admin settings page.
	 */
	public function admin_screen()
	{
		$user_email	= $this->get_current_user_email();
		$messages	= array();
		
		// Load admin screen logic.
		if ( !empty( $_POST[$this->validator_prefix . 'nonce'] ) &&
			 wp_verify_nonce( $_POST[$this->validator_prefix . 'nonce'], $this->validator_token . '-nonce' ) ) {
			if ( isset( $_POST[ $this->validator_token . '-login'] ) ) {
				$messages = $this->save_license_keys();
			}
		}

		// getting new keys after they were updated
		$keys = $this->get_keys();

		require(  $this->plugin_path . 'licence-manager/templates/admin-screen.php' );
	}

	/**
	 * Deactivate API request
	 * @param string $product_id
	 * @param string $license_key
	 * @param string $email
	 * @return boolean
	 */
	private function deactivate( $product_id, $license_key, $email )
	{
		// POST data to send to your API
		$args = array(
			'email'			=> $email,
			'licence_key'	=> $license_key,
			'product_id'	=> $product_id,
			'instance'		=> $this->generate_plugin_instance()
		);

		// Send request for detailed information
		return $this->prepare_request( 'deactivation', $args );
	}

	/**
	 * Displaying the error message in admin panel in case when plugin is enabled without active licence key
	 */
	public function display_inactive_plugin_warning()
	{
		require( $this->plugin_path . 'licence-manager/templates/inactive-plugin-warning.php' );
	}

	/**
	 * Instance key for current WP installation
	 * @return type
	 */
	private function generate_plugin_instance()
	{
		return $_SERVER['HTTP_HOST'] . '@' . $_SERVER['SERVER_ADDR'];
	}

	/**
	 * Method returns the email which belong to currently logged in user.
	 * @return type
	 */
	private function get_current_user_email()
	{
		$current_user = wp_get_current_user();
		return $current_user->user_email;
	}
	
	/**
	 * Method generates and returns the URL used for downloading a latest version of plugin
	 * @param string $email
	 * @param string $licence
	 * @return string
	 */
	private function get_download_url( $email, $licence )
	{
		return $this->api_url . '?licence-api=plugin_download' . 
								'&email=' . $email . 
								'&licence_key=' . $licence . 
								'&product_id=' . $this->plugin_identifier;
	}

	/**
	 * Returns a set of licence keys
	 * @return array
	 */
	private function get_keys()
	{
		return get_option( $this->validator_prefix . 'license_keys' );
	}
	
	/**
	 * API request for status/version of current plugin. Method returns the version number of latest plugin
	 * @param string $email
	 * @param string $license_key
	 * @return string
	 */
	private function get_latest_version( $email, $license_key )
	{
		// POST data to send to your API
		$args = array(
			'email'			=> $email,
			'licence_key'	=> $license_key,
			'product_id'	=> $this->plugin_identifier
		);

		// Send request for detailed information
		$response = $this->prepare_request( 'plugin_info', $args );
		return ( isset( $response->version ) ) ? $response->version : 0;
	}
	
	/**
	 * API request for information about the plugin. 
	 * @param string $email
	 * @param string $license_key
	 * @return string
	 */
	private function get_plugin_info( $email, $license_key )
	{
		// POST data to send to your API
		$args = array(
			'email'			=> $email,
			'licence_key'	=> $license_key,
			'product_id'	=> $this->plugin_identifier
		);

		// Send request for detailed information
		$response = $this->prepare_request( 'plugin_info', $args );
		
		$obj = new stdClass();
		$obj->sections = array(
			'description'	=> ( isset( $response->info ) ) ? $response->info : ''
		);
		$obj->new_version	= $response->version;
		$obj->slug			= $this->plugin_slug;
		$obj->last_updated	= $response->last_update;
		
		return $obj;
	}	

	/**
	 * Method checks whether submenu option is already added by other extension
	 * @return boolean
	 */
	private function instance_exists()
	{
		global $submenu;

		$exists = false;

		// Check if the menu item already exists.
		if ( isset( $submenu['index.php'] ) && is_array( $submenu['index.php'] ) ) {
			foreach ( $submenu['index.php'] as $k => $v ) {
				if ( isset( $v[2] ) && ( $v[2] == $this->validator_token ) ) {
					$exists = true;
					break;
				}
			}
		}

		return $exists;
	}

	/**
	 * is_licence_active() function
	 * @return boolean
	 */
	public function is_licence_active()
	{
		$keys = $this->get_keys();
		$active = ( isset( $keys[$this->plugin_identifier]['status'] ) && $keys[$this->plugin_identifier]['status'] );

		if ( !$active ) {
			add_action( 'admin_notices', array( $this,'display_inactive_plugin_warning' ) );
		}

		return $active;
	}

	/**
	 * Prepare a request and send it to API.
	 * @param string $action
	 * @param array $args
	 * @return boolean
	 */
	private function prepare_request( $action, $args )
	{
		$request = wp_remote_post( $this->api_url . '?licence-api=' . $action, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => $args,
				'cookies'     => array(),
				'sslverify'   => false,
			)
		);

		// Make sure the request was successful
		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			// Request failed
			return false;
		}

		// Read server response and return it
		return json_decode( wp_remote_retrieve_body( $request ) );
	}

	/**
	 * Adding the menu option to Dashboard
	 * @return boolean
	 */
	public function register_nav_menu_link()
	{
		// Don't register the menu if it's already there.
		if ( $this->instance_exists() ) { 
			return false;
		}

		if ( function_exists( 'add_submenu_page' ) ) {
			add_submenu_page(
				'index.php',
				__( 'Manage licences', $this->validator_token ),
				__( 'Manage licences', $this->validator_token ),
				'switch_themes',
				$this->validator_token,
				array( $this, 'admin_screen' )
			);
		}
	}

	/**
	 * Storing licence keys in database
	 * @return array
	 */
	private function save_license_keys()
	{
		$messages	= array();
		$keys		= $this->get_keys();

		if ( !isset( $_POST['license_keys'] ) ) {
			return $messages;
		}

		foreach ( $_POST['license_keys'] as $product_id => $license_key ) {

			$license_key		= trim( $license_key );
			$activation_email	= ( isset( $_POST['licence_emails'][$product_id] ) && is_email( $_POST['licence_emails'][$product_id] ) ) ?
									$_POST['licence_emails'][$product_id] : $this->get_current_user_email();
			$licence_active		= ( isset( $keys[$product_id]['status'] ) && $keys[$product_id]['status'] );

			// Deactivate this key as it was removed
			if ( empty( $license_key ) && isset( $keys[$product_id]['status'] ) && $keys[$product_id]['status'] && $licence_active ) {
				$response = $this->deactivate( $product_id, $keys[$product_id]['license_key'], $activation_email );
				if ( isset( $response->success ) && $response->success ) {
					$messages[] = array(
						'success'	=> true,
						'message'	=> sprintf( __( '<b>Key deactivated.</b> License key for <i>%s</i> has been <b>deactivated</b>.', $this->validator_token ), self::$plugins[$product_id]['title'] )
					);
					// set status as inactive and remove licence from database
					$keys[$product_id] = array(
						'license_key'	=> '',
						'status'		=> false,
						'email'			=> ''
					);
				}
				else {
                    $error_message = $response ? $response-> error : __( 'There is a problem with connecting to licence activation API', $this->validator_token );
					$messages[] = array(
						'success'	=> false,
						'message'	=> sprintf( __( '%s deactivation: ', $this->validator_token ), self::$plugins[$product_id]['title'] ) . $error_message
					);
				}
			}
			// Activate this key
			elseif( !$licence_active ) {
				$response = $this->activate( $product_id, $license_key, $activation_email );
				if ( isset( $response->success ) && $response->success ) {
					$messages[] = array(
						'success'	=> true,
						'message'	=> sprintf( __( '<b>Key activated.</b> License key for <i>%s</i> has been <b>activated</b>.', $this->validator_token ), self::$plugins[$product_id]['title'] )
					);

					$keys[$product_id] = array(
						'license_key'	=> $license_key,
						'status'		=> true,
						'email'			=> ( isset( $_POST['licence_emails'][$product_id] ) && is_email( $_POST['licence_emails'][$product_id] ) ) ?
										   $_POST['licence_emails'][$product_id] : ''
					);
				}
				else {
                    $error_message = $response ? $response-> error : __( 'There is a problem with connecting to licence activation API', $this->validator_token );
					$messages[] = array(
						'success'	=> false,
						'message'	=> sprintf( __( '%s activation: ', $this->validator_token ), self::$plugins[$product_id]['title'] ) . $error_message
					);
				}
			}
		}

		$this->set_keys( $keys );
		return $messages;
	}

	/**
	 * Saves a new keys array in database
	 * @param array $keys
	 * @return boolean
	 */
	private function set_keys( $keys )
	{
		return update_option( $this->validator_prefix . 'license_keys', $keys );
	}
	
	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 * @param $transient
	 * @return object $ transient
	 */
	public function update_check( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		
		$keys = $this->get_keys();
		if ( isset( $keys[$this->plugin_identifier]['status'] ) && $keys[$this->plugin_identifier]['status'] ) {

			$email = is_email( $keys[$this->plugin_identifier]['email'] ) ? $keys[$this->plugin_identifier]['email'] : $this->get_current_user_email();
			
			// Get the remote version
			$remote_version = $this->get_latest_version( $email, $keys[$this->plugin_identifier]['license_key']	);
		
			// If a newer version is available, add the update
			if ( version_compare( $this->plugin_version, $remote_version, '<' ) ) {
				$obj = new stdClass();
				$obj->slug = $this->plugin_slug;
				$obj->new_version = $remote_version;
				$obj->url = $this->get_download_url( $email, $keys[$this->plugin_identifier]['license_key'] );
				$obj->package = $this->get_download_url( $email, $keys[$this->plugin_identifier]['license_key'] );
				$transient->response[$this->plugin_slug] = $obj;
			}
		}

		return $transient;		
	}
	
	/**
	 * Method is fired when admin/editor displays the plugin information window
	 * @param type $false
	 * @param type $action
	 * @param type $arg
	 * @return boolean
	 */
	public function update_info( $false, $action, $arg )
	{
		if ( $arg->slug === $this->plugin_slug ) {  
			$keys = $this->get_keys();
			return $this->get_plugin_info(
				is_email( $keys[$this->plugin_identifier]['email'] ) ? $keys[$this->plugin_identifier]['email'] : $this->get_current_user_email(),
				$keys[$this->plugin_identifier]['license_key']				
			);
		}
		
		return false;
	}
}