// Ajax Filter

function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');


add_action( 'wp_footer', 'my_action_javascript' ); // Write our JS below here
function my_action_javascript() { ?>
	<script type="text/javascript" >
		jQuery(document).ready(function($) {
	    var page_count = <?php echo ceil(wp_count_posts('movies')->publish / 2); ?>;
	    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	    var CurrentPage = 2;
	    
	    jQuery('#load_more').click(function() {
	        var data = {
	            'action': 'my_action',
	            'paged': CurrentPage,
	           
	        };

	        jQuery.post(ajaxurl, data, function(response) {
	            jQuery('#container').append(response);

	            if (page_count == CurrentPage) {
	                jQuery('#load_more').hide();
	            }

	            CurrentPage++;
	        });
	    });
	});

	</script> <?php
}

add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

function my_action() {
  
  if (isset($_POST['paged'])) {
    $current_page = absint($_POST['paged']); 
  } else {
   	echo "curent page is not getting";
    $current_page =2; 
  }
	$args = [
	    'post_type' => 'movies',
	    'posts_per_page' => 2,
	    'orderby' => 'ASC',
	    'paged' => $current_page,

	];

  
	if (isset($_POST['exclude_posts']) && is_array($_POST['exclude_posts'])) {
	   $args['post__not_in'] = $_POST['exclude_posts'];
	}

    $ajax_query = new WP_Query($args);

	if ($ajax_query->have_posts()) {
	    while ($ajax_query->have_posts()) { ?>	   
	    <?php   $ajax_query->the_post();
	      ?>
		    <li><?php echo get_the_title(); ?><br>
                <?php the_post_thumbnail('custom-size-thumbnail'); ?>
			</li> 
	    	   <?php
	     		}
	   			} else {
	   			  echo 'No more posts'; // You can customize this message
	   	} ?>
  <?php   wp_reset_postdata();
   wp_die();
}
