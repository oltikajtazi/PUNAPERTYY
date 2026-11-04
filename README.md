# Kajtazi Digital Solutions – website (Netlify-ready)

Static site: no build step, no dependencies.

## Deploy
1. Go to app.netlify.com → your site `olti` → **Deploys**.
2. Drag this whole folder onto the deploy drop zone (or connect it to a Git repo; leave build command empty, publish directory `.`).
3. In **Forms**, the `contact` form appears after the first deploy. Turn on email notifications (Site configuration → Forms → Form notifications) so messages reach you.

## Project screenshots
The project cards use preview images from the live project sites, so they also work when opening `index.html` directly from your computer. To use local screenshots instead, save them here:
- `assets/projects/rr-netzmontage.jpg`
- `assets/projects/vital-clinic.jpg`

Then update the matching image `src` values in `index.html` to those local file paths.
For the social preview card, add `assets/og-image.png` (1200×630).

## Edit text
- English is written directly in `index.html` (this is what Google sees).
- German and Albanian (and the same English text) live in `js/i18n.js`. When you change a sentence in `index.html`, change it in `js/i18n.js` too.
- Prices are in the Services section of `index.html`.

## Automatic language
On the first visit the site picks a language in this order:
1. the visitor's earlier manual choice (language dropdown)
2. their browser language, if it is Albanian, English or German
3. their time zone as a location hint (Germany/Austria/Switzerland → German, Albania → Albanian)
4. English

To change the time-zone mapping, edit `TZ_LANG` in `js/main.js`. No server setup is needed, so drag-and-drop deploys work.

## Logo files (`assets/logo/`)
- `logo-mark.svg` / `logo-mark-1024.png`: the K icon (dark tile) for light backgrounds
- `logo-mark-inverse.svg` / `logo-mark-inverse-512.png`: light tile, for dark backgrounds
- `logo-full.svg` / `logo-full.png`: icon + "Kajtazi Digital Solutions" for light backgrounds
- `logo-full-inverse.svg` / `logo-full-inverse.png`: same for dark backgrounds
Text in the SVGs is converted to outlines, so they look the same on every computer. The same icon is used for `favicon.svg` and `apple-touch-icon.png`.

## Structure
```
index.html        main page
privacy.html      privacy policy
thank-you.html    shown after the contact form is sent
404.html          not-found page
netlify.toml      redirects (/portfolio.html → /) + headers
css/style.css     all styles (light/dark themes)
js/main.js        theme, language, menu, live-preview modal
js/i18n.js        SQ / EN / DE translations
assets/           images
favicon.svg  robots.txt  sitemap.xml
```
