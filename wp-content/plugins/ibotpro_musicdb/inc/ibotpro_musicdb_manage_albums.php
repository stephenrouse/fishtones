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
	if ($_POST['musicdb_action'] || $_REQUEST['action']) {
		if ($_POST['musicdb_action'] == 'add_map') {
			//Form data sent  
			$title = $_POST['musicdb_map_title']; 
			$rolloverContent = $_POST['musicdb_map_rolloverContent']; 
			$infoContent = $_POST['musicdb_map_infoContent']; 
			$latitude = $_POST['musicdb_map_latitude']; 
			$longitude = $_POST['musicdb_map_longitude'];
			$artistID = $_POST['musicdb_map_artistID'];
			if (isset($_POST['musicdb_map_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE, 
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
		<div class="updated"><p><strong><?php _e('Map saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_map") {
			//Form data sent  
			$title = $_POST['musicdb_map_title']; 
			$rolloverContent = $_POST['musicdb_map_rolloverContent']; 
			$infoContent = $_POST['musicdb_map_infoContent']; 
			$latitude = $_POST['musicdb_map_latitude']; 
			$longitude = $_POST['musicdb_map_longitude'];
			$artistID = $_POST['musicdb_map_artistID'];
			if (isset($_POST['musicdb_map_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
			$musicdb_mapID = $_POST['musicdb_mapID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'rolloverContent' => stripslashes($rolloverContent), 
					'infoContent' => stripslashes($infoContent), 
					'latitude' => $latitude, 
					'longitude' => $longitude,
					'artistID' => $artistID, 
					'active' => $active
				), 
				array( 'id' => $musicdb_mapID )
			);
	?>
		<div class="updated"><p><strong><?php _e('Map saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit map display  
			$maps = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE . ' WHERE id='.$_REQUEST['mapID']); 
			
			$title = $maps[0]->title;
			$rolloverContent = $maps[0]->rolloverContent;
			$infoContent = $maps[0]->infoContent;
			$latitude = $maps[0]->latitude;
			$longitude = $maps[0]->longitude;
			$artistID = $maps[0]->artistID;
			$active = $maps[0]->active;
			
			if ($_POST['musicdb_action'] == "delete_map") {
				//Form data sent  
				$mapID = $_POST['musicdb_mapID'];
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE . " WHERE id=" .$_POST['musicdb_mapID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE . " WHERE mapID=" .$_POST['musicdb_mapID']);
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE . " WHERE id=" .$_POST['musicdb_mapID']);
				?>  
				<div class="updated"><p><strong><?php _e('Map deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php">Back to maps</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_map") {
			?>
				<h2><?php _e("Manage Maps", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Map', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $title . ".  Are you sure you want to delete this map and all it's POIs?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_map') || ($action == 'delete_map')) { ?>
			            <input type="hidden" name="musicdb_mapID" value="<? echo $_REQUEST['mapID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Delete Map', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_map") {
	?>
			<h2><?php _e("Manage Maps", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit Map', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_map') { ?>
		            <input type="hidden" name="musicdb_mapID" value="<? echo $_REQUEST['mapID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'Map Details', 'ibotpro_musicdb' ) . "</h4>"; ?>  
		            <p><?php _e("Map Title: " ); ?><input type="text" name="musicdb_map_title" value="<?php echo stripslashes($title); ?>" size="20"><?php _e(" ex: My Favorite Place" ); ?></p>
		            <p><?php _e("Rollover Content: " ); ?><textarea name="musicdb_map_rolloverContent" rows="5" cols="40"><?php echo stripslashes($rolloverContent); ?></textarea></p>
		            <p><?php _e("Info Content: " ); ?><textarea name="musicdb_map_infoContent" rows="5" cols="40"><?php echo stripslashes($infoContent); ?></textarea></p>
		            <p><?php _e("Latitude: " ); ?><input type="text" name="musicdb_map_latitude" value="<?php echo $latitude; ?>" size="20"></p>
		            <p><?php _e("Longitude: " ); ?><input type="text" name="musicdb_map_longitude" value="<?php echo $longitude; ?>" size="20"></p>
		            <p><?php _e("Lat/Long Finder: " ); ?><a href="http://developer.mapquest.com/web/tools/lat-long-finder" target="_blank"><img src="http://content.mqcdn.com/winston-148/cdn/dotcom3/images/icons/resolved/single.png"/></a></p>
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
		            				<li><input type="radio" name="musicdb_map_artistID" value="<?php echo $artist->id; ?>"<?php echo $artist_checked ?>></li>
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
		            <p><?php _e("Active: " ); ?><input type="checkbox" name="musicdb_map_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Map', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another map, simply replace the data in the fields above with the new map data and click the "Add/Edit Map" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$maps = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage Maps", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_map"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($maps)) {
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
		//loop through the results of all the maps
		$class = '';
		
		foreach($maps as $map) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $map->id;?></td>
					<td>
						<?php echo $map->title; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $map->title;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_map&amp;mapID=<?php echo $map->id; ?>">Edit</a> | </span>
							<span class="add_poi"><a title="Manage POIs for &quot;<?php echo $map->title;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=manage_pois&amp;mapID=<?php echo $map->id; ?>">Manage POIs</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $map->title;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_map&amp;mapID=<?php echo $map->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $map->title;?>&quot;" href="<? echo site_url(); ?>/map?mapID=<?php echo $map->id;?>" target="_blank">View</a></span>
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

