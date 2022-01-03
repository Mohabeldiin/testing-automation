<?php

if ( ! isset( $color_scheme[2] ) ) {

	$custom_css = '';

} else {

	$custom_css = "

		/* Proggress bar */
		
		.bt_bb_progress_bar.bt_bb_style_outline.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_bg,
		.bt_bb_progress_bar.bt_bb_style_line.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_bg	{
			background: {$color_scheme[2]};
		}
		
		.bt_bb_progress_bar.bt_bb_style_outline.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_inner,
		.bt_bb_progress_bar.bt_bb_style_line.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_inner	{
			border-color: {$color_scheme[1]};
			color: {$color_scheme[1]};
		}
		
		.bt_bb_progress_bar.bt_bb_style_filled.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_bg {
			background: {$color_scheme[1]};
		}
		
		.bt_bb_progress_bar.bt_bb_style_filled.bt_bb_color_scheme_{$scheme_id} .bt_bb_progress_bar_inner {
			background: {$color_scheme[2]};
			color: {$color_scheme[1]};
		}

		/* Icons */
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon .bt_bb_icon_holder { color: {$color_scheme[1]}; }
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon:hover a.bt_bb_icon_holder { color: {$color_scheme[2]}; }
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline .bt_bb_icon_holder:before {
			background-color: transparent;
			box-shadow: 0 0 0 1px {$color_scheme[1]} inset;
			color: {$color_scheme[1]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline:hover a.bt_bb_icon_holder:before {
			background-color: {$color_scheme[1]};
			box-shadow: 0 0 0 1em {$color_scheme[1]} inset;
			color: {$color_scheme[2]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled .bt_bb_icon_holder:before {
			box-shadow: 0 0 0 1em {$color_scheme[2]} inset;
			color: {$color_scheme[1]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled:hover a.bt_bb_icon_holder:before {
			box-shadow: 0 0 0 1px {$color_scheme[2]} inset;
			background-color: {$color_scheme[1]};
			color: {$color_scheme[2]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless .bt_bb_icon_holder:before {
			color: {$color_scheme[1]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless:hover a.bt_bb_icon_holder:before {
			color: {$color_scheme[2]};
		}
		

		/* Buttons */
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a {
			box-shadow: 0 0 0 1px {$color_scheme[1]} inset;
			color: {$color_scheme[1]};
			background-color: transparent;
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a:hover {
			box-shadow: 0 0 0 2em {$color_scheme[1]} inset;
			color: {$color_scheme[2]};		
		}

		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a {
			box-shadow: 0 0 0 2em {$color_scheme[2]} inset;
			color: {$color_scheme[1]};		
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a:hover {
			box-shadow: 0 0 0 0px {$color_scheme[2]} inset;
			background-color: {$color_scheme[1]};
			color: {$color_scheme[2]};		
		}

		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_clean a,
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless a {
			color: {$color_scheme[1]};
		}
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_clean a:hover,
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless:hover a {
			color: {$color_scheme[2]};
		}

		/* Services */
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service .bt_bb_icon_holder	{
			box-shadow: 0 0 0 1px {$color_scheme[1]} inset;
			color: {$color_scheme[1]};
			background-color: transparent;
		}

		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service:hover .bt_bb_icon_holder {
			box-shadow: 0 0 0 1em {$color_scheme[1]} inset;
			background-color: {$color_scheme[1]};
			color: {$color_scheme[2]};
		}	
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service .bt_bb_icon_holder {
			box-shadow: 0 0 0 1em {$color_scheme[2]} inset;
			color: {$color_scheme[1]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service:hover .bt_bb_icon_holder	{
			box-shadow: 0 0 0 1px {$color_scheme[2]} inset;
			background-color: {$color_scheme[1]};
			color: {$color_scheme[2]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_borderless.bt_bb_service .bt_bb_icon_holder {
			color: {$color_scheme[1]};
		}
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_borderless.bt_bb_service:hover .bt_bb_icon_holder {
			color: {$color_scheme[2]};
		}
		
		/* Headline */
		
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline
		{
			color: {$color_scheme[1]};
		}
		.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline .bt_bb_headline_superheadline
		{
			color: {$color_scheme[2]};
		}

		/* Tabs */
		
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_tabs_header,
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_tabs_header {
			border-color: {$color_scheme[1]};
		}
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_tabs_header li,
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_tabs_header li:hover,
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_tabs_header li.on {
			border-color: {$color_scheme[1]};
			color: {$color_scheme[1]};
			background-color: transparent;
		}

		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_tabs_header li:hover,
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_tabs_header li.on,
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_tabs_header li {
			background-color: {$color_scheme[1]};
			color: {$color_scheme[2]};
			border-color: {$color_scheme[1]};		
		}

		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_tabs_header li {
			color: {$color_scheme[2]};
		}
		
		.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_tabs_header li.on {
			color: {$color_scheme[1]};
			border-color: {$color_scheme[1]};
		}

		/* Accordion */
		
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id} .bt_bb_accordion_item {
			border-color: {$color_scheme[1]};
		}
		
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item_title {
			border-color: {$color_scheme[1]};
			color: {$color_scheme[1]};
			background-color: transparent;
		}
		
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item.on .bt_bb_accordion_item_title,
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item .bt_bb_accordion_item_title:hover {
			color: {$color_scheme[2]};
			background-color: {$color_scheme[1]};
		}	
		
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item .bt_bb_accordion_item_title {
			color: {$color_scheme[2]};
			background-color: {$color_scheme[1]};
		}
		
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item.on .bt_bb_accordion_item_title,
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item .bt_bb_accordion_item_title:hover {
			color: {$color_scheme[1]};
			background-color: transparent;
		}

		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item .bt_bb_accordion_item_title {
			color: {$color_scheme[1]};
			border-color: {$color_scheme[1]};
		}

		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item .bt_bb_accordion_item_title:hover,
		.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item.on .bt_bb_accordion_item_title {
			color: {$color_scheme[2]};
			border-color: {$color_scheme[2]};
		}


		/* Price List */
		
		.bt_bb_price_list.bt_bb_color_scheme_{$scheme_id} {
			border-color: {$color_scheme[2]};
		}
		.bt_bb_price_list.bt_bb_color_scheme_{$scheme_id} .bt_bb_price_list_title {
			color: {$color_scheme[1]};
			background-color: {$color_scheme[2]};
		}

		.bt_bb_price_list.bt_bb_color_scheme_{$scheme_id} ul li {
			border-color: {$color_scheme[2]};	
		}

		/* Section */
		
		.bt_bb_section.bt_bb_color_scheme_{$scheme_id} {
			color: {$color_scheme[1]};
			background-color: {$color_scheme[2]};
		}

		/* Row */
		
		.bt_bb_row.bt_bb_color_scheme_{$scheme_id} {
			color: {$color_scheme[1]};
			background-color: {$color_scheme[2]};
		}

		/* Column */
		
		.bt_bb_column.bt_bb_color_scheme_{$scheme_id} {
			color: {$color_scheme[1]};
			background-color: {$color_scheme[2]};
		}

	";

}