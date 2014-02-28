<?php  
	if($_POST['musicdb_hidden'] == 'Y') {  
		//Form data sent  
		$apikey = $_POST['musicdb_apikey'];  
		update_option('musicdb_apikey', $apikey);   
		?>  
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>  
		<?php  
	} else {  
		//Normal page display  
		$apikey = get_option('musicdb_apikey');  
	}  
?>


    <div class="wrap">  
        <?php    echo "<h2>" . __( 'MapquestThat Options', 'ibotpro_musicdb' ) . "</h2>"; ?>  
      
        <form name="musicdb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
            <input type="hidden" name="musicdb_hidden" value="Y">  
            <?php    echo "<h4>" . __( 'MapquestThat Settings', 'ibotpro_musicdb' ) . "</h4>"; ?>  
            <p><?php _e("Mapquest API Key: " ); ?><input type="text" name="musicdb_apikey" value="<?php echo $apikey; ?>" size="20"><?php _e(" ex: 12345678901" ); ?></p>
      
            <p class="submit">  
            <input type="submit" name="Submit" value="<?php _e('Update Options', 'ibotpro_musicdb' ) ?>" />  
            </p>  
        </form>  
    </div> 