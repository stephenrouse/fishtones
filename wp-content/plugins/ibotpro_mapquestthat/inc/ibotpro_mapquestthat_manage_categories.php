<?php
	define('IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE', 'ibotpro_mapquestthat_categories');
	define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_categories');
	define('IBOTPRO_MAPQUESTTHAT_MAP_TABLE', 'ibotpro_mapquestthat_maps');
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
	if (($_POST['mapquestthat_action'] || $_REQUEST['action']) && ($_REQUEST['action'] != "manage_categories")) {
		if ($_POST['mapquestthat_action'] == 'add_category') {
			//Form data sent  
			$title = $_POST['mapquestthat_category_title']; 
			$imageURI = $_POST['mapquestthat_category_imageURI']; 
	
			$wpdb->insert( 
				$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'imageURI' => stripslashes($imageURI)
				)
			);
	?>
		<div class="updated"><p><strong><?php _e('Category saved.' ); ?></strong></p></div>  
	<?php		
		} elseif ($_POST['mapquestthat_action'] == "edit_category") {
			//Form data sent  
			$title = $_POST['mapquestthat_category_title']; 
			$imageURI = $_POST['mapquestthat_category_imageURI']; 
			$mapquestthat_categoryID = $_POST['mapquestthat_categoryID'];
			
			$wpdb->update( 
				$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE, 
				array( 
					'title' => stripslashes($title), 
					'imageURI' => stripslashes($imageURI)
				), 
				array( 'id' => $mapquestthat_categoryID )
			);
	?>
		<div class="updated"><p><strong><?php _e('Category saved.' ); ?></strong></p></div>  
	<?php
		} else { 
			//Edit category display  
			$categories = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . ' WHERE id='.$_REQUEST['categoryID']); 
			
			$title = $categories[0]->title;
			$imageURI = $categories[0]->imageURI;
			
			if ($_POST['mapquestthat_action'] == "delete_category") {
				//Form data sent  
				$categoryID = $_POST['mapquestthat_categoryID']; 
			
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE, 
					array( 
						'categoryID' => 1
					), 
					array( 'categoryID' => $_POST['mapquestthat_categoryID'] )
				);
				
				$wpdb->update( 
					$wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE, 
					array( 
						'categoryID' => 1
					), 
					array( 'categoryID' => $_POST['mapquestthat_categoryID'] )
				);
		
				//echo "DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . " WHERE id=" .$_POST['mapquestthat_categoryID'];
				$wpdb->query("DELETE FROM " . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . " WHERE id=" .$_POST['mapquestthat_categoryID']);
				?>  
				<div class="updated"><p><strong><?php _e('Category deleted.' ); ?></strong></p></div>
				<p><a href="admin.php?page=ibotpro_mapquestthat.php&action=manage_categories">Back to categories</a></p>
			<?php
			} elseif ($_REQUEST['action'] == "delete_category") {
			?>
				<h2><?php _e("Manage Categories", 'ibotpro_mapquestthat'); ?></h2>
	    		<div class="wrap"> 
	    			<?php    echo "<h2>" . __( 'Delete Category', 'ibotpro_mapquestthat' ) . "</h2>"; ?>
	    			<?php    echo "<p>" . __( 'You are about to delete ', 'ibotpro_mapquestthat' ) . $title . ".  Are you sure you want to delete this category?</p>"; ?>
			        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
			            <input type="hidden" name="mapquestthat_action" value="<? echo $action ?>">
			            <?php if (($action == 'edit_category') || ($action == 'delete_category')) { ?>
			            <input type="hidden" name="mapquestthat_categoryID" value="<? echo $_REQUEST['categoryID'] ?>">
			            <?php } ?>
			            <p class="submit">  
			            <input type="submit" name="Submit" value="<?php _e('Delete Category', 'ibotpro_mapquestthat' ) ?>" />  
			            </p>
			        </form>
	    		</div>
	    	<?php
			}
		}
		
		if ($_REQUEST['action'] != "delete_category") {
	?>
			<h2><?php _e("Manage Categories", 'ibotpro_mapquestthat'); ?></h2>
		    <div class="wrap">  
		        <?php    echo "<h2>" . __( 'Add/Edit Category', 'ibotpro_mapquestthat' ) . "</h2>"; ?>  
		      
		        <form name="mapquestthat_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		            <input type="hidden" name="mapquestthat_action" value="<? echo $action ?>">
		            <?php if ($action == 'edit_category') { ?>
		            <input type="hidden" name="mapquestthat_categoryID" value="<? echo $_REQUEST['categoryID'] ?>">
		            <?php } ?>
		            <?php    echo "<h4>" . __( 'Category Details', 'ibotpro_mapquestthat' ) . "</h4>"; ?>  
		            <p><?php _e("Category Title: " ); ?><input type="text" name="mapquestthat_category_title" value="<?php echo $title; ?>" size="20"><?php _e(" ex: Category One" ); ?></p>
		            <p><?php _e("Category Icon: " ); ?><input type="text" name="mapquestthat_category_imageURI" value="<?php echo $imageURI; ?>" size="40"></p>
		            <p class="submit">  
		            <input type="submit" name="Submit" value="<?php _e('Add/Edit Category', 'ibotpro_mapquestthat' ) ?>" />  
		            </p>  
		            <p>To add another category, simply replace the data in the fields above with the new category data and click the "Add/Edit Category" button.</p> 
		        </form>  
		    </div>
<?php
		}
	} else {
		$categories = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . '.id ASC');
?>
		<h2><?php _e("Manage Categories", 'ibotpro_mapquestthat'); ?>
			<a class="button add-new-h2" href="admin.php?page=ibotpro_mapquestthat.php&action=add_category"><?php _e("Add New", 'ibotpro_mapquestthat'); ?></a>
		</h2>
<?php
		if ( !empty($categories)) {
?>
		<table class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th class="id"><?php _e('ID','ibotpro_mapquestthat') ?></th>
					<th><?php _e('Icon','ibotpro_mapquestthat') ?></th>
					<th><?php _e('Title','ibotpro_mapquestthat') ?></th>
				</tr>
			</thead>
			<tbody>
	<?php
		//loop through the results of all the categories
		$class = '';
		
		foreach($categories as $category) {
			//make the rows look nice by alternating the colors of the row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			
			$url = get_bloginfo('wpurl');
			//output the info into the table.. It will call itelf when they press delete... PHP_SELF
	?>
				<tr class="<?php echo $class; ?>">
					<td class="id"><?php echo $category->id;?></td>
					<td class="icon"><img src="<?php echo $category->imageURI; ?>"/></td>
					<td>
						<?php echo $category->title; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit &quot;<?php echo $category->title;?>&quot;" href="admin.php?page=ibotpro_mapquestthat.php&amp;action=edit_category&amp;categoryID=<?php echo $category->id; ?>">Edit</a> | </span>
							<span class="trash"><a title="Move &quot;<?php echo $category->title;?>&quot; to the Trash" href="admin.php?page=ibotpro_mapquestthat.php&amp;action=delete_category&amp;categoryID=<?php echo $category->id; ?>" class="submitdelete">Trash</a>
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

