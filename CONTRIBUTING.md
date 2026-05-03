# Contributing

Thanks for the interest. This is a small, focused plugin — the surface is one file and one URL — so the contribution bar is "modern WordPress, lint clean, no scope creep."

## Before you open a PR

1. Run `php -l random-page-redirect-for-wordpress.php` — must be clean.
2. Run `composer install && composer run lint:phpcs` — must be clean against the bundled `phpcs.xml.dist` (WordPress-Extra ruleset).
3. If you're touching the rewrite rule, the `template_redirect` handler, or the activation hook, test on both pretty permalinks and subdirectory installs. The whole point of the rewrite rule is that the previous `REQUEST_URI` string match broke on subdirs.

## Scope guardrails

In scope: bug fixes, security hardening, accessibility, documentation, translations, lint compliance, filter additions that other site builders genuinely need.

Out of scope (for now): settings screens, REST endpoints, blocks, WP-CLI commands. The plugin's value is that it does one thing without UI weight. If you want one of these, open an issue first.

## Versioning

Releases use a Julian-day pre-release scheme: `x.Yddd` where `x` is the release class (`0` = pre-release, `1` = full), `Y` is the last digit of the year, and `ddd` is the day of year. The maintainer bumps the version on push; PRs should not bump it.

## Reporting security issues

Do not file public issues for security findings. See `SECURITY.md`.
