<script type='text/javascript' src='<?php echo admin_url() ;?>load-scripts.php?c=1&amp;load=admin-bar,hoverIntent,common,jquery-color,password-strength-meter,user-profile&amp;ver=a7fb631618cd49271fc9d5062e35503e'></script>
<script type="text/javascript">
/* <![CDATA[ */
var pwsL10n = {"empty":"Strength indicator","short":"Very weak","bad":"Weak","good":"Medium","strong":"Strong","mismatch":"Mismatch"};/* ]]> */
</script>

<?php 
global $LoSSiteUserTable; global $wpdb;
if(isset($_REQUEST['editProfile']))
{
	if($_REQUEST['emailaddress'] == '') {
	 	$error = ' Please enter an e-mail address.';
	}
	elseif($_REQUEST['emailaddress'] != '')
	{
		if ( !is_email($_REQUEST['emailaddress']) ) {
			$error = ' Please enter an Valid e-mail address.';
		}
	}
	else{
	
	}
	 if($_REQUEST['password'] != $_REQUEST['repassword']  )
	 {
	  $error = ' Please enter the same password in the two password fields.';
	 }
	
	if($error == '') {
		$qry_up = "update ".$LoSSiteUserTable." set "; 
		$qry_up .="emailaddress ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['emailaddress']))."',";
		$qry_up .="password ='".md5($_REQUEST['pass1'])."',";
		$qry_up .="fname ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['fname']))."',";
		$qry_up .="lname ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['lname']))."',";
		$qry_up .="address1  ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['address1']))."',";
		$qry_up .="address2 ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['address2']))."',";
		$qry_up .="city ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['city']))."',";
		$qry_up .="state ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['state']))."',";
		$qry_up .="country ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['country']))."',";
		$qry_up .="zipcode ='".$wpdb->escape(nisu_safeFormInput($_REQUEST['zipcode']))."',";
		$qry_up .="status ='".$_REQUEST['status']."'";
		$qry_up .=" where user_id  ='".$_REQUEST['user_id']."'";
		$wpdb->query($qry_up);
		$msg = 1; 
	}
}
$edit_id = $_REQUEST['uid'];
$qry_ui = "select * from ".$LoSSiteUserTable." where user_id = ".$edit_id;
$recs_ui = $wpdb->get_var($qry_ui);
			if($recs_ui > 0) { 
			$data_ui = $wpdb->get_results($qry_ui);
			foreach($data_ui as $data_ui) {?>

<div id="profile-page" class="wrap">
  <div class="icon32" id="icon-users"><br>
  </div>
  <h2> Edit</h2>
  <?php if($msg) { ?>
  <div class="updated" id="message">
    <p><strong>Profile updated.</strong></p>
  </div>
  <?php } ?>
  
    <?php if($error) { 
     echo '<div class="error"><p><strong>ERROR</strong>:'. $error.'</p></div>';
     } ?>
  <form method="post" action="" id="your-profile">
    <h3>Personal Options</h3>
    <h3>Name</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th><label for="user_login">Username</label></th>
          <td><input type="text" class="regular-text" disabled="disabled" value="<?php echo nisu_safeOutput($data_ui->username) ;?>" id="user_login" name="user_login">
            <span class="description">Usernames cannot be changed.</span></td>
        </tr>
        <tr>
          <th><label for="first_name">First Name</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->fname) ;?>" id="fname" name="fname"></td>
        </tr>
        <tr>
          <th><label for="last_name">Last Name</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->lname) ;?>" id="lname" name="lname"></td>
        </tr>
      </tbody>
    </table>
    <h3>Contact Info</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th><label for="emailaddress">E-mail <span class="description">(required)</span></label></th>
          <td><input type="text" class="regular-text" value="<?php echo $data_ui->emailaddress ;?>" id="emailaddress" name="emailaddress">
          </td>
        </tr>
        <tr>
          <th><label for="address1">Address 1</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->address1) ;?>" id="address1" name="address1"></td>
        </tr>
        <tr>
          <th><label for="address2">Address 2</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->address2) ;?>" id="address2" name="address2"></td>
        </tr>
        <tr>
          <th><label for="yim">City</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->city) ;?>" id="city" name="city"></td>
        </tr>
        <tr>
          <th><label for="state">State</label></th>
          <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->state) ;?>" id="state" name="state"></td>
        </tr>
		<tr>
      <th><label for="jabber">Country</label></th>
        <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->country) ;?>" id="country" name="country"></td>
      </tr>
      <tr>
        <th><label for="zipcode">Zip Code</label></th>
        <td><input type="text" class="regular-text" value="<?php echo nisu_safeOutput($data_ui->zipcode) ;?>" id="zipcode" name="zipcode"></td>
      </tr>
      </tbody>
    </table>
    <h3>Other Info</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th>Status</th>
          <td><input type="radio" id="status-1" name="status" value="1" <?php echo ($data_ui->status)? 'checked' : '' ;?>   />
            &nbsp;
            <label for="status-1">Active</label>
            &nbsp;&nbsp;
            <input type="radio" id="status-0" name="status" value="0"   <?php echo ($data_ui->status)? '' : 'checked' ;?> />
            &nbsp;
            <label for="status-0">Inactive</label></td>
        </tr>
        <tr>
          <th>Date Registered</th>
          <td><input type="text" size="35"  disabled="disabled" value="<?php echo date('F j Y H:i:s A',strtotime($data_ui->createddate)) ;?>" id="createddate " name="createddate "></td>
        </tr>
        <tr id="password">
          <th><label for="pass1">New Password</label></th>
          <td><input type="password" autocomplete="off" value="" size="16" id="pass1" name="pass1">
            <span class="description">If you would like to change the password type a new one. Otherwise leave this blank.</span><br>
            <input type="password" autocomplete="off" value="" size="16" id="pass2" name="pass2">
            <span class="description">Type your new password again.</span><br>
            <div id="pass-strength-result" style="display: block;">Strength indicator</div>
            <p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).</p></td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" value="update" name="action">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $data_ui->user_id ;?>">
    <p class="submit">
      <input type="submit" value="Update Profile" class="button button-primary" id="editProfile" name="editProfile">
    </p>
  </form>
</div>
<?php }} else { 
wp_die( __('You do not have sufficient permissions to access this page.') );
}?>
