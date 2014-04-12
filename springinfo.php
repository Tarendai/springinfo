<?php
/*
Plugin Name: SpringInfo
Plugin URI: http://tomjn.com
Description: Redirects and fixes needed for springinfo
Author: Tom J Nowell
Version: 1.0
Author URI: http://www.tomjn.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function post_link_redirect ( $permalink, $post = null, $leavename = false ) {

	if ( ! is_object( $post ) && ! is_numeric( $post ) )
		return $permalink;

	$id = is_object( $post ) ? $post->ID : $post;

	$misc = get_post_meta( $id, 'source-guid', true );
	if ( ! empty( $misc ) )
		$permalink = esc_url( $misc );

	return $permalink;
}

add_filter( 'post_link', 'post_link_redirect', 100, 3 );
add_filter( 'page_link', 'post_link_redirect', 100, 3 );
add_filter( 'post_type_link', 'post_link_redirect', 100, 3 );

function mp_permalink($permalink) {
	global $wp_query;
	if($url = get_post_meta($wp_query->post->ID, 'source-guid', true)) {
		return $url;
	}
	return $permalink;
}
add_filter('the_permalink_rss', 'mp_permalink');


function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');
