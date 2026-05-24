# This Is My URL - Random Page Redirect for WordPress

[![CI](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml) [![WordPress Tested](https://img.shields.io/badge/WordPress-4.5%2B-blue)](https://wordpress.org/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Adds a `/random` URL to WordPress that redirects to a random published post. Drop-in discovery surface — useful on archive-heavy sites where readers want a "surprise me" button or to navigate back to older content.

## The Short Story

This plugin started in the WordPress 4.x era under contributors `nickadamstv` and `revaultmedia` and went quiet after 2016. I picked it up because the underlying behaviour — a single registered URL that 302s to `WP_Query orderby=rand limit=1` — is exactly the kind of small, focused thing WordPress should ship and doesn't.

Same plugin, same redirect approach, just maintained again on modern WordPress. Original contributors retain full credit in the plugin header and `readme.txt`.

## What It Does

- **Registers `/random`** (configurable) as a top-level URL on your site.
- **Redirects to random posts:** On hit, queries for a random `post_status=publish` post and 302s to its permalink.
- **Sensible filtering:** Filters out password-protected, sticky, and trashed posts by default.
- **CDN-friendly headers:** Sends `Cache-Control: no-store` so CDNs don't pin the redirect.

## What It Doesn't Do

- It doesn't randomise within a category or tag — full-site only. Per-archive randomisation is on the roadmap.
- It doesn't shuffle post order in archive views. This is a redirect endpoint, not an archive sorter.
- It doesn't cache the redirect target. Each visit queries for a fresh random post.

## Requirements

- WordPress 4.5+
- PHP 7.2+

## Installation

**WordPress.org plugin directory (recommended):**
- Install directly from WordPress admin: **Plugins > Add New** → search "Random Page Redirect" → **Install Now**
- Or visit [Random Page Redirect for WordPress on WordPress.org](https://wordpress.org/plugins/random-page-redirect-for-wordpress/)

**Manual installation:**
1. Download the latest release from [Releases](https://github.com/thisismyurl/random-page-redirect-for-wordpress/releases)
2. Unzip to `/wp-content/plugins/random-page-redirect-for-wordpress/`
3. Activate through **Plugins > Installed Plugins**

## Usage

Once activated:
- Visit `/random` on your site to see it in action.
- You can link to `/random` from anywhere to provide a "surprise me" button.
- Each visit generates a fresh random redirect.

### Customising the URL

By default, the plugin uses `/random`. To change it, add this to your `wp-config.php`:

```php
define( 'RANDOM_PAGE_REDIRECT_URL', '/random-post' );
```

Then use that custom URL instead (e.g., `/random-post`).

## Standards

- Direct access protection with ABSPATH checks.
- Nonce and capability checks for all admin actions.
- Aligned with WordPress coding standards.

---

## Support and Contribute

### Ways to Support

I'm building these tools because WordPress developers and site owners deserve straightforward, practical solutions. There's no tracking, no ads, and you don't need to pay to use these plugins.

If you find them helpful, here are some genuine ways to support the work:

- **Sponsor if it fits your budget:** You can sponsor the project through [GitHub Sponsors](https://github.com/sponsors/thisismyurl). Sponsorship helps, but it's always optional.
- **Contribute code or ideas:** Opening a pull request, reporting an issue, or testing edge cases is just as valuable as sponsorship.
- **Share your experience:** A follow on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps others discover this work.

### Report Issues and Questions

Found a bug? Want to suggest a feature? Questions about how it works?

- **File an issue:** Use the [Issues](../../issues) tab. Include your WordPress and PHP version, and steps to reproduce.
- **Start a discussion:** Use the [Discussions](../../discussions) tab for questions, ideas, or general conversation.

### Contributing Code

Code contributions are welcome and genuinely valuable:

1. **Fork this repository** and clone it locally.
2. **Create a feature branch** with a clear name (e.g., `feature/category-random-redirect`).
3. **Make your changes** and test thoroughly on WordPress 4.5+ and modern versions.
4. **Follow WordPress coding standards** — run `composer run lint:phpcs` before opening a PR.
5. **Open a pull request** with a clear description of what changed and why.

---

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — current maintainer
- **Nick Adams** (`nickadamstv`) — original author
- **Revault Media** (`revaultmedia`) — original co-author
- **Contributors:** Thanks to everyone who's reported issues and tested edge cases

## License

GPL-2.0-or-later. Original contributors `nickadamstv` and `revaultmedia` retain full credit in the plugin header and `readme.txt`.

---

**More about This Is My URL:** Christopher Ross is a WordPress developer on the open web since 1996 and on WordPress since 2007. Learn more at [thisismyurl.com](https://thisismyurl.com/).


---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*

