<?php

if (!defined('ABSPATH'))
    exit;

class WCPA_Front_End extends WCPA_Order_Meta
{

    static $cart_error = array();
    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;
    public $products = false;
    public $dpproducts = false;
    public $hooked_field_tag = false;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;
    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;
    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;
    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;
    private $conversion_unit = false;
    /**
     * Check if price has to be display in cart and checkout
     * @var type
     * @var boolean
     * @access private
     * @since 3.4.2
     */
    private $show_price = false;


    function __construct($file = '', $version = '1.0.0')
    {
// Load frontend JS & CSS

        $this->_version = $version;
        $this->_token = WCPA_TOKEN;
        add_action('init', array($this, 'register_type_forms'));
        /**api
         * Check if WooCommerce is active
         * */
        if ($this->check_woocommerce_active()) {

            add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 10);
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 10);

            $this->file = $file;
            $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
            add_action('init', array($this, 'render_init_function')); // initiate render methods after init it will work in all cases
            add_action('woocommerce_before_add_to_cart_form', array($this, 'render_init_function'));// initiate render methods after loading $product,
            // $product might be needed to set hooks based on product loaded


            add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 3);
            add_filter('woocommerce_add_to_cart_validation', array($this, 'add_to_cart_validation'), 10, 3);


            add_filter('woocommerce_order_item_display_meta_value', array($this, 'display_meta_value'), 10, 3);


//            add_action('woocommerce_checkout_order_processed', array($this, 'checkout_order_processed'), 10, 3);
            add_action('woocommerce_checkout_update_order_meta', array($this, 'checkout_order_processed'), 1, 1);

            add_action('woocommerce_checkout_subscription_created', array($this, 'checkout_subscription_created'), 10, 1);//compatibility with subscription plugin
//            add_action('woocommerce_update_order_item', array($this, 'update_order_item'), 10, 3);
            //checkout_subscription_created

            add_filter('woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2);
            add_action('woocommerce_checkout_create_order_line_item', array($this, 'checkout_create_order_line_item'), 10, 4);
            add_filter('post_class', array($this, 'product_class'), 10, 3);

            add_filter('woocommerce_get_cart_item_from_session', array($this, 'cart_item_from_session'), 10, 1);


            add_action('woocommerce_cart_loaded_from_session', array($this, 'before_calculate_totals_1'), 10, 1);
            add_action('woocommerce_before_calculate_totals', array($this, 'before_calculate_totals_1'), 10, 1);

            add_action('woocommerce_cart_loaded_from_session', array($this, 'before_calculate_totals_2'), 999, 1);
            add_action('woocommerce_before_calculate_totals', array($this, 'before_calculate_totals_2'), 999, 1);

            add_action('woocommerce_get_price_html', array($this, 'get_price_html'), 10, 2);


            add_filter('woocommerce_get_discounted_price', array($this, 'get_discounted_price'), 10, 3);
//            add_filter('woocommerce_cart_subtotal', array($this, 'cart_subtotal'), 10, 3);
//            add_filter('woocommerce_cart_get_subtotal', array($this, 'get_subtotal'), 10, 1);

            add_filter('woocommerce_cart_item_subtotal', array($this, 'cart_item_subtotal'), 10, 2);
            add_filter('woocommerce_order_formatted_line_subtotal', array($this, 'order_formatted_line_subtotal'), 10, 2);

            add_filter('woocommerce_cart_calculate_fees', array($this, 'cart_calculate_fees'), 10, 2);

            add_filter('woocommerce_product_add_to_cart_url', array($this, 'add_to_cart_url'), 20, 2);
            add_filter('woocommerce_product_supports', array($this, 'product_supports'), 10, 3);
            add_filter('woocommerce_product_add_to_cart_text', array($this, 'add_to_cart_text'), 10, 2);

            add_filter('woocommerce_order_again_cart_item_data', array($this, 'order_again_cart_item_data'), 50, 3);

            add_filter('woocommerce_cart_item_class', array($this, 'cart_item_class'), 10, 3);


            add_action('woocommerce_order_item_get_formatted_meta_data', array($this, 'order_item_get_formatted_meta_data'), 10, 2);

            add_filter('woocommerce_display_item_meta', array($this, 'display_item_meta'), 10, 3);

            add_filter('woocommerce_available_variation', array($this, 'available_variation'), 10, 3);

            add_action('wp_ajax_wcpa_ajax_upload', array($this, 'wcpa_ajax_upload'));
            add_action('wp_ajax_nopriv_wcpa_ajax_upload', array($this, 'wcpa_ajax_upload'));

            add_action('woocommerce_single_product_summary', array($this, 'check_if_product_has_set_price'), 30);


            add_filter('pllwc_translate_cart_item', array($this, 'pllwc_translate_cart_item'), 10);


            add_action('rest_api_init', array($this, 'rest_api_init'));

            add_filter('woocommerce_email_format_string', array($this, 'email_format_string'), 2, 10);

            // other plugin compatibility codes
            add_filter('woo_discount_rules_has_price_override', array($this, 'woo_discount_rules_has_price_override'), 10, 2);
            // for https://wordpress.org/plugins/woo-discount-rules/


        }
    }

    public function check_woocommerce_active()
    {
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return true;
        }
        if (is_multisite()) {
            $plugins = get_site_option('active_sitewide_plugins');
            if (isset($plugins['woocommerce/woocommerce.php']))
                return true;
        }
        return false;
    }

    static function get_cart_error($product_id = false)
    {
        if (!$product_id) {
            return self::$cart_error;
        } else {
            return isset(self::$cart_error[$product_id]) ? self::$cart_error[$product_id] : false;
        }
    }

    static function set_cart_error($product_id, $status)
    {
        self::$cart_error[$product_id] = $status;
    }

    public static function instance($parent)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
        }
        return self::$_instance;
    }

    public function woo_discount_rules_has_price_override($status, $product)
    {
        if ($product) {
            $status = $this->is_wcpa_product($product->get_id());
        }
        return $status;
    }


    public function is_wcpa_product($product_id)
    {


        if (!$this->products) {
            $form = new WCPA_Form();
            $this->products = $form->get_wcpa_products();
        }

        foreach ($this->products as $pro) {

            return in_array($product_id, $pro);
        }

    }

    public function rest_api_init()
    {
        register_rest_field('product', 'wcpa_form_fields', array(
                'get_callback' => array($this, 'get_wcpa_form_fields_api'),
                'schema' => null,
            )
        );
    }

    public function get_wcpa_form_fields_api($object)
    {
        $pro_id = $object['id'];
        if ($this->is_wcpa_product($pro_id)) {
            $form = new WCPA_Form();
            $form->set_product($pro_id);
            $form->get_forms_by_product($pro_id);

            return $form->render($pro_id, true);
        } else {
            return null;
        }

    }

    public function rest_product_schema($properties)
    {

        if (isset($properties)) {
            //test
        }

        return $properties;

    }


    public function check_if_product_has_set_price()
    {
        global $product;
        if (!$product->is_purchasable() && ($product->is_type(['simple', 'variable']))) {
            $product_id = $product->get_id();
            // check if admin user
            if (current_user_can('manage_options') && $this->is_wcpa_product($product_id)) {
                echo '<p style="color:red">' . __('WCPA fields will show only if product has set price', 'woo-custom-product-addons') . '</p>';
            }
        }
    }

    public function render_init_function()
    {
        if ($this->hooked_field_tag !== false) {
            remove_action($this->hooked_field_tag[0], array($this, 'before_add_to_cart_button'), $this->hooked_field_tag[1]);
        }
        $this->hooked_field_tag = get_wcpa_display_hook("fields");
        add_action($this->hooked_field_tag[0], array($this, 'before_add_to_cart_button'), $this->hooked_field_tag[1]);
    }

    /**
     *    Create post type forms
     */
    public function register_type_forms()
    {

        $post_type = WCPA_POST_TYPE;
        $labels = array(
            'name' => __('Product Form', 'wcpa-text-domain'),
            'singular_name' => __('Product Form', 'wcpa-text-domain'),
            'name_admin_bar' => 'WCPA_Form',
            'add_new' => _x('Add New Product Form', $post_type, 'wcpa-text-domain'),
            'add_new_item' => sprintf(__('Add New %s', 'wcpa-text-domain'), 'Form'),
            'edit_item' => sprintf(__('Edit %s', 'wcpa-text-domain'), 'Form'),
            'new_item' => sprintf(__('New %s', 'wcpa-text-domain'), 'Form'),
            'all_items' => sprintf(__('Custom Product Options', 'wcpa-text-domain'), 'Form'),
            'view_item' => sprintf(__('View %s', 'wcpa-text-domain'), 'Form'),
            'search_items' => sprintf(__('Search %s', 'wcpa-text-domain'), 'Form'),
            'not_found' => sprintf(__('No %s Found', 'wcpa-text-domain'), 'Form'),
            'not_found_in_trash' => sprintf(__('No %s Found In Trash', 'wcpa-text-domain'), 'Form'),
            'parent_item_colon' => sprintf(__('Parent %s'), 'Form'),
            'menu_name' => 'Custom Product Options'
        );

        $args = array(
            'labels' => apply_filters($post_type . '_labels', $labels),
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=product',
            'show_in_nav_menus' => false,
            'query_var' => false,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'rest_base' => $post_type,
            'hierarchical' => false,
            'show_in_rest' => false,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports' => array('title'),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-post',
            'taxonomies' => array()
        );

        register_post_type($post_type, apply_filters($post_type . '_register_args', $args, $post_type));

        if (is_admin()) {
            register_taxonomy_for_object_type('product_cat', $post_type);
        }
    }

    public function available_variation($avail_variations, $product_variable, $variation)
    {
        return array_merge($avail_variations, [
            'stock_status' => $variation->get_stock_status('edit'),
            'stock_quantity' => $variation->get_stock_quantity('edit')
        ]);
    }

    public function get_price_html($price, $product)
    {
        $label = wcpa_get_option('price_prefix_label', '');

        if (trim($label) !== '') {
            if ($this->is_wcpa_product($product->get_id())) {
                $price = $label . ' ' . $price;
            }
        }
        return $price;
    }

    public function product_class($classes = array(), $class = false, $product_id = false)
    {
        if ($product_id && $this->is_wcpa_product($product_id)) {
            $classes[] = 'wcpa_has_options';
        }

        return $classes;
    }

    public function wcpa_ajax_upload()
    {
        $response = [['status' => false, 'message' => '']];
        if (isset($_POST['wcpa_file_details']) && isset($_FILES['wcpa_file'])) {
            $form = new WCPA_Form();
            $details = stripslashes($_POST['wcpa_file_details']);
            $details = json_decode($details);

            if (isset($details->product_id) && is_numeric($details->product_id) && isset($details->element_id)) {
                $response = $form->ajax_upload($details);
            }
        }
        wp_send_json($response);
        die(0);
    }

    public function order_again_cart_item_data($cart_item_data, $item, $order)
    {

        $form = new WCPA_Form();
        $data = $form->order_again_item_data($item);
        $cart_item_data[WCPA_CART_ITEM_KEY] = $data;
        remove_filter('woocommerce_add_to_cart_validation', array($this, 'add_to_cart_validation'));

        return $cart_item_data;
    }

    public function order_item_get_formatted_meta_data($formatted_meta, $item)
    {


        if (!wcpa_get_option('show_meta_in_order', true)) {
            return parent::order_item_get_formatted_meta_data($formatted_meta, $item);
        } else {
            return $formatted_meta;
        }
    }

    public function cart_calculate_fees($cart_obj)
    {
        if (is_admin() && !defined('DOING_AJAX'))
            return;
        $cart_contents = $cart_obj->get_cart();
        global $woocommerce;


        $fees = array();

        foreach ($cart_contents as $key => $value) {
            if (isset($value[WCPA_CART_ITEM_KEY]) && is_array($value[WCPA_CART_ITEM_KEY])) {

                foreach ($value[WCPA_CART_ITEM_KEY] as $_key => $v) {
                    $price = 0.0;

                    if (isset($v['price']) && (!isset($v['is_show_price']) || $v['is_show_price'] === false)) {
                        if ((isset($v['form_data']->form_rules['pric_use_as_fee']) && $v['form_data']->form_rules['pric_use_as_fee'])) {
                            if (is_array($v['price'])) {
                                foreach ($v['price'] as $p) {
                                    $price += $p;
                                }
                            } else if ($v['price']) {
                                $price += $v['price'];
                            }
                            if (!isset($fees[$v['form_data']->form_id])) {
                                $fees[$v['form_data']->form_id] = ['price' => 0.0, 'label' => '', 'key' => $key];
                            }

                            if (wcpa_get_option('count_fee_once_in_a_order', false)) {
                                $fees[$v['form_data']->form_id]['price'] = $price;
                            } else {
                                $fees[$v['form_data']->form_id]['price'] += $price;
                            }


                            $fees[$v['form_data']->form_id]['label'] = $v['form_data']->form_rules['fee_label'];
                        } else if ((isset($v['is_fee']) && $v['is_fee'] === true)) {


                            if (is_array($v['price'])) {
                                foreach ($v['price'] as $i => $p) {
                                    $elem_id = sanitize_key($v['form_data']->form_id . '_' . $v['form_data']->elementId . '_' . $i);
                                    if (!isset($fees[$elem_id])) {
                                        $fees[$elem_id] = ['price' => 0.0, 'label' => '', 'key' => $key];
                                    }

                                    if (wcpa_get_option('count_fee_once_in_a_order', false)) {
                                        $fees[$elem_id]['price'] = $p;
                                    } else {
                                        $fees[$elem_id]['price'] += $p;
                                    }

                                    $fees[$elem_id]['label'] = $this->get_fee_label($v, $i);

                                    // $price += $p;
                                }
                            } else if ($v['price']) {
                                $price += $v['price'];


                                $elem_id = sanitize_key($v['form_data']->form_id . '_' . $v['form_data']->elementId);
                                if (!isset($fees[$elem_id])) {
                                    $fees[$elem_id] = ['price' => 0.0, 'label' => '', 'key' => $key];
                                }

                                if (wcpa_get_option('count_fee_once_in_a_order', false)) {
                                    $fees[$elem_id]['price'] = $price;
                                } else {
                                    $fees[$elem_id]['price'] += $price;
                                }
                                $fees[$elem_id]['label'] = $this->get_fee_label($v);
                            }
                        }
                    }
                }
            }
        }
        $discounts = array();
        if (wcpa_get_option('wcpa_apply_coupon_to_fee', false)) {
            $totals = $cart_obj->get_totals();
            $sub_total = $totals['subtotal'];
            foreach ($cart_obj->get_coupon_discount_totals() as $coupon => $value) {
                $coupon_obj = new WC_Coupon($coupon);
                if ($coupon_obj) {
                    if ($coupon_obj->is_type('percent')) {
                        $discounts[] = ['type' => 'percent', 'value' => $coupon_obj->get_amount('edit')];

                    } else if ($coupon_obj->is_type('fixed_cart')) {
                        $amount = $coupon_obj->get_amount('edit') - $value;
                        $discounts[] = ['type' => 'fixed', 'value' => $amount];
                    }
                }
            }

        }
//        ‌‌$cart_obj->get_coupon_discount_totals()
        $fee_total = 0;
        // sum fee if labels are same
        $added_fees = array();
        foreach ($fees as $fee) {
            if (isset($fee['price'])) {
//                $fee_total += $fee['price'];


                $price = $fee['price'];
                $fee_id = sanitize_title($fee['label']);
                if (isset($added_fees[$fee_id])) {
                    $fee['label'] = $fee['label'] . '(' . ($added_fees[$fee_id] + 1) . ')';
                    $added_fees[$fee_id] += 1;
                } else {
                    $added_fees[$fee_id] = 1;
                }


                $tax_status = $tax_status = $cart_contents[$fee['key']]['data']->is_taxable();
                $tax_class = $cart_contents[$fee['key']]['data']->get_tax_class();
                $consider_tax = wcpa_get_option('consider_product_tax_conf', true);
                $mc = new WCPA_MC();
                if ($consider_tax == false) {

                    $_price = $mc->mayBeConvert($fee['price']);
                    $woocommerce->cart->add_fee($fee['label'], $_price);
                } else if ($tax_status == true && wc_prices_include_tax()) {
                    $base_tax_rates = WC_Tax::get_base_tax_rates($tax_class);
                    $taxes = WC_Tax::calc_tax($fee['price'], $base_tax_rates, true);
                    $_price = $mc->mayBeConvert(($fee['price'] - array_sum($taxes)));
                    $woocommerce->cart->add_fee($fee['label'], $_price, $tax_status, $tax_class);
                } else {
                    $_price = $mc->mayBeConvert($fee['price']);
                    $woocommerce->cart->add_fee($fee['label'], $_price, $tax_status, $tax_class);
                }
// apply coupon discount on the fee amount
                if (!empty($discounts)) {
                    $discount = 0;
                    $total_discount = 0;
                    $price = wc_add_number_precision($price);
                    $discounted_price = $price - $discount;
                    foreach ($discounts as $coupon_amount) {
                        $price_to_discount = ('yes' === get_option('woocommerce_calc_discounts_sequentially', 'no')) ? $discounted_price : $price;
                        if ('percent' == $coupon_amount['type']) {
                            $discount = floor($price_to_discount * ($coupon_amount['value'] / 100));
                        } else {
                            $discount = wc_add_number_precision($coupon_amount['value']);
                        }

                        $discount = min($price_to_discount, $discount);
                        $total_discount += $discount;
                        $discounted_price = $discounted_price - $discount;
                    }
                    $total_discount = wc_remove_number_precision($total_discount);
                    //apply coupon discount on the fee amount
                    $woocommerce->cart->add_fee($fee['label'] . __(' - Discount', 'wcpa-text-domain'), -$total_discount, false, '');
                }


            }
        }

//        $cart_obj->set_subtotal($cart_obj->get_subtotal()+$fee_total);
    }

//    public function cart_subtotal($cart_subtotal, $compound, $cart) {
//        if ($compound) {
//            $cart_subtotal = wc_price($cart->get_cart_contents_total() + $cart->get_shipping_total() + $cart->get_taxes_total(false, false));
//        } elseif ($cart->display_prices_including_tax()) {
//            $cart_subtotal = wc_price($cart->get_subtotal() + $cart->get_subtotal_tax() + $cart->get_fee_total() + $cart->get_fee_tax());
//
//            if ($cart->get_subtotal_tax() > 0 && !wc_prices_include_tax()) {
//                $cart_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
//            }
//        } else {
//            $cart_subtotal = wc_price($cart->get_subtotal() + $cart->get_fee_total());
//
//            if ($cart->get_subtotal_tax() > 0 && wc_prices_include_tax()) {
//                $cart_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
//            }
//        }
//
//
//        return $cart_subtotal;
//    }

    public function get_fee_label($v, $i = false)
    {

        if (is_array($v['value']) && $i !== false) {
            if (isset($v['form_data']->fee_label) && !empty($v['form_data']->fee_label)) {
                $fee_label = $v['form_data']->fee_label;
                preg_match("/{(.*)}/", $fee_label, $matches);
                if ($matches && count($matches)) {
                    $fee_label = str_replace(['{field_label}', '{option_label}', '{option_value}'],
                        [$v['label'], isset($v['value'][$i]['label']) ? $v['value'][$i]['label'] : '', isset($v['value'][$i]['value']) ? $v['value'][$i]['value'] : ''],
                        $fee_label);
                    return $fee_label;
                } else {
                    return $fee_label;
                }
                // return $v['form_data']->fee_label;
            } else {
                return isset($v['value'][$i]['label']) ? $v['value'][$i]['label'] : (isset($v['value'][$i]['value']) ? $v['value'][$i]['value'] : '');
                //return ($v['label'] == WCPA_EMPTY_LABEL) ? strip_tags($v['value']) : $v['label'];
            }
        } else {
            if (isset($v['form_data']->fee_label) && !empty($v['form_data']->fee_label)) {

                return $v['form_data']->fee_label;
            } else {

                return ($v['label'] == WCPA_EMPTY_LABEL) ? strip_tags($v['value']) : $v['label'];
            }
        }

    }

    /*
      Sums all fields price, exluding fee and pric_cal_option_once
     *      */

    public function order_formatted_line_subtotal($subtotal, $item)
    {
        $meta_data = $item->get_meta(WCPA_ORDER_META_KEY);

        if ($meta_data && is_array($meta_data)) {
            $fees = array();

            foreach ($meta_data as $v) {
                $price = 0.0;
                if (isset($v['price'])) {
                    if ((isset($v['form_data']->form_rules['pric_cal_option_once']) && $v['form_data']->form_rules['pric_cal_option_once'] === true) ||
                        (isset($v['form_data']->form_rules['pric_use_as_fee']) && $v['form_data']->form_rules['pric_use_as_fee'])) {
                        if (is_array($v['price'])) {
                            foreach ($v['price'] as $p) {
                                $price += $p;
                            }
                        } else if ($v['price']) {
                            $price += $v['price'];
                        }
                        if (!isset($fees[$v['form_data']->form_id])) {
                            $fees[$v['form_data']->form_id] = ['price' => 0.0, 'label' => ''];
                        }


                        $fees[$v['form_data']->form_id]['price'] += $price;
                        $fees[$v['form_data']->form_id]['label'] = $v['form_data']->form_rules['fee_label'];
                    } else if ((isset($v['is_fee']) && $v['is_fee'] === true)) {
                        if (is_array($v['price'])) {
                            foreach ($v['price'] as $p) {
                                $price += $p;
                            }
                        } else if ($v['price']) {
                            $price += $v['price'];
                        }

                        $elem_id = sanitize_key($v['form_data']->form_id . '_' . $v['form_data']->elementId);
                        if (!isset($fees[$elem_id])) {
                            $fees[$elem_id] = ['price' => 0.0, 'label' => ''];
                        }
                        $fees[$elem_id]['price'] += $price;
                        $fees[$elem_id]['label'] = $this->get_fee_label($v);
                    }
                }
            }

            $items = '';
            if (!empty($fees)) {
                foreach ($fees as $fee) {
                    if ($fee['price'] > 0) {
                        $price = $fee['price']; //wcpa_get_price_cart($item->get_product(), $fee['price']);

                        $items .= '<br>' . wc_price($price) . '<small class="woocommerce-Price-taxLabel tax_label">(' . $fee['label'] . ')</small>';
                    }
                }
                $subtotal .= $items;
            }
        }
        return $subtotal;
    }

    public function cart_item_subtotal($total, $cart = false)
    {

        if (!wcpa_get_option('show_fee_in_line_subtotal', true)) {
            return $total;
        }
        $fees = array();
        if ((isset($cart['wcpa_cart_rules']['pric_use_as_fee']) && $cart['wcpa_cart_rules']['pric_use_as_fee'] == true) ||
            (isset($cart['wcpa_cart_rules']['pric_cal_option_once']) && $cart['wcpa_cart_rules']['pric_cal_option_once'] == true)
        ) {

            if (isset($cart[WCPA_CART_ITEM_KEY]) && is_array($cart[WCPA_CART_ITEM_KEY])) {

                foreach ($cart[WCPA_CART_ITEM_KEY] as $v) {

                    $price = 0.0;
                    if (isset($v['price'])) {
                        if ((isset($v['form_data']->form_rules['pric_cal_option_once']) && $v['form_data']->form_rules['pric_cal_option_once'] === true) ||
                            (isset($v['form_data']->form_rules['pric_use_as_fee']) && $v['form_data']->form_rules['pric_use_as_fee'])
                        ) {
                            if (is_array($v['price'])) {
                                foreach ($v['price'] as $p) {
                                    $price += $p;
                                }
                            } else if ($v['price']) {
                                $price += $v['price'];
                            }
                            if (!isset($fees[$v['form_data']->form_id])) {
                                $fees[$v['form_data']->form_id] = ['price' => 0.0, 'label' => ''];
                            }


                            $fees[$v['form_data']->form_id]['price'] += $price;
                            $fees[$v['form_data']->form_id]['label'] = $v['form_data']->form_rules['fee_label'];
                        } else if ((isset($v['is_fee']) && $v['is_fee'] === true)) {
                            if (is_array($v['price'])) {
                                foreach ($v['price'] as $i => $p) {
//                                    $price += $p;


                                    $elem_id = sanitize_key($v['form_data']->form_id . '_' . $v['form_data']->elementId . '_' . $i);
                                    if (!isset($fees[$elem_id])) {
                                        $fees[$elem_id] = ['price' => 0.0, 'label' => ''];
                                    }

                                    if (wcpa_get_option('count_fee_once_in_a_order', false)) {
                                        $fees[$elem_id]['price'] = $p;
                                    } else {
                                        $fees[$elem_id]['price'] += $p;
                                    }

                                    $fees[$elem_id]['label'] = $this->get_fee_label($v, $i);


                                }
                            } else if ($v['price']) {
                                $price += $v['price'];
                                $elem_id = sanitize_key($v['form_data']->form_id . '_' . $v['form_data']->elementId);
                                if (!isset($fees[$elem_id])) {
                                    $fees[$elem_id] = ['price' => 0.0, 'label' => ''];
                                }
                                $fees[$elem_id]['price'] += $price;
                                $fees[$elem_id]['label'] = $this->get_fee_label($v);
                            }


                        }
                    }
                }
            }
            $items = '';

            foreach ($fees as $fee) {
                if ($fee['price'] > 0) {
                    $price = wcpa_get_price_cart($cart['data'], $fee['price']);
                    $items .= '<br>' . wcpa_price($price * $this->get_con_unit($cart['data'])) . '<small class="woocommerce-Price-taxLabel tax_label">(' . $fee['label'] . ')</small>';
                }
            }


            $total .= $items;
        }

        return $total;
    }

    public function get_con_unit($product, $toMultiplyWithShopPrice = true)
    {
        if ($this->conversion_unit === false) {
            $mc = new WCPA_MC();
            $this->conversion_unit = $mc->get_con_unit($product, false, false, $toMultiplyWithShopPrice);
            return $this->conversion_unit;
        } else {
            return $this->conversion_unit;
        }
    }

//    public function get_con_unit($product)
//    {
//        if ($this->conversion_unit === false) {
//            $view_price = $product->get_price('view');
//            $edit_price = $product->get_price('edit');
//
//            if ($view_price && $edit_price) {
//                $this->conversion_unit = $view_price / $edit_price;
//            } else {
//                $this->conversion_unit = 1;
//            }
//            if ($this->conversion_unit === 1) {
//                $this->conversion_unit = apply_filters('wcml_raw_price_amount', 1);
//            }
//
//            if ($this->conversion_unit == 1) {
//                $from_currency = get_option('woocommerce_currency');
//                $to_currency = get_woocommerce_currency();
//                $converted_amount = apply_filters('wc_aelia_cs_convert', 1, $from_currency, $to_currency);
//                $this->conversion_unit = $converted_amount;
//            }
//            return $this->conversion_unit;
//        } else {
//            return $this->conversion_unit;
//        }
//    }


    public function get_discounted_price($total, $object, $cart)
    {
        if (isset($object['wcpa_cart_rules']['pric_cal_option_once']) && $object['wcpa_cart_rules']['pric_cal_option_once'] === true) {
            $price = 0.0;

            if (isset($object[WCPA_CART_ITEM_KEY]) && is_array($object[WCPA_CART_ITEM_KEY])) {

                foreach ($object[WCPA_CART_ITEM_KEY] as $v) {
                    if (isset($v['price']) &&
                        (isset($v['form_data']->form_rules['pric_cal_option_once']) && $v['form_data']->form_rules['pric_cal_option_once'] === true) &&
                        (!isset($v['form_data']->form_rules['pric_use_as_fee']) || $v['form_data']->form_rules['pric_use_as_fee'] === false) &&
                        (!isset($v['is_fee']) || $v['is_fee'] === false) &&
                        (!isset($v['is_show_price']) || $v['is_show_price'] === false)
                    ) {

                        if (isset($v['price']) && is_array($v['price'])) {
                            foreach ($v['price'] as $p) {
                                $price += $p;
                            }
                        } else if (isset($v['price']) && $v['price']) {
                            $price += $v['price'];
                        }
                    }
                }
            }
            $total += $price;

            //  $cart->set_subtotal($total);
        }


        return $total;
    }

    public function before_calculate_totals_1($cart_obj)
    {
        $this->before_calculate_totals($cart_obj, 'start');
    }

    public function before_calculate_totals($cart_obj, $priority = 'start')
    {
        if (is_admin() && !defined('DOING_AJAX'))
            return;
        if (method_exists($cart_obj, 'get_cart')) {
            $cart_contents = $cart_obj->get_cart();
        } else {
            $cart_contents = $cart_obj->cart_contents;
        }


        foreach ($cart_contents as $key => $value) {
            $price = 0.0;
            if (isset($cart_contents[$key]['wcpa_options_price_' . $priority])) {
                continue;
            }
            if (isset($value[WCPA_CART_ITEM_KEY]) && is_array($value[WCPA_CART_ITEM_KEY])) {
                foreach ($value[WCPA_CART_ITEM_KEY] as $_key => $v) {
                    if (isset($v['form_data']->form_rules['exclude_from_discount']) && $v['form_data']->form_rules['exclude_from_discount']) {
                        if ($priority == 'start') {
                            continue;
                        }
                    } else {
                        if ($priority == 'end') {
                            continue;
                        }
                    }
                    if (isset($v['quantity_depend_label']) && $v['quantity_depend_label'] !== false && $v['quantity_depend_label'] != '') {
                        $out = $v['quantity_depend_label'];
                        if (preg_match_all('/\#\=(.+?)\=\#/', $v['quantity_depend_label'], $matches) >= 1) {
                            foreach ($matches[1] as $k => $match) {
                                $formula = str_replace(['{quantity}'], [$value['quantity']], $match);
                                try {
                                    $res = eval('return ' . $formula . ';');
                                    if (is_numeric($res) && $res % 1 != 0) {
                                        $res = number_format($res, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
                                    }
                                    $out = str_replace($matches[0][$k], $res, $out);

                                } catch (Throwable $t) {
                                    $out = str_replace($matches[0][$k], $formula, $out);
                                }
                            }
                        }
                        $cart_contents[$key][WCPA_CART_ITEM_KEY][$_key]['value'] = $out;

                    }
                    if (isset($v['quantity_depend']) && $v['quantity_depend'] !== false) {

                        if (is_array($v['quantity_depend'])) {
                            $_price = array();
                            foreach ($v['quantity_depend'] as $k => $formula) {
                                $formula = str_replace(['{quantity}'], [$value['quantity']], $formula);
                                try {
                                    $elem_price = eval('return ' . $formula . ';');
                                } catch (Throwable $t) {
                                    $elem_price = 0;
                                }
                                $_price[$k] = $elem_price;
                            }
                        } else {
                            $formula = str_replace(['{quantity}'], [$value['quantity']], $v['quantity_depend']);
                            try {
                                $elem_price = eval('return ' . $formula . ';');
                            } catch (Throwable $t) {
                                $elem_price = 0;
                            }
                            $_price = $elem_price;
                        }

                        $cart_contents[$key][WCPA_CART_ITEM_KEY][$_key]['price'] = $v['price'] = $_price;
                    }


                    if (method_exists($cart_obj, 'set_cart_contents')) {
                        $cart_obj->set_cart_contents($cart_contents);
                    } else {
                        $cart_obj->cart_contents = $cart_contents; // for add to quote plugin
                    }


                    if ((isset($v['form_data']->form_rules['pric_cal_option_once']) &&
                            $v['form_data']->form_rules['pric_cal_option_once']) ||
                        (isset($v['form_data']->form_rules['pric_use_as_fee']) &&
                            $v['form_data']->form_rules['pric_use_as_fee'])
                    ) {
                        continue;
                    }


                    if ((!isset($v['is_fee']) || $v['is_fee'] === false) && (!isset($v['is_show_price']) || $v['is_show_price'] === false)) {
                        if (isset($v['price']) && is_array($v['price'])) {
                            foreach ($v['price'] as $p) {
                                $price += $p;
                            }
                        } else if (isset($v['price']) && $v['price']) {
                            $price += $v['price'];
                        }
                    }
                }


                $cart_contents[$key]['wcpa_options_price_' . $priority] = $price;
                if (method_exists($cart_obj, 'set_cart_contents')) {
                    $cart_obj->set_cart_contents($cart_contents);
                } else {
                    $cart_obj->cart_contents = $cart_contents; // for add to quote plugin
                }


            }
            $mc = new WCPA_MC();
            $price = $mc->mayBeConvert($price);

            if (isset($value['wcpa_cart_rules']['pric_overide_base_price']) && $value['wcpa_cart_rules']['pric_overide_base_price'] === true) {
                $total_price = max($price, $value['data']->get_price('edit'));
            } else if (isset($value['wcpa_cart_rules']['pric_overide_base_price_if_gt_zero']) && $value['wcpa_cart_rules']['pric_overide_base_price_if_gt_zero'] === true && $price > 0) {
                $total_price = $price;
            } else {

                $total_price = $price + floatval($value['data']->get_price('edit'));

            }
            if ($total_price < 0) {
                $total_price = 0; // can't be the price be negative at any case
            }

            $value['data']->set_price(round($total_price, wc_get_price_decimals()));

            if (isset($value['wcpa_cart_rules']['thumb_image']) && is_numeric($value['wcpa_cart_rules']['thumb_image'])) {
                $value['data']->set_image_id($value['wcpa_cart_rules']['thumb_image']);
            }


        }

        // remove_action('woocommerce_before_calculate_totals', array($this, 'before_calculate_totals'), 10);
        //remove_action('woocommerce_cart_loaded_from_session', array($this, 'before_calculate_totals'), 10);
    }

    public function pllwc_translate_cart_item($item)
    {

        if (isset($item['wcpa_options_price_start'])) {
            unset($item['wcpa_options_price_start']);
        }
        return $item;
    }

    public function before_calculate_totals_2($cart_obj)
    {
        $this->before_calculate_totals($cart_obj, 'end');
    }

    public function cart_item_from_session($session_data)
    {
        if (isset($session_data['wcpa_options_price'])) {
            unset($session_data['wcpa_options_price']);
        }
        if (isset($session_data['wcpa_options_price_start'])) {
            unset($session_data['wcpa_options_price_start']);
        }
        if (isset($session_data['wcpa_options_price_end'])) {
            unset($session_data['wcpa_options_price_end']);
        }
        return $session_data;
    }

    public function add_to_cart_text($text, $product)
    {
        $product_id = $product->get_id();

        if ($this->is_wcpa_product($product_id) && !$this->is_direct_purchasable_product($product_id)) {
            $text = wcpa_get_option('add_to_cart_text', 'Select options', true);
        }

        return $text;
    }

    public function is_direct_purchasable_product($product_id)
    {
        if (!$this->products) {
            $form = new WCPA_Form();
            $this->products = $form->get_wcpa_products();
        }

        return in_array($product_id, $this->products['direct_purchasable']);
    }

    /**
     * Remove ajax add to cart feature for wcpa products.
     * @param $support
     * @param $feature
     * @param $product
     * @return bool
     */
    public function product_supports($support, $feature, $product)
    {
        $product_id = $product->get_id();
        if ($feature == 'ajax_add_to_cart' && $this->is_wcpa_product($product_id) && !$this->is_direct_purchasable_product($product_id)) {
            $support = FALSE;
        }
        return $support;
    }

    public function add_to_cart_url($url, $product)
    {

        $product_id = $product->get_id();
        if ($this->is_wcpa_product($product_id) && !$this->is_direct_purchasable_product($product_id) && !$product->is_type('external')) {
            return $product->get_permalink();
        } else {
            return $url;
        }
    }

    public function add_cart_item_data($cart_item_data, $product_id, $variation_id)
    {


        $form = new WCPA_Form();


        $data = $form->submited_data($product_id, $variation_id);
        if (!isset($cart_item_data[WCPA_CART_ITEM_KEY])) { // if already set  by order again option
            $cart_item_data[WCPA_CART_ITEM_KEY] = $data;
            if (isset($form->settings['pric_overide_base_price'])) {
                $cart_item_data['wcpa_cart_rules'] = [
                    'pric_overide_base_price' => $form->settings['pric_overide_base_price'],
                    'pric_overide_base_price_if_gt_zero' => $form->settings['pric_overide_base_price_if_gt_zero'],
                    'pric_cal_option_once' => $form->settings['pric_cal_option_once'],
                    'pric_use_as_fee' => $form->settings['pric_use_as_fee'],
                    'bind_quantity' => $form->settings['bind_quantity'],
                    'thumb_image' => $form->settings['thumb_image']
                ];
            }
        }


        return $cart_item_data;
    }

    public function add_to_cart_validation($passed, $product_id)
    {

        $form = new WCPA_Form();

        $passed = $form->validate_form_data($product_id);

        return $passed;
    }

    public function get_item_data($item_data, $cart_item)
    {
        if (!is_array($item_data)) {
            $item_data = array();
        }

        $_product = $cart_item['data'];
        if (((wcpa_get_option('show_meta_in_cart', true) && !is_checkout()) || (is_checkout() && wcpa_get_option('show_meta_in_checkout', true))) && isset($cart_item[WCPA_CART_ITEM_KEY]) && is_array($cart_item[WCPA_CART_ITEM_KEY]) && !empty($cart_item[WCPA_CART_ITEM_KEY])) {
            foreach ($cart_item[WCPA_CART_ITEM_KEY] as $v) {


                if (isset($v['form_data']->hideFieldIn_cart) && $v['form_data']->hideFieldIn_cart) {
                    continue;
                }

                if (in_array($v['type'], array('header', 'paragraph', 'hidden')) && (!isset($v['form_data']->show_in_checkout) || $v['form_data']->show_in_checkout == false)) {
                    continue;
                }
                if (!in_array($v['type'], array('separator'))) {

                    $item_data[] = array(
                        'name' => $v['name'],
                        'key' => $v['label'],
                        'value' => $this->cart_display($v, $_product, $cart_item['quantity'])
                    );
                }
            }
        }

        return $item_data;
    }

    public function cart_display($v, $product, $quantity = 1)
    {
        $display = '';
//        if ($this->conversion_unit === false) {
//            $view_price = $product->get_price('view');
//            $edit_price = $product->get_price('edit');
//            if ($view_price && $edit_price) {
//                $this->conversion_unit = $view_price / $edit_price;
//            } else {
//                $this->conversion_unit = 1;
//            }
//        }
        $hide_zero = wcpa_get_option('cart_hide_price_zero', false);
        if (((wcpa_get_option('show_price_in_cart', true) && !is_checkout()) || (is_checkout() && wcpa_get_option('show_price_in_checkout', true)))) {
            $this->show_price = true;
        } else {
            $this->show_price = false;
        }

        $field_price_multiplier = 1;
        if (wcpa_get_option('show_field_price_x_quantity', false)) {
            $field_price_multiplier = $quantity;
        }

        if (
            (isset($v['form_data']->form_rules['pric_cal_option_once'])
                && $v['form_data']->form_rules['pric_cal_option_once'] === true)
            || (isset($v['form_data']->form_rules['pric_use_as_fee']) && $v['form_data']->form_rules['pric_use_as_fee'] === true) ||
            (isset($v['is_fee']) && $v['is_fee'] === true)
        ) {
            $field_price_multiplier = 1;
        }
        $field_price_multiplier = $field_price_multiplier * $this->get_con_unit($product);

        switch ($v['type']) {
            case 'text':
            case 'date':
            case 'number':
            case 'date':
            case 'time':
            case 'datetime-local':
            case 'header':
            case 'datetime-local':
                $display = $v['value'];
                if ($v['price'] && $this->show_price && (!$hide_zero || $v['price'] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price']);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';

                }

                break;
            case 'paragraph':
                $display = $this->cart_display_pargraph($v);
                if ($v['price'] && $this->show_price && (!$hide_zero || $v['price'] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price']);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                }

                break;
            case 'textarea':
                $display = $this->cart_display_textarea($v['value']);
                if ($v['price'] && $this->show_price && (!$hide_zero || $v['price'] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price']);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                }

                break;
            case 'select':
            case 'checkbox-group':
            case 'radio-group':
                $display = $this->cart_display_array($v, $product, $field_price_multiplier);
                break;
            case 'color':
                $display = $this->cart_display_color($v['value']);
                if ($v['price'] && $this->show_price && (!$hide_zero || $v['price'] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price']);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                }
                break;
            case 'file':
                $display = $this->cart_display_file($v);
                if ($v['price'] && $this->show_price && (!$hide_zero || $v['price'] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price']);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                }
                break;
            case 'image-group':

                $display = $this->cart_display_image($v, $product, $field_price_multiplier);

                break;
            case 'color-group':
                $display = $this->cart_display_colorgroup($v, $product, $field_price_multiplier);
                break;
            case 'placeselector':
                $display = '';
                if (!empty($v['value']['formated'])) {
                    $display = $v['value']['formated'] . '<br>';
                    if (!empty($v['value']['splited']['street_number'])) {
                        $display .= __('Street address:', 'wcpa-text-domain') . ' ' . $v['value']['splited']['street_number'] . ' ' . $v['value']['splited']['route'] . ' <br>';
                    }
                    if (!empty($v['value']['splited']['locality'])) {
                        $display .= __('City:', 'wcpa-text-domain') . ' ' . $v['value']['splited']['locality'] . '<br>';
                    }
                    if (!empty($v['value']['splited']['administrative_area_level_1'])) {
                        $display .= __('State:', 'wcpa-text-domain') . ' ' . $v['value']['splited']['administrative_area_level_1'] . '<br>';
                    }
                    if (!empty($v['value']['splited']['postal_code'])) {
                        $display .= __('Zip code:', 'wcpa-text-domain') . ' ' . $v['value']['splited']['postal_code'] . '<br>';
                    }
                    if (!empty($v['value']['splited']['country'])) {
                        $display .= __('Country:', 'wcpa-text-domain') . ' ' . $v['value']['splited']['country'] . '<br>';
                    }
                    if (isset($v['value']['cords']['lat']) && !empty($v['value']['cords']['lat'])) {
                        $display .= __('Latitude:', 'wcpa-text-domain') . ' ' . $v['value']['cords']['lat'] . '<br>';
                        $display .= __('Longitude:', 'wcpa-text-domain') . ' ' . $v['value']['cords']['lng'] . '<br>';
                        $display .= '<a href="https://www.google.com/maps/?q=' . $v['value']['cords']['lat'] . ',' . $v['value']['cords']['lng'] . '" target="_blank">' . __('View on map', 'wcpa-text-domain') . '</a> <br>';
                    }
                }
                if ($v['price'][0] && $this->show_price && (!$hide_zero || $v['price'][0] != 0)) {
                    $price = wcpa_get_price_cart($product, $v['price'][0]);
                    $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                }

                break;
        }

        if ($display == '') {
            $display = '&nbsp;';
        }
        //return '<div class="wcpa_cart_val wcpa_cart_type_' . $v['type'] . '" >' . $display . '</div>';
        return apply_filters('wcpa_display_cart_value', '<div class="wcpa_cart_val wcpa_cart_type_' . $v['type'] . '" >' . $display . '</div>', $display, $v);


    }

    public function cart_display_pargraph($value)
    {
        $display = do_shortcode(nl2br($value['value']));
        return $display;
    }

    public function cart_display_textarea($value)
    {
        $display = nl2br($value);
        return $display;
    }

    public function cart_display_array($value, $product, $field_price_multiplier = 1)
    {
        $display = '';
        $hide_zero = wcpa_get_option('cart_hide_price_zero', false);

        if (is_array($value['value'])) {

            foreach ($value['value'] as $k => $v) {
                if ($k === 'other') {
                    //Other text has to apply i18n
                    $display .= '<span>' . __($v['label'] . ':', 'wcpa-text-domain') . ' ' . $v['value'] . '</span>';
                } else {
                    //Label no need to apply i18n.
                    $display .= '<span>' . $v['label'] . ' </span>';
                }
                if ($value['price'] !== FALSE && is_array($value['price']) && $this->show_price) {
                    if (isset($value['price'][$k]) && $value['price'][$k] !== FALSE && (!$hide_zero || $value['price'][$k] != 0)) {
                        $price = wcpa_get_price_cart($product, $value['price'][$k]);
                        $display .= '<span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                } else {
                    if ($value['price'] !== FALSE && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                        $price = wcpa_get_price_cart($product, $value['price']);
                        $display .= ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                }
                $display .= '<br>';
            }
        } else {
            $display = $value['value'];
            if ($value['price'] && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                $price = wcpa_get_price_cart($product, $value['price']);
                $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
            }
        }

        return $display;
    }

    public function cart_display_color($value)
    {
        $display = '<span style="color:' . $value . ';font-size: 20px;
            padding: 0;
    line-height: 0;">&#9632;</span>' . $value;
        return $display;
    }

    public function cart_display_file($v)
    {
        $display = '';
        $hideImage = false;
        if (isset($v['form_data']->hideImageIn_cart) && $v['form_data']->hideImageIn_cart) {
            $hideImage = true;
        }
        $value = $v['value'];
        if (isset($value['url'])) {
            $display .= '<a href="' . $value['url'] . '"  target="_blank" download="' . $value['file_name'] . '">';
            if (!$hideImage && in_array($value['type'], array('image/jpg', 'image/png', 'image/gif', 'image/jpeg'))) {
                $display .= '<img class="wcpa_img" src="' . $value['url'] . '" />';
            } else {
                $display .= '<img class="wcpa_icon" src="' . wp_mime_type_icon($value['type']) . '" />';
            }
            $display .= '<span>' . $value['file_name'] . '</span></a>';
        }

        return $display;
    }

    public function cart_display_image($value, $product, $field_price_multiplier = 1)
    {
        $display = '';
        $class = '';
        $hide_zero = wcpa_get_option('cart_hide_price_zero', false);

        if (isset($value['form_data']->img_preview) && $value['form_data']->img_preview) {
            $class = 'class="wcpa_cart_img_preview"';
        }
        $hideImage = false;
        if (isset($value['form_data']->hideImageIn_cart) && $value['form_data']->hideImageIn_cart) {
            $hideImage = true;
        }
        if (is_array($value['value'])) {
            foreach ($value['value'] as $k => $v) {
                if ($k === 'other') {
                    $display .= '<p>' . __($v['label'] . ':', 'wcpa-text-domain') . ' ' . $v['value'] . '';
                } else {

                    $img_size_style = ((isset($value['form_data']->disp_size_img) && $value['form_data']->disp_size_img > 0) ? 'style="width:' . $value['form_data']->disp_size_img . 'px"' : '');

                    $display .= '<p ' . $class . '>' . (!$hideImage ? '<img ' . $img_size_style . '  src="' . $v['image'] . '" />' : '');
                    if (!empty($v['label'])) {
                        $display .= ' <span >' . $v['label'] . '</span> ';
                    }
                }

                if ($value['price'] && is_array($value['price']) && $this->show_price) {
                    if (isset($value['price'][$k]) && $value['price'][$k] !== FALSE && (!$hide_zero || $value['price'][$k] != 0)) {
                        $price = wcpa_get_price_cart($product, $value['price'][$k]);
                        $display .= '<span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                } else {
                    if ($value['price'] !== FALSE && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                        $price = wcpa_get_price_cart($product, $value['price']);
                        $display .= ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                }


                $display .= '</p>';
            }
        } else {
            $display = $value['value'];
            if ($value['price'] && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                $price = wcpa_get_price_cart($product, $value['price']);
                $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
            }
        }
        return $display;
    }

    public function cart_display_colorgroup($value, $product, $field_price_multiplier = 1)
    {
        $display = '';
        $hide_zero = wcpa_get_option('cart_hide_price_zero', false);

        if (is_array($value['value'])) {
            foreach ($value['value'] as $k => $v) {
                if ($k === 'other') {
                    $display .= '<p>' . __($v['label'] . ':', 'wcpa-text-domain') . ' ' . $v['value'] . '';
                } else {

                    $display .= '<p>';
                    $size = '';
                    if (isset($value['form_data']->cart_display_type) && $value['form_data']->cart_display_type == 'text') {


                        $display .= '<span style="color:' . $v['color'] . ';font-size: 20px;
            padding: 0;
    line-height: 0;">&#9632;</span>' . (!wcpa_empty($v['label']) ? $v['label'] : $v['value']) . '  ';


                    } else {


                        if (isset($value['form_data']->disp_size) && $value['form_data']->disp_size > 10) {
                            $size .= 'height:' . $value['form_data']->disp_size . 'px;';
                            if (isset($value['form_data']->show_label_inside) && $value['form_data']->show_label_inside) {
                                $size .= 'min-width:' . $value['form_data']->disp_size . 'px;line-height:' . ($value['form_data']->disp_size - 2) . 'px;';
                            } else {
                                $size .= 'width:' . $value['form_data']->disp_size . 'px;';
                            }
                        }

                        if (isset($value['form_data']->show_label_inside) && $value['form_data']->show_label_inside) {
                            $display .= '<span class="wcpa_cart_color label_inside disp_' . $value['form_data']->disp_type . ' ' . wcpa_colorClass($v['color']) . ' ' . ((isset($value['form_data']->adjust_width) && $value['form_data']->adjust_width) ? 'wcpa_adjustwidth' : '') . '"'
                                . ' style="background-color:' . $v['color'] . ';' . $size . '" >'
                                . '' . $v['label'] . '</span>';
                        } else {
                            $display .= '<span class="wcpa_cart_color disp_' . $value['form_data']->disp_type . ' ' . wcpa_colorClass($v['color']) . ' ' . ((isset($value['form_data']->adjust_width) && $value['form_data']->adjust_width) ? 'wcpa_adjustwidth' : '') . '"'
                                . ' style="background-color:' . $v['color'] . ';' . $size . '" ></span>';
                            if (!empty($v['label'])) {
                                $display .= ' <span >' . $v['label'] . '</span> ';
                            }
                        }
                    }

                }

                if ($value['price'] && is_array($value['price']) && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                    if (isset($value['price'][$k]) && $value['price'][$k] !== FALSE) {
                        $price = wcpa_get_price_cart($product, $value['price'][$k]);
                        $display .= '<span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                } else {
                    if ($value['price'] !== FALSE && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                        $price = wcpa_get_price_cart($product, $value['price']);
                        $display .= ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
                    }
                }


                $display .= '</p>';
            }
        } else {
            $display = $value['value'];
            if ($value['price'] && $this->show_price && (!$hide_zero || $value['price'] != 0)) {
                $price = wcpa_get_price_cart($product, $value['price']);
                $display = $display . ' <span class="wcpa_cart_price">(' . wcpa_price($price * $field_price_multiplier) . ')</span>';
            }
        }
        return $display;
    }

    public function cart_item_class($class, $cart_item)
    {
        if (isset($cart_item['wcpa_cart_rules']['bind_quantity']) && $cart_item['wcpa_cart_rules']['bind_quantity']) {
            $class .= ' wcpa_bind_quantity';
        }
        if (isset($cart_item['wcpa_data']) && count($cart_item['wcpa_data'])) {
            $class .= ' wcpa_cart_has_fields';
        }

        return $class;
    }

    public function before_add_to_cart_button()
    {
        global $product;

        $product_id = $product->get_id();
        $form = new WCPA_Form();
        $form->get_forms_by_product($product_id);
        $form->render($product_id);
    }

    /**
     * Load frontend CSS.
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function enqueue_styles()
    {
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.min.css', array(), $this->_version);
        wp_register_style($this->_token . '-datetime', esc_url($this->assets_url) . 'plugins/datetimepicker/jquery.datetimepicker.min.css', array(), $this->_version);
        wp_register_style($this->_token . '-colorpicker', esc_url($this->assets_url) . 'plugins/spectrum/spectrum.min.css', array(), $this->_version);

        wp_register_style($this->_token . '-sumoselector', esc_url($this->assets_url) . 'plugins/sumoselector/sumoselect.min.css', array(), $this->_version);

        wp_enqueue_style($this->_token . '-datetime');
        wp_enqueue_style($this->_token . '-colorpicker');
        wp_enqueue_style($this->_token . '-frontend');
        $load_all_scripts = wcpa_get_option('load_all_scripts', false);
        if ($load_all_scripts) {
            wp_enqueue_style($this->_token . '-sumoselector');
        }
//
    }

    /**e
     * Load frontend Javascript.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function enqueue_scripts()
    {
        $google_map_api = wcpa_get_option('google_map_api_key', '');
        $load_all_scripts = wcpa_get_option('load_all_scripts', false);

        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend.min.js', array('jquery'), $this->_version, true);
        wp_register_script($this->_token . '-datetime', esc_url($this->assets_url) . 'plugins/datetimepicker/jquery.datetimepicker.full.js', array('jquery', 'momentjs'), $this->_version);
        wp_register_script('momentjs', esc_url($this->assets_url) . 'plugins/moment.min.js', array('jquery'), $this->_version);
        wp_register_script($this->_token . '-sumoselector', esc_url($this->assets_url) . 'plugins/sumoselector/jquery.sumoselect.min.js', array('jquery'), $this->_version);
        wp_register_script($this->_token . '-colorpicker', esc_url($this->assets_url) . 'plugins/spectrum/spectrum.min.js', array('jquery'), $this->_version);
        wp_register_script($this->_token . '-googlemapplace', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api . '&libraries=places&callback=wcpaInitMap', array($this->_token . '-frontend'), $this->_version);
        wp_register_script($this->_token . '-recaptcha', 'https://www.google.com/recaptcha/api.js', array($this->_token . '-frontend'), $this->_version);

        if ($load_all_scripts) {
            wp_enqueue_script($this->_token . '-datetime');
            wp_enqueue_script($this->_token . '-sumoselector');
            wp_enqueue_script($this->_token . '-colorpicker');

        }

        wp_enqueue_script('jquery');

        wp_enqueue_script($this->_token . '-frontend');

        $this->print_global_data();
    }

    public function print_global_data()
    {

// Put your plugin code here
//        if (!is_product()) {
//            return;
//        }
//        global $product;
//        if (!is_object($product)) {
//            $product = wc_get_product(get_the_ID());
//        }
        // $wcpa_global_vars['wc_product_price'] = apply_filters('raw_woocommerce_price', wcpa_get_price_shop($product)); //wcpa_get_price_shop($product); //$product->get_price();
        //$wcpa_global_vars['product_title'] = $product->get_title();
        $wcpa_global_vars['wc_currency_symbol'] = get_woocommerce_currency_symbol('');
        $wcpa_global_vars['wc_thousand_sep'] = wc_get_price_thousand_separator();
        $wcpa_global_vars['wc_price_decimals'] = wc_get_price_decimals();
        $wcpa_global_vars['wc_decimal_sep'] = wc_get_price_decimal_separator();
        $wcpa_global_vars['price_format'] = get_woocommerce_price_format();
        $wcpa_global_vars['wc_decimal_sep'] = wc_get_price_decimal_separator();
        $wcpa_global_vars['wc_currency_pos'] = get_option('woocommerce_currency_pos');
        //$wcpa_global_vars['is_variable'] = $product->is_type('variable') ? true : FALSE;
        $wcpa_global_vars['date_format'] = __(get_option('date_format'), 'wcpa-text-domain');
        $wcpa_global_vars['date_format_js'] = $this->convertPhpToJsMomentFormat(__(get_option('date_format'), 'wcpa-text-domain'));
        $wcpa_global_vars['time_format'] = __(get_option('time_format'), 'wcpa-text-domain');
        $wcpa_global_vars['time_format_js'] = $this->convertPhpToJsMomentFormat(__(get_option('time_format'), 'wcpa-text-domain'));

        $wcpa_global_vars['use_sumo'] = wcpa_get_option('use_sumo_selector', false);

        $wcpa_global_vars['start_of_week'] = __(get_option('start_of_week'), 'wcpa-text-domain');
        $wcpa_global_vars['today'] = ['days' => floor(current_time('timestamp') / (60 * 60 * 24)), 'seconds' => current_time('timestamp')];
        $wcpa_global_vars['google_map_api'] = wcpa_get_option('google_map_api_key');
        $wcpa_global_vars['ajax_url'] = admin_url('admin-ajax.php');
        $wcpa_global_vars['change_price_as_quantity'] = wcpa_get_option('change_price_as_quantity', false) || wcpa_get_option('show_field_price_x_quantity', false);
        $wcpa_global_vars['show_field_price_x_quantity'] = wcpa_get_option('show_field_price_x_quantity', false);
        $wcpa_global_vars['show_strike_product_price'] = wcpa_get_option('show_strike_product_price', false);
        $wcpa_global_vars['strings'] = array(
            'ajax_file_upload' => __('Files are being uploaded...', 'wcpa-text-domain'),
            'ajax_upload_error' => __('Upload error', 'wcpa-text-domain'),
            'sel_min_req_error' => __('You have to select minimum %d items', 'wcpa-text-domain'),
            'sel_max_req_error' => __('You can select maximum %d items', 'wcpa-text-domain'),
            'fix_val_errors' => __('Please correct the errors shown for fields', 'wcpa-text-domain'),
            'field_is_requied' => __('This field is requied', 'wcpa-text-domain'),
            'character_not_valid' => __('Character %s is not supported', 'wcpa-text-domain'),
            'sumo_strings' => [
                'captionFormat'=>__('{0} Selected', 'wcpa-text-domain'),
                'captionFormatAllSelected'=>__('{0} all selected!', 'wcpa-text-domain'),
            ],
        );
        $wcpa_init_triggers = wcpa_get_option('wcpa_init_triggers', []);
        if (!is_array($wcpa_init_triggers) && !empty($wcpa_init_triggers)) {
            $wcpa_init_triggers = [$wcpa_init_triggers];
        }
        $wcpa_global_vars['wcpa_init_triggers'] = array_unique(array_merge($wcpa_init_triggers, array('qv_loader_stop', 'quick_view_pro:load', 'elementor/popup/show', 'xt_wooqv-product-loaded')));

        wp_localize_script($this->_token . '-frontend', 'wcpa_global_vars', $wcpa_global_vars);
    }

// End enqueue_styles ()

    public function convertPhpToJsMomentFormat($phpFormat)
    {
        $replacements = [
            'A' => 'A',      // for the sake of escaping below
            'a' => 'a',      // for the sake of escaping below
            'B' => '',       // Swatch internet time (.beats), no equivalent
            'c' => 'YYYY-MM-DD[T]HH:mm:ssZ', // ISO 8601
            'D' => 'ddd',
            'd' => 'DD',
            'e' => 'zz',     // deprecated since version 1.6.0 of moment.js
            'F' => 'MMMM',
            'G' => 'H',
            'g' => 'h',
            'H' => 'HH',
            'h' => 'hh',
            'I' => '',       // Daylight Saving Time? => moment().isDST();
            'i' => 'mm',
            'j' => 'D',
            'L' => '',       // Leap year? => moment().isLeapYear();
            'l' => 'dddd',
            'M' => 'MMM',
            'm' => 'MM',
            'N' => 'E',
            'n' => 'M',
            'O' => 'ZZ',
            'o' => 'YYYY',
            'P' => 'Z',
            'r' => 'ddd, DD MMM YYYY HH:mm:ss ZZ', // RFC 2822
            'S' => 'o',
            's' => 'ss',
            'T' => 'z',      // deprecated since version 1.6.0 of moment.js
            't' => '',       // days in the month => moment().daysInMonth();
            'U' => 'X',
            'u' => 'SSSSSS', // microseconds
            'v' => 'SSS',    // milliseconds (from PHP 7.0.0)
            'W' => 'W',      // for the sake of escaping below
            'w' => 'e',
            'Y' => 'YYYY',
            'y' => 'YY',
            'Z' => '',       // time zone offset in minutes => moment().zone();
            'z' => 'DDD',
        ];

        // Converts escaped characters.
        foreach ($replacements as $from => $to) {
            $replacements['\\' . $from] = '[' . $from . ']';
        }

        return strtr($phpFormat, $replacements);
    }

// End enqueue_scripts ()
// End instance()
}
