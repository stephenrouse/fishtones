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
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_songs")) {
		if ($_POST['musicdb_action'] == 'add_song') {
			//Form data sent  
			$title = $_POST['musicdb_song_title']; 
			$rolloverContent = $_POST['musicdb_song_rolloverContent']; 
			$infoContent = $_POST['musicdb_song_infoContent']; 
			$latitude = $_POST['musicdb_song_latitude']; 
			$longitude = $_POST['musicdb_song_longitude'];
			$artistID = $_POST['musicdb_song_artistID'];
			if (isset($_POST['musicdb_song_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE, 
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
		<div class="updated"><p><strong><?php _e('SONG saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_song") {
			//Form data sent  
			$title = $_POST['musicdb_song_title']; 
			$rolloverContent = $_POST['musicdb_song_rolloverContent']; 
			$infoContent = $_POST['musicdb_song_infoContent']; 
			$latitude = $_POST['musicdb_song_latitude']; 
			$longitude = $_POST['musicdb_song_longitude'];
			$artistID = $_POST['musicdb_song_artistID'];
			if (isset($_POST['musicdb_song_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
			$musicdb_songID = $_POST['musicdb_songID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'rolloverContent' => stripslashes($rolloverContent), 
					'infoContent' => stripslashes($infoContent), 
					'latitude' => $latitude, 
					'longitude' => $longitude, 
					'artistID' => $artistID,
					'active' => $active
				), 
				array( 'id' => $musicdb_songID )
			);
	?>
		<div class="updated"><p><strong><?php _e('SONG saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit song display  
			$songs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' WHERE id='.$_REQUEST['songID']); 
			
			$title = $songs[0]->title;
			$mapID = $songs[0]->mapID;
			$rolloverContent = $songs[0]->rolloverContent;
			$infoContent = $songs[0]->infoContent;
			$latitude = $songs[0]->latitude;
			$longitude = $songs[0]->longitude;
			$artistID = $songs[0]->artistID;
			$active = $songs[0]->active;
			
			if ($_POST['musicdb_action'] == "delete_song") {
				//Form data sent  
				$songID = $_POST['musicdb_songID'];
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_SONG_TABLE . " WHERE id=" .$_POST['musicdb_songID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . " WHERE id=" .$_POST['musicdb_songID']);
				?>  
				<div class="updated"><p><strong><?php _e('SONG deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_songs&mapID=<?php echo $mapID ?>">Back to SONGs</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_song") {
			?>
				<h2><?php _e("Manage SONGs", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete SONG', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $title . ".  Are you sure you want to delete this SONG?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_song') || ($action == 'delete_song')) { ?>
			            <input type="hidden" name="musicdb_songID" value="<? echo $_REQUEST['songID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Add/Edit SONG', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_song") {
	?>
			<h2><?php _e("Manage SONGs", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit SONG', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_song') { ?>
		            <input type="hidden" name="musicdb_songID" value="<? echo $_REQUEST['songID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'SONG Details', 'ibotpro_musicdb' ) . "</h4>"; ?>  
		            <p><?php _e("Map Title: " ); ?><input type="text" name="musicdb_song_title" value="<?php echo stripslashes($title); ?>" size="20"><?php _e(" ex: My Favorite Place" ); ?></p>
		            <p><?php _e("Rollover Content: " ); ?><textarea name="musicdb_song_rolloverContent" rows="5" cols="40"><?php echo stripslashes($rolloverContent); ?></textarea></p>
		            <p><?php _e("Info Content: " ); ?><textarea name="musicdb_song_infoContent" rows="5" cols="40"><?php echo stripslashes($infoContent); ?></textarea></p>
		            <p><?php _e("Latitude: " ); ?><input type="text" name="musicdb_song_latitude" value="<?php echo $latitude; ?>" size="20"></p>
		            <p><?php _e("Longitude: " ); ?><input type="text" name="musicdb_song_longitude" value="<?php echo $longitude; ?>" size="20"></p>
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
		            				<li><input type="radio" name="musicdb_song_artistID" value="<?php echo $artist->id; ?>"<?php echo $artist_checked ?>></li>
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
		            <p><?php _e("Active: " ); ?><input type="checkbox" name="musicdb_song_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Map', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another SONG, simply replace the data in the fields above with the new SONG data and click the "Add/Edit SONG" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$songs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' WHERE ID=' . $_REQUEST['ID'] . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONG_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage Songs", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_song"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($songs)) {
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
		//loop through the results of all the songs
		$class = '';
		
		foreach($songs as $song) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $song->id;?></td>
					<td>
						<?php echo $song->title; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $song->title;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_song&amp;songID=<?php echo $song->id; ?>">Edit</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $song->title;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_song&amp;songID=<?php echo $song->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $song->title;?>&quot;" href="<? echo site_url(); ?>/song?songID=<?php echo $song->id;?>" target="_blank">View</a></span>
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

