<?php

/**
* @wordpress-plugin
* Plugin Name:       Kntnt Disable Avatars
* Plugin URI:        https://github.com/Kntnt/kntnt-disable-avatars
* Description:       Disables avatars and prevents users' email address being sent to Gravatar.
* Version:           1.0.0
* Author:            Thomas Barregren
* Author URI:        https://www.kntnt.com/
* License:           GPL-3.0+
* License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
*/


// Prevents users' email address being sent to Gravatar. Defautl avatar is
// used instead.
add_filter( 'pre_get_avatar', '__return_false' );

// Disables avatars except in the adminbar (and possible some other places).
add_action( 'admin_init', function() {
	if ( get_option( 'show_avatars', true ) ) {
		update_option( 'show_avatars', false );
	}
} );
