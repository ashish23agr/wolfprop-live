<?php
$args = array(
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post_type' => array('sale-listing', 'rental-listing'),
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'featured',
            'value' => 1,
            'compare' => '='
        ),
        array(
            'key' => 'listing_end',
            'value' => date('Y-m-d'),
            'compare' => '>='
        )
    )
);
$my_query = new WP_Query($args);
if ($my_query->have_posts()) {
    ?>

    <div class="section1">
        <h2>Featured Properties</h2>
        <ul class="feature-slider">
            <?php
            while ($my_query->have_posts()):
                $my_query->the_post();

                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'grid-thumb');
                $sale_price = get_field("listing_price");
                $property_photos = get_field('property_photos');

                echo '<li><a href="' . get_the_permalink() . '">';
                if (!empty($thumbnail)):
                    echo '<img src="' . $thumbnail . '" alt="thumbnail">';
                elseif ($property_photos && $property_photos[0]['photo']['url']):
                    echo '<img src="' . $property_photos[0]['photo']['url'] . '" alt="thumbnail">';
                else:
                    echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
                endif;
                echo '<span class="price">$' . number_format($sale_price) . '</span>
                        <span class="featured-tag">Featured</span>'; ?>
                <?php
                //echo "Test";
                $open_house_true = get_post_meta(get_the_ID(), 'open_house', true);
                $open_house_stare_date = get_post_meta(get_the_ID(), 'open_house_start_date', true);
                $open_house_end_date = get_post_meta(get_the_ID(), 'end_date', true);
                $open_house_start_time = get_post_meta(get_the_ID(), 'start_time', true);
                $open_house_end_time = get_post_meta(get_the_ID(), 'end_time', true);
                $day = get_field('day', get_the_ID());
                $todayDate = date('Y/m/d');
                $checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
                if ($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate) {
                    echo 'TEst';
                    $start_month = date('M', strtotime($open_house_stare_date));
                    $start_date = date('dS', strtotime($open_house_stare_date));
                    $end_month = date('M', strtotime($open_house_end_date));
                    $end_date = date('dS', strtotime($open_house_end_date));
                    $start_time = ltrim(date('h:ia', strtotime($open_house_start_time)), 0);
                    $end_time = ltrim(date('h:ia', strtotime($open_house_end_time)), 0); ?>

                    <div class="custom-sidebar-open-house">
                        <div class="inner-sidebar-open-house">
                            <h4>Open House:
                                <?php echo $day . ', ' . $start_month . ' ' . $start_date . ', '; ?>
                                <?php echo $start_time . ' - ' . $end_time; ?>
                            </h4>
                        </div>
                    </div>
                <?php } //else: ?>
                <!--<style>.custom-sidebar-open-house{display: none;}</style>-->
                <?php // endif; ?>
                <?php echo '</a></li>';
            endwhile;
            ?>
        </ul>
    </div>
<?php } ?>
<div class="section1 margin-top30">
    <h2>Property Status</h2>
    <?php
    $for_rent = new WP_Query(array(
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_type'      => 'rental-listing',
        'meta_query'     => array(
            'relation' => 'OR',
            array('key' => 'sold_under_contract', 'value' => '1', 'compare' => '!='),
            array('key' => 'sold_under_contract', 'compare' => 'NOT EXISTS'),
        )
    ));
    $for_sale = new WP_Query(array(
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_type'      => 'sale-listing',
        'meta_query'     => array(
            'relation' => 'OR',
            array('key' => 'sold_under_contract', 'value' => '1', 'compare' => '!='),
            array('key' => 'sold_under_contract', 'compare' => 'NOT EXISTS'),
        )
    ));
    $for_sold = new WP_Query(array(
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_type'      => array('sale-listing', 'rental-listing'),
        'meta_query'     => array(
            array('key' => 'sold_under_contract', 'value' => '1', 'compare' => '='),
        )
    ));
    ?>
    <ul class="style01">
        <li><a class="filter_property_type" data-type="rent"
                href="/properties/?filter_property_type=rent&action=filter_properties">For
                Rent</a><span>(<?php echo $for_rent->found_posts; ?>)</span></li>
        <li><a class="filter_property_type" data-type="sale"
                href="/properties/?filter_property_type=sale&action=filter_properties">For
                Sale</a><span>(<?php echo $for_sale->found_posts; ?>)</span></li>
        <li><a class="filter_property_type" data-type="sold"
                href="/properties/?filter_property_type=sold&action=filter_properties">Sold</a><span>(<?php echo $for_sold->found_posts; ?>)</span>
        </li>
    </ul>
</div>
<div class="section1 margin-top30">
    <h2>Property Types</h2>
    <?php
    $building_type = get_terms(array(
        'taxonomy' => 'building-style',
        'hide_empty' => false,
        'orderby' => 'count',
        'order' => 'DESC',
        'post_status' => 'publish',
    ));
    ?>
    <ul class="style01">

        <?php
        $filter_property_type = isset($_REQUEST['filter_property_type']) ? $_REQUEST['filter_property_type'] : 'all';
        foreach ($building_type as $type) {
            if (is_object($type) && $type->count > 0) {
                $args = array(
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'post_type' => array('sale-listing', 'rental-listing'),
                    'meta_query' => array(
                        array(
                            'key' => 'listing_end',
                            'value' => date('Y-m-d'),
                            'compare' => '>='
                        ),
                        array(
                            'relation' => 'AND',
                            array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'sold_under_contract',
                                    'value' => '1',
                                    'compare' => 'NOT LIKE',
                                ),
                            ),
                            array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'listing_status',
                                    'value' => 'Sold',
                                    'compare' => 'NOT LIKE',
                                ),
                            ),
                        )
                    ),

                    'tax_query' => array(
                        array(
                            'taxonomy' => 'building-style',
                            'field' => 'term_id',
                            'terms' => $type->term_id,
                        ),
                    ),
                );
                $my_query = new WP_Query($args);
                // echo "<br>+++++++++++++++++++++++++++";
                // pre($args);
                if ($my_query->found_posts > 0) {
                    ?>
                    <li><a class="property_type" data-type="<?php echo $type->term_id; ?>"
                            href="/properties/?filter_building_type=<?php echo $type->term_id; ?>&action=filter_properties&filter_property_type=<?php echo $filter_property_type; ?>"><?php echo $type->name; ?></a><span>(<?php echo $my_query->found_posts; ?>)</span>
                    </li>
                    <?php
                }
            }
        }
        ?>
    </ul>
</div>
<div class="section1 margin-top30">
    <h2>Latest Posts</h2>
    <?php
    $args = array(
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post_type' => array('sale-listing', 'rental-listing'),
        'meta_query' => array(
            array(
                'key' => 'listing_end',
                'value' => date('Y-m-d'),
                'compare' => '>='
            )
        )
    );
    $my_query = new WP_Query($args);
    if ($my_query->have_posts()) {
        ?>
        <ul class="style02">
            <?php
            while ($my_query->have_posts()):
                $my_query->the_post();
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'blog-thumb');
                $property_photos = get_field('property_photos');

                ?>
                <li>
                    <div class="post-image">
                        <?php
                        if (!empty($thumbnail)):
                            echo '<img src="' . $thumbnail . '" alt="thumbnail">';
                        elseif ($property_photos && $property_photos[0]['photo']['url']):
                            echo '<img src="' . $property_photos[0]['photo']['url'] . '" alt="thumbnail">';
                        else:
                            echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
                        endif;
                        ?>
                    </div>
                    <div class="post-content">
                        <h3><?php the_title(); ?></h3>
                        <?php
                        $excerpt = $post->post_content;
                        $excerpt = strip_tags($excerpt);
                        $excerpt = substr($excerpt, 0, 70);
                        $excerpt = $excerpt . "...";
                        ?>
                        <p><?php echo $excerpt; ?></p>
                        <a href="<?php the_permalink(); ?>">Read more</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php } ?>
</div>