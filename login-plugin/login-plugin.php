<?php

/*
Plugin Name: Login plugin
Description: A simplistic login for your custom website.
Version: 1.0
Author: Padraig Montague
*/

if (isset($_POST['login_btn'])){
	
	global $wpdb;
	global $wp_session;
	
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	if(!empty($username) && !empty($password)) {
		$query = $wpdb->get_results($wpdb->prepare("SELECT `username` FROM `wp_defaultlogin` WHERE `password` = '$password'"),ARRAY_A);
	
		if(empty($query)){
			$result = '';
			echo $username;
		}
		else{
			$result = $query['0']['username'];
		}
		
		if($username === $result){
			echo '<h1>Success! You are logged in</h1>';
		}
		else{
			echo '<h1>Incorrect Username or Password</h1>';
		}
	} else {
		echo '<h1>Please enter required data</h1>';	
	}
	
}

function renderTemplate() {

	$template = file_get_contents( plugin_dir_url(__FILE__) . '/templates/login.html');
	echo $template;

}

function login_shortcode() {
	
	ob_start();	
	add_action(init,renderTemplate());	
	return ob_get_clean();
	
}

wp_register_style('login-plugin-style', plugin_dir_url(__FILE__) . '/styles/login.css');
wp_enqueue_style('login-plugin-style');

add_shortcode( 'default_login', 'renderTemplate' );

global $database_version;

$database_version = '1.0';

// Creates mysql table when the plugin is installed.

function plugin_inst() {
	
	global $wpdb;	
	global $database_version;
		
	$table_name = $wpdb->prefix . 'defaultLogin';		
	$charset_collate = $wpdb->get_charset_collate();
		
	$sql = "CREATE TABLE $table_name (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
		username tinytext NOT NULL,
        password tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );	
	dbDelta( $sql );	
	add_option( 'database_version', $database_version );
}
register_activation_hook( __FILE__, 'plugin_inst' );
?>