<?php
/**
 * Callback functions for comments
 *
 * @since Education Base 1.0.0
 *
 * @param $comment
 * @param $args
 * @param $depth
 * @return void
 *
 */
if ( !function_exists('education_base_commment_list') ) :

    function education_base_commment_list($comment, $args, $depth) {
        extract($args, EXTR_SKIP);
        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        }
        else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag ?>
        <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, '64'); ?>
            <?php printf(__('<cite class="fn">%s</cite>', 'education-base' ), get_comment_author_link()); ?>
        </div>
        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'education-base'); ?></em>
            <br/>
        <?php endif; ?>
        <div class="comment-meta commentmetadata">
            <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                <i class="fa fa-clock-o"></i>
                <?php
                /* translators: 1: date, 2: time */
                printf(__('%1$s at %2$s', 'education-base'), get_comment_date(), get_comment_time()); ?>
            </a>
            <?php edit_comment_link(__('(Edit)', 'education-base'), '  ', ''); ?>
        </div>
        <?php comment_text(); ?>
        <div class="reply">
            <?php comment_reply_link( array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
endif;

/**
 * Select sidebar according to the options saved
 *
 * @since Education Base 1.0.0
 *
 * @param null
 * @return string
 *
 */
/**
 * Select sidebar according to the options saved
 *
 * @since Education Base 1.0.0
 *
 * @param null
 * @return string
 *
 */
if ( !function_exists('education_base_sidebar_selection') ) :
    function education_base_sidebar_selection( ) {
        wp_reset_postdata();
        global $education_base_customizer_all_values;
        global $post;
        if(
            isset( $education_base_customizer_all_values['education-base-sidebar-layout'] ) &&
            (
                'left-sidebar' == $education_base_customizer_all_values['education-base-sidebar-layout'] ||
                'both-sidebar' == $education_base_customizer_all_values['education-base-sidebar-layout'] ||
                'no-sidebar' == $education_base_customizer_all_values['education-base-sidebar-layout']
            )
        ){
            $education_base_body_global_class = $education_base_customizer_all_values['education-base-sidebar-layout'];
        }
        else{
            $education_base_body_global_class= 'right-sidebar';
        }

        if( is_front_page() ){
            if( isset( $education_base_customizer_all_values['education-base-front-page-sidebar-layout'] ) ){
                if(
                    'right-sidebar' == $education_base_customizer_all_values['education-base-front-page-sidebar-layout'] ||
                    'left-sidebar' == $education_base_customizer_all_values['education-base-front-page-sidebar-layout'] ||
                    'both-sidebar' == $education_base_customizer_all_values['education-base-front-page-sidebar-layout'] ||
                    'no-sidebar' == $education_base_customizer_all_values['education-base-front-page-sidebar-layout']
                ){
                    $education_base_body_classes = $education_base_customizer_all_values['education-base-front-page-sidebar-layout'];
                }
                else{
                    $education_base_body_classes = $education_base_body_global_class;
                }
            }
            else{
                $education_base_body_classes= $education_base_body_global_class;
            }
        }
        elseif (is_singular() && isset( $post->ID )) {
            $post_class = get_post_meta( $post->ID, 'education_base_sidebar_layout', true );
            if ( 'default-sidebar' != $post_class ){
                if ( $post_class ) {
                    $education_base_body_classes = $post_class;
                } else {
                    $education_base_body_classes = $education_base_body_global_class;
                }
            }
            else{
                $education_base_body_classes = $education_base_body_global_class;
            }

        }
        elseif ( is_archive() ) {
            if( isset( $education_base_customizer_all_values['education-base-archive-sidebar-layout'] ) ){
                $education_base_archive_sidebar_layout = $education_base_customizer_all_values['education-base-archive-sidebar-layout'];
                if(
                    'right-sidebar' == $education_base_archive_sidebar_layout ||
                    'left-sidebar' == $education_base_archive_sidebar_layout ||
                    'both-sidebar' == $education_base_archive_sidebar_layout ||
                    'no-sidebar' == $education_base_archive_sidebar_layout
                ){
                    $education_base_body_classes = $education_base_archive_sidebar_layout;
                }
                else{
                    $education_base_body_classes = $education_base_body_global_class;
                }
            }
            else{
                $education_base_body_classes= $education_base_body_global_class;
            }
        }
        else {
            $education_base_body_classes = $education_base_body_global_class;
        }
        return $education_base_body_classes;
    }
endif;

/**
 * BreadCrumb Settings
 */
if( ! function_exists( 'education_base_breadcrumbs' ) ):
    function education_base_breadcrumbs() {
        if ( ! function_exists( 'breadcrumb_trail' ) ) {
            require education_base_file_directory('acmethemes/library/breadcrumbs/breadcrumbs.php');
        }
        $breadcrumb_args = array(
            'container'   => 'div',
            'show_browse' => false
        );
        echo "<div class='breadcrumbs init-animate slideInUp2'><div id='education-base-breadcrumbs'>";
        breadcrumb_trail( $breadcrumb_args );
        echo "</div></div>";
    }
endif;


/**
 * Return content of fixed length
 *
 * @since Education Base 1.0.0
 *
 * @param string $education_base_content
 * @param int $length
 * @return string
 *
 */
if ( ! function_exists( 'education_base_words_count' ) ) :
    function education_base_words_count( $education_base_content = null, $length = 16 ) {
        $length = absint( $length );
        $source_content = preg_replace( '`\[[^\]]*\]`', '', $education_base_content );
        $trimmed_content = wp_trim_words( $source_content, $length, '...' );
        return $trimmed_content;
    }
endif;

/**
 * More Text
 *
 * @since Education Base 1.0.0
 *
 * @param null
 * @return string
 *
 */
if ( !function_exists('education_base_blog_archive_more_text') ) :
    function education_base_blog_archive_more_text( ) {
        global $education_base_customizer_all_values;
        $education_base_blog_archive_read_more = $education_base_customizer_all_values['education-base-blog-archive-more-text'];
        $education_base_blog_archive_read_more = esc_html( $education_base_blog_archive_read_more );
        return $education_base_blog_archive_read_more;
    }
endif;