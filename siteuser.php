<?php
/*
Plugin Name: Site User
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Plugin for user registration and login. These users  are not wp-users.
Version: 1.0
Author: Nethues India
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/
?>
<?php
/* 
==============================
==========ALL Hooks===========
==============================
*/
?>
<?php
global $LoSSiteUserTable, $wpdb, $LoSPluginCurrentVersion;
$LoSSiteUserTable = $wpdb->prefix . "siteusers";
$LoSPluginCurrentVersion = "1.0"; 
 

// include language file...
function nisu_myplugin_init() {
 include( dirname(__FILE__ ) . '/languages/en_US.php' );
}
add_action('plugins_loaded', 'nisu_myplugin_init');

function nisu_safeFormInput($input)
{
	return stripslashes($input);
}

function nisu_safeOutput($input)
{
	return htmlentities($input, ENT_QUOTES);
	
}


// Hooks call when activate the plugin, it will create table in db 

register_activation_hook( __FILE__, 'nisu_siteuser_activate' );

function nisu_siteuser_activate(){	
	global $wpdb;
	global $LoSSiteUserTable, $LoSPluginCurrentVersion;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$LoSPrevVersion = get_option('siteusers_plugin_version');
	
	if($LoSPrevVersion == false){
		if($wpdb->get_var("show tables like '$LoSSiteUserTable'") != $LoSSiteUserTable) {
			$LoSCreateTable = "CREATE TABLE `" . $LoSSiteUserTable . "` (
								`user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
								`username` VARCHAR( 255 ) NULL ,
								`emailaddress` VARCHAR( 255 ) NULL ,
								`password` VARCHAR( 255 ) NULL ,
								`fname` VARCHAR( 255 ) NULL ,
								`lname` VARCHAR( 255 ) NULL ,
								`address1` VARCHAR( 255 ) NULL ,
								`address2` VARCHAR( 255 ) NULL ,
								`city` VARCHAR( 255 ) NULL ,
								`state` VARCHAR( 255 ) NULL ,
								`country` VARCHAR( 255 ) NULL ,
								`zipcode` VARCHAR( 10 ) NULL ,
								`status` TINYINT NOT NULL DEFAULT '0',
								`createddate` DATETIME NULL
								) ENGINE = MYISAM ;";
			dbDelta($LoSCreateTable);     					
			update_option("siteusers_plugin_version", $LoSPluginCurrentVersion);	
			update_option( "usersite_confirm_password", 1 );		
			update_option( "usersite_first_name", 1 );		
			update_option( "usersite_last_name", 1 );		
			update_option( "usersite_address_1", 1 );		
			update_option( "usersite_address_2", 1 );		
			update_option( "usersite_city", 1 );		
			update_option( "usersite_state", 1 );		
			update_option( "usersite_country", 1 );		
			update_option( "usersite_zipcode", 1 );		
			update_option( "usersite_submit_title", "Submit" );				
		}							
	}	
}

// Hooks call when deactivate the plugin

register_deactivation_hook( __FILE__, 'nisu_siteuser_deactivate' );

function nisu_siteuser_deactivate(){	
		
}


// Hooks call when uninstall/delete the plugin, it will delete table from db 

register_uninstall_hook( __FILE__, 'nisu_siteuser_uninstall' );

function nisu_siteuser_uninstall(){	
	global $wpdb;
	global $LoSSiteUserTable, $LoSPluginCurrentVersion;	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$LoSDropTable = "DROP TABLE IF EXISTS " . $LoSSiteUserTable; 
	dbDelta($LoSDropTable); 
	
	delete_option( "usersite_confirm_password");		
	delete_option( "usersite_first_name");		
	delete_option( "usersite_last_name");		
	delete_option( "usersite_address_1");		
	delete_option( "usersite_address_2");		
	delete_option( "usersite_city");		
	delete_option( "usersite_state");		
	delete_option( "usersite_country");		
	delete_option( "usersite_zipcode");		
	delete_option( "usersite_submit_title");			
}

// create plugin settings menu and User manager menu
add_action( 'admin_menu', 'nisu_siteuser_create_menu' );

function nisu_siteuser_admin_init() {	
	wp_register_style( 'myPluginStylesheet', plugins_url('css/admin.css', __FILE__) );		
}

add_action( 'admin_init', 'nisu_siteuser_admin_init' );
 
function nisu_siteuser_create_menu() {
	add_menu_page( "Site Users", "Site Users", 'administrator', "manage_siteuser", 'nisu_siteusermanager', plugins_url('siteuser/images/UserIcon.png') );		
	add_submenu_page( "manage_siteuser", "Registration Settings", "Registration Settings", "administrator", 'manage_siteuser_settings', "nisu_siteusersettings");
	add_submenu_page( 'manage_siteuser', "Edit Site User", "", "administrator", 'edit_site_user', "nisu_editSiteUser");
			
	wp_enqueue_style( 'myPluginStylesheet' );
}

function nisu_siteusermanager() {
	global $wpdb;
	global $LoSSiteUserTable;
	include("manageuser.php");
}


function nisu_siteusersettings() {	
	include("settings.php");
}
function nisu_editSiteUser(){
	include("edit_siteuser.php");
}

add_action( 'wp_enqueue_scripts', 'nisu_add_siteuser_stylesheet' );
function nisu_add_siteuser_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'siteuser-style', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_style( 'siteuser-style' );
 }
/*======= WIDGET FUNCTION==================*/

class siteUserRegistrationLogin_Widget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
	 		'site_user_registration_login', // Base ID
			'Site User Registration/Login', // Name
			array( 'description' => __( 'Site user registration and login form. Allow end user to register through site not as wordpress user.' ), ) // Args
		);
	}

 	public function form( $instance ) {
		// outputs the options form on admin
		
		
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		
	}
	 public function checkErrors($fields) 
	 {
	    global $LoSSiteUserTable;
		global $wpdb;
	 	$errorArry = array();
	 	if($fields['username'] == '')
		{
		  $errorArry[] = 'Please fill username.';
		}
		else
		{
			  $exist = $wpdb->get_var("select user_id from " .$LoSSiteUserTable. " where username ='".$wpdb->escape(nisu_safeforminput($fields['username']))."'");
			  if(count($exist) > 0)
			  {
				$errorArry[] = 'The Username "'.nisu_safeOutput(nisu_safeFormInput($fields['username'])) .'" already exist .';
			  }
		 }
		  if($fields['emailaddress'] == '')
		  {
		   	$errorArry[] = 'Please fill email address.';
		  }
		  else
		  {
			   if ( !is_email($fields['emailaddress']) ) {
				$errorArry[] = 'Please enter an Valid e-mail address.';
				}
				else{
						$exist = $wpdb->get_var("select user_id from " .$LoSSiteUserTable. " where emailaddress ='".$wpdb->escape(nisu_safeFormInput($fields['emailaddress']))."'");
					  	if(count($exist) > 0)
					  	{
							$errorArry[] = 'The email address "'.nisu_safeOutput(nisu_safeFormInput($fields['emailaddress'])) .'" already exist .';
					  	}
				
					}
				
		  }
		if($fields['password'] == '' )
		{
			$errorArry[] = ' Please fill a password.';
		}
		 if(get_option("usersite_confirm_password"))
		 {
		 	if($fields['password'] != $fields['confirm_password']  )
			{
				$errorArry[] = ' Please enter the same password in the two password fields.';
			}
		 }
		
		return $errorArry;
	 }
	 public function nisu_registerUser($fields){
	  global $LoSSiteUserTable;
	  global $wpdb;
	  global $LoSSiteUserTable ;
	  $responseArray = array();
	  $qry = "insert into ".$LoSSiteUserTable ." set ";  
	  $qry .= "username ='".$wpdb->escape(nisu_safeFormInput($fields['username']))."'" ;
	  $qry .= " , emailaddress ='".$wpdb->escape(nisu_safeFormInput($fields['emailaddress']))."'" ;
	  $qry .= " , password='".md5($fields['password'])."'" ;
	  $qry .= " , fname ='".$wpdb->escape(nisu_safeFormInput($fields['fname']))."'" ;
	  $qry .= " , lname ='".$wpdb->escape(nisu_safeFormInput($fields['lname']))."'" ;
	  $qry .= " , address1 ='".$wpdb->escape(nisu_safeFormInput($fields['address1']))."'" ;
	  $qry .= " , address2 ='".$wpdb->escape(nisu_safeFormInput($fields['address2']))."'" ;
	  $qry .= " , city ='".$wpdb->escape(nisu_safeFormInput($fields['city']))."'" ;
	  $qry .= " , state ='".$wpdb->escape(nisu_safeFormInput($fields['state']))."'" ;
	  $qry .= " , country ='".$wpdb->escape(nisu_safeFormInput($fields['country']))."'" ;
	  $qry .= " , zipcode ='".$wpdb->escape(nisu_safeFormInput($fields['zipcode']))."'" ;
	  $qry .= " , status  = 1" ;
	  $qry .= " , createddate = now()" ;			
	  	
		$response = $wpdb->query($qry);
		if($response){
		
		 	$responseArray[] = 'User registered sucessfully';
		}
		else
		{
			$responseArray[] = 'User could not be registered'; 
		}
		return $responseArray;
	 }

public function nisu_sendMailUser($tomail,$subject,$message,$headers) 
{
  mail($tomail,$subject,$message,$headers);
}


public function nisu_buildRegistrationForm()
{
?>
		<form method="post" action="" class="registerform" name="registerform" >
		<p class="formblock">
		<label><?php echo SITEUSER_USERNAME; ?><sup>*</sup></label> 
		<input class="widefat" id="username" name="username" value="<?php echo (isset($_REQUEST['username']))? nisu_safeOutput(nisu_safeFormInput($_REQUEST['username'])) : '';?>" type="text"  />
		</p>
		<p class="formblock">
		<label><?php echo SITEUSER_EMAILADDRESS; ?><sup>*</sup></label> 
		<input class="widefat" id="emailaddress" name="emailaddress" type="text" value="<?php echo isset($_REQUEST['emailaddress'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['emailaddress'])) : '';?>" />
		</p>
		<p class="formblock">
		<label><?php echo SITEUSER_PASSWORD; ?><sup>*</sup></label> 
		<input class="widefat" id="password" name="password" type="password" value="" />
		</p>
		 <?php if(get_option("usersite_confirm_password")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_CONFIRM_PASSWORD; ?><sup>*</sup></label> 
		<input class="widefat" id="confirm_password" name="confirm_password" type="password" value="" />
		</p><?php } ?>
		<?php if(get_option("usersite_first_name")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_FIRST_NAME; ?></label> 
		<input class="widefat" id="fname" name="fname" type="text" value="<?php echo isset($_REQUEST['fname'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['fname'])) : '';?>" />
		</p><?php } ?>
		<?php if(get_option("usersite_last_name")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_LAST_NAME; ?></label> 
		<input class="widefat" id="lname" name="lname" type="text" value="<?php echo isset($_REQUEST['lname'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['lname'])) : '';?>" />
		</p><?php } ?>
		<?php if(get_option("usersite_address_1")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_ADDRESS1; ?></label> 
		<input class="widefat" id="address1" name="address1" type="text" value="<?php echo isset($_REQUEST['address1'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['address1'])) : '';?>" />
		</p><?php } ?>	
		<?php if(get_option("usersite_address_2")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_ADDRESS2; ?></label> 
		<input class="widefat" id="address2" name="address2" type="text" value="<?php echo isset($_REQUEST['address2'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['address2'])) : '';?>" />
		</p><?php } ?>
		
		<?php if(get_option("usersite_city")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_CITY; ?></label> 
		<input class="widefat" id="city" name="city" type="text" value="<?php echo isset($_REQUEST['city'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['city'])) : '';?>" />
		</p><?php } ?>
		<?php if(get_option("usersite_state")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_STATE; ?></label> 
		<input class="widefat" id="state" name="state" type="text" value="<?php echo isset($_REQUEST['state'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['state'])) : '';?>" />
		</p><?php } ?>	
		<?php if(get_option("usersite_country")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_COUNTRY; ?></label> 
		<input class="widefat" id="country" name="country" type="text" value="<?php echo isset($_REQUEST['country'])? nisu_safeOutput(nisu_safeFormInput($_REQUEST['country'])) : '';?>" />
		</p><?php } ?>					
		<?php if(get_option("usersite_zipcode")){?>
		<p class="formblock">
		<label><?php echo SITEUSER_ZIPCODE; ?></label> 
		<input class="widefat" id="zipcode" name="zipcode" type="text" value="<?php echo isset($_REQUEST['zipcode'])? $_REQUEST['zipcode'] : '';?>" />
		</p><?php } ?>			
		
		<p class="formblock"><label>&nbsp;</label><input type="submit" name="register_user" value="<?php echo nisu_safeOutput(get_option("usersite_submit_title")) ;?>" />
        <p class="formblock"><label>&nbsp;</label><a onclick="javascript:disp_hide('loginform',0);" href="javascript:void(0)">Login</a></p>
        <input type="hidden" name="actiondo" value="register"  />
		</form><?php
}


public function nisu_buildLoginForm()
{?>
		<form method="post" action="" class="loginform" name="loginform" >
		<p class="formblock">
		<label><?php echo SITEUSER_USERNAME; ?><sup>*</sup></label> 
		<input class="widefat" id="username_login" name="username_login" type="text" value="<?php echo nisu_safeOutput(nisu_safeFormInput($_REQUEST['username_login'])) ; ?>" />
		</p>
		<p class="formblock">
	
		<label><?php echo SITEUSER_PASSWORD; ?><sup>*</sup></label> 
		<input class="widefat" id="password_login" name="password_login" type="password" value="" />
		</p>	
		<p class="formblock"><label>&nbsp;</label> <input type="submit" name="login_user" value="<?php echo nisu_safeOutput(get_option("usersite_submit_title")) ;?>" /></p>
		<p class="formblock"><label>&nbsp;</label><a onclick="javascript:disp_hide('regform',0);" href="javascript:void(0)">Register</a></p>
		<input type="hidden" name="actiondo" value="login"  />
        </form><?php
}

	public function widget( $args, $instance ) {
	
	?>
    <script type="text/javascript">
	function disp_hide(id,opt)
	 {
	   if(id == 'loginform')
	   {
			if(document.getElementById('loginform')){
			document.getElementById('loginform').style.display = ''; 
			}
			
			if(document.getElementById('regform')){
			document.getElementById('regform').style.display = 'none';
			}
			
			if(document.getElementById('registererror')&& opt == 0)
			{
				document.getElementById('registererror').style.display = 'none';
			}	
	   }
	   else
	   {
			document.getElementById('loginform').style.display = 'none'; 
			document.getElementById('regform').style.display = '';
			if(document.getElementById('loginerror')&& opt == 0)
			{
				document.getElementById('loginerror').style.display = 'none';
	   		}
	   }	

	 }
	</script>
    <?php
	 if(isset($_REQUEST['logout']) && $_REQUEST['logout'] == 1)
		 {
		 	nisu_endSession();
			exit();
		 }
	
	     if(isset($_POST['register_user']))
		 {
			   $errors = $this->checkErrors($_POST);  
			 
			 if(count($errors) > 0)
			 {
				echo '<div id="registererror" ><strong>Errors:</strong><ul>';
				foreach($errors as $error)
				{
					echo "<li>".$error."</li>";
				}
				echo '</ul></div>';
			 }
			 else
			 {
			  	$response = $this->nisu_registerUser($_POST);
			 } 
			 
			 if(count($response) > 0)
			 {
		   $subject = 'Welcome to '.get_bloginfo('name');
		   
		   $mail_to_name = nisu_safeFormInput($_POST['username']);
		    $mail_to_mail = $_POST['emailaddress'];
		   $name = get_bloginfo('name');

		   $headers = "From: $name<$name>\n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";
		   
		   	$message = 'Hello '. nisu_safeOutput(nisu_safeFormInput($_POST['username'])).",<br/>";
			$message .= 'Welcome to the '.get_bloginfo('name')."<br/><br/>";
			
			$message .= 'Username: '.nisu_safeOutput(nisu_safeFormInput($_POST['username']))."<br/>";
			$message .= 'Password: '.$_POST['password']."<br/>";
			$message .= 'URL : '.'<a href="'.home_url().'">'.home_url().'</a>';
			$message .= "<br/><br/>";
			$message .= get_bloginfo('name')." Team";
			$varHTML = '';
		$varHTML = $varHTML . '<div style="font-family:Verdana; font-size:12px;">';
		$varHTML = $varHTML . '[DATA]';
		$varHTML = $varHTML . '</div>';
		$varHTML = str_replace("[DATA]", $message, $varHTML);

			
			   $this->nisu_sendMailUser($_POST['emailaddress'],$subject,$varHTML,$headers); 
				nisu_loginUser($_POST['username'],$_POST['password']);
				echo '<div id="sucess" >';	
				echo $response[0];
				echo '</div>';
				 
			 }
		 }
		 
		 if(isset($_REQUEST['login_user']))
		 {
				$loginerror = array();
				// login to user
				if(isset($_REQUEST['username_login']) && $_REQUEST['username_login'] == '' )
				 {
					 $loginerror[] = 'Please fill Username.';
				 }
				 if(isset($_REQUEST['password_login']) && $_REQUEST['password_login'] == '' )
				 {
					$loginerror[] = 'Please fill Password.';
				 }
				 if(empty($loginerror))
				 {
					  if(nisu_loginUser(nisu_safeFormInput($_REQUEST['username_login']),$_REQUEST['password_login']))
					   {
						//do nothing
					   }
					  else
					  {
							$loginerror[] = 'username/password you provided are not valid';
					  }
				 }
			 
				if(!empty($loginerror)) 
				{
					echo '<div id="loginerror" > <strong>Errors:</strong><ul>';	
					foreach($loginerror as $lr ) 
					{
						echo "<li>".$lr."</li>"; 	
					}
					echo '</ul></div>';			 
				}		 
		 }
		  
		// outputs the content of the widget
		$LoSWidgetTitle = (isset($instance['title']) && $instance['title']!="")?$instance['title']:"";
		     
		   global $user_ID;
			if(!$_SESSION['user']['id']) 
			{	
				echo '<div style="display:none;" id="regform">';
				$this->nisu_buildRegistrationForm();
				echo '</div>';
				
				echo '<div id="loginform">';
				$this->nisu_buildLoginForm();
				echo '</div>';
			}
			else
			{
			 echo "welcome ".$_SESSION['user']['username']." &raquo; ".'<a href="?logout=1">Logout</a>';
			}
		
			   if($_REQUEST['actiondo'] == "login" || isset($response[0]))
			   {
					echo '<script type="text/javascript">disp_hide("loginform",1);</script>';
			   }
			   else if($_REQUEST['actiondo'] == "register")
			   {
					echo '<script type="text/javascript">disp_hide("regform",1);</script>';
			   }
		}

}  // class widget

add_action( 'widgets_init', create_function( '', 'register_widget( "siteUserRegistrationLogin_Widget" );' ) );
add_action('init', 'nisu_startSession', 1);


/*======= PLUGIN FUNCTION==================*/
// 0 for inactive, 1 for actice and "" for all
function nisu_startSession() {
    if(!session_id()) {
        session_start();
    }
}
function nisu_endSession() {
    session_destroy ();
	echo "<script type=\"text/javascript\">window.location = '".home_url()."'</script>";
	exit;
}
function nisu_getTotalUser($LoIUserType = ""){
	global $LoSSiteUserTable; global $wpdb;$recs_u = '';
  	$qry_u = "select * from ".$LoSSiteUserTable." where 1 = 1";
	if($LoIUserType == '0' || $LoIUserType == '1' ){
	 $qry_u .= " and status = ".$LoIUserType;
	}
    $recs_u = $wpdb->get_results($qry_u);
	return $wpdb->num_rows;

}

function nisu_loginUser($username,$password)
{
 global $wpdb;
				  global $LoSSiteUserTable ;
				  $responseArray = array();$responseq = '';
				  $qryq = "select *  from ".$LoSSiteUserTable ." ";  
				  $qryq .= "where username  ='".$username."'" ;
				  $qryq .= " and password ='".md5($password)."' and status = 1" ;		
				  $responseq = $wpdb->get_results($qryq);
				  if(count($responseq) >  0 ) 
				  {
					 foreach($responseq as $res)
					 {
				     $_SESSION['user']['id'] = $res->user_id;
					 $_SESSION['user']['username'] = nisu_safeOutput($res->username);
					 $_SESSION['user']['emailaddress'] = $res->emailaddress;
					 $_SESSION['user']['fname'] = nisu_safeOutput($res->fname);
					 $_SESSION['user']['lname'] = nisu_safeOutput($res->lname);
					 }
					 return 1;
				  }
				  else{
				  
				  return 0;
				  }

}
function nisu_performAction($userArr,$action)
{
	global $LoSSiteUserTable; global $wpdb;
	if($action == 'trash')
	{
		$qry_del = "delete from ".$LoSSiteUserTable." where user_id in(".implode(',',$userArr).")";
  		if($wpdb->query($qry_del)){
			return "Deleted Sucessfully";
		}
		
	}
	if($action == 'active')
	{
		$qry_del = "update ".$LoSSiteUserTable." set status = 1 where user_id in(".implode(',',$userArr).")";
  		if($wpdb->query($qry_del)){
			return "Updated Sucessfully";
		}
	}
	if($action == 'inactive')
	{
		$qry_del = "update ".$LoSSiteUserTable." set status = 0 where user_id in(".implode(',',$userArr).")";
  		if($wpdb->query($qry_del)){
			return "Updated Sucessfully";
		}
	}
}
?>