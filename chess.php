<?php
/*
Plugin Name: chess
Description: Display chess boards in posts and pages with a simple short cut.
Version: 0.0.1
Author: Nico Hoffmann
Author URI: http://maxdoom.com/
*/
/*  Copyright 2016 Nico Hoffmann (wp@maxdoom.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

function _chess_chess_shortcode_td_classes($eo, $char){
	$result	 = 'c'.$eo.' ';
	$result .= (ctype_lower($char)?'black':($char==' '?'blank':'white'));
	$result .= ' t'.$char;
	return $result;
}

function chess_chess_shortcode($atts, $content=null){
	$a = shortcode_atts( array(
		'float' => 'none',
		'headline' => '',
		'comment' => '',
	), $atts );
	
	
	
	$content = strip_tags($content);
	$content = preg_replace('/[^\.,\-#+rnbqkbnrpPRNBQKBNR]/','',$content);
	$content = preg_replace('/[\.,\-#+]/',' ',$content);
	
	if( strlen($content) != 64 ) return 'Error in shortcode "chess".';
	
	$lines = array();
	$lines_in = str_split($content, 8);
	$eo = 1;
	foreach($lines_in as $k => $v){
		$this_line = '<tr><th class="br">'.(8-$k).'</th>';
		foreach(str_split($v) as $char){
			$this_line .=
				'<td class="'._chess_chess_shortcode_td_classes($eo, $char).'">'.$char.'</td>';
			$eo = 1-$eo;
		}
		$this_line .= '<th class="bl">'.(8-$k).'</th></tr>';
		$lines[] = $this_line;
		$eo = 1-$eo;
	}
	
	wp_enqueue_style('chess', plugins_url('css/stylesheet.css', __FILE__));
	
	$line0 = '<tr class="bb"><th class="nb"></th><th>a</th><th>b</th><th>c</th><th>d</th><th>e</th><th>f</th><th>g</th><th>h</th><th class="nb"></th>';
	$line9 = '<tr class="ba"><th class="nb"></th><th>a</th><th>b</th><th>c</th><th>d</th><th>e</th><th>f</th><th>g</th><th>h</th><th class="nb"></th>';
	$thead = $a['headline'] ? "<thead><tr><th colspan=\"10\">$a[headline]</th></tr></thead>" : '';
	$tfoot = $a['comment']  ? "<tfoot><tr><th colspan=\"10\">$a[comment]</th></tr></tfoot>" : '';
	$content = implode($lines);
	return "<table class=\"chess $a[float]\">${thead}<tbody>${line0}${content}${line9}</tbody>${tfoot}</table>";
}

add_shortcode('chess', 'chess_chess_shortcode');