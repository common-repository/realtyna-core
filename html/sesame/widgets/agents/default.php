<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->lazyload = (isset($instance['data']['lazyload']) and trim($instance['data']['lazyload']) != '') ? $instance['data']['lazyload'] : 0;
$lazy_load = $this->lazyload ? 'lazyimg' : '';
$src = $this->lazyload ? 'data-src' : 'src';

/** import js codes **/
$this->_wpl_import('widgets.agents.scripts.js', true, true);

?>
<div class="wpl_agents_widget_container <?php echo ((isset($instance['data']['style']) and $instance['data']['style'] == '2') ? 'vertical' : '' ) ?> <?php echo $this->css_class; ?>">
    <?php
    foreach($wpl_profiles as $key=>$profile)
	{
        $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
        $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
        ?>
		<div class="wpl_profile_container" id="wpl_profile_container<?php echo $profile['data']['id']; ?>" <?php echo $this->itemscope.' '.$this->itemtype_RealEstateAgent; ?>>
			<div class="wpl_profile_picture"
				<?php
				    echo 'style="'.(isset($profile['profile_picture']['image_width']) ? 'width:'.$profile['profile_picture']['image_width'].'px;' : '').(isset($profile['profile_picture']['image_height']) ? 'height:'.$profile['profile_picture']['image_height'].'px;' : '').'"'; ?>>
					<?php
					if(isset($profile['profile_picture']['url']))
					{
						echo '<img class="'.$lazy_load.'" '.$this->itemprop_image.' '.$src.'="'.$profile['profile_picture']['url'].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
					}
					else
					{
						echo '<div class="no_image"></div>';
					}
				?>
			</div>

			<div class="wpl_profile_container_title">
				<?php
					echo '<a href='.$profile['profile_link'].'><h2 class="title" '.$this->itemprop_name.'>'.$agent_name.' '.$agent_l_name.'</h2></a>';
				?>
			</div>
			<ul>
			    <?php
    			    if(isset($instance['data']['mailto_status']) && $instance['data']['mailto_status'] == 1 || isset($instance['data']['mailto_status']) && $instance['data']['mailto_status'] == "Yes"){
    					if(isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '0') echo '<li class="email"><a '.$this->itemprop_email.' href="mailto:'.$profile['data']['main_email'].'"><img class="'.$lazy_load.'" '.$src.'="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" /></a></li>';
    					if(isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '1') echo '<li class="email"><a class="email" '.$this->itemprop_email.' href="mailto:'.$profile['data']['main_email'].'">'.$profile['data']['main_email'].'</a></li>';
    				}
                    else{
    					if (isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '0') echo '<li class="email"><img class="'.$lazy_load.'" '.$this->itemprop_email.' '.$src.'="' . $profile["main_email_url"] . '" alt="' . $agent_name . ' ' . $agent_l_name . '" /></li>';
    					if (isset($profile['main_email_url']) and wpl_global::get_setting('profile_email_type') == '1') echo '<li class="email"><p class="email">'.$profile['data']['main_email'].'</p></li>';
    				}
			    ?>
				<?php if(isset($profile['materials']['website']['value'])): ?>
					<li class="website">
						<a <?php echo $this->itemprop_url; ?> href="<?php
						$urlStr = $profile['materials']['website']['value'];
						$parsed = parse_url($urlStr);
						if (empty($parsed['scheme'])) {
							$urlStr = 'http://' . ltrim($urlStr, '/');
						}
						echo $urlStr;
						?>" target="_blank"><?php echo $urlStr; ?></a>
                    </li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['tel']['value'])): ?>
					<li class="phone">
						<a href="tel:<?php echo $profile['materials']['tel']['value']; ?>">
							<span <?php echo $this->itemprop_telephone; ?> ><?php echo $profile['materials']['tel']['value']; ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['mobile']['value'])): ?>
					<li class="mobile">
						<a href="tel:<?php echo $profile['materials']['mobile']['value']; ?>">
							<span <?php echo $this->itemprop_telephone; ?>><?php echo $profile['materials']['mobile']['value']; ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['fax']['value'])): ?>
					<li class="fax">
						<span <?php echo $this->itemprop_faxNumber; ?> style="display: none"><?php echo $profile['materials']['fax']['value']; ?></span>
					</li>
				<?php endif ;?>

				<?php if(isset($profile['materials']['company_address'])): ?>
					<li style="display:none">
						<div <?php echo $this->itemprop_address.' '.$this->itemscope.' '.$this->itemtype_PostalAddress; ?>  class="company_address"><span <?php echo $this->itemprop_addressLocality; ?>><?php echo $user_data['wpl_user']['materials']['company_address']['value']; ?></span></div>
					</li>
				<?php endif; ?>
			</ul>
			<div class="agents-profile-footer">
				<a <?php echo $this->itemprop_url; ?>  href="<?php echo $profile['profile_link']; ?>" class="view_properties"><?php echo __('View properties', 'real-estate-listing-realtyna-wpl'); ?></a>
				<?php if(isset($profile['materials']['tel']['value'])): ?>
					<a class="agents-profile-phone" href="tel:<?php echo $profile['materials']['tel']['value']; ?>">
						<span <?php echo $this->itemprop_telephone; ?> ><?php echo $profile['materials']['tel']['value']; ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php
	}
    ?>
</div>