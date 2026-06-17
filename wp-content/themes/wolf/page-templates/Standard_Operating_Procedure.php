<?php
/**
*Template Name: Standard Operating Procedure
*/
get_header(); 
get_sidebar('filter');
?>
<main id="main" class="site-main">
    <?php
    // Check if the page has a featured image
    if (has_post_thumbnail()) {
        // Get the featured image URL
        $featured_image_url = get_the_post_thumbnail_url(null, 'full');
        ?>
        <div class="featured-image-container" style="text-align: center; margin-bottom: 50px;">
            <img 
                src="<?php echo esc_url($featured_image_url); ?>" 
                alt="<?php echo esc_attr(get_the_title()); ?>" 
                style="
                    width: 583px;
                    object-fit: cover; 
                    max-width: 100%;
                    margin-top: 50px;"
            >
            
        </div>
        <style>
/* Base styling for the image */
.featured-image {
    width: 548px;
    height: 722px;
    object-fit: contain;
    max-width: 100%;
}

/* Responsive styling */
@media screen and (max-width: 768px) {
    .featured-image {
        width: 100%;
        height: auto;
    }
    .featured-image-container {
        padding: 0 20px;
    }
}

@media screen and (max-width: 480px) {
    .featured-image {
        width: 100%;
        height: auto;
    }
    .featured-image-container {
        padding: 0 10px;
    }
}
@media (max-width: 768px) {
        .featured-image {
            width: auto !important;
            height: auto !important;
            padding-left: 25px;
            padding-right: 25px;
        }
    }
</style>
    <?php 
    } else {
        echo '<p style="text-align:center;">No featured image is set for this page.</p>';
    }
    ?>
</main>

<?php get_footer(); ?>