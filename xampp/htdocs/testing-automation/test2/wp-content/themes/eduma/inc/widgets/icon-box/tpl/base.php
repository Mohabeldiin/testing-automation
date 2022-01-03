<?php
$extent_class = '';

$target_link   = ! empty( $instance['read_more_group']['target'] ) ? $instance['read_more_group']['target'] : '_self';
$nofollow_link = ! empty( $instance['read_more_group']['nofollow'] ) ? $instance['read_more_group']['nofollow'] : '';

if ( isset( $instance['font_image_group']['icon_img'] ) && $instance['font_image_group']['icon_img'] != '0' ) {
	$extent_class .= ' has_custom_image';
}
if ( isset( $instance['read_more_group']['read_more'] ) && $instance['read_more_group']['read_more'] == 'more' ) {
	$extent_class .= ' has_read_more';
}

if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'text_number' ) {
	$extent_class .= ' layout_text_number';
}

/* setup hover color */
$data_color     = $boxes_icon_style  = $style_bg = "";

if ( isset( $instance['color_group']['icon_hover_color'] ) && $instance['color_group']['icon_hover_color'] <> '' ) {
	$data_color .= ' data-icon="' . $instance['color_group']['icon_hover_color'] . '"';
}
if ( isset( $instance['color_group']['icon_border_color_hover'] ) && $instance['color_group']['icon_border_color_hover'] <> '' ) {
	$data_color .= ' data-icon-border="' . $instance['color_group']['icon_border_color_hover'] . '"';
}
if ( isset( $instance['color_group']['icon_bg_color_hover'] ) && $instance['color_group']['icon_bg_color_hover'] <> '' ) {
	$data_color .= ' data-icon-bg="' . $instance['color_group']['icon_bg_color_hover'] . '"';
}

if ( isset( $instance['read_more_group']['button_read_more_group']['bg_read_more_text_hover'] ) && $instance['read_more_group']['button_read_more_group']['bg_read_more_text_hover'] <> '' ) {
	$data_color .= ' data-btn-bg="' . $instance['read_more_group']['button_read_more_group']['bg_read_more_text_hover'] . '"';
}
if ( isset( $instance['read_more_group']['button_read_more_group']['read_more_text_color_hover'] ) && $instance['read_more_group']['button_read_more_group']['read_more_text_color_hover'] <> '' ) {
	$data_color .= ' data-text-readmore="' . $instance['read_more_group']['button_read_more_group']['read_more_text_color_hover'] . '"';
}

// custom font heading
$ct_font_heading = $style_font_heading = '';
$ct_font_heading .= ( isset( $instance['title_group']['color_title'] ) && $instance['title_group']['color_title'] != '' ) ? 'color: ' . $instance['title_group']['color_title'] . ';' : '';
if ( isset( $instance['title_group']['font_heading'] ) && $instance['title_group']['font_heading'] == 'custom' ) {
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_font_size'] != '' ) ? 'font-size: ' . $instance['title_group']['custom_heading']['custom_font_size'] . 'px; line-height: ' . $instance['title_group']['custom_heading']['custom_font_size'] . 'px;' : '';
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_font_weight'] != '' ) ? 'font-weight: ' . $instance['title_group']['custom_heading']['custom_font_weight'] . ';' : '';
	$ct_font_heading .= ( ! empty( $instance['title_group']['custom_heading']['custom_mg_top'] ) ) ? 'margin-top: ' . $instance['title_group']['custom_heading']['custom_mg_top'] . 'px;' : '';
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_mg_bt'] != '' ) ? 'margin-bottom: ' . $instance['title_group']['custom_heading']['custom_mg_bt'] . 'px;' : '';
}
if ( $ct_font_heading ) {
	$style_font_heading = 'style="' . $ct_font_heading . '"';
}
/* end setup hover color */

// icon style
$icon_style = $boxes_icon_style = '';
$icon_style .= ( isset( $instance['color_group']['icon_bg_color'] ) && $instance['color_group']['icon_bg_color'] != '' ) ? 'background-color: ' . $instance['color_group']['icon_bg_color'] . ';' : '';
$icon_style .= ( isset( $instance['color_group']['icon_border_color'] ) && $instance['color_group']['icon_border_color'] != '' ) ? 'border-color:' . $instance['color_group']['icon_border_color'] . ';' : '';
$icon_style .= ! empty( $instance['width_icon_box'] ) ? 'width: ' . $instance['width_icon_box'] . 'px;' : '';
$icon_style .= ( ! empty( $instance['height_icon_box'] ) && '' != $instance['height_icon_box'] ) ? 'height: ' . $instance['height_icon_box'] . 'px;' : '';
if ( $icon_style ) {
	$boxes_icon_style = 'style="' . $icon_style . '"';
}
// end icon style

// read more button css
$read_more = $read_more_style = $html_dot = '';
$read_more .= ( isset( $instance['read_more_group']['button_read_more_group']['border_read_more_text'] ) && $instance['read_more_group']['button_read_more_group']['border_read_more_text'] != '' ) ? 'border-color: ' . $instance['read_more_group']['button_read_more_group']['border_read_more_text'] . ';' : '';
$read_more .= ( isset( $instance['read_more_group']['button_read_more_group']['bg_read_more_text'] ) && $instance['read_more_group']['button_read_more_group']['bg_read_more_text'] != '' ) ? 'background-color: ' . $instance['read_more_group']['button_read_more_group']['bg_read_more_text'] . ';' : '';
$read_more .= ( isset( $instance['read_more_group']['button_read_more_group']['read_more_text_color'] ) && $instance['read_more_group']['button_read_more_group']['read_more_text_color'] != '' ) ? 'color: ' . $instance['read_more_group']['button_read_more_group']['read_more_text_color'] . ';' : '';
if ( $read_more ) {
	$read_more_style = ' style="' . $read_more . '"';
}
// end
// video background
$bg_video = $poster = $class_bg_video = $icon_play = '';
if ( isset( $instance['self_poster'] ) && $instance['self_poster'] != '' ) {
	$poster = ' poster="' . wp_get_attachment_url( $instance['self_poster'] ) . '"';
}
if ( isset( $instance['widget_background'] ) && $instance['widget_background'] == 'bg_video' && isset( $instance['self_video'] ) && $instance['self_video'] != '' ) {
	$src            = wp_get_attachment_url( $instance['self_video'] );
	$bg_video       = '<video loop muted ' . $poster . ' class="full-screen-video">
					  <source src="' . $src . '" type="video/mp4">
  				</video>';
	$class_bg_video = ' background-video';
	$icon_play      = '<span class="bg-video-play"></span>';
}
$bg_pos = '';
if ( isset( $instance['layout_group']['style_box'] ) && $instance['layout_group']['style_box'] == 'image_box' && isset( $instance['layout_group']['bg_image_box'] ) && $instance['layout_group']['bg_image_box'] != '' ) {
	$bg_pos .= 'background-image:url( ' . wp_get_attachment_url( $instance['layout_group']['bg_image_box'] ) . ');background-repeat:no-repeat';
	$bg_pos .= $instance['layout_group']['bg_image_pos'] ? ";background-position:" . $instance['layout_group']['bg_image_pos'] : '';
}
if ( isset( $instance['layout_group']['style_box'] ) && $instance['layout_group']['style_box'] == 'contact_info' && isset( $instance['layout_group']['dot_line'] ) && $instance['layout_group']['dot_line'] != '' ) {
	$extent_class    .= ' dot_line_buttom_top';
	$style_color_dot = ( $instance['color_group']['icon_color'] != '' ) ? ' style="color:' . $instance['color_group']['icon_color'] . ';background-color:' . $instance['color_group']['icon_color'] . ';"' : '';
	$html_dot        = '<span class="dot-line"' . $style_color_dot . '><span' . $style_color_dot . '></span></span>';
}

if ( isset( $instance['widget_background'] ) && $instance['widget_background'] == 'bg_color' && isset( $instance['bg_box_color'] ) && $instance['bg_box_color'] != '' ) {
	$style_bg = 'style="background-color: ' . $instance['bg_box_color'] . ';' . $bg_pos . '"';
} elseif ( $bg_pos ) {
	$style_bg = 'style="' . $bg_pos . '"';
}

// show title
$html_title = $border_bottom_title = $class_separator = '';

if ( isset( $instance['title_group']['line_after_title'] ) && ( $instance['title_group']['line_after_title'] == '1' || $instance['title_group']['line_after_title'] == true ) ) {
	$border_bottom_title = '<span class="line-heading"></span>';
	$extent_class      .= ' wrapper-line-heading';
}
if ( isset( $instance['layout_group']['text_align_sc'] ) ) {
	$class_bg_video .= ' ' . $instance['layout_group']['text_align_sc'];
}
if ( isset( $instance['layout_group']['style_box'] ) ) {
	$class_bg_video .= ' ' . $instance['layout_group']['style_box'];
}
$prefix = '<div class="wrapper-box-icon' . $extent_class . $class_bg_video . '" ' . $style_bg . ' ' . $data_color . '>';
$suffix = '</div>';
//wrapper-box-icon

// Set link to Box
$more_link = $link_prefix = $link_sufix = $complete_prefix = $complete_suffix = $icon_link_prefix = $icon_link_suffix = '';
if ( isset( $instance['read_more_group']['link'] ) && $instance['read_more_group']['link'] != '' ) {
	if ( isset( $instance['read_more_group']['read_more'] ) && $instance['read_more_group']['read_more'] == 'complete_box' ) {
		$complete_prefix .= '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . '>';
		$complete_suffix .= '</a>';
	} elseif ( isset( $instance['read_more_group']['read_more'] ) && $instance['read_more_group']['read_more'] == 'more' ) {
		// Display Read More
		$more_link = '<a class="smicon-read sc-btn" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . ' ' . $read_more_style . ' >';
		$more_link .= $instance['read_more_group']['button_read_more_group']['read_text'];
		$more_link .= '<i class="fa fa-chevron-right"></i></a>';
	} elseif ( isset( $instance['read_more_group']['read_more'] ) && $instance['read_more_group']['read_more'] == 'title' ) {
		//Box Title
		$link_prefix .= '<a class="smicon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . ' >';
		$link_sufix  .= '</a>';
	}
}
// end
$boxes_content_style = $content_style = '';
if ( isset( $instance['layout_group']['pos'] ) && $instance['layout_group']['pos'] != 'top' ) {
	$boxes_content_style .= ( $instance['width_icon_box'] != '' && $instance['font_awesome_group']['icon'] != 'none' ) ? 'width: calc( 100% - ' . $instance['width_icon_box'] . 'px - 15px);' : '';
}
// new demo: edit by tuanta for new demo

if ( $boxes_content_style && isset( $instance['icon_type'] ) && $instance['icon_type'] != 'text_number' ) {
	$content_style = ' style="' . $boxes_content_style . '"';
}

// end new demo

if ( isset( $instance['title_group']['title'] ) && $instance['title_group']['title'] != '' ) {
	$html_title .= '<div class="sc-heading article_heading">';
	$html_title .= '<' . $instance['title_group']['size'] . ' class = "heading__primary' . $class_separator . '" ' . $style_font_heading . '>';
	if ( $complete_prefix != '' ) {
		$complete_prefix = '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . ' ' . $style_font_heading . '>';
	}
	if ( $link_prefix != '' ) {
		$link_prefix = '<a class="smicon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . ' ' . $style_font_heading . '>';
	}
	$html_title .= $complete_prefix . $link_prefix . $instance['title_group']['title'] . $link_sufix . $complete_suffix;
	$html_title .= '</' . $instance['title_group']['size'] . '>';
	$html_title .= $border_bottom_title;
	$html_title .= '</div>';
}
// end show title

$icon_link_prefix = $complete_prefix;
$icon_link_suffix = $complete_suffix;
if ( isset( $instance['read_more_group']['link'] ) && $instance['read_more_group']['link'] && ! empty( $instance['read_more_group']['link_to_icon'] ) && $instance['read_more_group']['link_to_icon'] != 'no' ) {
	$icon_link_prefix = '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '"' . $nofollow_link . '>';
	$icon_link_suffix = '</a>';
}

/* show icon or custom icon */
$html_icon = $icon_layout = '';
if ( isset( $instance['layout_group']['box_icon_style'] ) && $instance['layout_group']['box_icon_style'] ) {
	$icon_layout = ' ' . $instance['layout_group']['box_icon_style'];
}
if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'font-awesome' ) {

	if ( isset($instance['font_awesome_group']['icon']) &&  $instance['font_awesome_group']['icon'] == '' ) {
		$instance['font_awesome_group']['icon'] = 'none';
	}
	if (isset($instance['font_awesome_group']['icon']) && $instance['font_awesome_group']['icon'] != 'none' ) {
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';

		//		wp_enqueue_style( 'vc_font_awesome_5' );

		if ( strpos( $instance['font_awesome_group']['icon'], 'fa' ) !== false ) {
			$class = $instance['font_awesome_group']['icon'];
		} else {
			$class = 'fa fa-' . $instance['font_awesome_group']['icon'];
		}
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_awesome_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_awesome_group']['icon_size'] . 'px; line-height:' . $instance['font_awesome_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
} else if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'font-7-stroke' ) {
	if (isset($instance['font_7_stroke_group']['icon']) &&  $instance['font_7_stroke_group']['icon'] == '' ) {
		$instance['font_7_stroke_group']['icon'] = 'none';
	}
	if (isset($instance['font_7_stroke_group']['icon']) &&  $instance['font_7_stroke_group']['icon'] != 'none' ) {
		if ( strpos( $instance['font_7_stroke_group']['icon'], 'pe-7s' ) !== false ) {
			$class = $instance['font_7_stroke_group']['icon'];
		} else {
			$class = 'pe-7s-' . $instance['font_7_stroke_group']['icon'];
		}
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_7_stroke_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_7_stroke_group']['icon_size'] . 'px; line-height:' . $instance['font_7_stroke_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
} else if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'icomoon' ) {
	if (isset($instance['font_icomoon_group']['icon']) && $instance['font_icomoon_group']['icon'] == '' ) {
		$instance['font_icomoon_group']['icon'] = 'none';
	}
	if (isset($instance['font_icomoon_group']['icon']) && $instance['font_icomoon_group']['icon'] != 'none' ) {
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon icomoon">';
		$class     = 'icomoon-' . $instance['font_icomoon_group']['icon'];
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_icomoon_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_icomoon_group']['icon_size'] . 'px; line-height:' . $instance['font_icomoon_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
} else if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'font-flaticon' ) {

	if ( $instance['font_flaticon_group']['icon'] == '' ) {
		$instance['font_flaticon_group']['icon'] = 'none';
	}
	if ( $instance['font_flaticon_group']['icon'] != 'none' ) {
		if ( strpos( $instance['font_flaticon_group']['icon'], 'flaticon' ) !== false ) {
			$class = $instance['font_flaticon_group']['icon'];
		} else {
			$class = 'flaticon-' . $instance['font_flaticon_group']['icon'];
		}

		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_flaticon_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_flaticon_group']['icon_size'] . 'px; line-height:' . $instance['font_flaticon_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
} else if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'font_ionicons' ) {
	if ( $instance['font_ionicons_group']['icon'] == '' ) {
		$instance['font_ionicons_group']['icon'] = 'none';
	}
	if ( $instance['font_ionicons_group']['icon'] != 'none' ) {
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $complete_prefix . '<span class="inner-icon"><span class="icon">';
		$class     = $instance['font_ionicons_group']['icon'];
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_ionicons_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_ionicons_group']['icon_size'] . 'px; line-height:' . $instance['font_ionicons_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $complete_suffix . '</div>';
	}
} else if ( isset( $instance['icon_type'] ) && $instance['icon_type'] == 'text_number' ) {
	$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $complete_prefix . '<span class="inner-icon">';
	$style     = '';
	$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
	$style     .= ( $instance['text_number_font_size'] != '' ) ? ' font-size:' . $instance['text_number_font_size'] . 'px; line-height:' . $instance['height_icon_box'] . 'px; vertical-align: middle;' : '';
	$html_icon .= '<span class="text-number-icon" style="' . $style . '">' . $instance['text_number'] . '</span>';
	$html_icon .= '</span>' . $complete_suffix . '</div>';
} else {
	$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon icon-images">';
	$html_icon .= thim_get_feature_image( $instance['font_image_group']['icon_img'] );
	$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
}
/* end show icon or custom icon */

/* show CONTENT*/
$html_content = $color_desc = $line_height = '';

if ( isset( $instance['desc_group']['content'] ) && $instance['desc_group']['content'] != '' ) {
	if ( $instance['desc_group']['custom_font_size_des'] ) {
		$line_height = (int) $instance['desc_group']['custom_font_size_des'] + 7;
	}
	$color_desc .= ( $instance['desc_group']['color_description'] ) ? 'color: ' . $instance['desc_group']['color_description'] . ';' : '';
	$color_desc .= ( $instance['desc_group']['custom_font_size_des'] ) ? 'font-size: ' . $instance['desc_group']['custom_font_size_des'] . 'px;' : '';
	$color_desc .= ( $instance['desc_group']['custom_font_weight'] ) ? 'font-weight: ' . $instance['desc_group']['custom_font_weight'] . ';' : '';
	$color_desc .= ( $line_height ) ? 'line-height: ' . $line_height . 'px;' : '';
	if ( $color_desc <> '' ) {
		$color_desc = 'style="' . $color_desc . '"';
	}
	//
	$html_content .= '<div class="desc-icon-box">';
	$html_content .= ( $instance['desc_group']['content'] != '' ) ? '<div class="desc-content" ' . $color_desc . '>' . $complete_prefix . $instance['desc_group']['content'] . $complete_suffix . '</div>' : '';
	$html_content .= $more_link;
	$html_content .= "</div>";
}

if ( $html_content == '' && $more_link != '' ) {
	$html_content = $more_link;
}

// html
//start div wrapper-box-icon
$html = $prefix;
if ( isset( $instance['layout_group']['pos'] ) ) {
	$class_group_pos = $instance['layout_group']['pos'];
} else {
	$class_group_pos = 'top';
}
$html .= '<div class="smicon-box iconbox-' . $class_group_pos . '">';
// show icon
$html .= $html_icon;
// show title
$html .= '<div class="content-inner" ' . $content_style . '>';
$html .= $html_dot;
$html .= $html_title;

// show content
$html .= $html_content;
$html .= $icon_play;
$html .= '</div>';
$html .= '</div><!--end smicon-box-->';
$html .= $suffix;
$html .= $bg_video;
echo ent2ncr( $html );