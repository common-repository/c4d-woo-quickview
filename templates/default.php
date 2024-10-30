<?php 
global $product;
?>
<div class="woocommerce">
<div class="product">
	<div class="c4d-woo-qv__gallery">
			<div class="c4d-woo-qv__gallery_inner">
				<div class="woocommerce-product-gallery">
					<div class="c4d-woo-qv-main-gallery">
					<?php
						if ( has_post_thumbnail() ) {
							$post_thumbnail_id = get_post_thumbnail_id( $product->get_id() );
							$image_title = esc_attr( get_the_title( $post_thumbnail_id ) );
							$image_link  = wp_get_attachment_url( $post_thumbnail_id );
							
							$attachment_ids = $product->get_gallery_image_ids();
							
							if ($post_thumbnail_id) {
								array_unshift($attachment_ids, (int)$post_thumbnail_id);	
							}
							
							$attachment_count = count( $attachment_ids );

							if ( $attachment_count > 0 ) {

								foreach ( $attachment_ids as $attachment_id ) {
									$html  .= '<div class="item-slide">';
									$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, array('width' => ' ', 'height' => ' ') );
							 		$html .= '</div>';
								}
								
								echo '<div class="c4d-woo-qv-gallery">' . $html . '</div>';
								if ($attachment_count > 1) {
									echo '<div class="c4d-woo-qv-gallery-nav">' . $html . '</div>';	
								}
							} 
						} 
					?>
					</div>
				</div>
			</div>
	</div>
	<div class="c4d-woo-qv__info">
		<div class="c4d-woo-qv__info_inner">
			<?php do_action( 'c4d_woo_qv_before_single_product_summary' ); ?>
			<?php woocommerce_show_product_sale_flash(); ?>
			<h2 class="title"><?php woocommerce_template_single_title();?></h2>
			<?php woocommerce_template_single_price(); ?>
			<div class="c4d-woo-qv__short-desc">
	 			<div class="desc">
	 				<?php woocommerce_template_single_excerpt(); ?>	
	 			</div>
	 		</div>
			<?php echo do_shortcode('[c4d-woo-cb]'); ?>
			<div class="c4d-woo-qv__cart">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>
		</div>
	</div>
</div>
</div>