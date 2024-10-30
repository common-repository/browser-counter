<?php
/*
Plugin Name: Browser Counter
Plugin URI: 
Description: This plugin counts the number of Hits from Different Browsers and displays in the admin Page
Author: Abbas Suterwala
Version: 1.0
Author URI: 
*/

//This function installs the table used by the plugin
function browsercounter_install()
{
    global $wpdb;
    $table = $wpdb->prefix."browsercounter_counter";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        browsercounter_name VARCHAR(80) NOT NULL,
        browsercounter_mark VARCHAR(80) NOT NULL,
        browsercounter_visits INT(9) DEFAULT 0,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
}

add_action('activate_browsercounter/browsercounter.php', 'browsercounter_install');

//This function get which type of browser the client is using
function browsercounter_get_user_browser()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ub = '';
    if(preg_match('/MSIE/i',$u_agent))
    {
        $ub = "ie";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $ub = "firefox";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $ub = "safari";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $ub = "chrome";
    }
    elseif(preg_match('/Flock/i',$u_agent))
    {
        $ub = "flock";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $ub = "opera";
    }
   
    return $ub;
} 

function browsercounter()
{
    global $wpdb;
   
    $browser_name = browsercounter_get_user_browser();
	if(  $browser_name == '' ) return;
	
    $browsers = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."browsercounter_counter");
	$isUserAgentAlreadyPresent = 0;
	$table = $wpdb->prefix."browsercounter_counter";
	
	
    foreach($browsers as $browser)
    {
        if(eregi($browser->browsercounter_mark, $browser_name) || strcmp($browser->browsercounter_mark, $browser_name)==0 )
        {
		
            $wpdb->query("UPDATE ".$wpdb->prefix."browsercounter_counter SET browsercounter_visits = browsercounter_visits+1 WHERE id = ".$browser->id);
			$isUserAgentAlreadyPresent = 1;
             break;
        }
		
    }

	if( $isUserAgentAlreadyPresent == 0 ) // If User Agent is not already Present
	{
	
		$wpdb->query("INSERT INTO $table(browsercounter_name, browsercounter_mark, browsercounter_visits) VALUES('" .$browser_name."','".$browser_name."',1 )");

	}
}


add_action('wp_footer', 'browsercounter');

function browsercounter_menu()
{
    global $wpdb;
    include 'browsercounteradmin.php';
}
 
function browsercounter_admin_actions()
{
    add_options_page("Browser Counter", "Browser Counter", 1,"Browser-Counter", "browsercounter_menu");
}
 
add_action('admin_menu', 'browsercounter_admin_actions');

?>