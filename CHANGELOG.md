# Changelog

All notable changes to **Random Page Redirect for WordPress** are documented here.
This project follows [Semantic Versioning](https://semver.org/) where practical, plus a Julian-day pre-release scheme (`x.Yddd`) for in-flight builds: `x` = release class (`0` = pre-release, `1` = full), `Y` = last digit of year, `ddd` = Julian day.

## [0.6123] — 2026-05-03

### Changed

- Renamed plugin file from `random-post-redirect-for-wordpress.php` to `random-page-redirect-for-wordpress.php` to match the repo slug and `readme.txt` title.
- Replaced the `$_SERVER['REQUEST_URI']` string match with a proper `add_rewrite_rule()` + `query_var` registration; `/random` now resolves cleanly on subdirectory installs.
- Switched from `wp_redirect()` to `wp_safe_redirect()`.
- Modernised the plugin header: `Plugin URI`, `Author URI`, `Text Domain`, `Domain Path`, `Requires PHP`, `Requires at least`, `License`, `License URI`, `Update URI`.
- `readme.txt` brought up to current `.org` standards (Tested up to 6.8, full Changelog, Upgrade Notice, valid License/License URI, dropped invalid `Plugin URI` and overly broad `wordpress` tag).

### Added

- `Cache-Control: no-store, no-cache, must-revalidate, max-age=0` plus `nocache_headers()` so CDNs do not pin the redirect to a single destination.
- Filter `thisismyurl_random_redirect_post_types` for opting custom post types into the random pool.
- ABSPATH guard at the top of the plugin file.
- Activation and deactivation hooks that flush rewrite rules.

### Fixed

- Author copyright typo (`Chrsitopher Ross` → `Christopher Ross`).
- HTTP plugin URI / author URI references upgraded to HTTPS.
- `foreach`-over-a-1-result query replaced with a single ID lookup (`fields => 'ids'`, `posts_per_page => 1`).

## [1.0.1] — historical

- Maintenance update under prior maintainership; no documented changes.

## [1.0.0] — historical

- Initial release.
