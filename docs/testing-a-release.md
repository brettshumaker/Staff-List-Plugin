# Testing a release

A short checklist for testing a Simple Staff List release before tagging it. The plugin has no automated tests yet, so this is the safety net.

The main rule: **existing sites must keep rendering the same way after an update.** Shortcode output only changes where the changelog says so (a "FIXED" line).

## 1. Set up a test site

Any local WordPress install works: WordPress Studio, `wp-env`, Local, or Playground. You need WP-CLI for the snapshot script.

- Turn on `WP_DEBUG` and `WP_DEBUG_LOG` in `wp-config.php`.
- Copy `trunk/` into `wp-content/plugins/simple-staff-list/` and activate it.
- Add some sample data that covers the awkward cases:
  - staff with every field filled in, a photo, and a group
  - staff with empty fields (no photo, no Facebook or Twitter)
  - a staff member with no meta at all
  - two groups, one whose slug differs from its name (the snapshot script expects "Leadership" and "Support Team" with slug `support`)
- Add a page containing `[simple-staff-list]`.

## 2. Snapshot the shortcode output

`tools/snapshot.php` saves the shortcode output for every attribute, both CSS modes and two templates (32 files), so you can diff two versions.

```sh
# With the previous release installed:
wp eval-file /path/to/tools/snapshot.php snapshots/before

# Then install the new trunk/ and run it again:
wp eval-file /path/to/tools/snapshot.php snapshots/after

diff -r snapshots/before snapshots/after
```

On WordPress Studio, run `studio wp` instead of `wp`.

Emails are obfuscated with `antispambot()`, which is random on every run. Decode the HTML entities before diffing so those don't show up as changes. For example:

```sh
for d in before after; do
  mkdir -p "snapshots/$d-decoded"
  for f in snapshots/$d/*.html; do
    python3 -c 'import html,sys; print(html.unescape(open(sys.argv[1]).read()))' "$f" > "snapshots/$d-decoded/$(basename "$f")"
  done
done
diff -r snapshots/before-decoded snapshots/after-decoded
```

Every difference should match a changelog line. Anything else is a regression.

## 3. Check it in a browser

- Re-run each fix in the release by hand, as both an admin and an Editor, and in a real browser.
- Look at the staff list page, a single staff member page, and every admin page under Staff Members (Order, Templates, Usage, Options, Export).
- Check the browser console for errors.

## 4. Check the PHP log

`wp-content/debug.log` should have no new notices, warnings or deprecations from the plugin.

## 5. Check the minimum versions

The readme's `Requires at least` and `Requires PHP` are promises. Don't use a WordPress function newer than `Requires at least`, or PHP syntax newer than `Requires PHP`, without a fallback. Look up anything new with its `@since` tag.
