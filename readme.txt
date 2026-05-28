=== This Is My URL - Random Page Redirect for WordPress ===
Contributors: thisismyurl, nickadamstv, revaultmedia
Donate link: https://github.com/sponsors/thisismyurl
Tags: random, redirect, discovery
Requires at least: 6.4
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.6147
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a /random URL that 302s to a random published post. No settings screen, no cruft — drop-in discovery surface.

== Description ==

Random Page Redirect adds a single `/random` URL to your site. Hit it and WordPress 302s the visitor to a random published post.

Useful on archive-heavy sites where you want a "surprise me" link in the navigation, the footer, or a sidebar widget.

**What it does**

* Registers `/random` as a rewrite rule (subdir-install safe — no string match on `REQUEST_URI`).
* Picks a random published post via `WP_Query orderby=rand` with `fields=ids` so it's a single thin query.
* Issues a 302 to the post permalink via `wp_safe_redirect()`.
* Sends `Cache-Control: no-store` and `nocache_headers()` so CDNs don't pin the redirect to a single destination.

**What it doesn't do**

* No settings screen. The URL is `/random` — that's it.
* No archive-scoped randomisation. This is a redirect endpoint, not an archive sorter.
* No JavaScript, no admin pages, no database options.

**Filters**

* `thisismyurl_random_redirect_post_types` — array of post types eligible for the random pick. Defaults to `[ 'post' ]`. Add your CPTs here.

== Installation ==

1. Upload the plugin folder to `wp-content/plugins/`, or install from the WordPress.org plugin directory.
2. Activate the plugin through the **Plugins** screen.
3. Visit `/random` on your site.

If `/random` 404s after activation, visit **Settings → Permalinks** and click **Save** to flush rewrite rules.

== Frequently Asked Questions ==

= How do I see it work? =

Activate the plugin and visit `yourdomain.com/random`. You'll be 302d to a random published post.

= Can I include custom post types? =

Yes. Hook the `thisismyurl_random_redirect_post_types` filter:

`add_filter( 'thisismyurl_random_redirect_post_types', function ( $types ) {
    $types[] = 'product';
    return $types;
} );`

= Why a 302 and not a 301? =

The destination is intentionally different on every hit. A 301 would let browsers and CDNs cache the redirect to a single post — exactly the wrong behaviour for a "random" URL.

= Does this work behind a CDN? =

Yes. The plugin sends `Cache-Control: no-store, no-cache, must-revalidate, max-age=0` plus `nocache_headers()` so well-behaved edges treat the redirect as uncacheable.

== Changelog ==

= 1.6148 =
* Added WordPress 7.0 Abilities API support: the `random-page-redirect/get-random-url` ability returns one or more random published posts as {id, url} pairs for REST/AI discovery (read-only; never performs the redirect).
* Extracted the random-post selection into a shared `thisismyurl_random_redirect_get_random_urls()` function used by both the /random endpoint and the new ability.

= 1.6147 =
* Unified plugin versioning to the x.Yddd calendar-version scheme.
* Confirmed compatibility with WordPress 7.0.


= 1.6143 =
* First full release (class 1). The 0.6xxx line was pre-release on the `x.Yddd` scheme.
* Standardized the donation link to GitHub Sponsors.

= 0.6123 =
* Rewrite of the plugin under modernised WordPress standards.
* Replace `REQUEST_URI` string match with a proper rewrite rule and query var (subdir-install safe).
* Switch from `wp_redirect()` to `wp_safe_redirect()`.
* Add `Cache-Control: no-store` headers so CDNs don't pin the redirect.
* Filterable post types via `thisismyurl_random_redirect_post_types`.
* Activation/deactivation hooks flush rewrite rules.
* Modern plugin header (Text Domain, Requires PHP, Requires at least, License URI, Update URI).
* ABSPATH guard, removed direct `$_SERVER` access.
* Renamed plugin file to match repository slug.

= 1.0.1 =
* Maintenance update under prior maintainership; no documented changes.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 0.6123 =
Plugin file rename and rewrite-rule upgrade. After upgrading, visit Settings → Permalinks and click Save to flush rewrite rules. The URL is now `/random` (was `/random-post`).
