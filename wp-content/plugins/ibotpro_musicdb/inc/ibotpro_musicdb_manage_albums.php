<?php
	define('IBOTPRO_MUSICDB_ARTISTS_TABLE', 'ibotpro_musicdb_artists');
	define('IBOTPRO_MUSICDB_ALBUMS_TABLE', 'ibotpro_musicdb_albums');
	define('IBOTPRO_MUSICDB_SONGS_TABLE', 'ibotpro_musicdb_songs');
	define('IBOTPRO_MUSICDB_CATEGORIES_TABLE', 'ibotpro_musicdb_categories');

	global $wpdb;
	
	if (!empty($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
	}
?>
	<style>
	.id{width:20px;}
	ul.categories{overflow:hidden;width:300px;}
	ul.categories .category{float:left;text-align:center;}
	</style>
	<div class="wrap">
<?php
	if (($_POST['musicdb_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_albums")) {
		if ($_POST['musicdb_action'] == 'add_album') {
			//Form data sent  
			$name = $_POST['musicdb_album_name']; 
			$categoryID = $_POST['musicdb_album_categoryID'];
			if (isset($_POST['musicdb_album_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'categoryID' => $categoryID, 
					'active' => $active
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('Album saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['musicdb_action'] == "edit_album") {
			//Form data sent  
			$name = $_POST['musicdb_album_name']; 
			$categoryID = $_POST['musicdb_album_categoryID'];
			if (isset($_POST['musicdb_album_active'])) {
				$active = 1;
			}else{
				$active = 0;
			}
			$musicdb_albumID = $_POST['musicdb_albumID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE, 
				array( 
					'name' => stripslashes($name), 
					'categoryID' => $categoryID,
					'active' => $active
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
			$artistID = $albums[0]->artistID;
			$categoryID = $albums[0]->categoryID;
			$active = $albums[0]->active;
			
			if ($_POST['musicdb_action'] == "delete_album") {
				//Form data sent  
				$albumID = $_POST['musicdb_albumID'];
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . " WHERE id=" .$_POST['musicdb_albumID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . " WHERE id=" .$_POST['musicdb_albumID']);
				?>  
				<div class="updated"><p><strong><?php _e('Album deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_musicdb.php&action=manage_albums&artistID=<?php echo $artistID ?>">Back to Albums</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_album") {
			?>
				<h2><?php _e("Manage Albums", 'ibotpro_musicdb'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Album', 'ibotpro_musicdb' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_musicdb' ) . $name . ".  Are you sure you want to delete this Album?</p>"; ?>
			        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="musicdb_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_album') || ($action == 'delete_album')) { ?>
			            <input type="hidden" name="musicdb_albumID" value="<? echo $_REQUEST['albumID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Add/Edit Album', 'ibotpro_musicdb' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_album") {
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
		            <p><?php _e("Album Title: " ); ?><input type="text" name="musicdb_album_name" value="<?php echo stripslashes($name); ?>" size="20"><?php _e(" ex: My Favorite Place" ); ?></p>
		            <p><?php _e("Category: " ); ?>
		            <?php
		            	$categories = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE . '.id ASC');
		            	
		            	if (!empty($categories)) {
		            	?>
		            	<ul class="categories">
		            	<?php
		            		foreach($categories as $category) {
		            			$category_checked = "";
		            			if ($category->id == $categoryID) {
		            				$category_checked = " checked";
		            			}
		            ?>
		            	
		            		<li class="category">
		            			<ul class="category-details">
		            				<li><img src="<?php echo $category->imageURI; ?>"/></li>
		            				<li><input type="radio" name="musicdb_album_categoryID" value="<?php echo $category->id; ?>"<?php echo $category_checked ?>></li>
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
		            <p><?php _e("Active: " ); ?><input type="checkbox" name="musicdb_album_active" <?php if ($active == 1){ ?>checked<? } ?>></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Map', 'ibotpro_musicdb' ) ?>" />  
		            </p>  
		            <p>To add another Album, simply replace the data in the fields above with the new Album data and click the "Add/Edit Album" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$albums = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' WHERE artistID=' . $_REQUEST['artistID'] . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage Albums", 'ibotpro_musicdb'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_musicdb.php&action=add_album"><?php _e("Add New", 'ibotpro_musicdb'); ?></a>
		</h2>
<?php
		if ( !empty($albums)) {
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
					<td>
						<?php echo $album->name; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $album->name;?>&quot;" href="admin.php?page=ibotpro_musicdb.php&amp;action=edit_album&amp;albumID=<?php echo $album->id; ?>">Edit</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $album->name;?>&quot; to the Trash" href="admin.php?page=ibotpro_musicdb.php&amp;action=delete_album&amp;albumID=<?php echo $album->id; ?>" class="submitdelete">Trash</a> | </span>
							<span class="view"><a rel="permalink" title="View &quot;<?php echo $album->name;?>&quot;" href="<? echo site_url(); ?>/album?albumID=<?php echo $album->id;?>" target="_blank">View</a></span>
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

