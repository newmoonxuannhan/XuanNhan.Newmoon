<?php
// Add custom Theme Functions here

add_action( 'wp_enqueue_scripts', 'my_custom_script_load' );
function my_custom_script_load(){
    wp_enqueue_style( 'my-custom-theme', get_stylesheet_directory_uri() . '/assets/css/famiprints.css', array(), '3.7', 'all' );
}




// ------------------------------- // 
function nhan_sticky_add_to_cart_before() {
    if ( ! is_product() || ! get_theme_mod( 'product_sticky_cart', 0 ) ) {
		return;
	}
	echo '<div class="sticky-add-to-cart">';
	echo '<div class="product-title-small>';
    return;
}

add_filter('woocommerce_before_add_to_cart_button','nhan_sticky_add_to_cart_before',-101);

// move Price single product
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );

// add words "Price" 
function nhan_add_price(){
    global $product;
    ?>
    <a style="font-size: 20px; color: black;"><?php echo 'Price:';?></a>
    <a style="font-size: 30px;"><?php echo $product->get_price_html();?></a>
    <?php
    return;
}
add_filter('woocommerce_before_add_to_cart_form','nhan_add_price',1);


//add words " Quantity
function nhan_add_quantity(){
    ?>
    <a style="font-size: 20px; color: black;"><?php echo 'Quantity:';?></a>
    <?php
    return ;
}
add_filter('woocommerce_before_add_to_cart_quantity','nhan_add_quantity');




