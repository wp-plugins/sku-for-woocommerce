<?php
/**
 * SKU for WooCommerce
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU' ) ) :

class Alg_WC_SKU {

	/**
	 * Constructor.
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_sku_for_woocommerce_enabled' ) ) {

			add_action( 'wp_insert_post', array( $this, 'set_new_product_sku' ), PHP_INT_MAX, 3 );

			add_action( 'alg_sku_for_woocommerce_before_regenerator_tool', array( $this, 'regenerate_tool_set_sku' ),     PHP_INT_MAX );
			add_action( 'alg_sku_for_woocommerce_after_regenerator_tool',  array( $this, 'regenerate_tool_preview_sku' ), PHP_INT_MAX );
		}
	}

	/**
	 * regenerate_tool_set_sku.
	 */
	function regenerate_tool_set_sku() {
		if ( ! isset( $_POST['alg_set_sku'] ) ) return;
		$this->set_all_sku( false );
//		_e( 'SKUs generated and set successfully!', 'sku-for-woocommerce' );
	}

	/**
	 * regenerate_tool_preview_sku.
	 */
	function regenerate_tool_preview_sku() {
		if ( ! isset( $_GET['alg_preview_sku'] ) ) return;
		$preview_html = '';//'<h5>' . __( 'SKU Preview', 'sku-for-woocommerce' ) . '</h5>';
		$preview_html .= '<table class="widefat" style="width:50%; min-width: 300px;">';
		$preview_html .= '<tr>' .
							'<th>#</th>' .
							'<th>' . __( 'Product', 'sku-for-woocommerce' ) . '</th>' .
							'<th>' . __( 'SKU', 'sku-for-woocommerce' ) . '</th>' .
						'</tr>';
		ob_start();
		$this->set_all_sku( true );
		$preview_html .= ob_get_clean();
		$preview_html .= '</table>';
		echo $preview_html;
	}

	/**
	 * set_all_sku.
	 */
	function set_all_sku( $is_preview ) {
		$this->product_counter = 1;
		$limit = 1000;
		$offset = 0;
		while ( TRUE ) {
			$posts = new WP_Query( array(
				'posts_per_page' => $limit,
				'offset'         => $offset,
				'post_type'      => 'product',
				'post_status' 	 => 'any',
			));
			if ( ! $posts->have_posts() ) break;
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$this->set_sku_with_variable( $posts->post->ID, $is_preview );
			}
			$offset += $limit;
		}
	}

	/**
	 * set_new_product_sku.
	 */
	function set_new_product_sku( $post_ID, $post, $update ) {
		if ( 'product' === $post->post_type && false === $update ) {
			$this->set_sku_with_variable( $post_ID, false );
		}
	}

	/**
	 * set_sku_with_variable.
	 */
	function set_sku_with_variable( $product_id, $is_preview ) {

		$this->set_sku( $product_id, $product_id, '', $is_preview );

		// Handling variable products
		$variation_handling = 'as_variable';
		$product = wc_get_product( $product_id );
		if ( $product->is_type( 'variable' ) ) {

			$variations = $product->get_available_variations();

			if ( 'as_variable' === $variation_handling ) {
				foreach( $variations as $variation )
					$this->set_sku( $variation['variation_id'], $product_id, '', $is_preview );
			}
		}
	}

	/**
	 * set_sku.
	 */
	function set_sku( $product_id, $sku_number, $variation_suffix, $is_preview ) {

		$the_sku = sprintf( '%s%0' . get_option( 'alg_sku_for_woocommerce_minimum_number_length', 0 ) . 'd%s%s',
			get_option( 'alg_sku_for_woocommerce_prefix', '' ),
			$sku_number,
			get_option( 'alg_sku_for_woocommerce_suffix', '' ),
			$variation_suffix );

		if ( $is_preview ) {
			echo '<tr>' .
				'<td>' . $this->product_counter++ . '</td>' .
				'<td>' . get_the_title( $product_id ) . '</td>' .
				'<td>' . $the_sku . '</td>' .
			 '</tr>';
		}
		else
			update_post_meta( $product_id, '_' . 'sku', $the_sku );
	}
}

endif;

return new Alg_WC_SKU();
