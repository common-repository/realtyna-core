<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

?>
<div class="re-carousel-items style-two">
    <div class="re-testimonial-container clearfix">
        <?php
            if(!empty($rating = get_post_meta(get_the_ID(), 'rating', true))){
                $html_rating  = '<div class="rating">';
                for ($i=1;$i<=5;$i++){
                    $html_rating .= '<span class="fa fa-star '. ($i<=$rating ? 'active':'') .'"></span>';
                }
                $html_rating .= '</div>';
                echo $html_rating;
            }
        ?>
        <div class="re-content">
            <?php
                $content = get_the_content();
            ?>
            <div class="re-testimonial-content">
                <span class="first-part"><?php echo wp_trim_words( $content, $num_words , '...' ); ?></span>
            </div>
        </div>
        <div class="re-testimonial-thumb">
            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_post_thumbnail(); ?></a>
            <span class="re-client-name" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></span>
        </div>
    </div>
</div>
