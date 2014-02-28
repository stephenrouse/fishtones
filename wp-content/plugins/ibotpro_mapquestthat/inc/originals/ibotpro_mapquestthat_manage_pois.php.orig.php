<?php
	define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_pois');

	global $wpdb;
	$pois = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . ' WHERE mapID=' . $_REQUEST['mapID'] . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . '.id ASC');
	//echo 'SELECT * FROM ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . ' WHERE mapID=' . $_REQUEST['mapID'] . ' ORDER BY ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . '.id ASC';
	if( !empty($pois)) {
?>
<style>
.id{width:20px;}
</style>
<div class="wrap">
	<h2><?php _e("Manage POIs", 'ibotpro_mapquestthat'); ?></h2>
	<table class="wp-list-table widefat" cellspacing="0">
		<thead>
			<tr>
				<th class="id"><?php _e('ID','ibotpro_mapquestthat') ?></th>
				<th><?php _e('Title','ibotpro_mapquestthat') ?></th>
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
		
		$poi_is_active = $poi->active;
		//output the info into the table.. It will call itelf when they press delete... PHP_SELF
?>
			<tr class="<?php echo $class; ?>">
				<td class="id"><?php echo $poi->id;?></td>
				<td>
					<?php echo $poi->title; ?>
					<div class="row-actions">
						<span class="edit"><a title="Edit &quot;<?php echo $poi->title;?>&quot;" href="admin.php?page=ibotpro_mapquestthat.php&amp;action=edit_poi&amp;poiID=<?php echo $poi->id; ?>">Edit</a> | </span>
						<span class="trash"><a title="Move &quot;<?php echo $poi->title;?>&quot; to the Trash" href="admin.php?page=ibotpro_mapquestthat.php&amp;action=delete_poi&amp;poiID=<?php echo $poi->id; ?>" class="submitdelete">Trash</a> | </span>
						<span class="view"><a rel="permalink" title="View &quot;<?php echo $poi->title;?>&quot;" href="<? echo site_url(); ?>/map?mapID=<?php echo $poi->id;?>" target="_blank">View</a></span>
					</div>
				</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div>
<?php
	}
?>


