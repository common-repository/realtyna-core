<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'field_308';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

// Membership ID of current user
$current_user_membership_id = wpl_users::get_user_membership();

// Favorites
if(wpl_global::check_addon('PRO') and $this->favorite_btn) $favorites = wpl_addon_pro::favorite_get_pids();

global $sesame_options;

foreach($this->wpl_properties as $key=>$property)
{
	if($key == 'current') continue;

	/** unset previous property **/
	unset($this->wpl_properties['current']);

	/** set current property **/
	$this->wpl_properties['current'] = $property;

	if(isset($property['materials']['bedrooms']['value']) and trim($property['materials']['bedrooms']['value'])) $room = sprintf('<div class="bedroom"><span class="value">%s</span><span class="name">%s</span></div>', $property['materials']['bedrooms']['value'], __("Bedroom(s)", 'real-estate-listing-realtyna-wpl'));
	elseif(isset($property['materials']['rooms']['value']) and trim($property['materials']['rooms']['value'])) $room = sprintf('<div class="room"><span class="value">%s</span><span class="name">%s</span></div>', $property['materials']['rooms']['value'], __("Room(s)", 'real-estate-listing-realtyna-wpl'));
	else $room = '';

	$bathroom = (isset($property['materials']['bathrooms']['value']) and trim($property['materials']['bathrooms']['value'])) ? sprintf('<div class="bathroom"><span class="value">%s</span><span class="name">%s</span></div>', $property['materials']['bathrooms']['value'], __("Bathroom(s)", 'real-estate-listing-realtyna-wpl')) : '';
	$parking = (isset($property['materials']['f_150']['values'][0]) and trim($property['materials']['f_150']['values'][0])) ? sprintf('<div class="parking"><span class="value">%s</span><span class="name">%s</span></div>', $property['materials']['f_150']['values'][0], __("Parking(s)", 'real-estate-listing-realtyna-wpl')) : '';
	$pic_count = (isset($property['raw']['pic_numb']) and trim($property['raw']['pic_numb'])) ? sprintf('<div class="pic_count"><span class="value">%s</span><span class="name">%s</span></div>', $property['raw']['pic_numb'], __("Picture(s)", 'real-estate-listing-realtyna-wpl')) : '';

	$living_area = isset($property['materials']['living_area']['value']) ? explode(' ', $property['materials']['living_area']['value']) : (isset($property['materials']['lot_area']['value']) ? explode(' ', $property['materials']['lot_area']['value']): array());
	$living_area_count = count($living_area);

	$build_up_area = $living_area_count ? '<div class="built_up_area">'.(isset($living_area[0]) ? implode(' ', array_slice($living_area, 0, $living_area_count-1)) : '').'<span>'.$living_area[$living_area_count-1].'</span></div>' : '';
	$property_price = isset($property['materials']['price']['value']) ? $property['materials']['price']['value'] : '&nbsp;';

	$description = stripslashes(strip_tags($property['raw'][$description_column]));
	if($description) $cut_position = (trim($description) ? strrpos(substr($description, 0, 400), '.', -1) : 0);
		
	if(!@$cut_position) $cut_position = 399;

	$property_id = $property['data']['id'];

	$office_name = $agent_name = '';
	if(wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name )
	{
		$office_name = isset($property['raw']['field_2111']) ? '<div class="wpl-prp-office-name"><label>'.$this->label_office_name.'</label> <span>'.stripslashes($property['raw']['field_2111']).'</span></div>' : '';
		$agent_name = isset($property['raw']['field_2112']) ? '<div class="wpl-prp-agent-name"> <label>'.$this->label_agent_name.'</label> <span>'.stripslashes($property['raw']['field_2112']).'</span></div>' : '';
	}
	?>
	<div class="wpl-column">
		<div class="wpl_prp_cont wpl_prp_cont_old
			<?php echo ((isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box'))) ? $this->property_css_class : ''); ?>"
			 id="wpl_prp_cont<?php echo $property['data']['id']; ?>"
			<?php	echo $this->itemscope.' '.$this->itemtype_SingleFamilyResidence; ?> >
			<div class="wpl_prp_top">
				<div class="wpl_prp_top_boxes front">
					<?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties'=>$this->wpl_properties)); ?>
				</div>
				<div class="wpl_prp_top_boxes back">
					<a <?php echo $this->itemprop_url;?> id="prp_link_id_<?php echo $property['data']['id']; ?>" href="<?php echo $property['property_link']; ?>" class="view_detail"><?php echo __('More Details', 'real-estate-listing-realtyna-wpl'); ?></a>
				</div>
			</div>
			<div class="wpl_prp_bot">

				<a <?php echo 'id="prp_link_id_'.$property['data']['id'].'_view_detail" href="'.$property['property_link'].'" class="view_detail" title="'.$property['property_title'].'"'; ?>>
					<h3 class="wpl_prp_title"	<?php echo $this->itemprop_name; ?> > <?php echo $property['property_title'] ?></h3>
				</a>

				<?php $location_visibility = wpl_property::location_visibility($property['data']['id'], $property['data']['kind'], $current_user_membership_id); ?>
				<h4 class="wpl_prp_listing_location"><span <?php echo $this->itemprop_address.''.$this->itemscope.' '.$this->itemtype_PostalAddress;?> ><span <?php echo $this->itemprop_addressLocality; ?>><?php echo ($location_visibility === true ? $property['location_text'] : $location_visibility);?></span></span></h4>
				<?php if(wpl_global::check_addon('MLS') && $this->show_agent_name || $this->show_office_name && (!empty($agent_name) || !empty($office_name))): ?>
					<div class="wpl-mls-brokerage-info">
						<?php if($this->show_agent_name) echo $agent_name; ?>
						<?php if($this->show_office_name) echo $office_name; ?>
					</div>
				<?php endif; ?>
				<div class="wpl_prp_desc" <?php echo $this->itemprop_description; ?>><?php echo substr($description, 0, $cut_position + 1); ?></div>
				<div class="price_box" <?php echo $this->itemscope.' '.$this->itemtype_offer; ?>>
				    <span <?php echo $this->itemprop_price; ?>><?php echo $property_price; ?></span>
			    </div>
			    <div class="wpl_prp_listing_icon_box">
					<?php echo $room . $bathroom . $parking . $build_up_area; ?>
					<?php if(wpl_global::get_setting('show_plisting_visits')): ?>
						<div class="visits_box">
							<span class="name"><?php echo __('Visits', 'real-estate-listing-realtyna-wpl'); ?>:</span><span class="value"><?php echo wpl_property::get_property_stats_item($property['data']['id'], 'visit_time'); ?></span>
						</div>
					<?php endif; ?>
					
					<?php if($sesame_options['wpl_listings_share_icons'] == 1 || $sesame_options['wpl_listings_fav_icon'] == 1): ?>
					<div class="wpl_prp_listing_like_share_box">
    	    			<?php if($sesame_options['wpl_listings_fav_icon'] == 1 && isset($favorites)): ?>
            				<div class="wpl_prp_listing_like">
            					<div class="wpl_listing_links_container">
            						<ul>
            							<?php $find_favorite_item = in_array($property_id, $favorites); ?>
            							<li class="favorite_link<?php echo ($find_favorite_item ? ' added' : '') ?>">
            								<a href="#" style="<?php echo ($find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_add_<?php echo $property_id; ?>" onclick="return wpl_favorite_control('<?php echo $property_id; ?>', 1);" title="<?php echo __('Add to favorites', 'real-estate-listing-realtyna-wpl'); ?>"></a>
            								<a href="#" style="<?php echo (!$find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_remove_<?php echo $property_id; ?>" onclick="return wpl_favorite_control('<?php echo $property_id; ?>', 0);" title="<?php echo __('Remove from favorites', 'real-estate-listing-realtyna-wpl'); ?>"></a>
            							</li>
            						</ul>
            					</div>
            				</div>
            			<?php endif; ?>
            			<?php if($sesame_options['wpl_listings_share_icons'] == 1): ?>
                        <div class="wpl_listing_links_container" id="wpl_listing_links_container">
                        	<ul>
                                <?php if($sesame_options['wpl_listings_share_facebook'] == 1): ?>
                        		<li class="facebook_link">
                        			<a class="wpl-tooltip-left" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $property['property_link']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;" title="<?php echo __('Share on Facebook', 'real-estate-listing-realtyna-wpl'); ?>"></a>
                                    <div class="wpl-util-hidden"><?php echo __('Share on Facebook', 'real-estate-listing-realtyna-wpl'); ?></div>
                                </li>
                                <?php endif; ?>
                        
                                <?php if($sesame_options['wpl_listings_share_twitter'] == 1): ?>
                        		<li class="twitter_link">
                        			<a class="wpl-tooltip-left" href="https://twitter.com/share?url=<?php echo $property['property_link']; ?>" target="_blank" title="<?php echo __('Tweet', 'real-estate-listing-realtyna-wpl'); ?>"></a>
                                    <div class="wpl-util-hidden"><?php echo __('Share on Twitter', 'real-estate-listing-realtyna-wpl'); ?></div>
                                </li>
                                <?php endif; ?>
                        
                                <?php if($sesame_options['wpl_listings_share_pinterest'] == 1): ?>
                        		<li class="pinterest_link">
                        			<a class="wpl-tooltip-left" href="http://pinterest.com/pin/create/link/?url=<?php echo $property['property_link']; ?>&media=<?php echo wpl_property::get_property_image($property_id, '300*300'); ?>&description=<?php echo (isset($property['property_title']) ? $property['property_title'] : ''); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;" title="<?php echo __('Pin it', 'real-estate-listing-realtyna-wpl'); ?>"></a>
                                    <div class="wpl-util-hidden"><?php echo __('Share on Pinterest', 'real-estate-listing-realtyna-wpl'); ?></div>
                                </li>
                                <?php endif; ?>
                                
                                <?php if($sesame_options['wpl_listings_share_linkedin'] == 1): ?>
                        		<li class="linkedin_link">
                        			<a class="wpl-tooltip-left" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $property['property_link']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;" title="<?php echo __('Share on Linkedin', 'real-estate-listing-realtyna-wpl'); ?>"></a>
                                    <div class="wpl-util-hidden"><?php echo __('Share on Linkedin', 'real-estate-listing-realtyna-wpl'); ?></div>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a class="share_link"></a>
                                </li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; // Share & Like Box ?>
				</div>
				
				<?php if($sesame_options['wpl_listings_agent_info'] == '1'):
    			        
                        $main_user_id = isset($property['raw']['user_id']) ? $property['raw']['user_id'] : '';
                        
                        if(!empty($main_user_id)):
                        
                        $user_ids = array();
                        $user_ids[] = $main_user_id;
                        
                        // Apply Filters
                        @extract(wpl_filters::apply('wpl_property_agent_user_ids', array('user_ids'=>$user_ids, 'property_id'=>$property['raw']['id'], 'pdf'=>false)));
                        
                        $pshow_fields = wpl_users::get_pshow_fields();
                        
                        $users_data = array();
                        foreach($user_ids as $user_id)
                        {
                            $wpl_user = wpl_users::full_render($user_id, $pshow_fields, NULL, array(), true);
                        	
                        	// User is not exists
                        	if(!isset($wpl_user['data']) or (isset($wpl_user['data']) and !$wpl_user['data']['id'])) continue;
                        	
                        	// Profile Size
                        	$agent_profile_size = (isset($sesame_options['wpl_listings_agent_image_size']) ? $sesame_options['wpl_listings_agent_image_size']:'35');
                        	
                            /** resizing profile image **/
                            $params                   = array();
                            $params['image_parentid'] = $user_id;
                            $params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
                            $profile_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
                            $profile_image            = wpl_images::create_profile_images($profile_path, $agent_profile_size, $agent_profile_size, $params);
                        
                            $agent_name               = isset($wpl_user['materials']['first_name']['value']) ? $wpl_user['materials']['first_name']['value'] : '';
                            $agent_l_name             = isset($wpl_user['materials']['last_name']['value']) ? $wpl_user['materials']['last_name']['value'] : '';
                            $profile_url              = wpl_users::get_profile_link($user_id);
                        
                            $users_data[] = array('wpl_user'=>$wpl_user, 'profile_image'=>$profile_image, 'agent_name'=>$agent_name, 'agent_l_name'=>$agent_l_name, 'profile_url'=>$profile_url);
                        }
                        
                        ?>
                        <div class="wpl_prp_listings_agent_box">
                        	<?php foreach($users_data as $user_data): ?>
                        	<a href="<?php echo esc_url($user_data['profile_url']); ?>">
                    			<div class="agent_profile">
                					<?php if($user_data['profile_image']): ?>
                						<img src="<?php echo esc_url($user_data['profile_image']); ?>" class="profile_image" alt="<?php echo esc_attr($user_data['agent_name']). ' '.esc_attr($user_data['agent_l_name']); ?>" />
                					<?php else: ?>
                						<div class="no_image"></div>
                					<?php endif; ?>
                        		</div>
                        		<div class="agent_name"><?php echo esc_html($user_data['agent_name']).' '.esc_html($user_data['agent_l_name']); ?></div>
                        	</a>
                        	<?php endforeach; ?>
				        </div>
                        <?php endif; ?>
			    <?php endif; // Show Agent Info ?>
			     
			</div>
			
		</div>
	</div>
	<?php
}
?>


<script>
function prev_slide(slider){
    slider.goToPrevSlide();
    jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul li').each(function(index){
        if(index+1 === slider.getCurrentSlideCount())
            jQuery(this).addClass('active');
        else
            jQuery(this).removeClass('active');
    });
    if(slider.getCurrentSlideCount() > 5)
        jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul').css({"transform": "translateX(-"+((slider.getCurrentSlideCount()-1)*13)+"px)"});
    else
        jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul').css({"transform": "translateX(0px)"});

}
function next_slide(slider){
    slider.goToNextSlide();
    jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul li').each(function(index){
        if(index+1 === slider.getCurrentSlideCount())
            jQuery(this).addClass('active');
        else
            jQuery(this).removeClass('active');
    });
    if(slider.getCurrentSlideCount() > 5)
        jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul').css({"transform": "translateX(-"+((slider.getCurrentSlideCount()-1)*13)+"px)"});
    else
        jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul').css({"transform": "translateX(0px)"});
}
function go_to_slider(slider,slide){
    slider.goToSlide(slide);
    jQuery(slider[0]).parents('.wpl_prp_top_boxes').find('.gallery_actions_dots ul li').each(function(index){
        if(index+1 === slider.getCurrentSlideCount())
            jQuery(this).addClass('active');
        else
            jQuery(this).removeClass('active');
    });
}
</script>