<?php
// Copyright (c) 2011-2011 Ártica Soluciones Tecnológicas
// http://www.artica.es  <info@artica.es>

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Turn on output buffering.
// The entire buffer will be discarded later so that any accidental output
// does not corrupt images generated by fgraph.
ob_start();

global $config;

if (empty($config['homedir'])) {
	require_once ('../../include/config.php');
	global $config;
}

include_once($config['homedir'] . '/include/functions.php');

$ttl = get_parameter('ttl', 1);
$graph_type = get_parameter('graph_type', '');

if (!empty($graph_type)) {
	include_once($config['homedir'] . '/include/functions_html.php');
	include_once($config['homedir'] . '/include/graphs/functions_gd.php');
	include_once($config['homedir'] . '/include/graphs/functions_utils.php');
	include_once($config['homedir'] . '/include/graphs/functions_d3.php');
	include_once($config['homedir'] . '/include/graphs/functions_flot.php');
}

// Clean the output buffer and turn off output buffering
ob_end_clean ();

switch($graph_type) {
	case 'histogram':
		$width = get_parameter('width');
		$height = get_parameter('height');
		$data = json_decode(io_safe_output(get_parameter('data')), true);

		$max = get_parameter('max');
		$title = get_parameter('title');
		$mode = get_parameter ('mode', 1);
		gd_histogram ($width, $height, $mode, $data, $max, $config['fontpath'], $title);
		break;
	case 'progressbar':
		$width = get_parameter('width');
		$height = get_parameter('height');
		$progress = get_parameter('progress');

		$out_of_lim_str = io_safe_output(get_parameter('out_of_lim_str', false));
		$out_of_lim_image = get_parameter('out_of_lim_image', false);

		$title = get_parameter('title');

		$mode = get_parameter('mode', 1);

		$fontsize = get_parameter('fontsize', 10);

		$value_text = get_parameter('value_text', '');
		$colorRGB = get_parameter('colorRGB', '');

		gd_progress_bar ($width, $height, $progress, $title, $config['fontpath'],
			$out_of_lim_str, $out_of_lim_image, $mode, $fontsize,
			$value_text, $colorRGB);
		break;
	case 'progressbubble':
		$width = get_parameter('width');
		$height = get_parameter('height');
		$progress = get_parameter('progress');

		$out_of_lim_str = io_safe_output(get_parameter('out_of_lim_str', false));
		$out_of_lim_image = get_parameter('out_of_lim_image', false);

		$title = get_parameter('title');

		$mode = get_parameter('mode', 1);

		$fontsize = get_parameter('fontsize', 7);

		$value_text = get_parameter('value_text', '');
		$colorRGB = get_parameter('colorRGB', '');

		gd_progress_bubble ($width, $height, $progress, $title, $config['fontpath'],
			$out_of_lim_str, $out_of_lim_image, $mode, $fontsize,
			$value_text, $colorRGB);
		break;
}

function histogram($chart_data, $width, $height, $font, $max, $title,
	$mode, $ttl = 1) {

	$graph = array();
	$graph['data'] = $chart_data;
	$graph['width'] = $width;
	$graph['height'] = $height;
	$graph['font'] = $font;
	$graph['max'] = $max;
	$graph['title'] = $title;
	$graph['mode'] = $mode;

	$id_graph = serialize_in_temp($graph, null, $ttl);

	return "<img src='include/graphs/functions_gd.php?static_graph=1&graph_type=histogram&ttl=".$ttl."&id_graph=".$id_graph."'>";
}

function progressbar($progress, $width, $height, $title, $font,
	$mode = 1, $out_of_lim_str = false, $out_of_lim_image = false,
	$ttl = 1) {

	$graph = array();

	$graph['progress'] = $progress;
	$graph['width'] = $width;
	$graph['height'] = $height;
	$graph['out_of_lim_str'] = $out_of_lim_str;
	$graph['out_of_lim_image'] = $out_of_lim_image;
	$graph['title'] = $title;
	$graph['font'] = $font;
	$graph['mode'] = $mode;

	$id_graph = serialize_in_temp($graph, null, $ttl);
	if (is_metaconsole()) {
		return "<img src='../../include/graphs/functions_gd.php?static_graph=1&graph_type=progressbar&ttl=".$ttl."&id_graph=".$id_graph."'>";
	}
	else {
		return "<img src='include/graphs/functions_gd.php?static_graph=1&graph_type=progressbar&ttl=".$ttl."&id_graph=".$id_graph."'>";
	}
}


function slicesbar_graph($chart_data, $period, $width, $height, $colors,
	$font, $round_corner, $home_url = '', $ttl = 1) {

	$graph = array();
	$graph['data'] = $chart_data;
	$graph['period'] = $period;
	$graph['width'] = $width;
	$graph['height'] = $height;
	$graph['font'] = $font;
	$graph['round_corner'] = $round_corner;
	$graph['color'] = $colors;

	$id_graph = serialize_in_temp($graph, null, $ttl);

	return "<img src='".$home_url."include/graphs/functions_pchart.php?static_graph=1&graph_type=slicebar&ttl=".$ttl."&id_graph=".$id_graph."' style='width:100%;'>";
}

function vbar_graph(
	$flash_chart,
	$chart_data,
	$width,
	$height,
	$color,
	$legend,
	$long_index,
	$no_data_image,
	$xaxisname = "",
	$yaxisname = "",
	$water_mark = "",
	$font = '',
	$font_size = '',
	$unit = '',
	$ttl = 1,
	$homeurl = '',
	$backgroundColor = 'white',
	$from_ux = false,
	$from_wux = false,
	$tick_color = 'white'
) {

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	if (empty($chart_data)) {
		return '<img src="' . $no_data_image . '" />';
	}

	if ($flash_chart) {
		return flot_vcolumn_chart ($chart_data, $width, $height, $color,
			$legend, $long_index, $homeurl, $unit, $water_mark_url,
			$homedir,$font,$font_size, $from_ux, $from_wux, $backgroundColor,
			$tick_color);
	}
	else {
		$new_chart_data = array();
		foreach ($chart_data as $key => $value) {
			if(strlen($key) > 20 && strpos($key, ' - ') !== false){
				$key_temp = explode(" - ",$key);
				$key_temp[0] = $key_temp[0]."   \n";
				$key_temp[1]= '...'.substr($key_temp[1],-15);
				$key2 = $key_temp[0].$key_temp[1];
				io_safe_output($key2);
				$new_chart_data[$key2]['g'] = $chart_data[$key]['g'];
			} else {
				$new_chart_data[$key] = $value;
			}
		}

		$graph = array();
		$graph['data'] = $new_chart_data;
		$graph['width'] = $width;
		$graph['height'] = $height;
		$graph['color'] = $color;
		$graph['legend'] = $legend;
		$graph['xaxisname'] = $xaxisname;
		$graph['yaxisname'] = $yaxisname;
		$graph['water_mark'] = $water_mark_file;
		$graph['font'] = $font;
		$graph['font_size'] = $font_size;

		$id_graph = serialize_in_temp($graph, null, $ttl);

		return "<img src='" . $homeurl . "include/graphs/functions_pchart.php?static_graph=1&graph_type=vbar&ttl=".$ttl."&id_graph=".$id_graph."'>";
	}
}

function area_graph(
	$agent_module_id, $array_data, $color,
	$legend, $series_type, $date_array,
	$data_module_graph, $show_elements_graph,
	$format_graph, $water_mark, $series_suffix_str,
	$array_events_alerts
) {
	global $config;

	include_once('functions_flot.php');

	if ($config['flash_charts']) {
		return flot_area_graph(
			$agent_module_id,
			$array_data,
			$color,
			$legend,
			$series_type,
			$date_array,
			$data_module_graph,
			$show_elements_graph,
			$format_graph,
			$water_mark,
			$series_suffix_str,
			$array_events_alerts
		);
	}
	else {
		//XXXXX
		//Corregir este problema
		//tener en cuenta stacked, area, line

		$graph = array();
		$graph['data']            = $chart_data;
		$graph['width']           = $width;
		$graph['height']          = $height;
		$graph['color']           = $color;
		$graph['legend']          = $legend;
		$graph['xaxisname']       = $xaxisname;
		$graph['yaxisname']       = $yaxisname;
		$graph['water_mark']      = $water_mark_file;
		$graph['font']            = $font;
		$graph['font_size']       = $font_size;
		$graph['backgroundColor'] = $backgroundColor;
		$graph['unit']            = $unit;
		$graph['series_type']     = $series_type;
		$graph['percentil']       = $percentil_values;

		$id_graph = serialize_in_temp($graph, null, $ttl);
		// Warning: This string is used in the function "api_get_module_graph" from 'functions_api.php' with the regec patern "/<img src='(.+)'>/"
		return "<img src='" .
			ui_get_full_url (false, false, false, false) .
			"include/graphs/functions_pchart.php?" .
				"static_graph=1&" .
				"graph_type=area&" .
				"ttl=" . $ttl . "&" .
				"id_graph=" . $id_graph . "'>";
	}
}

function stacked_bullet_chart($flash_chart, $chart_data, $width, $height,
	$color, $legend, $long_index, $no_data_image, $xaxisname = "",
	$yaxisname = "", $water_mark = "", $font = '', $font_size = '',
	$unit = '', $ttl = 1, $homeurl = '', $backgroundColor = 'white') {

	include_once('functions_d3.php');

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	if (empty($chart_data)) {
		return '<img src="' . $no_data_image . '" />';
	}
	if ($flash_chart) {
		return d3_bullet_chart(
				$chart_data,
				$width,
				$height,
				$color,
				$legend,
				$homeurl,
				$unit,
				$font,
				$font_size
				);
	}
	else {
		$legend = array();
		$new_data = array();
		foreach($chart_data as $key => $data) {
			$temp[] = ($data['min'] != false) ? $data['min'] : 0;
			$temp[] = ($data['value'] != false) ? $data['value'] : 0;
			$temp[] = ($data['max'] != false) ? $data['max'] : 0;

			$legend[] = $data['label'];
			array_push($new_data, $temp);
			$temp = array();
		}
		$graph = array();
		$graph['data'] = $new_data;
		$graph['width'] = $width;
		$graph['height'] = $height;
		$graph['color'] = $color;
		$graph['legend'] = $legend;
		$graph['xaxisname'] = $xaxisname;
		$graph['yaxisname'] = $yaxisname;
		$graph['water_mark'] = $water_mark_file;
		$graph['font'] = $font;
		$graph['font_size'] = $font_size;
		$graph['backgroundColor'] = $backgroundColor;

		$id_graph = serialize_in_temp($graph, null, $ttl);

		return "<img src='" . $homeurl . "include/graphs/functions_pchart.php?static_graph=1&graph_type=bullet_chart&ttl=".$ttl."&id_graph=" . $id_graph . "' />";
	}
}

function stacked_gauge($flash_chart, $chart_data, $width, $height,
	$color, $legend, $long_index, $no_data_image, $xaxisname = "",
	$yaxisname = "", $water_mark = "", $font = '', $font_size = '',
	$unit = '', $ttl = 1, $homeurl = '', $backgroundColor = 'white') {

	include_once('functions_d3.php');

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	if (empty($chart_data)) {
		return '<img src="' . $no_data_image . '" />';
	}

	return d3_gauges(
			$chart_data,
			$width,
			$height,
			$color,
			$legend,
			$homeurl,
			$unit,
			$font,
			$font_size + 2,
			$no_data_image
			);
}

function kiviat_graph($graph_type, $flash_chart, $chart_data, $width,
	$height, $no_data_image, $ttl = 1, $homedir="") {

	if (empty($chart_data)) {
		return '<img src="' . $no_data_image . '" />';
	}

	$graph = array();
	$graph['data'] = $chart_data;
	$graph['width'] = $width;
	$graph['height'] = $height;

	$id_graph = serialize_in_temp($graph, null, $ttl);

	return "<img src='".$homedir."include/graphs/functions_pchart.php?static_graph=1&graph_type=".$graph_type."&ttl=".$ttl."&id_graph=" . $id_graph . "' />";
}

function hbar_graph($flash_chart, $chart_data, $width, $height,
	$color, $legend, $long_index, $no_data_image, $xaxisname = "",
	$yaxisname = "", $water_mark = "", $font = '', $font_size = '',
	$unit = '', $ttl = 1, $homeurl = '', $backgroundColor = 'white',
	$tick_color = "white", $val_min=null, $val_max=null) {

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	if (empty($chart_data)) {
		return '<img src="' . $no_data_image . '" />';
	}

	if ($flash_chart) {
		return flot_hcolumn_chart(
			$chart_data, $width, $height, $water_mark_url, $font, $font_size, $backgroundColor, $tick_color, $val_min, $val_max);
	}
	else {
		foreach ($chart_data as $key => $value) {
			$str_key = io_safe_output($key);
			if(strlen($str_key) > 40){
					if(strpos($str_key, ' - ') != -1){
						$key_temp = explode(" - ",$str_key);
						$key_temp[0] = $key_temp[0]."   <br>";
						$key_temp[1]= '...'.substr($key_temp[1],-20);
						$key2 = $key_temp[0].$key_temp[1];
					}
				$chart_data[$key2]['g'] = $chart_data[$key]['g'];
				unset($chart_data[$key]);
			}
		}

		$graph = array();
		$graph['data'] = $chart_data;
		$graph['width'] = $width;
		$graph['height'] = $height;
		$graph['color'] = $color;
		$graph['legend'] = $legend;
		$graph['xaxisname'] = $xaxisname;
		$graph['yaxisname'] = $yaxisname;
		$graph['force_height'] = $force_height;
		$graph['water_mark'] = $water_mark_file;
		$graph['font'] = $font;
		$graph['font_size'] = $font_size;
		$graph['force_steps'] = $force_steps;

		$id_graph = serialize_in_temp($graph, null, $ttl);

		return "<img src='" . $homeurl . "include/graphs/functions_pchart.php?static_graph=1&graph_type=hbar&ttl=".$ttl."&id_graph=".$id_graph."'>";
	}
}

function pie3d_graph($flash_chart, $chart_data, $width, $height,
	$others_str = "other", $homedir = "", $water_mark = "", $font = '',
	$font_size = '', $ttl = 1, $legend_position = false, $colors = '',
	$hide_labels = false) {

	return pie_graph('3d', $flash_chart, $chart_data, $width, $height,
		$others_str, $homedir, $water_mark, $font, $font_size, $ttl,
		$legend_position, $colors, $hide_labels);
}

function pie2d_graph($flash_chart, $chart_data, $width, $height,
	$others_str = "other", $homedir="", $water_mark = "", $font = '',
	$font_size = '', $ttl = 1, $legend_position = false, $colors = '',
	$hide_labels = false) {

	return pie_graph('2d', $flash_chart, $chart_data, $width, $height,
		$others_str, $homedir, $water_mark, $font, $font_size, $ttl,
		$legend_position, $colors, $hide_labels);
}

function pie_graph($graph_type, $flash_chart, $chart_data, $width,
	$height, $others_str = "other", $homedir="", $water_mark = "",
	$font = '', $font_size = '', $ttl = 1, $legend_position = false,
	$colors = '', $hide_labels = false) {

	if (empty($chart_data)) {
		return graph_nodata_image($width, $height, 'pie');
	}

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	// This library allows only 8 colors
	$max_values = 9;

	//Remove the html_entities
	$temp = array();
	foreach ($chart_data as $key => $value) {
		$temp[io_safe_output($key)] = $value;
	}
	$chart_data = $temp;

	if (count($chart_data) > $max_values) {
		$chart_data_trunc = array();
		$n = 1;
		foreach ($chart_data as $key => $value) {
			if ($n < $max_values) {
				$chart_data_trunc[$key] = $value;
			}
			else {
				if (!isset($chart_data_trunc[$others_str])) {
					$chart_data_trunc[$others_str] = 0;
				}
				$chart_data_trunc[$others_str] += $value;
			}
			$n++;
		}
		$chart_data = $chart_data_trunc;
	}

	if ($flash_chart) {
		return flot_pie_chart(array_values($chart_data),
			array_keys($chart_data), $width, $height, $water_mark_url,
			$font, $font_size, $legend_position, $colors, $hide_labels);
	}
	else {
		//TODO SET THE LEGEND POSITION
		$graph = array();
		$graph['data'] = $chart_data;
		$graph['width'] = $width;
		$graph['height'] = $height;
		$graph['water_mark'] = $water_mark_file;
		$graph['font'] = $font;
		$graph['font_size'] = $font_size;
		$graph['legend_position'] = $legend_position;
		$graph['color'] = $colors;

		$id_graph = serialize_in_temp($graph, null, $ttl);

		switch ($graph_type) {
			case "2d":
				return "<img src='" . $homedir . "include/graphs/functions_pchart.php?static_graph=1&graph_type=pie2d&ttl=".$ttl."&id_graph=".$id_graph."'>";
				break;
			case "3d":
				return "<img src='" . $homedir . "include/graphs/functions_pchart.php?static_graph=1&graph_type=pie3d&ttl=".$ttl."&id_graph=".$id_graph."'>";
				break;
		}
	}
}

function ring_graph($flash_chart, $chart_data, $width,
	$height, $others_str = "other", $homedir="", $water_mark = "",
	$font = '', $font_size = '', $ttl = 1, $legend_position = false,
	$colors = '', $hide_labels = false,$background_color = 'white') {

	if (empty($chart_data)) {
		return graph_nodata_image($width, $height, 'pie');
	}

	setup_watermark($water_mark, $water_mark_file, $water_mark_url);

	// This library allows only 8 colors
	$max_values = 18;

	if ($flash_chart) {
		return flot_custom_pie_chart ($flash_chart, $chart_data,
		$width, $height, $colors, $module_name_list, $long_index,
		$no_data, false, '', $water_mark, $font, $font_size,
		$unit, $ttl, $homeurl, $background_color, $legend_position,$background_color);
	}
	else {
		$total_modules = $chart_data['total_modules'];
		unset($chart_data['total_modules']);

		$max_values = 9;
		//Remove the html_entities
		$n = 0;
		$temp = array();
		$coloretes = array();
		foreach ($chart_data as $key => $value) {
			if ($n < $max_values) {
				$temp[io_safe_output($key)] = $value['value'];
				$legend[] = io_safe_output($key) .": " . $value['value'] . " " .$value['unit'];
			}
			$n++;
		}
		$chart_data = $temp;

		$chart_data_trunc = array();
		$coloretes = array();
		$n = 1;
		//~ foreach ($chart_data as $key => $value) {
			//~ if ($n < $max_values) {

				//~ $chart_data_trunc[$key] = $value;
			//~ }
			//~ else {
				//~ if (!isset($chart_data_trunc[$others_str])) {
					//~ $chart_data_trunc[$others_str] = 0;
				//~ }
				//~ $chart_data_trunc[$others_str] += $value;
			//~ }
			//~ $n++;
		//~ }
		//~ $chart_data = $chart_data_trunc;

		//TODO SET THE LEGEND POSITION
		$graph = array();
		$graph['data'] = $chart_data;
		$graph['width'] = $width;
		$graph['height'] = $height;
		$graph['water_mark'] = $water_mark_file;
		$graph['font'] = $font;
		$graph['font_size'] = $font_size;
		$graph['legend_position'] = $legend_position;
		$graph['legend'] = $legend;

		$id_graph = serialize_in_temp($graph, null, $ttl);

		return "<img src='" . $homedir . "include/graphs/functions_pchart.php?static_graph=1&graph_type=ring3d&ttl=".$ttl."&id_graph=".$id_graph."'>";

	}
}

?>
