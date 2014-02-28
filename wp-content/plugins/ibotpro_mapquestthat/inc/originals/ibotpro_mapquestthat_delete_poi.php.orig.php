<?php

	define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_pois');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}else{
		$action = "delete_poi";
	}

	if($_POST['mapquestthat_hidden'] == 'delete_poi') {  
		//Form data sent  
		$poiID = $_POST['mapquestthat_poiID'];

		//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . " WHERE id=" .$_POST['mapquestthat_poiID'];
		$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . " WHERE id=" .$_POST['mapquestthat_poiID']);
		?>  
		<div class="updated"><p><strong><?php _e('POI deleted.' ); ?></strong></p></div>  
		<?php  
	} else { 
		//Edit poi display  
		$pois = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_pois WHERE id='.$_REQUEST['poiID']); 
		
		$title = $pois[0]->title;
	?>
	    <div class="wrap">  
	        <?php    echo "<h2>" . __( 'Delete POI', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
	      
	        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	            <input type="hidden" name="mapquestthat_hidden" value="<? echo $action ?>">
	            <input type="hidden" name="mapquestthat_poiID" value="<? echo $_REQUEST['poiID'] ?>">
	            <?php    echo "<h4>" . __( 'POI Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
	            <p><?php _e("Are you sure you want to delete " ); echo stripslashes($title); ?></p>
	            <p class="submit">  
	            <input type="submit" name="Submit" value="<?php _e('Delete POI', 'ibotpro_mapquestthat' ) ?>" />  
	            </p> 
	        </form>  
	    </div>
    <?php } 
?>