<?php
/**
 * Plugin Name:       Random Page Redirect for WordPress
 * Plugin URI:        https://thisismyurl.com/plugins/random-page-redirect-for-wordpress/
 * Description:       Adds a /random URL that 302s to a random published post. Filterable post types, no-store cache headers, no settings screen.
 * Version:           0.6123
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       random-page-redirect-for-wordpress
 * Domain Path:       /languages
 * Update URI:        https://wordpress.org/plugins/random-page-redirect-for-wordpress/
 *
 * @package Random_Page_Redirect_For_WordPress
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the /random rewrite rule and its query var.
 *
 * Hook: init.
 *
 * @return void
 */
function thisismyurl_random_redirect_register_rewrite() {
	add_rewrite_rule( '^random/?$', 'index.php?thisismyurl_random=1', 'top' );
}
add_action( 'init', 'thisismyurl_random_redirect_register_rewrite' );

/**
 * Whitelist the custom query var so WordPress will surface it on WP::parse_request.
 *
 * @param string[] $vars Existing public query vars.
 * @return string[]
 */
function thisismyurl_random_redirect_query_vars( $vars ) {
	$vars[] = 'thisismyurl_random';
	return $vars;
}
add_filter( 'query_vars', 'thisismyurl_random_redirect_query_vars' );

/**
 * On a /random hit, look up a random published post and 302 to it.
 *
 * Sends Cache-Control: no-store so edges/CDNs don't pin the redirect to a single
 * destination. Uses wp_safe_redirect() to keep the destination on-host. Post
 * types are filterable via `thisismyurl_random_redirect_post_types`.
 *
 * Hook: template_redirect.
 *
 * @return void
 */
function thisismyurl_random_redirect_handle() {
	if ( '1' !== (string) get_query_var( 'thisismyurl_random' ) ) {
		return;
	}

	/**
	 * Filters the post types eligible for the /random redirect.
	 *
	 * @param string[] $post_types Defaults to [ 'post' ].
	 */
	$post_types = apply_filters( 'thisismyurl_random_redirect_post_types', array( 'post' ) );
	$post_types = array_values( array_filter( array_map( 'sanitize_key', (array) $post_types ) ) );

	if ( empty( $post_types ) ) {
		$post_types = array( 'post' );
	}

	$ids = get_posts(
		array(
			'post_type'              => $post_types,
			'post_status'            => 'publish',
			'has_password'           => false,
			'ignore_sticky_posts'    => true,
			'posts_per_page'         => 1,
			'orderby'                => 'rand',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		)
	);

	if ( empty( $ids ) ) {
		return;
	}

	$permalink = get_permalink( (int) $ids[0] );

	if ( false === $permalink ) {
		return;
	}

	nocache_headers();
	// nocache_headers() does not emit `no-store`; required to keep CDNs from pinning the redirect.
	header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );

	wp_safe_redirect( $permalink, 302 );
	exit;
}
add_action( 'template_redirect', 'thisismyurl_random_redirect_handle' );

/**
 * Flush rewrite rules on activation so /random resolves immediately.
 *
 * @return void
 */
function thisismyurl_random_redirect_activate() {
	thisismyurl_random_redirect_register_rewrite();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'thisismyurl_random_redirect_activate' );

/**
 * Drop the registered rewrite rule on deactivation.
 *
 * @return void
 */
function thisismyurl_random_redirect_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'thisismyurl_random_redirect_deactivate' );
