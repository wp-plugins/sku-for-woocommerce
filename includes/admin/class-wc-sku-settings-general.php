<?php
/**
 * SKU for WooCommerce - General Section Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Settings_General' ) ) :

class Alg_WC_SKU_Settings_General {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id   = 'general';
		$this->desc = __( 'General', 'sku-for-woocommerce' );

		add_filter( 'woocommerce_get_sections_alg_sku',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_sku_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_settings.
	 */
	function get_settings() {

		$desc_pro = __( 'Get <a href="http://coder.fm/items/sku-for-woocommerce-plugin/">SKU for WooCommerce Pro</a> plugin to change value.', 'sku-for-woocommerce' );

		$settings = array(

			array(
				'title'     => __( 'SKU for WooCommerce Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_for_woocommerce_options',
			),

			array(
				'title'     => __( 'SKU for WooCommerce', 'sku-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable', 'sku-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Add full SKU support to WooCommerce.', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),

			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_for_woocommerce_options',
			),

			array(
				'title'    => __( 'SKU Format Options', 'sku-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => __( 'This section lets you set format for SKUs. All new products will automatically get SKU according to set format values. To change SKUs of existing products, use Regenerator tool.', 'sku-for-woocommerce' ),
				'id'       => 'alg_sku_for_woocommerce_format_options'
			),

			array(
				'title'    => __( 'Prefix', 'sku-for-woocommerce' ),
				'id'       => 'alg_sku_for_woocommerce_prefix',
				'default'  => '',
				'type'     => 'text',
			),

			array(
				'title'    => __( 'Minimum Number Length', 'sku-for-woocommerce' ),
				'id'       => 'alg_sku_for_woocommerce_minimum_number_length',
				'default'  => 0,
				'type'     => 'number',
			),

			array(
				'title'    => __( 'Suffix', 'sku-for-woocommerce' ),
				'id'       => 'alg_sku_for_woocommerce_suffix',
				'default'  => '',
				'type'     => 'text',
				/* 'desc'     => $desc_pro,
				'custom_attributes'
				           => array( 'readonly' => 'readonly' ), */
			),

			array(
				'title'    => __( 'Variable Products Variations', 'sku-for-woocommerce' ),
				'id'       => 'alg_sku_for_woocommerce_variations_handling',
				'default'  => 'as_variable',
				'type'     => 'select',
				'options'  => array(
					'as_variable'             => __( 'SKU same as parent\'s product', 'sku-for-woocommerce' ),
					'as_variation'            => __( 'Generate different SKU for each variation', 'sku-for-woocommerce' ),
					'as_variable_with_suffix' => __( 'SKU same as parent\'s product + variation letter suffix', 'sku-for-woocommerce' ),
				),
				'desc'     => $desc_pro,
				'custom_attributes'
				           => array( 'disabled' => 'disabled' ),
			),

			array(
				'type'     => 'sectionend',
				'id'       => 'alg_sku_for_woocommerce_format_options'
			),

			/* array(
				'title'     => __( 'Search by SKU Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_for_woocommerce_search_options',
			),

			array(
				'title'     => __( 'Search by SKU', 'sku-for-woocommerce' ),
				'desc'      => __( 'Add', 'sku-for-woocommerce' ),
				'desc_tip'  => __( 'Add product searching by SKU on frontend.', 'sku-for-woocommerce' ) . ' ' . $desc_pro,
				'id'        => 'alg_sku_for_woocommerce_search_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes'
				           => array( 'disabled' => 'disabled' ),
			),

			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_for_woocommerce_search_options',
			), */
		);

		return $settings;
	}

}

endif;

return new Alg_WC_SKU_Settings_General();
