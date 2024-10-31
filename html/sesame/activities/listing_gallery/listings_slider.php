<?php
_wpl_import('libraries.images');

$image_width = isset($image_width) ? $image_width : 375;
$image_height = isset($image_height) ? $image_height : 270;
/** set params **/

//$js[] = (object) array('param1'=>'lightslider.js', 'param2'=>'packages/light_slider/js/lightslider.min.js','param4' => '1');
//foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

//$css[] = (object) array('param1'=>'lightslider.css', 'param2'=>'packages/light_slider/css/lightslider.min.css');
//foreach($css as $style) wpl_extensions::import_style($style);

/** import js/css codes **/
//$this->_wpl_import($this->tpl_path.'.scripts.plisting_slide', true, false);


foreach($wpl_properties as $key=>$property)
{
    $property_id = $property['data']['id'];

    $kind = $property['data']['kind'];
    $locations	 = $property['location_text'];

    // Get blog ID of property
    $blog_id = wpl_property::get_blog_id($property_id);
    ?>
    <script type="text/javascript">
        (function($)
        {
            $(function()
            {
                window.slider_prp_<?php echo $property_id; ?> = $('#wpl_gallery_wrapper-3001 #wpl_gallery_cont<?php echo $property_id; ?>').lightSlider(
                    {
                        pause : 4000,
                        auto: <?php echo (($this->autoplay) ? 'true' : 'false'); ?>,
                        mode: 'fade',
                        item: 1,
                        thumbItem:false,
                        loop: true,
                        autoWidth: true,
                        adaptiveHeight: true,
                        gallery: false,
                        controls: false,
                        //preload: 1,
                        enableTouch: false,
                        pager: false,
                        onSliderLoad: function(el)
                        {
                            slider_prp_<?php echo $property_id; ?>.goToSlide(2);
                        }
                    });
            });
            
        })(jQuery);

    </script>
    <div class="wpl-gallery-pshow-wp" id="wpl_gallery_wrapper-3001">

        <ul class="wpl-gallery-pshow" id="wpl_gallery_cont<?php echo $property_id; ?>">
            <?php
                    if(isset($property['items']['gallery']))
                    {

                        $i = 0;
                        $images_total = count($property['items']['gallery']);
                        $property_path = wpl_items::get_path($property_id, $kind, $blog_id);
                        foreach($property['items']['gallery'] as $key1 => $image) {
                            //$image = $property['items']['gallery'][0];
                            $params = array();
                            $params['image_name'] = $image->item_name;
                            $params['image_parentid'] = $image->parent_id;
                            $params['image_parentkind'] = $image->parent_kind;
                            $params['image_source'] = $property_path . $image->item_name;

                            if (isset($image->item_cat) and $image->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
                            else $image_url = $image->item_extra3;

                            echo '<li id="wpl-gallery-img-' . $i . '" style="position: absolute; opacity: 0;">
                                     <a href="'.$property['property_link'].'">
                                        <span>
                                           <img itemprop="image" id="wpl_gallery_image' . $property_id . '_' . $i . '" src="' . $image_url . '" class="wpl_gallery_image" width="' . $image_width . '" height="' . $image_height . '" style="width: ' . $image_width . 'px; height: ' . $image_height . 'px;"  />
                                        </span>
                                     </a>
                                  </li>';
                            $i++;
                        }
                    }
                    else
                    {
                        echo '<a href="'.$property['property_link'].'"><div class="no_image_box"></div></a>';
                    }
            ?>
        </ul>
    </div>
           
    <?php
        if($images_total > 1) {
            echo '<div class="load_gallery_actions"><a class="prev" onclick="return prev_slide(slider_prp_'.$property_id.')"></a>';
            echo '<div class="gallery_actions_dots"><ul style="width:'.($images_total*13).'px;transform:translateX(0px);">';
            for($i=1;$i<=$images_total;$i++){
                echo '<li class="dot '.($i == 2 ? "active":"").'" onclick="return go_to_slider(slider_prp_'.$property_id.','.$i.');"></li>';
            }
            echo '</ul></div>';
            echo '<a class="next" onclick="return next_slide(slider_prp_'.$property_id.');"></a></div>';
        }
    ?>
    
<?php } ?>