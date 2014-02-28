<?php

	define('IBOTPRO_MAPQUESTTHAT_MAP_TABLE', 'ibotpro_mapquestthat_maps');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}else{
		$action = "add_map";
	}

	if($_POST['mapquestthat_hidden'] == 'add_map') {  
		//Form data sent  
		$title = $_POST['mapquestthat_map_title']; 
		$rolloverContent = $_POST['mapquestthat_map_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_map_infoContent']; 
		$latitude = $_POST['mapquestthat_map_latitude']; 
		$longitude = $_POST['mapquestthat_map_longitude'];
		if (isset($_POST['mapquestthat_map_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		
		$wpdb->insert( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE, 
			array( 
				'title' => stripslashes($title), 
				'rolloverContent' => stripslashes($rolloverContent), 
				'infoContent' => stripslashes($infoContent), 
				'latitude' => $latitude, 
				'longitude' => $longitude, 
				'active' => $active
			)
		); 
		?>  
		<div class="updated"><p><strong><?php _e('Map saved.' ); ?></strong></p></div>  
		<?php  
	} elseif($_POST['mapquestthat_hidden'] == 'edit_map') {
		//Form data sent  
		$title = $_POST['mapquestthat_map_title']; 
		$rolloverContent = $_POST['mapquestthat_map_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_map_infoContent']; 
		$latitude = $_POST['mapquestthat_map_latitude']; 
		$longitude = $_POST['mapquestthat_map_longitude'];
		if (isset($_POST['mapquestthat_map_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		$mapquestthat_mapID = $_POST['mapquestthat_mapID'];
		
		$wpdb->update( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE, 
			array( 
				'title' => stripslashes($title), 
				'rolloverContent' => stripslashes($rolloverContent), 
				'infoContent' => stripslashes($infoContent), 
				'latitude' => $latitude, 
				'longitude' => $longitude, 
				'active' => $active
			), 
			array( 'id' => $mapquestthat_mapID )
		); 
		?>  
		<div class="updated"><p><strong><?php _e('Map saved.' ); ?></strong></p></div>  
		<?php  
	} else { 
		//Edit map display  
		$maps = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_maps WHERE id='.$_REQUEST['mapID']); 
		
		$title = $maps[0]->title;
		$rolloverContent = $maps[0]->rolloverContent;
		$infoContent = $maps[0]->infoContent;
		$latitude = $maps[0]->latitude;
		$longitude = $maps[0]->longitude;
		$active = $maps[0]->active;
	}
?>

    <div class="wrap">  
        <?php    echo "<h2>" . __( 'Add/Edit Map', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
      
        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
            <input type="hidden" name="mapquestthat_hidden" value="<? echo $action ?>">
            <?php if ($action == 'edit_map') { ?>
            <input type="hidden" name="mapquestthat_mapID" value="<? echo $_REQUEST['mapID'] ?>">
            <?php } ?>
            <?php    echo "<h4>" . __( 'Map Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
            <p><?php _e("Map Title: " ); ?><input type="text" name="mapquestthat_map_title" value="<?php echo $title; ?>" size="20"><?php _e(" ex: My Favorite Place" ); ?></p>
            <p><?php _e("Rollover Content: " ); ?><textarea name="mapquestthat_map_rolloverContent" rows="5" cols="40"><?php echo $rolloverContent; ?></textarea></p>
            <p><?php _e("Info Content: " ); ?><textarea name="mapquestthat_map_infoContent" rows="5" cols="40"><?php echo $infoContent; ?></textarea></p>
            <p><?php _e("Latitude: " ); ?><input type="text" name="mapquestthat_map_latitude" value="<?php echo $latitude; ?>" size="20"></p>
            <p><?php _e("Longitude: " ); ?><input type="text" name="mapquestthat_map_longitude" value="<?php echo $longitude; ?>" size="20"></p>
            <p>Lat/Long Finder <a href="http://developer.mapquest.com/web/tools/lat-long-finder" target="_blank"><img src="http://content.mqcdn.com/winston-148/cdn/dotcom3/images/icons/resolved/single.png"/></a></p>
            <p><?php _e("Active: " ); ?><input type="checkbox" name="mapquestthat_map_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
            <p class="submit">  
            <input type="submit" name="Submit" value="<?php _e('Add/Edit Map', 'ibotpro_mapquestthat' ) ?>" />  
            </p>  
            <p>To add another map, simply replace the data in the fields above with the new map data and click the "Add/Edit Map" button.</p> 
        </form>  
    </div> 