<?php

	define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_pois');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}else{
		$action = "add_poi";
	}

	if($_POST['mapquestthat_hidden'] == 'add_poi') {  
		//Form data sent  
		$mapID = $_POST['mapquestthat_poi_mapID'];
		$title = $_POST['mapquestthat_poi_title']; 
		$rolloverContent = $_POST['mapquestthat_poi_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_poi_infoContent']; 
		$latitude = $_POST['mapquestthat_poi_latitude']; 
		$longitude = $_POST['mapquestthat_poi_longitude'];
		if (isset($_POST['mapquestthat_poi_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		
		$wpdb->insert( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE, 
			array( 
				'mapID' => $mapID, 
				'title' => stripslashes($title), 
				'rolloverContent' => stripslashes($rolloverContent), 
				'infoContent' => stripslashes($infoContent), 
				'latitude' => $latitude, 
				'longitude' => $longitude, 
				'active' => $active
			)
		); 
		?>  
		<div class="updated"><p><strong><?php _e('POI saved.' ); ?></strong></p></div>  
		<?php  
	} elseif($_POST['mapquestthat_hidden'] == 'edit_poi') {
		//Form data sent  
		$title = $_POST['mapquestthat_poi_title']; 
		$rolloverContent = $_POST['mapquestthat_poi_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_poi_infoContent']; 
		$latitude = $_POST['mapquestthat_poi_latitude']; 
		$longitude = $_POST['mapquestthat_poi_longitude'];
		if (isset($_POST['mapquestthat_poi_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		$mapquestthat_poiID = $_POST['mapquestthat_poiID'];
		
		$wpdb->update( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE, 
			array( 
				'title' => stripslashes($title), 
				'rolloverContent' => stripslashes($rolloverContent), 
				'infoContent' => stripslashes($infoContent), 
				'latitude' => $latitude, 
				'longitude' => $longitude, 
				'active' => $active
			), 
			array( 'id' => $mapquestthat_poiID )
		); 
		?>  
		<div class="updated"><p><strong><?php _e('POI saved.' ); ?></strong></p></div>  
		<?php  
	} else { 
		//Edit poi display  
		$pois = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_pois WHERE id='.$_REQUEST['poiID']); 
		
		$title = $pois[0]->title;
		$rolloverContent = $pois[0]->rolloverContent;
		$infoContent = $pois[0]->infoContent;
		$latitude = $pois[0]->latitude;
		$longitude = $pois[0]->longitude;
		$active = $pois[0]->active;
	}
?>

    <div class="wrap">  
        <?php    echo "<h2>" . __( 'Add/Edit POI', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
      
        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
            <input type="hidden" name="mapquestthat_hidden" value="<? echo $action ?>">
            <?php if ($action == 'edit_poi') { ?>
            <input type="hidden" name="mapquestthat_poiID" value="<? echo $_REQUEST['poiID'] ?>">
            <?php } ?>
            <?php    echo "<h4>" . __( 'POI Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
            <p><?php _e("POI Title: " ); ?><input type="text" name="mapquestthat_poi_title" value="<?php echo stripslashes($title); ?>" size="20"><?php _e(" ex: A Restaurant by My Favorite Place" ); ?></p>
            <p><?php _e("Rollover Content: " ); ?><textarea name="mapquestthat_poi_rolloverContent" rows="5" cols="40"><?php echo stripslashes($rolloverContent); ?></textarea></p>
            <p><?php _e("Info Content: " ); ?><textarea name="mapquestthat_poi_infoContent" rows="5" cols="40"><?php echo stripslashes($infoContent); ?></textarea></p>
            <p><?php _e("Latitude: " ); ?><input type="text" name="mapquestthat_poi_latitude" value="<?php echo $latitude; ?>" size="20"></p>
            <p><?php _e("Longitude: " ); ?><input type="text" name="mapquestthat_poi_longitude" value="<?php echo $longitude; ?>" size="20"></p>
            <p>Lat/Long Finder <a href="http://developer.mapquest.com/web/tools/lat-long-finder" target="_blank"><img src="http://content.mqcdn.com/winston-148/cdn/dotcom3/images/icons/resolved/single.png"/></a></p>
            <input type="hidden" name="mapquestthat_poi_mapID" value="<? echo $_REQUEST['mapID'] ?>">
            <p><?php _e("Active: " ); ?><input type="checkbox" name="mapquestthat_poi_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
            <p class="submit">  
            <input type="submit" name="Submit" value="<?php _e('Add/Edit POI', 'ibotpro_mapquestthat' ) ?>" />  
            </p>  
            <p>To add another POI for this map, simply replace the data in the fields above with the new POI data and click the "Add/Edit POI" button.</p>  
        </form>  
    </div> 