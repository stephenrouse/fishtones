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
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_artists")) {
		if ($_POST['musicdb_action'] == 'add_artist') {
			//Form data sent  
			$name = $_POST['musicdb_artist_name']; 
			$imageURI = $_POST['musicdb_artist_imageURI']; 
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('Artist saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_artist") {
			//Form data sent  
			$name = $_POST['musicdb_artist_name']; 
			$imageURI = $_POST['musicdb_artist_imageURI']; 
			$musicdb_artistID = $_POST['musicdb_artistID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'imageURI' => stripslashes($imageURI)
				), 
				array( 'id' => $musicdb_artistID )
			);
	?>
		<div class="updated"><p><strong><?php _e('Artist saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit artist display  
			$artists = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . ' WHERE id='.$_REQUEST['artistID']); 
			
			$name = $artists[0]->name;
			$imageURI = $artists[0]->imageURI;
			
			if ($_POST['musicdb_action'] == "delete_artist") {
				//Form data sent  
				$artistID = $_POST['musicdb_artistID']; 
			
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_MAP_TABLE, 
					array( 
						'artistID' => 1
					), 
					array( 'artistID' => $_POST['musicdb_artistID'] )
				);
				
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MUSICDB_POI_TABLE, 
					array( 
						'artistID' => 1
					), 
					array( 'artistID' => $_POST['musicdb_artistID'] )
				);
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . " WHERE id=" .$_POST['musicdb_artistID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . " WHERE id=" .$_POST['musicdb_artistID']);
				?>  
				<div class="updated"><p><strong><?php _e('Artist deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_artists">Back to artists</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_artist") {
			?>
				<h2><?php _e("Manage Artists", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Artist', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $name . ".  Are you sure you want to delete this artist?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_artist') || ($action == 'delete_artist')) { ?>
			            <input type="hidden" name="musicdb_artistID" value="<? echo $_REQUEST['artistID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Delete Artist', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_artist") {
	?>
			<h2><?php _e("Manage Artists", 'ibotpro_musicdb'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit Artist', 'ibotpro_musicdb' ) . "</h2>"; ?>  
		      
		        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_artist') { ?>
		            <input type="hidden" name="musicdb_artistID" value="<? echo $_REQUEST['artistID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'Artist Details', 'ibotpro_musicdb' ) . "</h4>"; ?>  
		            <p><?php _e("Artist Name: " ); ?><input type="text" name="musicdb_artist_name" value="<?php echo $name; ?>" size="20"><?php _e(" ex: Artist One" ); ?></p>
		            <p><?php _e("Artist Icon: " ); ?><input type="text" name="musicdb_artist_imageURI" value="<?php echo $imageURI; ?>" size="40"></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Artist', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another artist, simply replace the data in the fields above with the new artist data and click the "Add/Edit Artist" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$artists = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage Artists", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_artist"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($artists)) {
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
		//loop through the results of all the artists
		$class = '';
		
		foreach($artists as $artist) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $artist->id;?></td>
					<td class="icon"><img src="<?php echo $artist->imageURI; ?>"/></td>
					<td>
						<?php echo $artist->name; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $artist->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_artist&amp;artistID=<?php echo $artist->id; ?>">Edit</a> | </span>
							<span class="add_album"><a title="Manage Albums for &quot;<?php echo $artist->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=manage_albums&amp;artistID=<?php echo $artist->id; ?>">Manage Albums</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $artist->name;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_artist&amp;artistID=<?php echo $artist->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $map->title;?>&quot;" href="<? echo site_url(); ?>/artist?artistID=<?php echo $artist->id;?>" target="_blank">View</a></span>
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

