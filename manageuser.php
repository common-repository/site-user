<?php
include('pagination.class.php'); 
$msg = '' ;

if(isset($_REQUEST['userArr']) || isset($_REQUEST['userid']) ){ 
   	$userArr = array();
   	(isset($_REQUEST['userArr']))? $userArr = $_REQUEST['userArr'] : $userArr[] = $_REQUEST['userid'];
   	if($_REQUEST['action_1'] != '-1'){
		$action = $_REQUEST['action_1'];
		$msg = nisu_performAction($userArr,$action);
	}else{
		if($_REQUEST['action_2'] != '-1'){
			$action = $_REQUEST['action_2']; 
			$msg = nisu_performAction($userArr,$action);	
		}
	}	  
}

global $LoSSiteUserTable; 
global $wpdb;
$data_u = array();
$_REQUEST['s'] = (isset($_REQUEST['s']))? nisu_safeFormInput($_REQUEST['s']) : '';

$qry_u = "select * from ".$LoSSiteUserTable." where 1= 1";
if(isset($_REQUEST['s'])){
	$qry_u .= " and (username like '%".$_REQUEST['s']."%' or emailaddress like '%".$_REQUEST['s']."%' )  ";	
}
if(isset($_REQUEST['user_status'])){
	$st = ($_REQUEST['user_status'] == 'active')? '1' : '0' ;
	$qry_u .= " and status = $st";
}
if(isset($_REQUEST['orderby'])){
	$qry_u .= " order by ".$_REQUEST['orderby'];
}else{
	$qry_u .= " order by username ";
}
if(isset($_REQUEST['order'])){
	$qry_u .= " ".$_REQUEST['order'];
}else{
	$qry_u .= " ASC";
}

$recs_u = count($wpdb->get_results($qry_u));
$limit = "";
if($recs_u > 0){
	$p = new pagination;
	$p->items($recs_u);
	$p->limit(15); // Limit entries per page
	$p->target("admin.php?page=manage_siteuser");
	$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
	$p->calculate(); // Calculates what to show
	$p->parameterName('paging');
	$p->adjacents(1); //No. of page away from the current page
			 
	if(!isset($_GET['paging'])) {
		$p->page = 1;
	} else {
		$p->page = $_GET['paging'];
	}
	 
	//Query for limit paging
	$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

}
$qry_u = $qry_u .' '. $limit;
			
?>
<div class="wrap">
	<div class="icon32 icon32-siteusers-page" id="icon-edit-siteuser"><br></div>
    <h2>
    	Site Users    
		<?php if(isset($_REQUEST['s'])){ ?>
            <span class="subtitle">Search results for <?php echo '&ldquo;'.$_REQUEST['s'].'&rdquo;';?></span></span>
        <?php } ?>
    </h2>
    <?php if($msg){ ?>
    	<div class="updated" id="message"><p><?php echo $msg; ?></p></div> 
	<?php } ?>
    <ul class="subsubsub">        
        <li class="active"><a class="current" href="admin.php?page=manage_siteuser">All <span class="count">(<?php echo nisu_getTotalUser() ;?>)</span></a> |</li>
        <li class="all"><a href="admin.php?page=manage_siteuser&amp;user_status=active">Active <span class="count">(<?php echo nisu_getTotalUser(1) ;?>)</span></a> |</li>
        <li class="all"><a href="admin.php?page=manage_siteuser&amp;user_status=inactive">In Active <span class="count">(<?php echo nisu_getTotalUser(0) ;?>)</span></a></li>
    
    </ul>
	<form method="get" action="" id="user-filter">
        <input type="hidden" name="page" value="manage_siteuser">
        <p class="search-box">
            <label for="post-search-input" class="screen-reader-text">Search Users:</label>
            <input type="search" value="<?php echo $_REQUEST['s'] ;?>" name="s" id="post-search-input">
            <input type="submit" value="Search Users" class="button" id="search-submit" name="">
        </p>
    </form>
</div>

<div class="wrap">
    <form method="get" action="" id="posts-filter1">
        <input type="hidden" name="page" value="manage_siteuser">

		<div class="tablenav top">
            <div class="alignleft actions">
                <select name="action_1">
                    <option selected="selected" value="-1">Bulk Actions</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="trash">Delete</option>
                </select>
                <input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">
            </div>
			<style>.pagination{ float:right;} .pagination span{ margin:0 5px;} .pagination a{ margin:0 2px;}</style>
			<div class="tablenav-pages one-page">
        		<span class="displaying-num"><?php echo $recs_u ; ?> user(s)</span>
			
			
			<?php if($recs_u > 0)
			{echo $p->show(); }  // Echo out the list of paging. ?>
			</div>
		<br class="clear">
	</div>
	<table cellspacing="0" class="wp-list-table widefat fixed pages">
        <thead>
        	<tr>
            	<th style="" class="manage-column column-cb check-column" id="cb" scope="col">
                	<input type="checkbox" >
                </th>
                <th style="" class="manage-column column-title sorted <?php echo (($_REQUEST['orderby'] == 'username') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="title" scope="col">
                	<a href="admin.php?page=manage_siteuser&amp;orderby=username&amp;order=<?php echo (($_REQUEST['orderby'] == 'username') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Username</span><span class="sorting-indicator"></span></a>
            	</th>
                <th style="" class="manage-column column-title sorted <?php echo (($_REQUEST['orderby'] == 'emailaddress') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="title" scope="col">
                	<a href="admin.php?page=manage_siteuser&amp;orderby=emailaddress&amp;order=<?php echo (($_REQUEST['orderby'] == 'emailaddress') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Email Address</span><span class="sorting-indicator"></span></a>
            	</th>
                <th style="" class="manage-column column-title sorted asc" id="title" scope="col">
                	<a href="javascript:void(0)"><span>User Status</span></a>
            	</th>

                
                 <th style="" class="manage-column column-date sortable <?php echo (($_REQUEST['orderby'] == 'createddate') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="date" scope="col"><a href="admin.php?page=manage_siteuser&amp;orderby=createddate&amp;order=<?php echo (($_REQUEST['orderby'] == 'createddate') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Date</span><span class="sorting-indicator"></span></a>
            </th>		
        	</tr>
        </thead>

	<tfoot>
		<tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col">
                <input type="checkbox">
             </th>
                <th style="" class="manage-column column-title sorted <?php echo (($_REQUEST['orderby'] == 'username') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="title" scope="col">
                	<a href="admin.php?page=manage_siteuser&amp;orderby=username&amp;order=<?php echo (($_REQUEST['orderby'] == 'username') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Username</span><span class="sorting-indicator"></span></a>
            	</th>
                <th style="" class="manage-column column-title sorted <?php echo (($_REQUEST['orderby'] == 'emailaddress') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="title" scope="col">
                	<a href="admin.php?page=manage_siteuser&amp;orderby=emailaddress&amp;order=<?php echo (($_REQUEST['orderby'] == 'emailaddress') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Email Address</span><span class="sorting-indicator"></span></a>
            	</th>
                  <th style="" class="manage-column column-title sorted asc" id="title" scope="col">
                	<a href="javascript:void(0)"><span>User Status</span></a>
            	</th>
            
            <th style="" class="manage-column column-date sortable <?php echo (($_REQUEST['orderby'] == 'createddate') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'asc' : 'desc') : '' ;?>" id="date" scope="col"><a href="admin.php?page=manage_siteuser&amp;orderby=createddate&amp;order=<?php echo (($_REQUEST['orderby'] == 'createddate') && isset($_REQUEST['order']))? (($_REQUEST['order'] == 'ASC')? 'DESC' : 'ASC') : 'ASC' ;?>"><span>Date</span><span class="sorting-indicator"></span></a>
            </th>	
        </tr>
	</tfoot>
    <tbody id="the-list">
    <?php 
			if($recs_u > 0) { 
			$data_u = $wpdb->get_results($qry_u);
			foreach($data_u as $data_u) {
			?>
		<tr valign="top" class="post-<?php echo $data_u->user_id ;?> page type-page status-publish hentry alternate iedit author-self" id="post-2">
			<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $data_u->user_id ;?>" name="userArr[]"></th>
			<td class="post-title page-title column-title">
            	<strong><a title="Edit <?php echo '&quot;' .nisu_safeOutput($data_u->username).'&quot;' ;?>" href="admin.php?page=edit_site_user&amp;uid=<?php echo $data_u->user_id ;?>&amp;action=edit" class="row-title"><?php echo nisu_safeOutput($data_u->username) ;?></a></strong>
				<div class="row-actions">
                	<span class="edit"><a title="Edit this user" href="admin.php?page=edit_site_user&amp;uid=<?php echo $data_u->user_id ;?>&amp;action=edit">Edit</a> | </span>
                    <span class="trash"><a href="admin.php?page=manage_siteuser&action_1=trash&userid=<?php echo $data_u->user_id ;?>" title="Delete this User" class="submitdelete">Trash</a> | </span>
                    <span class="view"><a rel="permalink" title="Make <?php echo '&quot;' .($data_u->status)? 'Inactive' : 'Active' .'&quot;';?>" href="admin.php?page=manage_siteuser&action_1=<?php echo ($data_u->status)? 'inactive' : 'active' ;?>&userid=<?php echo $data_u->user_id ;?>"><?php echo ($data_u->status)? 'Inactive' : 'Active' ;?></a></span>
              	</div>
			</td>
            <td class="author column-author">
            	<a href="mailto:<?php echo $data_u->emailaddress ;?>"><?php echo $data_u->emailaddress ;?></a>
			</td>
             <td class="author column-author2">
            	<?php echo ($data_u->status)? 'Active' : 'Inactive' ;?>
			</td>

			
			<td class="date column-date"><abbr title="<?php echo date( "Y/m/d h:i:s A", strtotime($data_u->createddate)) ;?>"><?php echo date( "Y/m/d", strtotime($data_u->createddate)) ;?></abbr></td></tr>
		
        <?php } } else{
		  if(isset($_REQUEST['s']))
		  {
		 	echo '<tr class="no-items"><td colspan="5" class="colspanchange">No matching users were found.</td></tr>';
		  }
		  }
		   ?>
	</table>
	<div class="tablenav bottom">
		<div class="alignleft actions">
			<select name="action_2">
				<option selected="selected" value="-1">Bulk Actions</option>
				<option value="active">Active</option>
                <option value="inactive">Inactive</option>
				<option value="trash">Delete</option>

			</select>
			<input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">
		</div>
		
		<div class="tablenav-pages one-page">
        	<span class="displaying-num"><?php echo $recs_u ; ?> user(s)</span>
					<?php if($recs_u > 0)
			{echo $p->show(); }  // Echo out the list of paging. ?>  
		</div>
		<br class="clear">
	
	</div>

</form>
<div id="ajax-response"></div>
<br class="clear">
</div>
