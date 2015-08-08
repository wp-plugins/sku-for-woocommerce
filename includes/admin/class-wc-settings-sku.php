<?php
/**
 * SKU for WooCommerce - Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_SKU' ) ) :

class Alg_WC_Settings_SKU extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	function __construct() {

		$this->id    = 'alg_sku';
		$this->label = __( 'SKU', 'sku-for-woocommerce' );

		parent::__construct();
	}

	/**
	 * get_settings.
	 */
	public function get_settings() {
		global $current_section;
		$the_current_section = ( '' != $current_section ) ? $current_section : 'general';
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $the_current_section, array() );
	}

	/**
	 * Output.
	 */
	public function output() {
		global $current_section, $hide_save_button;
		if ( 'regenerator' === $current_section ) {
			echo do_action( 'alg_sku_for_woocommerce_regenerator_tool' );
			$hide_save_button = true;
		} else {
			parent::output();
		}
	}
}

endif;

return new Alg_WC_Settings_SKU();
