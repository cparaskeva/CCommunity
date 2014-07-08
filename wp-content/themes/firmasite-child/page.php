<?php
/**
 * @package firmasite
 */

/**
 * This page template is overriden because we need a different behaviour for the "docs" page
 */

global $firmasite_settings;

get_header();
 ?>

		<div id="primary" class="content-area clearfix <?php echo $firmasite_settings["layout_primary_class"]; ?>">
			
			<?php 
				if ($post->post_name == 'docs') { // HERE: THE LAYOUT FOR THE "docs" PAGE 
					
					do_action( 'open_content' );
					do_action( 'open_page' );
					
					$cat = $_GET['cat'];
					if (! $cat > 0)
						$cat = 5; // default category
					
			?>		
			<style>
				.category_list li {list-style-type: none; font-size:20px;padding:5px 0}
			</style>
			<div class="col-md-4 category_list">		
					<?php
						if ($cat == 5) { // default value: display the category chooser
							$args =  array('exclude' => array(1,5)); // exclude cats : uncategorized+content_partners
							$cat_a = get_categories( $args );
							foreach ($cat_a as $c) {
								$cat_id = $c->term_id;
								$cat_name = $c->name;
								echo "<li><a href='/cecommunity/docs/?cat=$cat_id'>$cat_name</a></li>";
							}
						}  else 
							echo "<li><a href='/cecommunity/docs/'><- All docs</a></li>";
					?>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-body">
						<?php 
						$max_desc_len = 200;
						
						$posts = get_posts ("cat=$cat&showposts=20"); // only the posts in "content_partners" category
						if ($posts) {
							foreach ($posts as $p) {
						      	//setup_postdata($p); 
									
								$permalink = get_post_permalink($p->ID);
								$description = $p->post_content;
								/*if (strlen($description) > $max_desc_len)
									$description = substr($description, 0, $max_desc_len).'...';*/
								
						      	echo '<div class="post">';
								echo '<h3>'. $p->post_title .'</h3>';
								echo '<p>'. $description .'<br>';
								echo '</p></div>';
						    }
						  }
						?>
					</div>
				</div>
			</div>
			<?php 	
					//do_action( 'close_page' );
					//do_action( 'close_content' );
			 
				} else { // THEN: all other pages :
			?>

			<?php if ( have_posts() ) : ?>

				<?php do_action( 'open_content' ); ?>
				<?php do_action( 'open_page' ); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Type-specific template for the content.
						   If you want to support Post-Format, i suggest customize loop files with switch()
						 */
						global $post;
						get_template_part( 'templates/single', $post->post_type );
					?>

				<?php endwhile; ?>

				<?php do_action( 'close_page' ); ?>
				<?php do_action( 'close_content' ); ?>

			<?php else : ?>

				<?php get_template_part( 'templates/no-results', 'index' ); ?>

			<?php endif; ?>

			<?php 
				} // ENDS: all other pages (but "docs")
			?>
			
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>