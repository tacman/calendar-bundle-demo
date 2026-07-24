# ccal — Community Calendar Aggregator

A multi-organization community calendar. Organizations link iCal feeds (or create
calendars natively); the events are aggregated, color-coded, and rendered with
FullCalendar. Individuals can sign in and subscribe to the calendars they care about.

Rendering is provided by [`survos/ux-calendar-bundle`](https://github.com/survos/ux-calendar-bundle)
(FullCalendar v7 + AssetMapper + Stimulus). ccal is the app around it: the org/feed
data model, moderation, accounts, and ingestion.

Symfony 8 · PHP 8.4 · AssetMapper (no Webpack/yarn).

## Quick start

```bash
git clone git@github.com:survos/ccal.git
cd ccal
composer install
php bin/console importmap:install          # download JS assets (AssetMapper)
php bin/console doctrine:schema:update --force --complete
php bin/console app:load-demo-feeds        # load the sample Rappahannock feeds
php bin/console doctrine:fixtures:load -n   # optional

# serve it
symfony server:start -d
# OR
php -S 127.0.0.1:8124 -t public
```

Then open the homepage — the aggregated, color-coded calendar with a per-calendar
toggle legend.

## Workflow

* Individuals sign in ("users").
* Create/Join an Organization.
* Organization admins can:
    * link an iCal feed,
    * import an iCal (`.ics`) file,
    * create/edit a calendar that lives natively on ccal.
* Individuals subscribe to the feeds they want; subscribed calendars are shown by default.

## Data model

```
Org ──┬── Cal ──── Event      (calendars created natively on ccal)
      └── Feed ─── Booking     (events imported from an external iCal feed)
User                           (+ a Symfony Workflow on Feed for moderation)
```

## How aggregation works

`App\EventSource\DatabaseEventSource` (implements the bundle's `EventSourceInterface`)
reads every `Feed`, fetches its ICS, and tags each event with the feed's slug + color.
It is auto-registered into the bundle's `EventSourceRegistry` — the bundle does a
`registerForAutoconfiguration(EventSourceInterface::class)`, so no `services.yaml`
wiring is needed. The `/ux-calendar/events` feed endpoint serves the merged JSON to
FullCalendar.

`bin/console app:load-demo-feeds` reads the demo calendar list straight from the
installed bundle (`vendor/survos/ux-calendar-bundle/demo/...`) and upserts `Feed` rows.

## Asset pipeline notes

ccal is intentionally an AssetMapper/importmap app. There is no active Yarn or Webpack build pipeline.

The active browser entrypoint is `app` in `importmap.php`:

```php
'app' => ['path' => './assets/app.js', 'entrypoint' => true],
```

`assets/app.js` imports `assets/styles/app.css` and starts Stimulus through
`assets/bootstrap.js`. The base layout renders that entrypoint with:

```twig
{{ importmap('app') }}
```

Do not add legacy entrypoint helper tags back to the Twig layouts. Those helpers belonged to the old frontend build setup and are not part of this AssetMapper app.

The old Yarn lockfile was removed. Frontend packages that are needed by the app
should be managed through `importmap.php` and installed with:

```bash
php bin/console importmap:install
```

Use AssetMapper for verification and deployment:

```bash
php bin/console lint:twig templates
php bin/console asset-map:compile
```

When running `asset-map:compile` locally in dev, Symfony writes generated files to
`public/assets`. Delete that directory after verification so the dev server keeps
serving fresh mapped assets:

```bash
rm -rf public/assets
```

### Current cleanup status

* Removed legacy asset helper calls from the base layout and old demo templates.
* Replaced the standalone `mmenu_light` demo's legacy script tag with
  `{{ importmap('app') }}`.
* Deleted the obsolete `yarn.lock`.
* Fixed stale demo-template Twig syntax that prevented a full `lint:twig` pass.
* Verified `php bin/console lint:twig templates`.
* Verified `php bin/console asset-map:compile`, then removed generated
  `public/assets`.

## Tools

* iCal parsing: [`johngrogg/ics-parser`](https://github.com/u01jmg3/ics-parser)
* iCal generation: [`spatie/icalendar-generator`](https://github.com/spatie/icalendar-generator)

## Deployment (Dokku)

Uses [`survos/deployment-bundle`](https://github.com/survos/deployment-bundle) and an
`app.json` (Postgres addon + AssetMapper compile + schema update on predeploy):

```bash
bin/console dokku bootstrap --force        # create app + remote + scaffold
ssh dokku@ssh.survos.com postgres:create ccal-db && ssh dokku@ssh.survos.com postgres:link ccal-db ccal
bin/console dokku config APP_ENV=prod APP_SECRET=$(openssl rand -hex 16) --force
bin/console dokku deploy
```
