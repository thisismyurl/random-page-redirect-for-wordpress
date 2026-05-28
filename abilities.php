<?php
/**
 * WP 7 Abilities API registration for Random Page Redirect for WordPress.
 *
 * Exposes the plugin's random-published-post selection as a discoverable,
 * REST/AI-invokable ability. The ability returns URL data only — it never
 * performs the HTTP redirect that the /random endpoint does.
 *
 * @package Random_Page_Redirect_For_WordPress
 * @since   1.6148
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_abilities_api_init',
	static function (): void {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return; // Abilities API unavailable (WordPress < 6.9).
		}

		wp_register_ability(
			'random-page-redirect/get-random-url',
			array(
				'label'               => __( 'Get Random Page URL', 'random-page-redirect-for-wordpress' ),
				'description'         => __( 'Returns one or more random published posts as {id, url} pairs. Use this to surface a "surprise me" link or seed discovery without performing a redirect. Each call may return a different selection.', 'random-page-redirect-for-wordpress' ),
				'category'            => 'site',
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'post_type' => array(
							'type'        => 'string',
							'description' => __( 'Optional: a single public post type to draw from. Defaults to "post". Ignored if not a registered, publicly-queryable post type.', 'random-page-redirect-for-wordpress' ),
						),
						'count'     => array(
							'type'        => 'integer',
							'minimum'     => 1,
							'maximum'     => 20,
							'description' => __( 'Optional: how many random URLs to return (1-20). Defaults to 1.', 'random-page-redirect-for-wordpress' ),
						),
					),
					'additionalProperties' => false,
					'default'              => array(),
				),
				'output_schema'       => array(
					'type'                 => 'object',
					'required'             => array( 'items' ),
					'properties'           => array(
						'items' => array(
							'type'        => 'array',
							'description' => __( 'The selected random posts. Empty when no eligible published post exists.', 'random-page-redirect-for-wordpress' ),
							'items'       => array(
								'type'                 => 'object',
								'required'             => array( 'id', 'url' ),
								'properties'           => array(
									'id'  => array(
										'type'        => 'integer',
										'description' => __( 'The post ID.', 'random-page-redirect-for-wordpress' ),
									),
									'url' => array(
										'type'        => 'string',
										'description' => __( 'The permalink of the selected post.', 'random-page-redirect-for-wordpress' ),
									),
								),
								'additionalProperties' => false,
							),
						),
					),
					'additionalProperties' => false,
				),
				'execute_callback'    => static function ( $input = array() ) {
					$input = is_array( $input ) ? $input : array();

					$count = isset( $input['count'] ) ? (int) $input['count'] : 1;
					$count = max( 1, min( 20, $count ) );

					$post_types = null;
					if ( isset( $input['post_type'] ) && '' !== $input['post_type'] ) {
						$requested = sanitize_key( (string) $input['post_type'] );
						$public    = get_post_types( array( 'public' => true ), 'names' );

						if ( ! in_array( $requested, $public, true ) ) {
							return new WP_Error(
								'random_page_redirect_invalid_post_type',
								__( 'The requested post type is not a registered, public post type.', 'random-page-redirect-for-wordpress' ),
								array( 'status' => 400 )
							);
						}

						$post_types = array( $requested );
					}

					return array(
						'items' => thisismyurl_random_redirect_get_random_urls( $count, $post_types ),
					);
				},
				'permission_callback' => static function (): bool {
					// Returns only published, publicly-viewable content. 'read'
					// is the conservative floor; the same posts are reachable
					// anonymously via the /random endpoint.
					return current_user_can( 'read' );
				},
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => false, // Random selection: repeat calls may differ.
					),
					'show_in_rest' => true,
				),
			)
		);
	}
);
