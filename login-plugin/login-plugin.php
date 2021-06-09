<?php

/*
Plugin Name: Login plugin
Description: A simplistic login for your custom website.
Version: 1.0
Author: Padraig Montague
*/
require_once('classes/user.php');

if (isset($_POST['login_btn'])){
	
	global $wpdb;
	global $wp_session;
	
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$userObject = new User($username, $password);
	$userObject->login($wpdb);
	
}

if(isset($_POST['register_btn'])) {
	$registerUsername = htmlspecialchars($_POST['username']);
	$registerPassword = htmlspecialchars($_POST['password']);
	$userObject = new User($registerUsername, $registerPassword);
	$userObject->register($wpdb);
}

function renderTemplate() {

	$template = file_get_contents( plugin_dir_url(__FILE__) . '/templates/login.html');
	echo $template;

}

function renderRegisterTemplate() {
	$registerTemplate = file_get_contents(plugin_dir_url(__FILE__) . '/templates/register.html');
	echo $registerTemplate;
}

function login_shortcode() {
	
	ob_start();	
	add_action(init,renderTemplate());	
	return ob_get_clean();
	
}

function registerTemplate_shortcode(){
	ob_start();
	add_action(init, renderRegisterTemplate());
	return ob_get_clean();
}

//Connecting css files to main file

wp_register_style('login-plugin-style', plugin_dir_url(__FILE__) . '/styles/login.css');
wp_enqueue_style('login-plugin-style');

wp_register_style('register-template-style', plugin_dir_url(__FILE__) . '/styles/register.css');
wp_enqueue_style('register-template-style');

add_shortcode('default_login', 'renderTemplate');
add_shortcode('default_register', 'renderRegisterTemplate');

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