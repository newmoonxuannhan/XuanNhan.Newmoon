<?php
/**
* Plugin Name: Newmoon Import Product Plugin
* Plugin URI: http://newmoon2.local
* Description: This is import product .
* Version: 1.2
* Author: Xuan Nhan
* Author URI: http://newmoon2.local
* Text Domain: newmoon-import-plugin
**/


define('SITE_ROOT', dirname(__FILE__));
//Top Bar Plugin Page

function import_plugin_page() {
	$page_title = ' Newmoon Import';
	$menu_title = 'Newmoon Import';
	$capatibily = 'manage_options';
	$slug = 'import-plugin';
	$callback = 'import_page_html';
	$icon = 'dashicons-upload';
	$position = 60;

	add_menu_page($page_title, $menu_title, $capatibily, $slug, $callback, $icon, $position);
}

add_action('admin_menu', 'import_plugin_page');


add_action('admin_init', 'import_register_settings');

function import_page_html() {
  
  $home = get_home_url();
  ?>
 <link rel="stylesheet" href="<?php echo $home ?>/wp-content/plugins/newmoon-import-plugin/css/newmoon_import.css" />
  

 <h2>File Upload</h2>

<!-- Upload  -->
<form id="file-upload-form" class="uploader"action="" method="post" enctype="multipart/form-data">
  <input id="file-upload" type="file" name="fileToUpload"  />
  <label for="file-upload" id="file-drag">
    <div id="start">
      <i class="fa fa-download" aria-hidden="true"></i>
      <div>Select a file or drag here</div>
      <div id="notimage" class="hidden">Please select an image</div>
      <span id="file-upload-btn" class="btn btn-primary">Select a file</span>
    </div>
    <div id="response" class="hidden">
      <div id="messages"></div>
      <progress class="progress" id="file-progress" value="0">
        <span>0</span>%
      </progress>
    </div>
    
  </label>
  <input class="btn btn-primary" type="submit" value="Upload File" name="submittheform">
</form>
<script src='<?php echo $home ?>/wp-content/plugins/newmoon-import-plugin/js/import.js' ></script>

<?php
    global $wp_filesystem;
    WP_Filesystem();
    // path upload csv
    $content_directory = $wp_filesystem->wp_content_dir() . 'uploads/';
    $wp_filesystem->mkdir( $content_directory . 'import_csv' );
    $target_dir_location = $content_directory . 'import_csv/';
    
    //submit 
    if (isset($_POST['submittheform']) && isset($_FILES['fileToUpload'])) {
        $name_file = $_FILES['fileToUpload']['name'];
        $tmp_name = $_FILES['fileToUpload']['tmp_name'];

        //move file upload csv 
        if (move_uploaded_file($tmp_name, $target_dir_location . $name_file)) {
            
          ?><h2><?php echo "File was successfully uploaded".'<br>';?></h2><?php
            $path = SITE_ROOT.'/newmoon_import_scrapy.php';
            
            shell_exec('php '.$path.'>/dev/null 2>&1 & ');
            
        } else {
            echo "The file was not uploaded".'<br>';
        }
    }
    
    if(!is_dir_empty($target_dir_location)){
      $files = scandir($target_dir_location);

      foreach ($files as $file_name) {
          // echo $file_name;
          if (strpos(strtolower($file_name), ".csv") !== false) {
              ?><h2><?php echo $file_name.' - processing....'.'<br>'; ?></h2><?php
          }
      }
      
    }
    else {
      ?><h2><?php echo 'Finish !!!';?></h2><?php
    }
    
   
    
}

function is_dir_empty($dir) {
  if (!is_readable($dir)) {
      return null;
  }

  return (count(scandir($dir)) == 2);
}

require_once ABSPATH . 'wp-content/plugins/newmoon-import-plugin/plugin-update-checker/plugin-update-checker.php';

//self-hosted Plugin 
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://newmoon.sfo3.digitaloceanspaces.com/wordpress-plugins/newmoon-import-plugin/newmoon-import-plugin.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'newmoon-import-plugin'
);

