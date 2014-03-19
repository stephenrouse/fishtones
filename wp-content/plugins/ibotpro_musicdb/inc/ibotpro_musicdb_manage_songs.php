<?php
	define('IBOTPRO_MUSICDB_ARTISTS_TABLE', 'ibotpro_musicdb_artists');
	define('IBOTPRO_MUSICDB_ALBUMS_TABLE', 'ibotpro_musicdb_albums');
	define('IBOTPRO_MUSICDB_SONGS_TABLE', 'ibotpro_musicdb_songs');
	define('IBOTPRO_MUSICDB_CATEGORY_TABLE', 'ibotpro_musicdb_categories');
	
	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}
?>
	<style>
	.id{width:20px;}
	.icon{width:60px;}
	</style>
	<div class="wrap">
<?php
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_songs")) {
		if ($_POST['musicdb_action'] == 'add_song') {
			//Form data sent  
			$name = $_POST['musicdb_song_name']; 
			$imageURI = $_POST['musicdb_song_imageURI']; 
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('Song saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_song") {
			//Form data sent  
			$name = $_POST['musicdb_song_name']; 
			$imageURI = $_POST['musicdb_song_imageURI']; 
			$musicdb_songID = $_POST['musicdb_songID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				), 
				array( 'id' => $musicdb_songID )
			);
	?>
		<div class="updated"><p><strong><?php _e('Song saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit song display  
			$songs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' WHERE id='.$_REQUEST['songID']); 
			
			$name = $songs[0]->name;
			$imageURI = $songs[0]->imageURI;
			
			if ($_POST['musicdb_action'] == "delete_song") {
				//Form data sent  
				$songID = $_POST['musicdb_songID']; 
			
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE, 
					array( 
						'songID' => 1
					), 
					array( 'songID' => $_POST['musicdb_songID'] )
				);
				
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE, 
					array( 
						'songID' => 1
					), 
					array( 'songID' => $_POST['musicdb_songID'] )
				);
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . " WHERE id=" .$_POST['musicdb_songID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . " WHERE id=" .$_POST['musicdb_songID']);
				?>  
				<div class="updated"><p><strong><?php _e('Song deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_songs">Back to songs</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_song") {
			?>
				<h2><?php _e("Manage Songs", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Song', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $name . ".  Are you sure you want to delete this song?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_song') || ($action == 'delete_song')) { ?>
			            <input type="hidden" name="musicdb_songID" value="<? echo $_REQUEST['songID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Delete Song', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_song") {
			$albums = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . '.id ASC');
	?>
			<h2><?php _e("Manage Songs", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit Song', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_song') { ?>
		            <input type="hidden" name="musicdb_songID" value="<? echo $_REQUEST['songID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'Song Details', 'ibotpro_musicdb' ) . "</h4>"; ?>  
		            <p><?php _e("Album Name: " ); ?>
		            	<select>
		            		<option>Select An Album</option>
							<?php foreach($albums as $album) {
								$selected = "";
								if ($_REQUEST['albumID']) {
									if ($album->id == $_REQUEST['albumID']) {
										$selected = " selected";
									}
								}
								echo "<option" . $selected . ">" . $album->name . "</option>";
		            		} ?>
		            	</select>
		            </p>
		            <p><?php _e("Song Name: " ); ?><input type="text" name="musicdb_song_name" value="<?php echo $name; ?>" size="20"><?php _e(" ex: Song One" ); ?></p>
		            <p><?php _e("Song Icon: " ); ?><input type="text" name="musicdb_song_imageURI" value="<?php echo $imageURI; ?>" size="40"></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Song', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another song, simply replace the data in the fields above with the new song data and click the "Add/Edit Song" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {

		if ($_REQUEST['albumID']) {
			$songs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' WHERE albumID='. $_REQUEST['albumID'] .' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . '.id ASC');
		} else {
			$songs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . '.id ASC');
		}
?>
		<h2><?php _e("Manage Songs", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_song<?php if ($_REQUEST['albumID']) { echo "&albumID=".$_REQUEST['albumID'];} ?>"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($songs)) {
?>
		<table class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th class="id"><?php _e('ID','ibotpro_musicdb') ?></th>
					<th></th>
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
					<td class="icon"><img src="<?php echo $song->imageURI; ?>"/></td>
					<td>
						<?php echo $song->name; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $song->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_song&amp;songID=<?php echo $song->id; ?>">Edit</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $song->name;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_song&amp;songID=<?php echo $song->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $map->title;?>&quot;" href="<? echo site_url(); ?>/song?songID=<?php echo $song->id;?>" target="_blank">View</a></span>
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

