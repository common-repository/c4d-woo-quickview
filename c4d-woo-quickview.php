<?php
/*
Plugin Name: C4D Woocommerce Quickview
Plugin URI: http://coffee4dev.com/
Description: Add quickview button for product.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-woo-qv
Version: 2.1.0
*/

define('C4DWQV_PLUGIN_URI', plugins_url('', __FILE__));

add_action( 'wp_enqueue_scripts', 'c4d_woo_qv_safely_add_stylesheet_to_frontsite');
add_action( 'wp_ajax_c4d_woo_qv_get_product', 'c4d_woo_qv_get_product');
add_action( 'wp_ajax_nopriv_c4d_woo_qv_get_product', 'c4d_woo_qv_get_product');
add_filter( 'plugin_row_meta', 'c4d_woo_qv_plugin_row_meta', 10, 2 );
add_shortcode( 'c4d-woo-qv', 'c4d_woo_qv_shortcode');

function c4d_woo_qv_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_woo_qv_safely_add_stylesheet_to_frontsite( $page ) {
	wp_enqueue_style( 'c4d-woo-qv-frontsite-style', C4DWQV_PLUGIN_URI.'/assets/default.css' );
	wp_enqueue_script( 'c4d-woo-qv-frontsite-plugin-js', C4DWQV_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style( 'fancybox', C4DWQV_PLUGIN_URI.'/libs/jquery.fancybox.min.css'); 
	wp_enqueue_script( 'fancybox', C4DWQV_PLUGIN_URI.'/libs/jquery.fancybox.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style( 'slick', C4DWQV_PLUGIN_URI.'/libs/slick/slick.css'); 
	wp_enqueue_style( 'slick-theme', C4DWQV_PLUGIN_URI.'/libs/slick/slick-theme.css'); 
	wp_enqueue_script( 'slick', C4DWQV_PLUGIN_URI.'/libs/slick/slick.js', array( 'jquery' ), false, true ); 
	wp_enqueue_script( 'zoom', site_url().'/wp-content/plugins/woocommerce/assets/js/zoom/jquery.zoom.min.js', array( 'jquery' ), false, true ); 
	wp_localize_script( 'jquery', 'c4d_woo_qv',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function c4d_woo_qv_get_product() {
	$productId = esc_sql($_GET['pid']);
	if ($productId) {
		$uid = esc_sql($_GET['uid']);
		
  	$params = array(
		 'p' => $productId, 
		 'post_type' => 'product'
		);

		$wc_query = new WP_Query($params); 

		if ($wc_query->have_posts()) {
			while ($wc_query->have_posts()){
				 	$p = $wc_query->the_post();
				 	echo c4d_woo_qv_content($uid);	
				 	wp_reset_postdata();
				 	die();
			}
		}
	}
	die();
}

function c4d_woo_qv_shortcode($atts) {
	global $product;
	$html = '';
	$default = array(
		'title' => 1,
		'price' => 1,
		'short_desc' => 1,
		'button_text' => esc_html__('Quickview', 'c4dwqv'),
		'button_icon' => '' 
	);

	$atts = shortcode_atts($default, $atts);
	$uid = 'c4d-woo-qv-'.uniqid();
	$html .= '<a rel="group" href="#'.esc_attr($uid).'" data-uid="'.esc_attr($uid).'" data-product_id="'.esc_attr($product->get_id()).'" class="c4d-woo-qv__link" href="'.esc_attr(get_permalink()).'"><span class="loading"><i class="fa fa-sun-o fa-spin"></i></span><span class="icon '.esc_attr($atts['button_icon']).'"></span>'.$atts['button_text'].'</a>';
	return $html;
}

function c4d_woo_qv_content($uid) {
	ob_start();
?>
	<div id="<?php echo esc_attr($uid); ?>" class="c4d-woo-qv">
		<?php c4d_woo_qv_content__inner(); ?>
	</div>
<?php	
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

function c4d_woo_qv_content__inner() {
	$file = get_template_directory(). '/c4d-woo-quickview/templates/default.php';
	if (file_exists($file)) {
		require $file;
	} else {
		require dirname(__FILE__). '/templates/default.php';
	}
}
