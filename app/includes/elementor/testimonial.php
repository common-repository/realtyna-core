<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Elementor_Testimonial')):

/**
 * Elementor Dynamic Testimonial Widget.
 * Elementor widget that shows Testimonials 
 * @since 1.0.0
 */
class RTCORE_Elementor_Testimonial extends \Elementor\Widget_Base
{
    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'testimonial_list';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Dynamic Testimonials', 'realtyna-core');
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-testimonial-carousel';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array('general');
    }

    /**
     * Register oEmbed widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'realtyna-core'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'description_limit',
            [
                'label' => __('Description Limit Charachters', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '200',
                'title' => esc_html__('Enter some text', 'realtyna-core'),
            ]
        );

        $this->add_control(
            'testimonial_template',
            [
                'label' => __('Style', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'one',
                'options' => [
                    'one' => __('One', 'realtyna-core'),
                    'two' => __('Two', 'realtyna-core'),
                ]
            ]
        );
        
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of posts per page', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 4,
                'options' => [
                    1 => __('One', 'realtyna-core'),
                    2 => __('Two', 'realtyna-core'),
                    3 => __('Three', 'realtyna-core'),
                    4 => __('Four', 'realtyna-core'),
                    6 => __('Six', 'realtyna-core'),
                ]
            ]
        );

        $this->add_control(
            'posts_count',
            [
                'label' => __('Number of posts', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
            ]
        );

		$this->add_control(
			'num_words',
			[
				'label' => esc_html__( 'Number of words', 'realtyna-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '35',
				'min' => 0,
				'max' => 200,
				'step' => 1,
				'default' => 35,
			]
		);
		
        $this->add_control(
            'show_dots',
            [
                'label' => __('Show dot navigators', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __('Show arrow navigators', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $posts_count = $settings['posts_count'];
        $posts_per_page = $settings['posts_per_page'];
        $description_limit = $settings['description_limit'];
        $testimonial_template = (isset($settings['testimonial_template']) ? $settings['testimonial_template']:'one');
        $show_dots = ($settings['show_dots']) ? 'true' : 'false';
        $show_arrows = ($settings['show_arrows']) ? 'true' : 'false';
        $num_words = (isset($settings['num_words']) ? $settings['num_words']:'35');
        
        global $post;

        $args = array(
            'post__not_in' => array($post->ID),
            'posts_per_page' => $posts_count, 
            'post_type' => 'rtcore-testimonial',
        );

        $query = new wp_query($args);
        if($query->have_posts())
        {
            ?>
            <div class="re-testimonial re-carousel" data-slick='{"slidesToShow": <?php echo esc_attr($posts_per_page); ?>, "slidesToScroll": 1 , "dots": <?php echo esc_attr($show_dots); ?>, "arrows": <?php echo esc_attr($show_arrows); ?>}'>
            <?php
            while($query->have_posts())
            {
                $query->the_post();
                include 'tmpl/'. $testimonial_template . '.php';
            }

            echo '</div>';
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                   
                    $('.re-testimonial').find('.slick-active').removeClass('middle');
                    $('.re-testimonial').find('.slick-active').each(function(index){
                        if(index == 1)
                            $(this).addClass('middle');
                    });
                            
                    $('.re-testimonial').on('mouseup', function(e){
                        setTimeout(function(){
                            $('.re-testimonial').find('.slick-active').removeClass('middle');
                            $('.re-testimonial').find('.slick-active').each(function(index){
                                if(index == 1)
                                    $(this).addClass('middle');
                            });
                        },10);
                    });
                });
            </script>
            <?php
        }
        
        wp_reset_query();
    }
  
}

endif;