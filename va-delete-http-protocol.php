<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: VA Delete HTTP Protocol
Description: When SSL is effective, the HTTP protocol of the whole website is deleted.
Version: 0.0.1
Plugin URI: http://visualive.jp/download/wordpress/plugins/
Author: VisuAlive
Author URI: http://visualive.jp/
Text Domain: va_dhp
Domain Path: /languages
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

VisuAlive WordPress Plugin, Copyright (C) 2013 VisuAlive Inc

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! class_exists( 'VA_Delete_Http_Protocol' ) ) :
define( 'VA_DHP_VERSION', '0.0.1' );
define( 'VA_DHP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VA_DHP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
load_plugin_textdomain( 'va_dcc', false, VA_DHP_PLUGIN_PATH . '/languages' );

class VA_Delete_Http_Protocol {
	function __construct() {
		add_action( 'get_header', array( $this, '_va_dhp_start_replace_protocol' ), 1 );
		add_action( 'wp_footer', array( $this, '_va_dhp_end_replace_protocol' ), 9999 );
	}
	function va_dhp_replace_protocol( $buffer ) {
		$patterns = array(
			'(http?://)',
			'(<a(.*)href=\"(\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)\")',
			'(ecx\.images\-amazon\.com)',
			'(//www[0-9]+.a8.net/0.gif)'
			);
		$replacements = array(
			'//',
			'<a$1href="http:$2"',
			'images-na.ssl-images-amazon.com',
			'http:$0'
			);
		return preg_replace( $patterns, $replacements, $buffer );
	}
	function _va_dhp_start_replace_protocol() {
		ob_start( array( $this, 'va_dhp_replace_protocol' ) );
	}
	function _va_dhp_end_replace_protocol() {
		ob_end_flush();
	}
}
if ( is_ssl() ) {
	new VA_Delete_Http_Protocol;
}
endif; // VA_Delete_Http_Protocol
