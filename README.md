# Christopher Ross - Random Page Redirect for WordPress

[![CI](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/random-page-redirect-for-wordpress/actions/workflows/ci.yml) [![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue)](https://wordpress.org/plugins/random-page-redirect-for-wordpress/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Adds a `/random` URL to WordPress that redirects to a random published post. A drop-in discovery surface — useful on archive-heavy sites where readers want a "surprise me" button or a way back into older content.

## The short story

This plugin started in the WordPress 4.x era under contributors `nickadamstv` and `revaultmedia` and went quiet after 2016. I picked it up because the underlying behaviour — a single registered URL that 302s to `WP_Query orderby=rand limit=1` — is exactly the kind of small, focused thing WordPress should ship and doesn't.

Same plugin, same redirect approach, just maintained again on modern WordPress. The original contributors retain full credit in the plugin header and `readme.txt`.

## What it does

- **Registers `/random`** (configurable) as a top-level URL on your site.
- **Redirects to random posts:** on a hit, it queries for a random `post_status=publish` post and 302s to its permalink.
- **Sensible filtering:** filters out password-protected, sticky, and trashed posts by default.
- **CDN-friendly headers:** sends `Cache-Control: no-store` so CDNs don't pin the redirect.

## What it doesn't do

- It doesn't randomise within a category or tag — full-site only. Per-archive randomisation is on the roadmap.
- It doesn't shuffle post order in archive views. This is a redirect endpoint, not an archive sorter.
- It doesn't cache the redirect target. Each visit queries for a fresh random post.

## Requirements

- WordPress 6.4+
- PHP 7.4+

## Installation

**WordPress.org plugin directory (recommended):**

- Install from WordPress admin: **Plugins > Add New** → search "Random Page Redirect" → **Install Now**.
- Or visit [Random Page Redirect for WordPress on WordPress.org](https://wordpress.org/plugins/random-page-redirect-for-wordpress/).

**Manual installation:**

1. Download the latest release from [Releases](../../releases).
2. Unzip to `/wp-content/plugins/random-page-redirect-for-wordpress/`.
3. Activate through **Plugins > Installed Plugins**.

## Usage

Once activated:

- Visit `/random` on your site to see it in action.
- Link to `/random` from anywhere to provide a "surprise me" button.
- Each visit generates a fresh random redirect.

### Customising the URL

By default, the plugin uses `/random`. To change it, add this to your `wp-config.php`:

```php
define( 'RANDOM_PAGE_REDIRECT_URL', '/random-post' );
```

Then use that custom URL instead (for example, `/random-post`).

## Standards

- Direct-access protection with `ABSPATH` checks.
- Nonce and capability checks for all admin actions.
- Aligned with WordPress coding standards.

## Changelog

See [releases](../../releases) or [readme.txt](readme.txt).

---

## Support and donations

I build these tools because WordPress sites in the wild keep hitting the same problems, and a small, focused plugin is usually the right fix. They're free to use, with no tracking and no ads.

If one of them saves you time, here are the genuine ways to help:

- **Sponsor the work.** [GitHub Sponsors](https://github.com/sponsors/thisismyurl) is the simplest way, and the Sponsor button at the top of this repo lists it alongside Bitcoin, Dogecoin, PayPal, and Interac e-transfer. Any amount helps, and none of it is expected.
- **Contribute code or ideas.** A pull request, a bug report, or a tested edge case is worth as much as a donation. See [CONTRIBUTING.md](CONTRIBUTING.md) to get started.
- **Share it.** A note on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps other people find work that might save them the same afternoon.

### Report issues and questions

- **Found a bug or want a feature?** Open an issue on the [Issues](../../issues) tab. Include your WordPress and PHP versions and the steps to reproduce it.
- **Have a question?** Start a thread on the [Discussions](../../discussions) tab.

### Contributing code

Code contributions are welcome. The short version:

1. Fork the repository and clone your fork.
2. Create a branch with a clear name, like `feature/short-descriptive-name`.
3. Make your change and test it against the edge cases.
4. Run the coding-standards check before you open the pull request.
5. Open a pull request that explains what changed and why.

The full workflow and standards live in [CONTRIBUTING.md](CONTRIBUTING.md). Contributing is never required, but it is always appreciated.

## About Christopher Ross

This plugin is built and maintained by [Christopher Ross](https://thisismyurl.com/), the WordPress development and technical SEO practice of Christopher Ross. I help teams build WordPress sites that stay secure, fast, and maintainable, and I write small, focused plugins like this one for the problems those sites keep running into.

### My background

- On the web since 1996, and in WordPress since 2007
- WordPress.org plugin developer with 19 plugins published since 2009
- Technical SEO practitioner focused on performance, security, and search visibility
- Lead instructor and curriculum architect at the M.L. Campbell Training Center, the Sherwin-Williams® international training facility for its industrial wood division

### Ways to connect

- **Website:** [thisismyurl.com](https://thisismyurl.com/)
- **WordPress.org:** [profiles.wordpress.org/thisismyurl](https://profiles.wordpress.org/thisismyurl/)
- **GitHub:** [github.com/thisismyurl](https://github.com/thisismyurl)
- **LinkedIn:** [linkedin.com/in/thisismyurl](https://linkedin.com/in/thisismyurl)

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — current maintainer
- **Nick Adams** (`nickadamstv`) — original author
- **Revault Media** (`revaultmedia`) — original co-author
- Thanks to everyone who has reported issues and tested edge cases

## License

GPL-2.0-or-later — see [LICENSE](LICENSE) or [gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html). The original contributors `nickadamstv` and `revaultmedia` retain full credit in the plugin header and `readme.txt`.

---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*
