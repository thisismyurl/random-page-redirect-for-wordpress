# Random Page Redirect for WordPress

[![CI](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml) [![WordPress Tested](https://img.shields.io/badge/WordPress-4.5%2B-blue)](https://wordpress.org/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)


Adds a `/random` URL to WordPress that redirects to a random published post. Drop-in discovery surface — useful on archive-heavy sites where readers want a "surprise me" button.

## The short story

This plugin started in the WordPress 4.x era under contributors `nickadamstv` and `revaultmedia` and went quiet after 2016. I picked it up because the underlying behaviour — a single registered URL that 302s to `WP_Query orderby=rand limit=1` — is exactly the kind of small, focused thing WordPress should ship and doesn't.

Same plugin, same redirect approach, just maintained again on modern WordPress.

## What it does

- Registers `/random` (configurable) as a top-level URL.
- On hit: queries for a random `post_status=publish` post and 302s to its permalink.
- Filters out password-protected, sticky, and trashed posts by default.
- Sends `Cache-Control: no-store` so CDNs don't pin the redirect.

## What it doesn't do

- It doesn't randomise within a category or tag — full-site only. Per-archive randomisation is on the roadmap.
- It doesn't shuffle post order in archive views. This is a redirect endpoint, not an archive sorter.

## Installation

- WordPress.org plugin directory (recommended): [Random Page Redirect for WordPress](https://wordpress.org/plugins/random-page-redirect-for-wordpress/)
- Manual: download the latest release, upload to `wp-content/plugins/`, activate.

## Maintained by

Christopher Ross, on the open web since 1996 and on WordPress since 2007. More at [thisismyurl.com](https://thisismyurl.com/).

Original contributors `nickadamstv` and `revaultmedia` retain credit in the plugin header and `readme.txt`.

## License

GPL-2.0-or-later.
