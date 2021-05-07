<div class="wrap wcpa_settings">
    <div id="icon-options-general" class="icon32"></div>
    <h1><?php echo WCPA_PLUGIN_NAME; ?></h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">

                        <div class="inside ">
                            <form method="post" id="wcpa_settings_main" action="">
                                <?php wp_nonce_field('wcpa_save_settings', 'wcpa_nonce'); ?>
                                <ul class="wcpa_g_set_tabs ">
                                    <li><a href="#wcpa_disp_settings" class="active">
                                            <span class="icon_display"></span>
                                            <?php _e('Display Settings', 'wcpa-text-domain'); ?></a></li>
                                    <!-- <li> <a href="#wcpa_price_settings">Price Settings</a> </li> -->
                                    <li><a href="#wcpa_content_settings">
                                            <span class="icon_content"></span>
                                            <?php _e('Contents/Strings', 'wcpa-text-domain'); ?></a></li>
                                    <li><a href="#wcpa_other_settings">
                                            <span class="icon_other"></span>
                                            <?php _e('Other Settings', 'wcpa-text-domain'); ?></a></li>
                                    <li><a href="#wcpa_import_export">
                                            <span class="icon_import"></span>
                                            <?php _e('Import/Export', 'wcpa-text-domain'); ?></a></li>
                                    <li><a href="#wcpa_license_key">
                                            <span class="icon_license"></span>
                                            <?php _e('License Key', 'wcpa-text-domain'); ?></a></li>
                                </ul>
                                <div class="wcpa_g_set_tabcontents">
                                    <div id="wcpa_disp_settings" class="wcpa_tabcontent">

                                        <div class="options_group">
                                            <h3><?php _e('Price', 'wcpa-text-domain') ?></h3>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="disp_show_field_price"
                                                           id="disp_show_field_price"
                                                           value="1" <?php checked(wcpa_get_option('disp_show_field_price', true)); ?>>
                                                    <label for="disp_show_field_price"><?php _e('Show price against each fields', 'wcpa-text-domain'); ?>
                                                    </label>
                                                </li>
                                            </ul>
                                            <h3><?php _e('Price Summary Section', 'wcpa-text-domain') ?></h3>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="disp_summ_show_total_price"
                                                           id="disp_summ_show_total_price"
                                                           value="1" <?php checked(wcpa_get_option('disp_summ_show_total_price', true)); ?>>
                                                    <label for="disp_summ_show_total_price">
                                                        <?php _e('Show Total', 'wcpa-text-domain') ?> </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="disp_summ_show_product_price"
                                                           id="disp_summ_show_product_price"
                                                           value="1" <?php checked(wcpa_get_option('disp_summ_show_product_price', true)); ?>>
                                                    <label for="disp_summ_show_product_price">
                                                        <?php _e('Show Product Price', 'wcpa-text-domain') ?> </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="disp_summ_show_option_price"
                                                           id="disp_summ_show_option_price"
                                                           value="1" <?php checked(wcpa_get_option('disp_summ_show_option_price', true)); ?>>
                                                    <label for="disp_summ_show_option_price">
                                                        <?php _e('Show Options Price', 'wcpa-text-domain') ?>
                                                    </label>
                                                </li>
                                            </ul>
                                            <h3> <?php _e('Custom options data', 'wcpa-text-domain') ?> </h3>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="show_meta_in_cart"
                                                           id="show_meta_in_cart"
                                                           value="1" <?php checked(wcpa_get_option('show_meta_in_cart', true)); ?>>
                                                    <label for="show_meta_in_cart"> <?php _e('Show in Cart', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="show_meta_in_checkout"
                                                           id="show_meta_in_checkout"
                                                           value="1" <?php checked(wcpa_get_option('show_meta_in_checkout', true)); ?>>
                                                    <label for="show_meta_in_checkout">
                                                        <?php _e('Show in Checkout', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="show_meta_in_order"
                                                           id="show_meta_in_order"
                                                           value="1" <?php checked(wcpa_get_option('show_meta_in_order', true)); ?>>
                                                    <label for="show_meta_in_order">
                                                        <?php _e('Show in Order', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                            </ul>
                                            <h3> <?php _e('Show or Hide Price In', 'wcpa-text-domain') ?> </h3>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="show_price_in_cart"
                                                           id="show_price_in_cart"
                                                           value="1" <?php checked(wcpa_get_option('show_price_in_cart', true)); ?>>
                                                    <label for="show_price_in_cart"> <?php _e('Show in cart', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="show_price_in_checkout"
                                                           id="show_price_in_checkout"
                                                           value="1" <?php checked(wcpa_get_option('show_price_in_checkout', true)); ?>>
                                                    <label for="show_price_in_checkout">
                                                        <?php _e('Show in Checkout', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="show_price_in_order"
                                                           id="show_price_in_order"
                                                           value="1" <?php checked(wcpa_get_option('show_price_in_order', true)); ?>>
                                                    <label for="show_price_in_order">
                                                        <?php _e('Show in Order', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="show_price_in_order_meta"
                                                           id="show_price_in_order_meta"
                                                           value="1" <?php checked(wcpa_get_option('show_price_in_order_meta', true)); ?>>
                                                    <label for="show_price_in_order_meta">
                                                        <?php _e('Add in Order Meta( Price will be saved along with order meta, Third party plugins will be using this data)', 'wcpa-text-domain'); ?>  </label>
                                                </li>
                                            </ul>
                                            <?php submit_button(null, 'primary', 'wcpa_save_settings'); ?>
                                        </div>
                                    </div>

                                    <div id="wcpa_content_settings" class="wcpa_tabcontent" style="display: none">
                                        <div class="options_group">
                                            <h3><?php _e('Price Summary Section Labels', 'wcpa-text-domain') ?></h3>
                                            <ul>
                                                <li>

                                                    <label for="options_total_label"> <?php
                                                        _e('Options Price Label:', 'wcpa-text-domain'); ?>
                                                    </label>
                                                    <input type="text" name="options_total_label"
                                                           id="options_total_label"
                                                           value="<?php echo wcpa_get_option('options_total_label', 'Options Price'); ?>">
                                                </li>
                                                <li>

                                                    <label for="options_product_label"> <?php
                                                        _e('Product Price Label:', 'wcpa-text-domain');
                                                        ?></label>
                                                    <input type="text" name="options_product_label"
                                                           id="options_product_label"
                                                           value="<?php echo wcpa_get_option('options_product_label', 'Product Price'); ?>">
                                                </li>
                                                <li>

                                                    <label for="total_label"><?php
                                                        _e('Total Label:', 'wcpa-text-domain');
                                                        ?> </label>
                                                    <input type="text" name="total_label" id="total_label"
                                                           value="<?php echo wcpa_get_option('total_label', 'Total'); ?>">
                                                </li>
                                                <li>
                                                    <label for="fee_label"><?php
                                                        _e('Fee Label:', 'wcpa-text-domain');
                                                        ?> </label>
                                                    <input type="text" name="fee_label" id="fee_label"
                                                           value="<?php echo wcpa_get_option('fee_label', 'Fee'); ?>">
                                                </li>
                                                <li>
                                                    <label style="display: block" for="field_option_price_format"><?php
                                                        _e('Format for showing price in field options:', 'wcpa-text-domain');
                                                        ?> </label>
                                                    <input style="display: inline-block" type="text"
                                                           name="field_option_price_format"
                                                           id="field_option_price_format"
                                                           placeholder="(price)"
                                                           value="<?php echo wcpa_get_option('field_option_price_format', '(price)'); ?>">
                                                    <span class="wcpa_var_hilight"><?php _e('Preview:', 'wcpa-text-domain');
                                                        echo str_replace('price', wcpa_price(10), wcpa_get_option('field_option_price_format', '(price)')); ?></span>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="options_group section">

                                            <ul>
                                                <li>

                                                    <label for="add_to_cart_text">
                                                        <p><?php _e('Add to Cart button text', 'wcpa-text-domain'); ?> </p>
                                                        <small><?php _e('Add to cart button text in archive/product listing page in case product has additional fields', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                    <input type="text" name="add_to_cart_text" id="add_to_cart_text"
                                                           value="<?php echo wcpa_get_option('add_to_cart_text', 'Select options'); ?>">
                                                </li>
                                                <li>

                                                    <label for="price_prefix_label">
                                                        <p><?php _e('Product Price prefix', 'wcpa-text-domain'); ?></p>
                                                        <small><?php _e('Set a prefix text before the price in archive and product page. Leave blank if no prefix needed. eg: \'Starting at\' ', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                    <input type="text" name="price_prefix_label" id="price_prefix_label"
                                                           value="<?php echo wcpa_get_option('price_prefix_label', ''); ?>">
                                                </li>

                                            </ul>
                                            <?php submit_button(null, 'primary', 'wcpa_save_settings'); ?>
                                        </div>
                                    </div>

                                    <div id="wcpa_other_settings" class="wcpa_tabcontent" style="display: none">
                                        <div class="options_group">
                                            <h3><?php _e('Other Settings', 'wcpa-text-domain') ?></h3>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="form_loading_order_by_date"
                                                           id="form_loading_order_by_date"
                                                           value="1" <?php checked(wcpa_get_option('form_loading_order_by_date', false)); ?>>
                                                    <label for="form_loading_order_by_date" class="text">
                                                        <?php _e('Load form in recency order', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('If a product has assigned multiple forms, it will be loaded based on form created order', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="options_group">

                                            <ul>
                                                <li>

                                                    <input type="checkbox" name="hide_empty_data" id="hide_empty_data"
                                                           value="1" <?php checked(wcpa_get_option('hide_empty_data', false)); ?>>
                                                    <label for="hide_empty_data" class="text">
                                                        <?php _e('Hide empty fields in cart', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('Hide empty fields in cart, checkout and order', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="options_group">

                                            <ul>
                                                <li>

                                                    <input type="checkbox" name="change_price_as_quantity" id="change_price_as_quantity"
                                                           value="1" <?php checked(wcpa_get_option('change_price_as_quantity', false)); ?>>
                                                    <label for="change_price_as_quantity" class="text">
                                                        <?php _e('Update summary price as quantity change', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('In price summary section, price will be updated as quantity change', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="options_group">

                                            <ul>
                                                <li>

                                                    <input type="checkbox" name="wcpa_show_form_json" id="wcpa_show_form_json"
                                                           value="1" <?php checked(wcpa_get_option('wcpa_show_form_json', false)); ?>>
                                                    <label for="wcpa_show_form_json" class="text">
                                                        <?php _e('Show JSON code editor for form builder', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('You can see the JSON code and make changes manually for each forms.', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="options_group">

                                            <ul>
                                                <li>

                                                    <input type="checkbox" name="use_sumo_selector" id="use_sumo_selector"
                                                           value="1" <?php checked(wcpa_get_option('use_sumo_selector', false)); ?>>
                                                    <label for="use_sumo_selector" class="text">
                                                        <?php _e('Use Jquery Sumo Selector for multi selector dropdown', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('Sumo Selector is a jQuery plugin for customized dropdown ', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="options_group">

                                            <ul>
                                                <li>

                                                    <input type="checkbox" name="load_all_scripts" id="load_all_scripts"
                                                           value="1" <?php checked(wcpa_get_option('load_all_scripts', false)); ?>>
                                                    <label for="load_all_scripts" class="text">
                                                        <?php _e('Load all custom plugins scripts(for datepicker,colorpicker,SumoSelect) in all pages', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <small class="label"><?php _e('By default custom scripts will be loading in product page only. But if you want to use custom fields in quick view popups, it needs to loads scripts in all pages ', 'wcpa-text-domain'); ?> </small>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="options_group">
                                            <h3><?php _e('Custom fields for products', 'wcpa-text-domain'); ?> </h3>
                                            <ul>
                                                <li>
                                                    <label for="product_custom_fields">

                                                        <?php _e('This fields can be used in custom price formula with prefix \'wcpa_pcf_\'', 'wcpa-text-domain'); ?>
                                                        <br>
                                                        <?php _e('Example: ', 'wcpa-text-domain'); ?>
                                                        <span class="example_slug">{wcpa_pcf_packing_price}</span></label><br>

                                                    <div id="product_custom_fields">
                                                        <span class="custom_field_slug title"><?php _e('Custom Field Slug','wcpa-text-domain') ?></span>
                                                        <span class="default_value title"><?php _e('Default Value','wcpa-text-domain') ?></span>
                                                        <?php
                                                        $custom_fields = wcpa_get_option('product_custom_fields');
                                                        if (is_array($custom_fields)) {
                                                            foreach ($custom_fields as $key => $v) {
                                                                ?>
                                                                <div class="fields">
                                                                    <input type="text"
                                                                           name="product_custom_field_name[<?php echo $key ?>]"
                                                                           placeholder="<?php _e('Custom Field Slug', 'wcpa-text-domain') ?>"
                                                                           value="<?php echo $v['name']; ?>">
                                                                    <input type="text"
                                                                           name="product_custom_field_value[<?php echo $key ?>]"
                                                                           placeholder="<?php _e('Default Value', 'wcpa-text-domain') ?>"
                                                                           value="<?php echo $v['value']; ?>"/>
                                                                    <input type="submit" class="wcpa_rmv_btn"
                                                                           value="Remove">
                                                                    <span class="wcpa_var_hilight"> {wcpa_pcf_<?php echo $v['name']; ?>}</span>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <div id="product_custom_field_add">
                                                        <input type="text" class="product_custom_field_name"
                                                               placeholder="<?php _e('Custom Field Slug', 'wcpa-text-domain') ?>"
                                                               name="product_custom_field_name[0]" value="">
                                                        <input type="text" class="product_custom_field_value"
                                                               placeholder="<?php _e('Default Value', 'wcpa-text-domain') ?>"
                                                               name="product_custom_field_value[0]" value=""/>
                                                        <input type="submit" class="wcpa_add_btn" value="Add">
                                                    </div>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="options_group textbox_width">

                                            <ul>
                                                <li>
                                                    <label for="recaptcha_site_key" class="title"> <?php
                                                        _e('reCAPTCHA Site Key:', 'wcpa-text-domain');
                                                        ?></label>
                                                    <input type="text" name="recaptcha_site_key" id="recaptcha_site_key"
                                                           value="<?php echo wcpa_get_option('recaptcha_site_key', ''); ?>">
                                                    <div class="tooltip">
                                                        <img src="<?php echo $asset_url; ?>/img/help-circle.png">
                                                        <span class="tooltiptext">
                                                            <?php
                                                            _e(' If you need to use reCAPTCHA in your forms you have to paste reCAPTCHA Site Key here. Tick to enable option shown at: Products-> Custom Product Addons-> Other Settings after pasting both the keys.')
                                                            ?>
                                                        <a href="https://www.google.com/recaptcha/admin"
                                                           target="_blank"><br>Get reCAPTCHA Site Key</a>
                                                        </span>
                                                    </div>

                                                </li>
                                                <li>

                                                    <label for="recaptcha_secret_key" class="title"> <?php
                                                        _e('reCAPTCHA Secret Key:', 'wcpa-text-domain');
                                                        ?></label>
                                                    <input type="text" name="recaptcha_secret_key"
                                                           id="recaptcha_secret_key"
                                                           value="<?php echo wcpa_get_option('recaptcha_secret_key', ''); ?>">
                                                    <div class="tooltip">
                                                        <img src="<?php echo $asset_url; ?>/img/help-circle.png">
                                                        <span class="tooltiptext">If you need to use reCAPTCHA in your forms you have to paste reCAPTCHA Secret Key here. Tick enable option shown at: Products-> Custom Product Addons-> Other Settings after pasting both the keys.
                                                        <a href="https://www.google.com/recaptcha/admin"
                                                           target="_blank"><br>Get reCAPTCHA Secret Key</a>
                                                        </span>
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                        <div class="options_group textbox_width">
                                            <ul>
                                                <li>

                                                    <label for="google_map_api_key" class="title"> <?php
                                                        _e('Google Map API Key:', 'wcpa-text-domain');
                                                        ?></label>
                                                    <div>Don't forget to restrict the API key by site domains.</div>
                                                    <input type="text" name="google_map_api_key"
                                                           id="options_total_label"
                                                           value="<?php echo wcpa_get_option('google_map_api_key', ''); ?>">
                                                    <div class="tooltip">
                                                        <img src="<?php echo $asset_url; ?>/img/help-circle.png">
                                                        <span class="tooltiptext">If you need to use Google Maps in your forms, you have to paste Google Map API Key here.
                                                        <a href="https://developers.google.com/maps/documentation/embed/get-api-key"
                                                           target="_blank"><br>Get Google Map API Key</a>
                                                        </span>
                                                    </div>

                                                </li>
                                            </ul>
                                            <?php submit_button(null, 'primary', 'wcpa_save_settings'); ?>
                                        </div>
                                    </div>
                                    <div id="wcpa_import_export" class="wcpa_tabcontent" style="display: none">
                                        <div class="options_group">
                                            <p>
                                            <h3><?php _e('This can be used to import single product form', 'wcpa-text-domain'); ?></h3></p>
                                            <ul>
                                                <li>
                                                    <div>
                                                        <label class="import_descript1"><?php _e('Input the exported data here and press <strong>Import From</strong>', 'wcpa-text-domain'); ?></label>
                                                        <textarea rows="5" id="wcpa_import_form_data"></textarea>
                                                        <?php wp_nonce_field('wcpa_form_import_nonce', 'wcpa_form_import_nonce'); ?>
                                                    </div>
                                                    <button class="button-secondary" id="wcpa_import_form"><?php
                                                        _e('Import Form', 'wcpa-text-domain');
                                                        ?></button>
                                                </li>
                                            </ul>
                                            <p>
                                            <h3><?php _e('Export All Forms', 'wcpa-text-domain'); ?></h3></p>
                                            <ul>
                                                <li>
                                                    <div>
                                                        <a href="<?php echo admin_url('export.php?download=true&content=' . WCPA_POST_TYPE . '&submit=Download+Export+File'); ?>"
                                                           class="button-secondary"><?php
                                                            _e('Export Form', 'wcpa-text-domain');
                                                            ?></a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>
                                            <h3><?php _e('Import All Forms', 'wcpa-text-domain'); ?></h3></p>
                                            <div class="import_descript2"><?php _e('You can import the xml file using Wordpress default post import option at <a href="' . admin_url('import.php') . '">Tools&#187;Import</a>', 'wcpa-text-domain'); ?></div>

                                            <?php submit_button(null, 'primary', 'wcpa_save_settings'); ?>
                                        </div>
                                    </div>
                                    <div id="wcpa_license_key" class="wcpa_tabcontent" style="display:none">
                                        <div class="options_group">
                                            <?php
                                            $license = get_option('wcpa_activation_license_key');
                                            $status = get_option('wcpa_activation_license_status');
                                            ?>
                                            <form method="post" action="options.php">

                                                <?php settings_fields('wcpa_license'); ?>

                                                <table class="form-table">
                                                    <tbody>
                                                    <tr valign="top">
                                                        <th scope="row" valign="top" class="lic_heading">
                                                            <?php _e('Plugin License','wcpa-text-domain'); ?>
                                                        </th>
                                                        <th class="lic_heading"><?php _e('Status: ','wcpa-text-domain'); ?>
                                                            <?php if ($status !== false && $status == 'valid') { ?>
                                                                <span style="color:green;"><?php _e('Active','wcpa-text-domain'); ?></span>
                                                                <?php wp_nonce_field('wcpa_deactivate', 'wcpa_nounce'); ?>
                                                            <?php } else { ?>
                                                                <?php wp_nonce_field('wcpa_activate', 'wcpa_nounce'); ?>
                                                                <span style="color:red;"><?php _e('Inactive','wcpa-text-domain'); ?></span>
                                                            <?php } ?>

                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <?php _e('License Key','wcpa-text-domain'); ?>
                                                        <td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input id="edd_sample_license_key"
                                                                   name="wcpa_activation_license_key" type="text"
                                                                   class="regular-text"
                                                                   value="<?php esc_attr_e($license); ?>"
                                                                   placeholder="<?php _e("Enter your license key here",'wcpa-text-domain'); ?>"/>
                                                        </td>
                                                    </tr>

                                                    <tr valign="top">
                                                        <td>
                                                            <?php if ($status !== false && $status == 'valid') { ?>
                                                                <?php wp_nonce_field('wcpa_deactivate', 'wcpa_nounce'); ?>
                                                                <input type="submit" class="button-secondary"
                                                                       name="wcpa_license_deactivate"
                                                                       value="<?php _e('Deactivate License','wcpa-text-domain'); ?>"/>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <?php wp_nonce_field('wcpa_activate', 'wcpa_nounce'); ?>
                                                                <input type="submit" class="button-secondary"
                                                                       name="wcpa_license_activate"
                                                                       value="<?php _e('Activate License','wcpa-text-domain'); ?>"/>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>

                                            </form>
                                            <?php submit_button(null, 'primary', 'wcpa_save_settings'); ?>
                                        </div>
                                    </div>
                                </div>
                                <!--wcpa_g_set_tabcontents-->

                                <div style="clear: both">

                                </div>


                            </form>

                        </div>
                        <!-- .inside -->
                        <div class="support">
                            <h3>Dedicated Support Team </h3>
                            <p>Our support is what makes us No.1. We are available round the clock for any support.</p>
                            <p><a href="https://acowebs.com/support/">Guidelines</a></p>
                            <p><a href="https://support.acowebs.com/portal/newticket">Submit a new Ticket</a></p>
                            <div>

                            </div>
                            <!-- .postbox -->

                        </div>
                        <!-- .meta-box-sortables .ui-sortable -->

                    </div>
                    <!-- post-body-content -->


                    <!-- #postbox-container-1 .postbox-container -->

                </div>
                <!-- #post-body .metabox-holder .columns-2 -->


                <br class="clear">
            </div>
            <!-- #poststuff -->

        </div> <!-- .wrap -->
    </div>
</div>
