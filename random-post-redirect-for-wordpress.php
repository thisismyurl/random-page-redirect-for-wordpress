<?php
/*
Plugin Name: Random Post Redirect for WordPress
Plugin URI: http://thisismyurl.com/plugins/random-post-redirect-for-wordpress/
Description: Redirects users to a random post on your website
Author: Christopher Ross
Tags: wordpress, random, redirect
Author URI: http://thisismyurl.com/
Version: 1.0.1
*/

/**
 * Random Post Redirect for WordPress core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/random-post-redirect-for-wordpress/
 *
 * @package 	Random Post Redirect for WordPress
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Random Post Redirect for WordPress 1.0
 */

function thisismyurl_random_post_redirect() {

    if (  '/random-post' == $_SERVER['REQUEST_URI'] || '/random-post/' == $_SERVER['REQUEST_URI'] ) {

        foreach ( get_posts ( array( 'numberposts' => 1, 'orderby' => 'rand' ) ) as $post ) {
            wp_redirect ( get_permalink ( $post->ID ) , 302 );
            exit;
        }

    }

}
add_action( 'template_redirect', 'thisismyurl_random_post_redirect' );
