<?php

	define('IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE', 'ibotpro_mapquestthat_categories');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}else{
		$action = "add_category";
	}

	if($_POST['mapquestthat_hidden'] == 'add_category') {  
		//Form data sent  
		$mapID = $_POST['mapquestthat_category_mapID'];
		$title = $_POST['mapquestthat_category_title']; 
		$rolloverContent = $_POST['mapquestthat_category_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_category_infoContent']; 
		$latitude = $_POST['mapquestthat_category_latitude']; 
		$longitude = $_POST['mapquestthat_category_longitude'];
		if (isset($_POST['mapquestthat_category_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		
		$wpdb->insert( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE, 
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
		<div class="updated"><p><strong><?php _e('Category saved.' ); ?></strong></p></div>  
		<?php  
	} elseif($_POST['mapquestthat_hidden'] == 'edit_category') {
		//Form data sent  
		$title = $_POST['mapquestthat_category_title']; 
		$rolloverContent = $_POST['mapquestthat_category_rolloverContent']; 
		$infoContent = $_POST['mapquestthat_category_infoContent']; 
		$latitude = $_POST['mapquestthat_category_latitude']; 
		$longitude = $_POST['mapquestthat_category_longitude'];
		if (isset($_POST['mapquestthat_category_active'])) {
			$active = 1;
		}else{
			$active = 0;
		}
		$mapquestthat_categoryID = $_POST['mapquestthat_categoryID'];
		
		$wpdb->update( 
			$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE, 
			array( 
				'title' => stripslashes($title), 
				'rolloverContent' => stripslashes($rolloverContent), 
				'infoContent' => stripslashes($infoContent), 
				'latitude' => $latitude, 
				'longitude' => $longitude, 
				'active' => $active
			), 
			array( 'id' => $mapquestthat_categoryID )
		); 
		?>  
		<div class="updated"><p><strong><?php _e('Category saved.' ); ?></strong></p></div>  
		<?php  
	} else { 
		//Edit category display  
		$categories = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_categories WHERE id='.$_REQUEST['categoryID']); 
		
		$title = $categories[0]->title;
		$rolloverContent = $categories[0]->rolloverContent;
		$infoContent = $categories[0]->infoContent;
		$latitude = $categories[0]->latitude;
		$longitude = $categories[0]->longitude;
		$active = $categories[0]->active;
	}
?>

    <div class="wrap">  
        <?php    echo "<h2>" . __( 'Add/Edit Category', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
      
        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
            <input type="hidden" name="mapquestthat_hidden" value="<? echo $action ?>">
            <?php if ($action == 'edit_category') { ?>
            <input type="hidden" name="mapquestthat_categoryID" value="<? echo $_REQUEST['categoryID'] ?>">
            <?php } ?>
            <?php    echo "<h4>" . __( 'Category Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
            <p><?php _e("Category Title: " ); ?><input type="text" name="mapquestthat_category_title" value="<?php echo stripslashes($title); ?>" size="20"><?php _e(" ex: A Restaurant by My Favorite Place" ); ?></p>
            <p><?php _e("Rollover Content: " ); ?><textarea name="mapquestthat_category_rolloverContent" rows="5" cols="40"><?php echo stripslashes($rolloverContent); ?></textarea></p>
            <p><?php _e("Info Content: " ); ?><textarea name="mapquestthat_category_infoContent" rows="5" cols="40"><?php echo stripslashes($infoContent); ?></textarea></p>
            <p><?php _e("Latitude: " ); ?><input type="text" name="mapquestthat_category_latitude" value="<?php echo $latitude; ?>" size="20"></p>
            <p><?php _e("Longitude: " ); ?><input type="text" name="mapquestthat_category_longitude" value="<?php echo $longitude; ?>" size="20"></p>
            <p>Lat/Long Finder <a href="http://developer.mapquest.com/web/tools/lat-long-finder" target="_blank"><img src="http://content.mqcdn.com/winston-148/cdn/dotcom3/images/icons/resolved/single.png"/></a></p>
            <input type="hidden" name="mapquestthat_category_mapID" value="<? echo $_REQUEST['mapID'] ?>">
            <p><?php _e("Active: " ); ?><input type="checkbox" name="mapquestthat_category_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
            <p class="submit">  
            <input type="submit" name="Submit" value="<?php _e('Add/Edit Category', 'ibotpro_mapquestthat' ) ?>" />  
            </p>  
            <p>To add another category for this map, simply replace the data in the fields above with the new category data and click the "Add/Edit Category" button.</p>  
        </form>  
    </div> 