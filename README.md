# Banner(dot)Hub

## Overview
This repository contains a **Preview Platform** built on **Kirby CMS** (PHP).  
It lets **project managers**, **clients**, and **developers** preview HTML5 banner bundles in a structured dashboard:

- **PMs** sign in to a custom **Admin** page (not Kirby Panel) to see all projects, status, and quick links.
- **Clients** sign in with a **project-specific password** and are redirected straight to their **project dashboard**.
- A **public Portfolio** page lists selected projects for anyone with the URL.

The content tree is standardized as:

```
project (brand)
└── campaign
    └── version
        └── language   ← upload ZIP here
            └── bundle/ (unzipped)
                └── index.html (served via custom route)
```

## Key features
- **ZIP upload & auto-unzip** at the `language` level → served via custom **/bundle** routes.
- **Dynamic selectors** (campaign/version/language) and **size buttons** derived from the current page structure and the bundle’s `index.html`.
- **Single/Multi view** banner preview grid.
- **Only-one default** enforcement for campaign/version/language (prevents multiple “default” flags).
- **Draft/Unlisted detector** to keep everything publicly listed (required for selectors to traverse the tree).
- **Session-based roles**: `pm`, `client`, `portfolio` + full Panel user bypass.

## Important files
```
site/
  config/
    config.php              # Registers helpers, hooks, routes
  helpers/
    asset-helper.php        # Vite manifest → hashed asset URLs
    config-helper.php       # Environment URL + debug helpers
    panel-builder.php       # Generates selectors, ratio buttons, banners HTML
  hooks/
    page-create.php         # Auto-create child structure for new project; tidy bundle on new ZIP
    file-create.php         # Unzip ZIPs dropped on language pages (→ /bundle)
    only-one-default.php    # Enforce single default (campaign/version/language)
  routes/
    bundle.php              # Serves bundle index.html and its static assets
    detectDraft.php         # HTML report of draft/unlisted pages per brand
    logout.php              # Clears session + panel logout
    update-dashboard.php    # JSON endpoint returning rendered HTML fragments (selectors/ratios/banners)
templates/
  login.php, project.php, portfolio.php, admin.php, ...
```

## Roles & access control
- **Panel user (Kirby user):** Full access everywhere.
- **PM (`role=pm` via login):** Full access, redirected to `/admin`.
- **Client (`role=client` via login):** Access only to their project; stored in session as `projectAccess`.
- **Portfolio visitor (`role=portfolio`):** Read-only access to projects flagged for portfolio. Allowed slugs are stored in session.

Access control and redirection logic are handled in **dedicated controller files** for each page.  
For example, the logic for `login.php` is defined in `site/controllers/login.php`.

## Content model
- Top-level `project` pages represent **brands**.
- Child pages:
  - `campaign` → `version` → `language`.
- The **ZIP** is uploaded on the **language** page. On upload:
  - Hook unzips to `{language}/bundle/`.
  - Custom route serves `bundle/index.html` and static assets.

## Routes
- **Bundle HTML**  
  `GET /bundle-preview/{brand}/{campaign}/{version}/{language}` → serves `{language}/bundle/index.html`
- **Bundle assets**  
  `GET /bundle-preview/{brand}/{campaign}/{version}/{language}/{path…}` → serves static file under `{language}/bundle/`
- **Dashboard partials**  
  `GET /update-dashboard?defaults={...}` → returns JSON with:
  - `selectors`: HTML for the 3 dropdowns
  - `ratios`: HTML of size buttons / view mode
  - `banners`: HTML for the preview iframes
- **Detect drafts**  
  `GET /detect-drafts` → HTML report listing draft/unlisted pages by brand
- **Logout**  
  `GET /logout` → clears session role (`pm`, `client`, `portfolio`) and Kirby user

## Hooks
- **page-create.php**: Auto-create campaign/version/language skeleton for new projects.
- **file-create.php**: Unzip ZIPs dropped on language pages.
- **only-one-default.php**: Enforce single default for campaign/version/language.

## Helpers
- **PanelBuilder** → creates selectors, ratio buttons, banners.
- **AssetHelper** → reads Vite manifest to resolve asset URLs.
- **ConfigHelper** → dev/host URL and debug toggles used in config.php.

## Troubleshooting
- **ZIP uploaded but no preview:** Check unzip success, index.html exists.
- **Empty selectors:** Make sure parent pages are listed.
- **404 assets:** Verify `<base>` tag is present in served index.html.

## Add a project
To create a new project, follow these steps:

- **Log in** to the Kirby Panel.  
- Click **“+ Add”** to create a new **project page**.  
- Kirby will automatically generate the required base structure:  
  - `campaign`  
  - `version`  
  - `language`  
- Open the **language page**. It contains a **drop area** where you can upload your `bundle.zip`.  
- Kirby will unzip the archive into a `/bundle` folder and serve the `index.html` and related assets.  

⚠️ **Important:** Zip only the **files** (`index.html`, assets, scripts, etc.) — **do not zip the parent folder**.  

Once uploaded, the dashboard selectors and preview grid will automatically update, giving project managers and clients instant access to the new banners.

## Security
- Sessions enforce roles; panel users bypass.
- Use HTTPS and strong admin password.

## Notes
- Looking for details on the bundling process and asset handling for this project? Check out the dedicated repository: [ambientmoxie/kirby-vite-kit](https://github.com/ambientmoxie/kirby-vite-kit)
