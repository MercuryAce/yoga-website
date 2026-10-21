# Creative Space

Yoga studio site for Vinay Soraya in Shoreditch, London. Public pages are PHP (Forty by HTML5 UP) with shared header, contact, and footer includes. PHP 8 also handles the contact form, vegan recipes, and a small admin area. There is no database: messages and recipes are stored as JSON under `site/data/`.

The public website is the **`site/`** directory. Upload the *contents* of that folder to the host document root, not the parent project.

## Requirements

- PHP 8 (CLI for local development; PHP 8 on the host for production)
- A browser
- For production: a host that runs PHP 8 and allows file uploads (typical shared hosting / cPanel)

Confirm PHP locally:

```bash
php -v
```

Install notes are in [Install PHP](#install-php) if that command is missing.

## Local development

From the project root:

```bash
php -S 127.0.0.1:8765 -t site site/router.php
```

Open [http://127.0.0.1:8765](http://127.0.0.1:8765). Leave the process running. Stop it with `Ctrl+C`.

If port `8765` is in use:

```bash
php -S 127.0.0.1:8766 -t site site/router.php
```

`router.php` is only for the built-in PHP server. Apache hosts use `.htaccess` instead.

### Pages

| Path | Purpose |
|---|---|
| `/` or `/index.php` | Home |
| `/yoga.php` | Private classes, groups, events |
| `/creative.php` | Creative Collective |
| `/reviews.php` | Studio reviews |
| `/vegan.php` | Vegan kitchen / recipes |
| `/admin/login.php` | Admin (inbox and recipes) |
| `/sitemap.xml` | Sitemap (served by `sitemap.php`) |

Contact form posts go to `contact.php`. Locally, PHP `mail()` often does not send; messages are still written to the admin inbox.

## Configuration

Edit `site/data/config.php`:

```php
define('CS_MAIL_TO', 'mycreativesspace.yoga@gmail.com');
define('CS_SITE_ORIGIN', '');
define('CS_ADMIN_USER', 'vinay');
define('CS_ADMIN_PASSWORD_HASH', '$2y$12$JbIenT5eLy4ew241MJOrnO2agJviOEuZcoq0oOiH6pkLALA.MunfC');
```

| Constant | Purpose |
|---|---|
| `CS_MAIL_TO` | Inbox address for contact-form email |
| `CS_SITE_ORIGIN` | Public origin with no trailing slash, e.g. `https://www.example.com`. Leave empty until the live URL is known. Used for canonical URLs, sitemap, and JSON-LD. |
| `CS_ADMIN_USER` | Admin username |
| `CS_ADMIN_PASSWORD_HASH` | bcrypt hash of the admin password |

Studio email (`mycreativesspace.yoga@gmail.com`) and phone (`07399 968655`) on the public pages are unchanged. `CS_MAIL_TO` is the same address used for form mail.

## Admin

1. Open `/admin/login.php`
2. Default credentials:
   - Username: `vinay`
   - Password: `creativespace`
3. **Inbox** — contact-form messages (`site/data/messages.json`)
4. **Recipes** — add, edit, or delete vegan recipes. Uploaded images go in `site/images/recipes/`

Change the password before deploying.

### Change the admin password

Generate a hash:

```bash
php -r "echo password_hash('put-your-new-password-here', PASSWORD_DEFAULT), PHP_EOL;"
```

Replace `CS_ADMIN_PASSWORD_HASH` in `site/data/config.php` with the full `$2y$…` string. Keep the quotes and semicolon. Confirm a login at `/admin/login.php` before uploading.

## Deployment

Needs PHP 8. Static-only hosts will not run the form, recipes, or admin:

- GitHub Pages
- Netlify
- Cloudflare Pages

Suitable hosts: shared hosting with cPanel (Hostinger, DreamHost, Krystal, IONOS, and similar), or any PHP 8 environment with a writable document root.

### What to upload

Upload the **contents** of `site/`:

```
public_html/index.php
public_html/yoga.php
public_html/contact.php
public_html/admin/
public_html/assets/
public_html/data/
public_html/images/
…
```

Not:

```
public_html/site/index.php
```

### cPanel File Manager

1. Log in to the hosting control panel and open **File Manager**.
2. Open `public_html` (sometimes `www` or `htdocs`).
3. Remove the host’s default `index.html` if present.
4. Upload every file and folder from this project’s `site/` directory.
5. If bulk folder upload is unavailable, zip the *contents* of `site/`, upload the zip, extract it, then delete the zip.
6. Set permissions on `data` and `images/recipes` to **775** (owner and group can read, write, and enter).
7. Visit the domain over HTTPS.

### FTP / SFTP (FileZilla)

1. Install [FileZilla](https://filezilla-project.org/).
2. Connect with the host, username, password, and port (`21` FTP or `22` SFTP) from the hosting welcome mail.
3. Local pane: this project’s `site/` folder. Remote pane: `public_html`.
4. Upload everything inside `site/` into `public_html`.
5. Set `data` and `images/recipes` writable, as above.

### After upload

- Home page and `/yoga.php` load
- `/admin/login.php` accepts the configured credentials
- Contact form saves a test message to Inbox
- A test recipe with an image appears on `/vegan.php`
- `/sitemap.xml` lists the public pages
- Set `CS_SITE_ORIGIN` to the live origin and re-upload `data/config.php`

## HTTPS

Enable the host’s Let’s Encrypt / SSL option and serve the site on `https://`. Redirect HTTP to HTTPS if both are reachable.

## Host requirements

| Need | Used for |
|---|---|
| PHP 8 | Forms, admin, recipes, sitemap |
| Sessions / cookies | Admin login |
| Writable `data/` | Messages and recipes |
| Writable `images/recipes/` | Recipe photos from admin |
| `mail()` (optional) | Email copies of contact-form posts |

If `mail()` is disabled, messages still persist in the admin inbox.

Keep these Apache files in place:

- `site/.htaccess` — maps `/sitemap.xml` to `sitemap.php`; security headers; disables directory indexes
- `site/data/.htaccess` — blocks direct access to PHP/JSON config and data
- `site/includes/.htaccess` — denies public access to PHP helpers
- `site/images/recipes/.htaccess` — disables PHP in the recipe photo folder

Contact and admin posts are limited to the same site, use CSRF tokens, and rate-limit by IP. Change the admin password before going live.

## Install PHP

### macOS

```bash
brew install php
php -v
```

Install [Homebrew](https://brew.sh) first if `brew` is missing.

### Windows

[XAMPP](https://www.apachefriends.org): start Apache, copy the contents of `site/` into `C:\xampp\htdocs\`, then open [http://localhost](http://localhost). Apache replaces `php -S` in that setup.

### Linux (Debian/Ubuntu)

```bash
sudo apt install php php-cli
php -v
```

Then use the `php -S` command in [Local development](#local-development).

## Troubleshooting

| Symptom | Likely fix |
|---|---|
| Browser cannot connect locally | Restart `php -S` from the project root |
| `404` on every page | Files were uploaded inside an extra `site/` folder; move them up into `public_html` |
| Home works, `/admin/` is 404 | PHP is not enabled; ask the host for PHP 8 |
| Contact form appears to do nothing | Make `data/` writable; check Inbox anyway |
| “Could not save the image” | `images/recipes/` must be writable |
| Admin login always fails | Re-generate `CS_ADMIN_PASSWORD_HASH` |
| `sitemap.xml` missing or unstyled | Keep `.htaccess` on Apache, or open `/sitemap.php` |
| Missing images | Confirm `images/` uploaded alongside the PHP pages |

The contact form discards posts when the hidden `website` field is filled (honeypot). Real visitors never see that field.

## Layout

```
site/                 public website (upload this)
  index.php
  yoga.php
  creative.php
  reviews.php
  vegan.php
  contact.php         contact form handler
  admin/              login, inbox, recipes
  data/
    config.php        mail, origin, admin credentials
    recipes.json
    messages.json
  includes/           layout, security, and PHP helpers (not public)
  assets/             CSS, JavaScript, fonts
  images/
    recipes/          photos added in admin
  router.php          PHP built-in server only
  .htaccess           Apache production
```

`BUILD.md` is a rebuild diary. It is not required to run or deploy the site.

## Credits

Original material is licensed under [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/). The layout is based on **Forty** by [HTML5 UP](https://html5up.net), [CC BY 3.0](https://html5up.net/license). See `LICENSE`.

- Content: Vinay Soraya / Creative Space
- Layout based on **Forty** by [HTML5 UP](https://html5up.net), [CCA 3.0](https://html5up.net/license)
- Production mark: GnarledTree
