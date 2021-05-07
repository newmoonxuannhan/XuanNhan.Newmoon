<?php
// die();
// header("Content-Type: application/json");
// requirements: install plugin : https://wordpress.org/plugins/featured-image-by-url/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);

if(file_exists("wp-load.php"))require( 'wp-load.php' );
else require( '../wp-load.php' );

$querystr = "
SELECT ID FROM $wpdb->posts WHERE post_type = 'product' ORDER BY ID ASC LIMIT 29000
    ";

$results = $wpdb->get_results($querystr, OBJECT);

$counter = 0;

foreach($results as $row) {

	echo $counter++. " -- post_id = ".$row->ID."\n";

	$querystr = "update wp_posts set post_status = 'trash' where id = '".$row->ID."';";
	$post_source = $wpdb->get_results( $querystr, OBJECT );
	// if ( wp_delete_post($row->ID) ) {
	// 	echo  $counter++." DELETED! \n";
	// } else {
	// 	echo  $counter++." FAILED! \n";
	// }
	// break;
}

// $post_source = $wpdb->get_results( $querystr, OBJECT );
// // var_dump($post_source);
// if (!empty($post_source)){
// 	foreach($post_source as $post){
// 		echo $post->ID;
// 		echo "<br/>";
// 	}
// }

$querystr = "
DELETE p FROM wp_posts p WHERE p.post_type = 'product' AND p.post_status = 'trash'
	";

$post_source = $wpdb->get_results( $querystr, OBJECT );

// delete all order
$querystr = "
DELETE p FROM wp_posts p WHERE p.post_type = 'shop_order';
";

$post_source = $wpdb->get_results( $querystr, OBJECT );

$querystr = "
DELETE p FROM wp_posts p WHERE p.post_type = 'product_variation'
";
$post_source = $wpdb->get_results( $querystr, OBJECT );

$querystr = "
DELETE pm FROM wp_postmeta pm LEFT JOIN wp_posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL
";
$post_source = $wpdb->get_results( $querystr, OBJECT );

$querystr = "
DELETE tr FROM wp_term_relationships tr INNER JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.taxonomy != 'link_category' AND tr.object_id NOT IN ( SELECT ID FROM wp_posts )
";
$post_source = $wpdb->get_results( $querystr, OBJECT );


/** delete all order **/

$querystr = "
update wp_posts set post_status = 'trash' where post_type = 'shop_order';
";
$post_source = $wpdb->get_results( $querystr, OBJECT );

/** delete all tags **/
$querystr = "
DELETE a,c FROM wp_terms AS a
LEFT JOIN wp_term_taxonomy AS c ON a.term_id = c.term_id
LEFT JOIN wp_term_relationships AS b ON b.term_taxonomy_id = c.term_taxonomy_id
WHERE c.taxonomy = 'product_tag'
";
$post_source = $wpdb->get_results( $querystr, OBJECT );

echo "FINISHED \n";