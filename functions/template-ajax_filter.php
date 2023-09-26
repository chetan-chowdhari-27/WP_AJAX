<?php /* Template Name: Ajax Filter */

get_header();

?>

<?php 

$args = array(
    'post_type' => 'movies',
    'posts_per_page' => 2,
    'orderby' => 'ASC',
);

$the_query = new WP_Query($args);

if ($the_query->have_posts()) { ?>
    <ul id="container">
        <?php while ($the_query->have_posts()) {
            $the_query->the_post(); ?>

            <li> <?php echo the_title(); ?><br>
                <?php the_post_thumbnail('custom-size-thumbnail'); ?>
            </li> 

        <?php } ?>
    </ul>

    <button id="load_more"> Load More </button> 
<?php   
} else {
    echo "Sorry, no posts matched your criteria.";
}
wp_reset_postdata(); ?>
<?php 
get_footer();
?>
