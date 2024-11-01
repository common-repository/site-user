<?php

$LoSSubmitTitle 	= (isset($_POST['txt_submit_btn']) && $_POST['txt_submit_btn']!= "")?trim($_POST['txt_submit_btn']):"Submit";

$LoIConfirmPassword = (isset($_POST['chk_confirm_password']) && $_POST['chk_confirm_password']!= "")?trim($_POST['chk_confirm_password']):"0";
$LoIFirstName 		= (isset($_POST['chk_fname']) && $_POST['chk_fname']!= "")?trim($_POST['chk_fname']):"0";	
$LoILastName 		= (isset($_POST['chk_lname']) && $_POST['chk_lname']!= "")?trim($_POST['chk_lname']):"0";	
$LoIAddress1 		= (isset($_POST['chk_address1']) && $_POST['chk_address1']!= "")?trim($_POST['chk_address1']):"0";	
$LoIAddress2		= (isset($_POST['chk_address2']) && $_POST['chk_address2']!= "")?trim($_POST['chk_address2']):"0";	
$LoICity			= (isset($_POST['chk_city']) && $_POST['chk_city']!= "")?trim($_POST['chk_city']):"0";	
$LoIState			= (isset($_POST['chk_state']) && $_POST['chk_state']!= "")?trim($_POST['chk_state']):"0";	
$LoICountry			= (isset($_POST['chk_country']) && $_POST['chk_country']!= "")?trim($_POST['chk_country']):"0";	
$LoIZipcode 		= (isset($_POST['chk_zipcode']) && $_POST['chk_zipcode']!= "")?trim($_POST['chk_zipcode']):"0";	

if(isset($_POST['hdn_setting_submit']) && $_POST['hdn_setting_submit'] != "" && $_POST['hdn_setting_submit'] == "submit"){
	
	update_option( "usersite_confirm_password", $LoIConfirmPassword );		
	update_option( "usersite_first_name", $LoIFirstName );		
	update_option( "usersite_last_name", $LoILastName );		
	update_option( "usersite_address_1", $LoIAddress1 );		
	update_option( "usersite_address_2", $LoIAddress2 );		
	update_option( "usersite_city", $LoICity );		
	update_option( "usersite_state", $LoIState );		
	update_option( "usersite_country", $LoICountry );		
	update_option( "usersite_zipcode", $LoIZipcode );		
	update_option( "usersite_submit_title", nisu_safeFormInput($LoSSubmitTitle));	
	
}
$LoIConfirmPassword	=  get_option("usersite_confirm_password");	
$LoIFirstName		=  get_option("usersite_first_name");
$LoILastName		=  get_option("usersite_last_name");
$LoIAddress1		=  get_option("usersite_address_1");
$LoIAddress2		=  get_option("usersite_address_2");
$LoICity			=  get_option("usersite_city");
$LoIState			=  get_option("usersite_state");
$LoICountry			=  get_option("usersite_country");
$LoIZipcode			=  get_option("usersite_zipcode");
$LoSSubmitTitle		=  nisu_safeOutput(get_option("usersite_submit_title"));



$LoSCheckedString = "checked='checked'";
?>
<div class="wrap">
<h2>Registration Form Settings</h2>
<form method="post" action="" name="frm_settings" id="frm_settings">
	<input type="hidden" name="hdn_setting_submit" value="submit"  />
    <table class="form-table">
    	<tbody>
        	<tr valign="top">
            	<th scope="row"><label for="blogname">Registration Form Fields</label></th>
                <td>
                    <label for="chk_user_emailaddress">
                        <input type="checkbox" value="1" id="chk_user_emailaddress" name="chk_user_emailaddress" checked="checked" disabled="disabled" />&nbsp;
                        User email address
                    </label>
                    <br />
                    <label for="chk_username">
                        <input type="checkbox" value="1" id="chk_username" name="chk_username" checked="checked" disabled="disabled" />&nbsp;
                        Username
                    </label>
                    <br />
                    <label for="chk_password">
                        <input type="checkbox" value="1" id="chk_password" name="chk_password" checked="checked" disabled="disabled" />&nbsp;
                        Password
                    </label>
                    <br />
                    <label for="chk_confirm_password">
                        <input type="checkbox" value="1" <?php echo ($LoIConfirmPassword == 1)? $LoSCheckedString:"";?> id="chk_confirm_password" name="chk_confirm_password" />&nbsp;
                        Confirm Password
                    </label>
                    <br />
                    <label for="chk_fname">
                        <input type="checkbox" value="1" <?php echo ($LoIFirstName == 1)? $LoSCheckedString:"";?> id="chk_fname" name="chk_fname" />&nbsp;
                        First Name
                    </label>
                    <br />
                    <label for="chk_lname">
                        <input type="checkbox" value="1" <?php echo ($LoILastName == 1)? $LoSCheckedString:"";?> id="chk_lname" name="chk_lname" />&nbsp;
                        Last Name
                    </label>
                    <br />
                    <label for="chk_address1">
                        <input type="checkbox" value="1" <?php echo ($LoIAddress1 == 1)? $LoSCheckedString:"";?> id="chk_address1" name="chk_address1" />&nbsp;
                        Address 1
                    </label>
                    <br />
                    <label for="chk_address2">
                        <input type="checkbox" value="1" <?php echo ($LoIAddress2 == 1)? $LoSCheckedString:"";?> id="chk_address2" name="chk_address2" />&nbsp;
                        Address 2
                    </label>
                    
                    <br />
                    <label for="chk_city">
                        <input type="checkbox" value="1" <?php echo ($LoICity == 1)? $LoSCheckedString:"";?> id="chk_city" name="chk_city" />&nbsp;
                        City
                    </label>
                    
                    <br />
                    <label for="chk_state">
                        <input type="checkbox" value="1" <?php echo ($LoIState == 1)? $LoSCheckedString:"";?> id="chk_state" name="chk_state" />&nbsp;
                        State
                    </label>
                    <br />
                    <label for="chk_country">
                        <input type="checkbox" value="1" <?php echo ($LoICountry == 1)? $LoSCheckedString:"";?> id="chk_country" name="chk_country" />&nbsp;
                        Country
                    </label>
                    <br />
                    <label for="chk_zipcode">
                        <input type="checkbox" value="1" <?php echo ($LoIZipcode == 1)? $LoSCheckedString:"";?> id="chk_zipcode" name="chk_zipcode" />&nbsp;
                        Zip Code
                    </label>
                </td>	
            </tr>
            <tr valign="top">
            	<th scope="row"><label for="blogname">Submit Button Title</label></th>
                <td>
                	<input type="text" class="regular-text" value="<?php echo $LoSSubmitTitle;?>" id="txt_submit_btn" name="txt_submit_btn" />                	
                </td>
            </tr>  
            <tr valign="top">            	
                <td colspan="2">
                           
You can also change form field label by changing in plugin <a target="_blank" href="<?php echo get_admin_url();?>
plugin-editor.php?file=siteuser%2Flanguages%2Fen_US.php&plugin=siteuser%2Fsiteuser.php">language file</a>.  
                </td>
            </tr>       
        </tbody>
    </table>
    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
</form>
</div>