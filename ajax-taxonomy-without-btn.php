===============================================================================================================================================================
													TEMPLATE filter.php
===============================================================================================================================================================

<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST" id="filter-3">
    <?php
    if ($terms = get_terms(array('taxonomy' => 'movies-cat', 'orderby' => 'name'))) :
        echo '<select name="categoryfilter" id="categoryfilter"><option value="">All Products </option>';
        foreach ($terms as $term) :
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        endforeach;
        echo '</select>';
    endif;
    ?>
    <input type="hidden" name="action" value="myfilter_2">
</form>
<div id="response">
    <?php


    $args = array(
        'post_type' => 'movies',
        'posts_per_page' => -1
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
        	 $product_quantity = get_field('product_quantity');

            echo '<h2>' . get_the_title() . '</h2>';
            echo "<br>";
            echo $product_quantity;
            echo "<br>";
            echo get_the_post_thumbnail();
        endwhile;
    else :
        echo '<p>No posts found</p>';
    endif;
    wp_reset_postdata();
    ?>
</div>


===============================================================================================================================================================
													TEMPLATE script.js
===============================================================================================================================================================


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#filter-3').change(function() {
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                type: $(this).attr('method'),
                success: function(data) {
                    $('#response').html(data);
                }
            });
            return false;
        });
    });
</script>


===============================================================================================================================================================
													Function.php   (Ajax call back)
===============================================================================================================================================================


function myfilter_2() {
    $category_filter = $_POST['categoryfilter'];
    $args = array(
        'post_type' => 'movies',
        'posts_per_page' => -1,
        'tax_query' => array(),
    );

    if (!empty($category_filter)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'movies-cat',
            'field' => 'id',
            'terms' => $category_filter,
        );
    }

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
        	$product_quantity = get_field('product_quantity');
            echo '<h2>' . get_the_title() . '</h2>';
            echo "<br>";
            echo $product_quantity;
            echo "<br>";
            echo get_the_post_thumbnail();
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No posts found</p>';
    endif;

    die();
}

add_action('wp_ajax_myfilter_2', 'myfilter_2');
add_action('wp_ajax_nopriv_myfilter_2', 'myfilter_2');
