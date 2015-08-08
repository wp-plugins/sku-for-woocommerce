<?php
/**
 * SKU for WooCommerce - Regenerator Tool
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Tools_Regenerator' ) ) :

class Alg_WC_SKU_Tools_Regenerator {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id   = 'regenerator';
		$this->desc = __( 'Regenerator', 'sku-for-woocommerce' );

		add_filter( 'woocommerce_get_sections_alg_sku',              array( $this, 'settings_section' ) );
//		add_filter( 'woocommerce_get_settings_alg_sku_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );

		add_action( 'alg_sku_for_woocommerce_regenerator_tool', array( $this, 'create_sku_tool' ) );
	}

	/**
	 * create_sku_tool.
	 */
	function create_sku_tool() {

		echo '<h3>' . __( 'SKU Regenerator', 'sku-for-woocommerce' ) . '</h3>';

		if ( 'yes' === get_option( 'alg_sku_for_woocommerce_enabled' ) ) {

			do_action( 'alg_sku_for_woocommerce_before_regenerator_tool' );
			if ( isset( $_GET['alg_preview_sku'] ) ) {
				?><p><form method="post" action="">
					<input class="button-primary" type="submit" name="alg_set_sku" value="<?php _e( 'Set SKUs for all products', 'sku-for-woocommerce' ); ?>">
					<?php wp_nonce_field( 'woocommerce-settings' ); ?>
				</form></p><?php
			} else {
				echo '<p>' .
						'<a href="' . add_query_arg( 'alg_preview_sku', '' ) . '">' . __( 'Generate SKU preview for all products', 'sku-for-woocommerce' )  . '</a>' .
					'</p>';
			}
			do_action( 'alg_sku_for_woocommerce_after_regenerator_tool' );

		} else {

			echo '<em>' . __( 'To use regenerator, SKU for WooCommerce must be enabled in General settings tab.', 'sku-for-woocommerce' ) . '</em>';

		}
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}
}

endif;

return new Alg_WC_SKU_Tools_Regenerator();
