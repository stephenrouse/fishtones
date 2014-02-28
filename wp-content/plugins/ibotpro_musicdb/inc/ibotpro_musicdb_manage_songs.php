<?php
	define('IBOTPRO_MUSICDB_ARTISTS_TABLE', 'ibotpro_musicdb_artists');
	define('IBOTPRO_MUSICDB_ALBUMS_TABLE', 'ibotpro_musicdb_albums');
	define('IBOTPRO_MUSICDB_SONGS_TABLE', 'ibotpro_musicdb_songs');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}
?>
	<style>
	.id{width:20px;}
	ul.artists{overflow:hidden;width:300px;}
	ul.artists .artist{float:left;text-align:center;}
	</style>
	<div class="wrap">
<?php
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_pois")) {
		if ($_POST['musicdb_action'] == 'add_poi') {
			//Form data sent  
			$title = $_POST['musicdb_poi_title']; 
			$rolloverContent = $_POST['musicdb_poi_rolloverContent']; 
			$infoContent = $_POST['musicdb_poi_infoContent']; 
			$latitude = $_POST['musicdb_poi_latitude']; 
			$longitude = $_POST['musicdb_poi_longitude'];
			$artistID = $_POST['musicdb_poi_artistID'];
			if (isset($_POST['musicdb_poi_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'rolloverContent' => stripslashes($rolloverContent), 
					'infoContent' => stripslashes($infoContent), 
					'latitude' => $latitude, 
					'longitude' => $longitude,
					'artistID' => $artistID, 
					'active' => $active
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('POI saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_poi") {
			//Form data sent  
			$title = $_POST['musicdb_poi_title']; 
			$rolloverContent = $_POST['musicdb_poi_rolloverContent']; 
			$infoContent = $_POST['musicdb_poi_infoContent']; 
			$latitude = $_POST['musicdb_poi_latitude']; 
			$longitude = $_POST['musicdb_poi_longitude'];
			$artistID = $_POST['musicdb_poi_artistID'];
			if (isset($_POST['musicdb_poi_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
			$musicdb_poiID = $_POST['musicdb_poiID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'rolloverContent' => stripslashes($rolloverContent), 
					'infoContent' => stripslashes($infoContent), 
					'latitude' => $latitude, 
					'longitude' => $longitude, 
					'artistID' => $artistID,
					'active' => $active
				), 
				array( 'id' => $musicdb_poiID )
			);
	?>
		<div class="updated"><p><strong><?php _e('POI saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit poi display  
			$pois = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . ' WHERE id='.$_REQUEST['poiID']); 
			
			$title = $pois[0]->title;
			$mapID = $pois[0]->mapID;
			$rolloverContent = $pois[0]->rolloverContent;
			$infoContent = $pois[0]->infoContent;
			$latitude = $pois[0]->latitude;
			$longitude = $pois[0]->longitude;
			$artistID = $pois[0]->artistID;
			$active = $pois[0]->active;
			
			if ($_POST['musicdb_action'] == "delete_poi") {
				//Form data sent  
				$poiID = $_POST['musicdb_poiID'];
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . " WHERE id=" .$_POST['musicdb_poiID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . " WHERE id=" .$_POST['musicdb_poiID']);
				?>  
				<div class="updated"><p><strong><?php _e('POI deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_pois&mapID=<?php echo $mapID ?>">Back to POIs</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_poi") {
			?>
				<h2><?php _e("Manage POIs", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete POI', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $title . ".  Are you sure you want to delete this POI?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_poi') || ($action == 'delete_poi')) { ?>
			            <input type="hidden" name="musicdb_poiID" value="<? echo $_REQUEST['poiID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Add/Edit POI', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_poi") {
	?>
			<h2><?php _e("Manage POIs", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit POI', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_poi') { ?>
		            <input type="hidden" name="musicdb_poiID" value="<? echo $_REQUEST['poiID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'POI Details', 'ibotpro_musicdb' ) . "</h4>"; ?>  
		            <p><?php _e("Map Title: " ); ?><input type="text" name="musicdb_poi_title" value="<?php echo stripslashes($title); ?>" size="20"><?php _e(" ex: My Favorite Place" ); ?></p>
		            <p><?php _e("Rollover Content: " ); ?><textarea name="musicdb_poi_rolloverContent" rows="5" cols="40"><?php echo stripslashes($rolloverContent); ?></textarea></p>
		            <p><?php _e("Info Content: " ); ?><textarea name="musicdb_poi_infoContent" rows="5" cols="40"><?php echo stripslashes($infoContent); ?></textarea></p>
		            <p><?php _e("Latitude: " ); ?><input type="text" name="musicdb_poi_latitude" value="<?php echo $latitude; ?>" size="20"></p>
		            <p><?php _e("Longitude: " ); ?><input type="text" name="musicdb_poi_longitude" value="<?php echo $longitude; ?>" size="20"></p>
		            <p>Lat/Long Finder <a href="http://developer.mapquest.com/web/tools/lat-long-finder" target="_blank"><img src="http://content.mqcdn.com/winston-148/cdn/dotcom3/images/icons/resolved/single.png"/></a></p>
		            <p><?php _e("Category: " ); ?>
		            <?php
		            	$artists = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . '.id ASC');
		            	
		            	if (!empty($artists)) {
		            	?>
		            	<ul class="artists">
		            	<?php
		            		foreach($artists as $artist) {
		            			$artist_checked = "";
		            			if ($artist->id == $artistID) {
		            				$artist_checked = " checked";
		            			}
		            ?>
		            	
		            		<li class="artist">
		            			<ul class="artist-details">
		            				<li><img src="<?php echo $artist->imageURI; ?>"/></li>
		            				<li><input type="radio" name="musicdb_poi_artistID" value="<?php echo $artist->id; ?>"<?php echo $artist_checked ?>></li>
		            			</ul>
		            		</li>
		            <?php
		            		}
		            ?>
		            	</ul>
		            <?php
						}
					?>
		            </p>
		            <p><?php _e("Active: " ); ?><input type="checkbox" name="musicdb_poi_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Map', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another POI, simply replace the data in the fields above with the new POI data and click the "Add/Edit POI" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$pois = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . ' WHERE mapID=' . $_REQUEST['mapID'] . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage POIs", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_poi"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($pois)) {
?>
		<table class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th class="id"><?php _e('ID','ibotpro_musicdb') ?></th>
					<th><?php _e('Title','ibotpro_musicdb') ?></th>
				</tr>
			</thead>
			<tbody>
	<?php
		//loop through the results of all the pois
		$class = '';
		
		foreach($pois as $poi) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $poi->id;?></td>
					<td>
						<?php echo $poi->title; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $poi->title;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_poi&amp;poiID=<?php echo $poi->id; ?>">Edit</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $poi->title;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_poi&amp;poiID=<?php echo $poi->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $poi->title;?>&quot;" href="<? echo site_url(); ?>/poi?poiID=<?php echo $poi->id;?>" target="_blank">View</a></span>
						</div>
					</td>
				</tr>
	<?php
		}
	?>
			</tbody>
		</table>
<?php		
		}
	}
?>
	</div>

