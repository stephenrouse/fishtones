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
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_albums")) {
		if ($_POST['musicdb_action'] == 'add_album') {
			//Form data sent  
			$name = $_POST['musicdb_album_name']; 
			$imageURI = $_POST['musicdb_album_imageURI']; 
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('Album saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_album") {
			//Form data sent  
			$name = $_POST['musicdb_album_name']; 
			$imageURI = $_POST['musicdb_album_imageURI']; 
			$musicdb_albumID = $_POST['musicdb_albumID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				), 
				array( 'id' => $musicdb_albumID )
			);
	?>
		<div class="updated"><p><strong><?php _e('Album saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit album display  
			$albums = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' WHERE id='.$_REQUEST['albumID']); 
			
			$name = $albums[0]->name;
			$imageURI = $albums[0]->imageURI;
			
			if ($_POST['musicdb_action'] == "delete_album") {
				//Form data sent  
				$albumID = $_POST['musicdb_albumID']; 
			
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE, 
					array( 
						'albumID' => 1
					), 
					array( 'albumID' => $_POST['musicdb_albumID'] )
				);
				
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE, 
					array( 
						'albumID' => 1
					), 
					array( 'albumID' => $_POST['musicdb_albumID'] )
				);
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . " WHERE id=" .$_POST['musicdb_albumID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . " WHERE id=" .$_POST['musicdb_albumID']);
				?>  
				<div class="updated"><p><strong><?php _e('Album deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_albums">Back to albums</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_album") {
			?>
				<h2><?php _e("Manage Albums", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Album', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $name . ".  Are you sure you want to delete this album?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_album') || ($action == 'delete_album')) { ?>
			            <input type="hidden" name="musicdb_albumID" value="<? echo $_REQUEST['albumID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Delete Album', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_album") {
			$artists = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . '.name ASC');
	?>
			<h2><?php _e("Manage Albums", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit Album', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_album') { ?>
		            <input type="hidden" name="musicdb_albumID" value="<? echo $_REQUEST['albumID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'Album Details', 'ibotpro_musicdb' ) . "</h4>"; ?>
		            <p><?php _e("Artist Name: " ); ?>
		            	<select>
		            		<option>Select An Artist</option>
							<?php foreach($artists as $artist) {
								$selected = "";
								if ($_REQUEST['artistID']) {
									if ($artist->id == $_REQUEST['artistID']) {
										$selected = " selected";
									}
								}
								echo "<option" . $selected . ">" . $artist->name . "</option>";
		            		} ?>
		            	</select>
		            </p>
		            <p><?php _e("Album Name: " ); ?><input type="text" name="musicdb_album_name" value="<?php echo $name; ?>" size="20"><?php _e(" ex: Album One" ); ?></p>
		            <p><?php _e("Album Icon: " ); ?><input type="text" name="musicdb_album_imageURI" value="<?php echo $imageURI; ?>" size="40"></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Album', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another album, simply replace the data in the fields above with the new album data and click the "Add/Edit Album" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		if ($_REQUEST['artistID']) {
			$albums = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' WHERE artistID='. $_REQUEST['artistID'] .' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . '.id ASC');
		} else {
			$albums = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . '.id ASC');
		}
?>
		<h2><?php _e("Manage Albums", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_album<?php if ($_REQUEST['artistID']) { echo "&artistID=".$_REQUEST['artistID'];} ?>"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($albums)) {
?>
		<table class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th class="id"><?php _e('ID','ibotpro_musicdb') ?></th>
					<th></th>
					<th><?php _e('Name','ibotpro_musicdb') ?></th>
				</tr>
			</thead>
			<tbody>
	<?php
		//loop through the results of all the albums
		$class = '';
		
		foreach($albums as $album) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $album->id;?></td>
					<td class="icon"><img src="<?php echo $album->imageURI; ?>"/></td>
					<td>
						<?php echo $album->name; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $album->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_album&amp;albumID=<?php echo $album->id; ?>">Edit</a> | </span>
							<span class="add_album"><a title="Manage Songs for &quot;<?php echo $album->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=manage_songs&amp;albumID=<?php echo $album->id; ?>">Manage Songs</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $album->name;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_album&amp;albumID=<?php echo $album->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $map->title;?>&quot;" href="<? echo site_url(); ?>/album?albumID=<?php echo $album->id;?>" target="_blank">View</a></span>
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

