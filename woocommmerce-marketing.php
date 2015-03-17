<?php

	/*
		Plugin Name: Woocommerce Marketing
	 	Description: Customized Reports for Subscription Marketing
	 	Author: tobinfekkes
	 	Author URI: http://tobinfekkes.com
	 	Version: 1.0
	*/
	define( 'WOOCOMMERCE_MARKETING_URL', __FILE__);
	define( 'WOOCOMMERCE_MARKETING_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
	
	function woocommerce_marketing_css_and_js() {
		wp_register_style('woocommerce_marketing_css', plugins_url('css/admin.css',__FILE__ ));
		wp_enqueue_style('woocommerce_marketing_css');
		wp_register_script('woocommerce_marketing_js', plugins_url('js/admin.js',__FILE__ ));
		wp_enqueue_script('woocommerce_marketing_js');
	}

	add_action( 'wp_enqueue_scripts','woocommerce_marketing_css_and_js');


	// call register settings function
	add_action( 'admin_init', 'register_woo_marketing_settings' );
	
	
	function register_woo_marketing_settings() {
		//register our settings
		register_setting( 'woocommerce_marketing_group', 'marketing_affiliates' );
	}

	function Woocommerce_Marketing() {
		add_menu_page('Marketing', 'Marketing', 'manage_options', 'marketing_dashboard', 'marketing_dashboard_callback', '');
		add_submenu_page('marketing_dashboard', 'Marketing Options', 'Options', 'manage_options', 'marketing_options', 'marketing_options_callback');
	}
	
	add_action('admin_menu', "Woocommerce_Marketing");
	
	
	function marketing_dashboard_callback() {
		global $wpdb;
          
		include WOOCOMMERCE_MARKETING_PATH . '/admin/dashboard.php';
	}
	
	function marketing_options_callback() {
		global $wpdb;
		
		include WOOCOMMERCE_MARKETING_PATH . '/admin/options.php';
	}
?>