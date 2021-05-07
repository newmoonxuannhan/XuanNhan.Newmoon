<?php
define('SITE_ROOT', dirname(__FILE__));

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);

if(file_exists("wp-load.php"))require( 'wp-load.php' );
else require( '../wp-load.php' );

$dir    = ABSPATH.'/wp-content/uploads/import_csv';//'/var/www/newmoon_demo2/wp-content/uploads/import_csv';//SITE_ROOT.'/newmoon_import';
$force_update = true;
$force_update_image= true;
$force_update_color= false;
// $config_content= file_get_contents(SITE_ROOT.'/scrapy_shirt/config_227.ini');
// $pattern='/folder_images = (.+)[\r\n]/';
// if (preg_match($pattern, $config_content, $match)) {
//     $folder_name = $match[1];
// } else {
//     echo "KHONG TIM THAY folder_name trong /scrapy_shirt/config_227.ini";
//     die();
// }
if (!defined('WCPA_PRODUCT_META_KEY')) {
    echo "Chua cai dat plugin Custom Product Add On For Woocommerce";
    exit();
}

// if (!defined('KNAWATFIBU_VERSION')) {
//     echo "Chua cai dat plugin KNAWATFIBU plugin";
//     exit();
// }
define('SKU_PREFIX', 'NM');

$files = scandir($dir);

$category_cache = array();

foreach($files as $file_name) {
    if (strpos(strtolower($file_name), ".csv") !== false) {
        $file_name_csv = $dir."/".$file_name;
    } else {
        continue;
    }

    $file = fopen($file_name_csv, "r");

    $counter = 0;
    $offset_row = 0;

    // while(! feof($file))
    while (($csv = fgetcsv($file, 0, ",")) !== FALSE)
    {
        $counter++;
        // echo $counter; echo "\n";
        // retrieve csv
        // $csv = fgetcsv($file);
        if ($counter == 1) continue;
        if ($counter < $offset_row ) continue;

        $title = ucwords($csv[0]); // title
        // $title = preg_replace("/[^A-Za-z0-9 ]/", '',$title);

        $images1 = $csv[1]; if ($images1) $mmolazi_gallery_images[] = $images1; // Image Link 1
        $images2 = $csv[2]; if ($images2) $mmolazi_gallery_images[] = $images2;// Imange Link 2
        $images3 = $csv[3]; if ($images3) $mmolazi_gallery_images[] = $images3; // Image lInk 3
        $images4 = $csv[4]; if ($images4) $mmolazi_gallery_images[] = $images4;// Image Link 4

        $mmolazi_gallery_images = array($images1, $images2, $images3, $images4);

        $sku = $csv[5]; // SKU
        $link = $csv[6]; // Link
        $mmolazi_type = $csv[7]; // mmolazi type
        $tags_str = $csv[8]; // Tags
        $category_str = $csv[9]; // Category

        if (empty($mmolazi_type)) {
            // echo "KHONG NHAN DANG DC BLACK / WHITE \n";
            continue;
        }

        if (empty($category_str)) {
            // echo "KHONG TIM THAY CATEGORIES \n";
            continue;
        }

        if ( $category_str == "sweatshirt") {
            if ($mmolazi_type == "tshirt-background-black") {
                $mmolazi_type = "sweatshirt-black";
            } else if ($mmolazi_type == "tshirt-background-white") {
                $mmolazi_type = "sweatshirt-white";
            }
            $category_str = "Sweatshirt";
            $price = 34.99;
            if (strpos(strtolower($title), "sweatshirt") === false) {
                $title = $title. " Sweatshirt";
            }
        } else if ( $category_str == "long-sleeve" ) {
            if ($mmolazi_type == "tshirt-background-black") {
                $mmolazi_type = "long-sleeve-black";
            } else if ($mmolazi_type == "tshirt-background-white") {
                $mmolazi_type = "long-sleeve-white";
            }
            $category_str = "Long Sleeve";
            $price = 25.99;
            if (strpos(strtolower($title), "long sleeve") === false) {
                $title = $title. " Long Sleeve Tee";
            }
        } else if ( $category_str == "hoodie" ) {
            if ($mmolazi_type == "tshirt-background-black") {
                $mmolazi_type = "hoodie-black";
            } else if ($mmolazi_type == "tshirt-background-white") {
                $mmolazi_type = "hoodie-white";
            }
            $category_str = "Hoodie";
            $price = 36.99;
            if (strpos(strtolower($title), "hoodie") === false) {
                $title = $title. " Hoodie";
            }
        } else if ( $category_str == "t-shirt" ) { // T Shirt
            $price = 16.99;
            if (strpos(strtolower($title), "shirt") === false) {
                $title = $title. " T-Shirt";
            }
        } else if ( strtolower($mmolazi_type) == "pod-shoes" ) { // T Shirt            
            $price = 230; //" 230-250-280-300-350";
        } else {
            // echo "KHONG NHAN DANG $category_str trong he thong \n";
            continue;
        }

        if (strpos($link, "http") === false) {
            // echo "Khong tim thay Link $link";
            continue;
        }

        $querystr = "
                SELECT post_id
                FROM $wpdb->postmeta
                WHERE meta_key = 'source' AND meta_value = '".$link."'
                ORDER BY meta_value ASC LIMIT 1
            ";

        $post_source = $wpdb->get_results( $querystr, OBJECT );
        if (count($post_source)) {
            // print($tags_str);
            // wp_set_post_terms($post_source[0]->post_id, $tags_str, 'product_tag');
            // echo "$link .\n Da Import Roi \n";
            if ($force_update) {

                $product_id =$post_source[0]->post_id;

                if ($product_id) {

                    if ($force_update_image) {

                        $image_url = array_shift($mmolazi_gallery_images);
                        print($image_url);
                        $attachmentid = get_post_thumbnail_id($product_id);

                        if ($attachmentid && $attachmentid != $product_id ){
                            $ext = wp_check_filetype($image_url)['type'];
                            // echo $qr;
                            $qr_insert_post_image = "UPDATE wp_posts SET post_mime_type = '$ext', guid = '$image_url' WHERE ID = $attachmentid;";
                            $wpdb->get_results($qr_insert_post_image);

                            $attachment_meta = array(
                                'width' => '800',
                                'height' => '800',
                                'file' => $image_url,
                                'sizes' => array(),
                                'mime-type' => $ext,
                            );

                            update_post_meta($attachmentid, '_wp_attached_file', $image_url);
                            update_post_meta($attachmentid, '_wp_attachment_metadata', $attachment_meta);

                            $_knawatfibu_wcgallary = array();
                            foreach( $mmolazi_gallery_images as $url) {
                                if ($url) {
                                    $_knawatfibu_wcgallary[] = array(
                                        'url' => $url,
                                        'width' => '',
                                        'height' => '',
                                    );
                                }
                            }
                            if (!empty($mmolazi_gallery_images)) {
                                update_post_meta($product_id, '_knawatfibu_wcgallary', $_knawatfibu_wcgallary);
                                // echo " Da xu ly Gallery Image \n";
                            }
                        }
                    }

                    if ($force_update_color) {
                        if (defined('WCPA_PRODUCT_META_KEY')) {
                            $meta_field = array();
                            $sql = "SELECT ID FROM wp_posts WHERE post_name LIKE '$mmolazi_type' AND post_type = 'wcpa_pt_forms' LIMIT 1";
                            $rows = $wpdb->get_results($sql, OBJECT);
                            if (count($rows)) {
                                $form_id = $rows[0]->ID;
                                $meta_field[] = $form_id;
                                update_post_meta($product_id, WCPA_PRODUCT_META_KEY, $meta_field);
                            }
                        } else {
                            // echo "KHONG TIM THAY WCPA_PRODUCT_META_KEY";
                            break;
                        }
                    }

                }
            }
            continue;
        }

        // $json = $row;
        $title  = esc_sql($title);
        $description = "
            $title
            [block id='pod-shoes']
        ";

        $post = array(
            'post_content' => $description,
            'post_name' => sanitize_title($title),
            'post_title' => $title,
            'post_status' => 'publish',
            'post_type' => 'product',
        );

        $product_id = wp_insert_post( $post, $wp_error );

        if (is_wp_error($product_id)) {
            // echo "<pre>";
            // var_dump($wp_error);
            var_dump($csv);
            // echo "</pre>";
            continue;
            // exit('failed: cannot import wp_insert_post');
        }

        if (empty($product_id)) {
            continue;
        }
        else {
            // echo "Create New Product: ".$product_id. "\n";
        }

        //SET THE WOO PRODUCT TYPE
        wp_set_object_terms($product_id, 'simple', 'product_type');

        // insert tags
        // echo $tags_str. "\n";
        if ($tags_str) {
            wp_set_post_terms($product_id, $tags_str, 'product_tag');
        }

        // insert category
        // echo $category_str. "\n";
        if ($category_str) {
            if (!isset($category_cache[$category_str])) {
                $category = get_term_by( 'name', $category_str, 'product_cat');
                if ($category) {
                    $cat_id = $category->term_id;
                } else {
                    $category = wp_insert_term(
                        $category_str, // the term
                        'product_cat', // the taxonomy
                        array(
                          'description'=> $category_str,
                          'slug' => sanitize_title($category_str)
                        )
                    );
                    $cat_id = $category->term_id;
                    // echo "Category New $cat_id";
                }

                $category_cache[$category_str] = $cat_id;

            } else {
                $cat_id = $category_cache[$category_str];
            }
            wp_set_object_terms($product_id, $cat_id, 'product_cat');
        }

        // update_post_meta($product_id, 'mmolazi_gallery_images', trim(implode("|", $mmolazi_gallery_images), "|"));
        update_post_meta($product_id, '_price', $price);
        update_post_meta($product_id, '_regular_price', $price);
        update_post_meta($product_id, '_sku', SKU_PREFIX.$product_id);
        update_post_meta($product_id, 'source', $link);

        $image_name = $title;
        $image_url = array_shift($mmolazi_gallery_images);

        // fifu_dev_set_image($product_id, $image_url);
        // update_post_meta($product_id, 'fifu_image_alt', $image_name);

        /*
        $_knawatfibu_url = array(
            'img_url' => $image_url,
            'width' => '',
            'height' => '',
        );
        update_post_meta($product_id, '_knawatfibu_url', $_knawatfibu_url);
        update_post_meta($product_id, '_knawatfibu_alt', $image_name);
        */

        $_knawatfibu_wcgallary = array();
        foreach( $mmolazi_gallery_images as $url) {
            if ($url) {
                $_knawatfibu_wcgallary[] = array(
                    'url' => $url,
                    'width' => '',
                    'height' => '',
                );
            }
        }
        if (!empty($mmolazi_gallery_images)) {
            update_post_meta($product_id, '_knawatfibu_wcgallary', $_knawatfibu_wcgallary);
        }


        // // featured image chuan simple product woo
        if ($image_name && $image_url) {
            $post_content = $image_name;
            $post_title = $image_name;

            $ext = wp_check_filetype($image_url)['type'];
            // echo $qr;
            $qr_insert_post_image = "INSERT INTO wp_posts (post_type, guid, post_status, post_mime_type,post_parent,post_content,post_title,post_name,post_excerpt, post_date, post_modified) VALUES ('attachment', '".$image_url."', 'inherit', '$ext',".$product_id.", '$post_content', '$post_title', '$post_title', '$post_title', now(), now());";
            $wpdb->get_results($qr_insert_post_image);
            $attachmentid = $wpdb->insert_id;

            $attachment_meta = array(
                'width' => '800',
                'height' => '800',
                'file' => $image_url,
                'sizes' => array(),
                'mime-type' => $ext,
            );

            update_post_meta($attachmentid, '_wp_attached_file', $image_url);
            update_post_meta($attachmentid, '_wp_attachment_image_alt', $image_name);
            update_post_meta($attachmentid, '_wp_attachment_metadata', $attachment_meta);
            update_post_meta($product_id, '_thumbnail_id', $attachmentid);

            // echo "DA XU LY DATABASE IMAGE \n<br>";
        }

        // $attachmentid = get_post_thumbnail_id($product_id);

        // if (!empty($attachmentid) && !empty($image_name)) {
        //     $qr_insert_post_image = "UPDATE wp_posts SET post_content = '$image_name' , post_title = '$image_name' ,post_name = '$image_name', post_excerpt = '$image_name' WHERE ID = $attachmentid ;";
        //     $wpdb->get_results($qr_insert_post_image);
        //     update_post_meta($attachmentid, '_wp_attachment_image_alt', $image_name);
        // } else {
        //     echo "KHONG TIM THAY attachment_id cho product_id $product_id";
        //     break;
        // }

        if (defined('WCPA_PRODUCT_META_KEY')) {
            $meta_field = array();
            $sql = "SELECT ID FROM wp_posts WHERE post_name LIKE '$mmolazi_type' AND post_type = 'wcpa_pt_forms' LIMIT 1";
            $rows = $wpdb->get_results($sql, OBJECT);
            if (count($rows)) {
                $form_id = $rows[0]->ID;
                $meta_field[] = $form_id;
                update_post_meta($product_id, WCPA_PRODUCT_META_KEY, $meta_field);
            }
        } else {
            // echo "KHONG TIM THAY WCPA_PRODUCT_META_KEY";
            break;
        }

        $message = "success";

        $response = array();
        $response[0] = array(
            'product_id' => $product_id,
            'error'=> False,
            'message'=> $message
        );

        // echo json_encode($response);

        // echo "\n";
        unset($csv);
    }

    // echo "FINISHED!!!"; echo "\n";
    // close file
    fclose($file);
    unlink($file_name_csv);
}

echo "FINISHED!!! ";
