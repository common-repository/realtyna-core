<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

?>
<div class="re-carousel-items">
    <div class="re-testimonial-container clearfix">
        <div class="re-testimonial-thumb">
            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_post_thumbnail(); ?></a>
        </div>
        <div class="re-content">
            <?php
                $content = get_the_content();
                $content_first_part = substr($content , 0 ,$description_limit);
                $content_second_part = substr($content , $description_limit ,strlen($content));
            ?>
            <div class="re-testimonial-content">
                <span class="first-part"><?php echo esc_html($content_first_part); ?></span>
                <span class="second-part"><?php echo esc_html($content_second_part); ?></span>
                <a href="#" class="read-more"><?php echo esc_html__('Read More','realtyna-core')  ?></a>
            </div>
            <span class="re-client-name" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></span>
        </div>
    </div>
</div>