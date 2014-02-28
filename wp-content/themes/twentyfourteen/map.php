<?php
/*
Template Name: Maps
*/

global $wpdb;

?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php 

	if(!empty($mapDetails->title)) {
		echo $mapDetails->title . " | ";
	}
	
	// Add the blog name.
	bloginfo( 'name' );
	
	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link href='http://fonts.googleapis.com/css?family=Coda+Caption:800' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Inconsolata' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/skins/tango/skin.css" />
<link rel="shortcut icon" href="<?php bloginfo( 'template_url' ); ?>/images/favicon.png" type="image/x-icon" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>

<?php
if (!empty($_REQUEST['mapID'])) { 
	$maps = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_maps WHERE id='.$_REQUEST['mapID'].' AND active=1');
	if(!empty($maps)) {
		foreach($maps as $map) {
		$categories = "";
			$poi_icon_URI = "";
			
			$categories = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_categories WHERE id='.$map->categoryID);
?>
<script type="text/javascript"> 
MQA.EventUtil.observe(window, 'load', function() {
	
	/*Create an object for options*/ 
	var options={
		elt:document.getElementById('mapquestthat_map'),       /*ID of element on the page where you want the map added*/ 
		zoom:15,                                  /*initial zoom level of the map*/ 
		latLng:{lat:<? echo $map->latitude ?>, lng:<? echo $map->longitude ?>},  /*center of map in latitude/longitude */ 
		mtype:'map',                              /*map type (map)*/ 
		bestFitMargin:0,                          /*margin offset from the map viewport when applying a bestfit on shapes*/ 
		zoomOnDoubleClick:true                    /*zoom in when double-clicking on map*/ 
	};
	
	/*Construct an instance of MQA.TileMap with the options object*/ 
	window.map = new MQA.TileMap(options);
	
	MQA.withModule('largezoom','viewoptions','traffictoggle','geolocationcontrol','insetmapcontrol', function() {
	
		map.addControl(
			new MQA.LargeZoom(),
			new MQA.MapCornerPlacement(MQA.MapCorner.TOP_LEFT, new MQA.Size(5,5))
		);
		
		map.addControl(new MQA.TrafficToggle());
		
		map.addControl(new MQA.ViewOptions());
		
		map.addControl(
			new MQA.GeolocationControl(),
			new MQA.MapCornerPlacement(MQA.MapCorner.TOP_RIGHT, new MQA.Size(10,50))
		);
	});

	var myMapCenter=new MQA.Poi( {lat:<? echo $map->latitude ?>, lng:<? echo $map->longitude ?>} );
	
	<?php
	if ( !empty($categories)) {
		$map_icon_URI = $categories[0]->imageURI;
	?>
var myMapCenter_icon=new MQA.Icon('<?php echo $map_icon_URI ?>',32,37);
myMapCenter.setIcon(myMapCenter_icon);
	<?php
	}
	?>
					
	map.addShape(myMapCenter);
	
	myMapCenter.setRolloverContent('<? echo str_replace("'","\'",$map->rolloverContent) ?>');
	myMapCenter.setInfoContentHTML('<? echo str_replace("'","\'",$map->infoContent) ?>');
	
<?php
			$pois = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_pois WHERE mapID='.$_REQUEST['mapID'].' AND active=1');
			if(!empty($pois)) {
				foreach($pois as $poi) {
					$categories = "";
					$poi_icon_URI = "";
					
					$categories = $wpdb->get_results( 'SELECT * FROM wp_rlinklove_com_ibotpro_mapquestthat_categories WHERE id='.$poi->categoryID);
					?>
	var poi_<? echo $poi->id ?>=new MQA.Poi( {lat:<? echo $poi->latitude ?>, lng:<? echo $poi->longitude ?>} );
					<?php
					if ( !empty($categories)) {
						$poi_icon_URI = $categories[0]->imageURI;
					?>
	var poi_<? echo $poi->id ?>_icon=new MQA.Icon('<?php echo $poi_icon_URI ?>',32,37);
	poi_<? echo $poi->id ?>.setIcon(poi_<? echo $poi->id ?>_icon);
					<?php
					}
					?>
	
	map.addShape(poi_<? echo $poi->id ?>);

	poi_<? echo $poi->id ?>.setRolloverContent('<? echo str_replace("'","\'",$poi->rolloverContent) ?>');
	poi_<? echo $poi->id ?>.setInfoContentHTML('<? echo $poi->infoContent ?>');
					<?php
				}
			}
		}
	}
}	
?>
});
</script>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<div id="banner-ad-728x90" class="ad 728x90">
		<iframe width="728" height="90" noresize scrolling=No frameborder=0 marginheight=0 marginwidth=0 src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150524/0/vh?z=monumental&dim=150267&kw=&click="><script language=JavaScript src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150524/0/vj?z=monumental&dim=150267&kw=&click=&abr=$scriptiniframe"></script><noscript><a href="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150524/0/cc?z=monumental"><img src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150524/0/vc?z=monumental&dim=150267&kw=&click=&abr=$imginiframe" width="728" height="90" border="0"></a></noscript></iframe>
		<!--<img src="http://www.monumentalreport.com/wp-content/themes/drudgery/images/728x90.gif" width="728" height="90" alt="advertisement" />-->
	</div>
	<header id="branding" role="banner">
			<hgroup>
				<div id="logo"></div>
				<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
			
			<?php
				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( ! empty( $header_image ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH ) ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else : ?>
					<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
				<?php endif; // end check for featured image or standard header ?>
			</a>
			<?php endif; // end check for removed header image ?>

			<?php
				// Has the text been hidden?
				if ( 'blank' == get_header_textcolor() ) :
			?>
				<div class="only-search<?php if ( ! empty( $header_image ) ) : ?> with-image<?php endif; ?>">
				<?php get_search_form(); ?>
				</div>
			<?php
				else :
			?>
				<?php get_search_form(); ?>
			<?php endif; ?>
			<div id="header-ad-300x250" class="ad 300x250">
				<iframe width="300" height="250" noresize scrolling=No frameborder=0 marginheight=0 marginwidth=0 src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150525/0/vh?z=monumental&kw=&click="><script language=JavaScript src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150525/0/vj?z=monumental&kw=&click=&abr=$scriptiniframe"></script><noscript><a href="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150525/0/cc?z=monumental"><img src="http://monumental.rotator.hadj7.adjuggler.net/servlet/ajrotator/150525/0/vc?z=monumental&kw=&click=&abr=$imginiframe" width="300" height="250" border="0"></a></noscript></iframe>
				<!--<img src="http://www.monumentalreport.com/wp-content/themes/drudgery/images/300x250.gif" width="300" height="250" alt="advertisement" />-->
			</div>
			<?php
				//if (is_home()) :
				$args = array(
					'tag' => 'featured',
					'posts_per_page' => 1
				);
				
				// The Query
				query_posts( $args );
				
				// The Loop
				while ( have_posts() ) : the_post();
				get_template_part( 'content-link', get_post_format() );
				endwhile;

				// Reset Query
				wp_reset_query();
				//endif;
			?>
			<nav id="access" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Main menu', 'twentyeleven' ); ?></h3>
				<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
				<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to secondary content', 'twentyeleven' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #access -->
	</header><!-- #branding -->


	<div id="main">
	<div id="primary">
		<div id="content" role="main">
		<center>
			<div id="mapquestthat_map" class="map" style="width:750px; height:475px;"></div>
			<p><br/>Map Icons By<br/>
			<a href="http://mapicons.nicolasmollet.com/"><img src="http://www.monumentalreport.com/wp-content/plugins/ibotpro_mapquestthat/images/miclogo-88x31.gif"></a>
			</p>
		</center>
		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>