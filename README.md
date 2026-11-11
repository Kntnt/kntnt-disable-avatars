# Kntnt Disable Avatars

WordPress mu-plugin that disables avatars and keeps every request, and every user's email hash, from reaching Gravatar.

- Avatars are off, whatever *Settings → Discussion* says.
- `get_avatar()` returns nothing.
- Every avatar URL is a transparent 1×1 GIF, including those in the REST API and the block editor, so no URL with an email hash is built.
- The server's HTTP requests to `gravatar.com` and `gravatar.net` fail.
- Resource hints to those hosts are removed.

## Installation

Put `kntnt-disable-avatars.php` in `wp-content/mu-plugins/`. It needs PHP 8.0 or later.

## Changelog

### 2.0.0

- Avatar URLs and avatar data are replaced too. 1.0.0 removed only the markup, so the REST API and the block editor still built Gravatar URLs with the user's email hash.
- The server's requests and the resource hints to Gravatar are stopped.
- `show_avatars` is filtered instead of written to the database.

### 1.0.0

- First release.
