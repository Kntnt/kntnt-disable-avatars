<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Disable Avatars
 * Plugin URI:        https://github.com/Kntnt/kntnt-disable-avatars
 * Description:       Disables avatars and keeps every request, and every user's email hash, from reaching Gravatar.
 * Version:           2.0.0
 * Requires PHP:      8.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) || exit;

// A transparent 1×1 GIF, given wherever WordPress asks for an avatar's URL.
const KNTNT_DISABLE_AVATARS_PIXEL = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

// Whether a URL's host is Gravatar's.
function kntnt_disable_avatars_is_gravatar( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! is_string( $host ) ) {
		return false;
	}
	$host = strtolower( rtrim( $host, '.' ) );
	foreach ( [ 'gravatar.com', 'gravatar.net' ] as $domain ) {
		if ( $host === $domain || str_ends_with( $host, '.' . $domain ) ) {
			return true;
		}
	}
	return false;
}

// Avatars are off, whatever the Discussion settings say.
add_filter( 'option_show_avatars', '__return_false' );

// No avatar markup anywhere.
add_filter( 'pre_get_avatar', fn() => '' );

// Every avatar URL, including the REST API's and the block editor's, is the
// pixel, so no URL carrying an email hash is ever built.
add_filter( 'get_avatar_url', fn() => KNTNT_DISABLE_AVATARS_PIXEL );
add_filter( 'get_avatar_data', function ( $args ) {
	$args['url'] = KNTNT_DISABLE_AVATARS_PIXEL;
	$args['found'] = true;
	return $args;
} );

// The server makes no request to Gravatar.
add_filter( 'pre_http_request', function ( $preempt, $parsed_args, $url ) {
	return kntnt_disable_avatars_is_gravatar( $url ) ? new WP_Error( 'kntnt_gravatar_blocked', 'Gravatar requests are disabled.' ) : $preempt;
}, 10, 3 );

// Nor does the browser, through a resource hint.
add_filter( 'wp_resource_hints', function ( $urls ) {
	if ( ! is_array( $urls ) ) {
		return $urls;
	}
	return array_values( array_filter( $urls, fn( $url ) => ! kntnt_disable_avatars_is_gravatar( is_array( $url ) ? ( $url['href'] ?? '' ) : $url ) ) );
} );
