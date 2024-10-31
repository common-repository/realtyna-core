<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$this->current_property = $wpl_properties['current'];

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 360;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 270;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->category = (isset($params['category']) and trim($params['category']) != '') ? $params['category'] : '';
$this->resize = (isset($params['resize']) and trim($params['resize']) != '') ? $params['resize'] : 1;
$this->rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$this->watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 0;

$this->lazyload = (isset($params['lazyload']) and trim($params['lazyload']) != '') ? $params['lazyload'] : 0;
$lazy_load = $this->lazyload ? 'lazyimg' : '';
$src = $this->lazyload ? 'data-src' : 'src';

/** show tags **/
$show_tags = (isset($params['show_tags']) and trim($params['show_tags']) != '') ? $params['show_tags'] : 1;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();

// Filter images by category
if(trim($this->category) != '') $raw_gallery = $this->categorize($raw_gallery, $this->category);

$gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));

/** import js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.simple', true, true);

/** Theme Options **/
global $sesame_options;

$js[] = (object) array('param1'=>'lightslider.js', 'param2'=>'packages/light_slider/js/lightslider.min.js','param4' => '1');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$css[] = (object) array('param1'=>'lightslider.css', 'param2'=>'packages/light_slider/css/lightslider.min.css');
foreach($css as $style) wpl_extensions::import_style($style);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php echo $this->property_id; ?>">
    <?php
    if(!count($gallery))
    {
        echo '<a class="no_image_box" href="'.$wpl_properties['current']['property_link'].'"></a>';
    }
    else
    {
        $image_url = $gallery[0]['url'];
        $image_alt = '';
        
        if(isset($gallery[0]['raw']['item_extra2'])) $image_alt = $gallery[0]['raw']['item_extra2'];
        elseif(isset($wpl_properties['current']['raw']['meta_keywords'])) $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

        if($this->resize and $this->image_width and $this->image_height and $gallery[0]['category'] != 'external')
        {
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $gallery[0]['raw']['item_name'];
            $params['image_parentid'] = $gallery[0]['raw']['parent_id'];
            $params['image_parentkind'] = $gallery[0]['raw']['parent_kind'];
            $params['image_source'] = $gallery[0]['path'];
            
            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
        }
        
        echo '<a class="noHover" href="'.$wpl_properties['current']['property_link'].'"><img '.$this->itemprop_image.' id="wpl_gallery_image'.$this->property_id .'" '.$src.'="'.$image_url.'" class="'.$lazy_load.' wpl_gallery_image '.$this->image_class.'" alt="'.$image_alt.'" width="'.$this->image_width.'" height="'.$this->image_height.'" style="width: '.$this->image_width.'px; height: '.$this->image_height.'px;" /></a>';
        if(count($gallery) > 1 && $sesame_options['wpl_listings_gallery'] == 1) {
            echo '<div class="load_gallery_actions"><a class="prev" onclick="return wpl_load_gallery('.$this->property_id.')"></a>';
            echo '<div class="gallery_actions_dots"><ul><li class="dot active" onclick="return wpl_load_gallery('.$this->property_id.')"></li><li class="dot" onclick="return wpl_load_gallery('.$this->property_id.')"></li><li class="dot" onclick="return wpl_load_gallery('.$this->property_id.')"></li><li class="dot" onclick="return wpl_load_gallery('.$this->property_id.')"></li><li class="dot" onclick="return wpl_load_gallery('.$this->property_id.')"></li></ul></div>';
            echo '<a class="next" onclick="return wpl_load_gallery('.$this->property_id.')"></a></div>';
        }
    }
	?>
</div>

<?php if($show_tags): ?>
<div class="wpl-listing-tags-wp">
    <div class="wpl-listing-tags-cnt">
        <?php /* Property tags */ echo $this->tags(); ?>
    </div>
</div>
<?php endif; ?>