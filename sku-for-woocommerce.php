<?php
/*
Plugin Name: SKU for WooCommerce
Plugin URI: http://coder.fm/items/sku-for-woocommerce-plugin/
Description: Add full SKU support to WooCommerce.
Version: 1.0.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Copyright: © 2015 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

// Check if Pro is active, if so then return
if ( in_array( 'sku-for-woocommerce-pro/sku-for-woocommerce-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

if ( ! class_exists( 'Alg_WooCommerce_SKU' ) ) :

/**
 * Main Alg_WooCommerce_SKU Class
 *
 * @class Alg_WooCommerce_SKU
 */

final class Alg_WooCommerce_SKU {

	/**
	 * @var Alg_WooCommerce_SKU The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WooCommerce_SKU Instance
	 *
	 * Ensures only one instance of Alg_WooCommerce_SKU is loaded or can be loaded.
	 *
	 * @static
	 * @return Alg_WooCommerce_SKU - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Alg_WooCommerce_SKU Constructor.
	 * @access public
	 */
	public function __construct() {

		// Include required files
		$this->includes();

		add_action( 'init', array( $this, 'init' ), 0 );

		// Settings
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @param mixed $links
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_sku' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>',
		), $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {

		$settings = array();
		$settings[] = require_once( 'includes/admin/class-wc-sku-settings-general.php' );
		if ( is_admin() ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						if ( isset ( $_GET['alg_sku_for_woocommerce_admin_options_reset'] ) ) {
							require_once( ABSPATH . 'wp-includes/pluggable.php' );
							if ( is_super_admin() ) {
								delete_option( $value['id'] );
							}
						}
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}

		require_once( 'includes/admin/class-wc-sku-tools-regenerator.php' );

		require_once( 'includes/class-wc-sku.php' );
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 */
	public function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-wc-settings-sku.php' );
		return $settings;
	}

	/**
	 * Init Alg_WooCommerce_SKU when WordPress initialises.
	 */
	public function init() {
		// Set up localisation
		load_plugin_textdomain( 'sku-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

/**
 * Returns the main instance of Alg_WooCommerce_SKU to prevent the need to use globals.
 *
 * @return Alg_WooCommerce_SKU
 */
if ( ! function_exists( 'AWCSGP' ) ) {
	function AWCSGP() {
		return Alg_WooCommerce_SKU::instance();
	}
}
AWCSGP();
