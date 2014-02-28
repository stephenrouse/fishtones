<?php

	define('IBOTPRO_MAPQUESTTHAT_MAP_TABLE', 'ibotpro_mapquestthat_maps');
	define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_pois');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}else{
		$action = "delete_map";
	}

	if($_POST['mapquestthat_hidden'] == 'delete_map') {  
		//Form data sent  
		$mapID = $_POST['mapquestthat_mapID'];

		//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE . " WHERE id=" .$_POST['mapquestthat_mapID'];
		$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . " WHERE mapID=" .$_POST['mapquestthat_mapID']);
		$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE . " WHERE id=" .$_POST['mapquestthat_mapID']);
		?>  
		<div class="updated"><p><strong><?php _e('Map deleted.' ); ?></strong></p></div>  
		<?php  
	} else { 
		//Edit map display  
		$maps = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_maps WHERE id='.$_REQUEST['mapID']); 
		
		$title = $maps[0]->title;
	?>
	    <div class="wrap">  
	        <?php    echo "<h2>" . __( 'Delete Map', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
	      
	        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	            <input type="hidden" name="mapquestthat_hidden" value="<? echo $action ?>">
	            <input type="hidden" name="mapquestthat_mapID" value="<? echo $_REQUEST['mapID'] ?>">
	            <?php    echo "<h4>" . __( 'Map Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
	            <p><?php _e("Are you sure you want to delete " ); echo stripslashes($title); ?></p>
	            <p class="submit">  
	            <input type="submit" name="Submit" value="<?php _e('Delete Map', 'ibotpro_mapquestthat' ) ?>" />  
	            </p> 
	        </form>  
	    </div> 
    <?php }
?>