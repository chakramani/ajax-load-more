<?php
function cm_scripts()
{
    wp_enqueue_script('cpm-child-js', get_stylesheet_directory_uri() . '/main.js');
    wp_localize_script('cpm-child-js', 'exporterajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cm_scripts');


add_action('wp_ajax_data_fetch_function', 'data_fetch_function');
add_action('wp_ajax_nopriv_data_fetch_function', 'data_fetch_function');


function data_fetch_function()
{
    $category = $_POST['category'];
    $edition_ratio_data = $_POST['edition'];
    $paged = $_POST['paged'];
    $p4d_session = $_POST['p4d_session'];
    $image_ratio = $_POST['image_ratio'];
    $_SESSION['myp4d'] = $p4d_session;
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 21,
        'paged' => $paged,
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' =>  $category,
            ],
        ],
        'meta_query'             => array(
            'relation' => 'AND',
            array(
                'key'     => 'aspect_ratio',
                'value'   => $image_ratio,
                'compare' => 'LIKE',
            ),
            array(
                'key'     => 'photo_editions',
                'value'   => 's:' . strlen($edition_ratio_data) . ':"' . $edition_ratio_data . '";',
                'compare' => 'LIKE',
            ),
        ),
    );
    $query = new WP_Query($args); ?>

    <div class="columns" id="shoppage">
        <?php
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>
                <div class="p4p_products">
                    <div class="product_img">
                        <?php if ($featured_img_url) { ?>
                            <img src="<?php echo $featured_img_url; ?>" alt="img">
                        <?php } else { ?>
                            <img src="https://photos4deco.com/wp-content/uploads/woocommerce-placeholder-500x375.png" alt="img">
                        <?php } ?>
                    </div>
                    <div class="product_desc">
                        <div class="product_size">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                        <div class="product_add_to_cart">
                            <?php echo do_shortcode('[add_to_cart id="' . get_the_ID() . '"]'); ?>
                        </div>
                    </div>
                </div>
            <?php
            endwhile; ?>
            <a href="javascript:void(0);" class="btn btn__primary" id="load-more" onclick='load_more_function();' style="display: none;">Load more</a>
        <?php else : ?>
            <script>
                jQuery("#load-more").hide();
            </script>
            <p><?php _e('Sorry, no posts matched your criteria. You can try with another aspect ratio.'); ?></p>
        <?php endif;
        wp_reset_query();
        ?>
    </div>

<?php
    die();
}
