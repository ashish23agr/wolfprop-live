<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Not Found', 'twentythirteen' ); ?></h1>
			</header>

			<div class="page-wrapper">
				<div class="page-content">
					<h2><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentythirteen' ); ?></h2>
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen' ); ?></p>

					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div><!-- .page-wrapper -->

		</div><!-- #content -->
	</div><!-- #primary -->
	<!-- <form id="rental-property-form">
		<label for="propertyName">Property Name:</label><br>
		<input type="text" id="propertyName" name="propertyName"><br>

		<label for="propertyDescription">Property Description:</label><br>
		<textarea id="propertyDescription" name="propertyDescription"></textarea><br>

		<label for="propertyImages">Property Images:</label><br>
		<input type="file" id="propertyImages" name="propertyImages" multiple accept="image/*" ><br>

		<input type="submit" value="Submit">
	</form>
	<script>
		jQuery(document).ready(function($) {
				$("#rental-property-form").validate({
					rules: {
						// Define rules for each input field
						propertyName: {
							required: true,
							minlength: 2
						},
						propertyDescription: {
							required: true,
							minlength: 10
						},
						// Add more rules for other fields as needed
						propertyImages: {
							required: true,
							checkMimeType: true // Specify that only image files are allowed
						}
					},
					messages: {
						// Define custom error messages for each rule
						propertyName: {
							required: "Please enter the property name",
							minlength: "Property name must be at least 2 characters long"
						},
						propertyDescription: {
							required: "Please enter a description for the property",
							minlength: "Description must be at least 10 characters long"
						},
						propertyImages: {
							required: "Please select at least one image",
							checkMimeType: "Only image files are allowed"
						}
						// Add more custom error messages as needed
					}
				});
			});

			 // Custom validation method to check MIME type
			 jQuery.validator.addMethod('checkMimeType', function(value, element) {
        var files = element.files;
        for (var i = 0; i < files.length; i++) {
            var fileType = files[i].type;
            if (fileType.split('/')[0] !== 'image') {
                return false;
            }
        }
        return true;
    }, 'Please select only image files');
		</script> -->



<?php get_footer(); ?>