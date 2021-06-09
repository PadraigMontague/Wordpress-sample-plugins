<?php
class User {
    public $username;
    public $password;
    function __construct($username, $password){
        $this->username = $username;
        $this->password = $password;
    }

    public function login($wpdb) {
        if(!empty($this->username) && !empty($this->password)) {
            $query = $wpdb->get_results($wpdb->prepare("SELECT `password` FROM `wp_defaultlogin` WHERE `username` = '$this->username'"),ARRAY_A);
            if(empty($query)){
                $result = '';
            }
            else{
                $encyptedPassword = $query['0']['password'];
                if(password_verify($this->password, $encyptedPassword)) {
                    echo '<h1>Success! You are logged in</h1>';
                } 
                else{
                    echo '<h1>Incorrect Username or Password</h1>';
                }
            }
    }
}

	public function alreadyExists($wpdb) {
            $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(`id`) FROM `wp_defaultlogin` WHERE `username` = '$this->username'"));
			if ((int)$result > 0) {
				return true;
			}
			else {
				return false;
			}
		}

    public function register($wpdb) {

        if(!empty($this->username) && !empty($this->password)) {
            if($this->alreadyExists($wpdb) === false) {
                $user_pass = password_hash($this->password, PASSWORD_DEFAULT);
                $query = $wpdb->get_results($wpdb->prepare("INSERT INTO `wp_defaultlogin` (`username`, `password`) VALUES('$this->username', '$user_pass')"));   
            } else {
                echo '<h1>Username already taken</h1>';	                
            }
        } else {
            echo '<h1>Please enter required data</h1>';	
        }
    }
}
?>