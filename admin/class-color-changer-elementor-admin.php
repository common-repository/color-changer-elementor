<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       elementinvader.com
 * @since      1.0.0
 *
 * @package    Color_Changer_Elementor
 * @subpackage Color_Changer_Elementor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Color_Changer_Elementor
 * @subpackage Color_Changer_Elementor/admin
 * @author     ElementInvader <support@elementinvader.com>
 */
class Color_Changer_Elementor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Color_Changer_Elementor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Color_Changer_Elementor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/color-changer-elementor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Color_Changer_Elementor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Color_Changer_Elementor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/color-changer-elementor-admin.js', array( 'jquery' ), $this->version, false );

	}

        /**
	 * Admin Page Display
	 */
	public function admin_page_display() {
		global $ccel_MVC, $submenu, $menu;

		$page = '';
        $function = '';

		if(isset($_GET['page']))$page = sanitize_text_field($_GET['page']);
		if(isset($_GET['function']))$function = sanitize_text_field($_GET['function']);

		$ccel_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$ccel_MVC->load_helper('basic');
		$ccel_MVC->load_controller($page, $function, array());
	}

    /**
     * To add Plugin Menu and Settings page
     */
    public function plugin_menu() {

        ob_start();

        add_menu_page(__('EL Color Changer','color-changer-elementor'), __('EL Color Changer','color-changer-elementor'), 
            'manage_options', 'ccel', array($this, 'admin_page_display'),
            //plugin_dir_url( __FILE__ ) . 'resources/logo.png',
            'dashicons-buddicons-topics',
            100 );
        
        add_submenu_page('ccel', 
            __('EL Color Changer','color-changer-elementor'), 
            __('EL Color Changer','color-changer-elementor'),
            'manage_options', 'ccel', array($this, 'admin_page_display'));
	
    }

}
