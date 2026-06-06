<?php
/**
 * Plugin Name:       - Random Page Redirect for WordPress by Christopher Ross
 * Plugin URI:        https://thisismyurl.com/plugins/random-page-redirect-for-wordpress/
 * Description:       Adds a /random URL that 302s to a random published post. Filterable post types, no-store cache headers, no settings screen.
 * Version:           1.6148.2110
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

require_once __DIR__ . '/abilities.php';

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
 * Resolve the post types eligible for the random pick.
 *
 * Applies the `thisismyurl_random_redirect_post_types` filter, sanitizes the
 * result, and falls back to `[ 'post' ]` when the filter yields nothing usable.
 *
 * A public-visibility floor is enforced *after* the filter so a third-party
 * filter callback can never opt a private or non-publicly-queryable type into
 * the random pool — the /random redirect is an anonymous, publicly reachable
 * URL. This mirrors the Abilities API execute_callback, which validates the
 * requested type against `get_post_types( [ 'public' => true ] )`; both paths
 * now share one public-type guard.
 *
 * @return string[] List of eligible public post-type slugs (never empty).
 */
function thisismyurl_random_redirect_post_types() {
	/**
	 * Filters the post types eligible for the /random redirect.
	 *
	 * @param string[] $post_types Defaults to [ 'post' ].
	 */
	$post_types = apply_filters( 'thisismyurl_random_redirect_post_types', array( 'post' ) );
	$post_types = array_values( array_filter( array_map( 'sanitize_key', (array) $post_types ) ) );

	$public     = get_post_types( array( 'public' => true ), 'names' );
	$post_types = array_values( array_intersect( $post_types, $public ) );

	if ( empty( $post_types ) ) {
		$post_types = array( 'post' );
	}

	return $post_types;
}

/**
 * Pick one or more random published posts and return their permalinks.
 *
 * The shared selection path for both the /random redirect handler and the
 * Abilities API endpoint. Queries only published, non-password-protected posts
 * of the eligible types, ordered randomly, and resolves each to a permalink.
 *
 * @param int           $count      How many random URLs to return. Clamped to 1+. Default 1.
 * @param string[]|null $post_types Eligible post-type slugs. Null resolves via the filter.
 * @return array<int, array{id:int, url:string}> Random {id, url} pairs; empty when nothing matches.
 */
function thisismyurl_random_redirect_get_random_urls( $count = 1, $post_types = null ) {
	$count      = max( 1, (int) $count );
	$post_types = null === $post_types ? thisismyurl_random_redirect_post_types() : $post_types;

	$ids = get_posts(
		array(
			'post_type'              => $post_types,
			'post_status'            => 'publish',
			'has_password'           => false,
			'ignore_sticky_posts'    => true,
			'posts_per_page'         => $count,
			'orderby'                => 'rand',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		)
	);

	if ( empty( $ids ) ) {
		return array();
	}

	$items = array();
	foreach ( $ids as $id ) {
		$permalink = get_permalink( (int) $id );

		if ( false === $permalink ) {
			continue;
		}

		$items[] = array(
			'id'  => (int) $id,
			'url' => $permalink,
		);
	}

	return $items;
}

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

	$items = thisismyurl_random_redirect_get_random_urls( 1 );

	if ( empty( $items ) ) {
		return;
	}

	$permalink = $items[0]['url'];

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

/**
 * Append a Sponsor link to the plugin row actions.
 *
 * @param string[] $links Existing action links.
 * @return string[]
 */
function thisismyurl_random_redirect_action_links( $links ) {
	$links[] = '<a href="' . esc_url( 'https://github.com/sponsors/thisismyurl' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Sponsor', 'random-page-redirect-for-wordpress' ) . '</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'thisismyurl_random_redirect_action_links' );
