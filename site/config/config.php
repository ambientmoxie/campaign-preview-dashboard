<?php
// Helpers
require_once __DIR__ . '/../helpers/index.php';

// Hooks
require_once __DIR__ . '/../hooks/page-create.php';
require_once __DIR__ . '/../hooks/file-create.php';
require_once __DIR__ . '/../hooks/only-one-default.php';

// Routes
require_once __DIR__ . '/../routes/bundle.php';
require_once __DIR__ . '/../routes/logout.php';
require_once __DIR__ . '/../routes/detectDraft.php';
require_once __DIR__ . '/../routes/update-dashboard.php';

return [
  'debug' => configHelper::setDebugMode(),
  'url'   => configHelper::setEnvURL(":8888", false),

  'thumbs' => [
    'srcsets' => [
      'default' => [
        '300w'  => ['width' => 300],
        '600w'  => ['width' => 600],
        '900w'  => ['width' => 900],
        '1200w' => ['width' => 1200],
        '1800w' => ['width' => 1800]
      ],
      'webp' => [
        '300w'  => ['width' => 300, 'format' => 'webp'],
        '600w'  => ['width' => 600, 'format' => 'webp'],
        '900w'  => ['width' => 900, 'format' => 'webp'],
        '1200w' => ['width' => 1200, 'format' => 'webp'],
        '1800w' => ['width' => 1800, 'format' => 'webp']
      ],
    ]
  ],

  'routes' => [

    // This route serves the unzipped bundle's index.html inside an iframe.
    // It reads the raw index.html file from the content folder and rewrites all relative
    // asset paths (like "p1.jpg", "main.js", etc.) found in `source` or `href` attributes.
    // The rewritten paths point to the second route (`bundle-assets`) so assets load properly.
    [
      // (:...) captures URL parameters for use in the action function
      'pattern' => 'bundle-preview/(:any)/(:any)/(:any)/(:any)',
      'action'  => function ($brand, $campaign, $version, $language) {
        return serveBundleHtml($brand, $campaign, $version, $language);
      }
    ],

    // This route serves static files from the unzipped bundle directory,
    // such as images, stylesheets, JavaScript, or fonts.
    // It is used by the first route after rewriting paths to pass through here.
    [
      'pattern' => 'bundle-assets/(:any)/(:any)/(:any)/(:any)/(:all)',
      'action'  => function ($brand, $campaign, $version, $language, $file) {
        return serveBundleAsset($brand, $campaign, $version, $language, $file);
      }
    ],
    [
      'pattern' => 'dashboard-update',
      'method'  => 'GET',
      'action'  => function () {
        return updateDashboard();
      }
    ],
    [
      'pattern' => 'logout',
      'method'  => 'POST',
      'action'  => function () {
        $session = kirby()->session();
        $session->remove('role');
        $session->remove('projectAccess');

        // Also destroy the whole session
        $session->destroy();

        return go(page('login')->url());
      }
    ],
    [
      'pattern' => 'logout',
      'method'  => 'GET|POST',
      'action'  => function () {
        return logout();
      }
    ],
    [
      'pattern' => 'detect-draft',
      'method'  => 'GET',
      'action'  => function () {
        return detectDraft();
      }
    ]
  ],

  'hooks' => [
    'page.create:after' => function ($page) {
      onProjectCreate($page);
    },
    'file.create:after' => function ($file) {
      onZipUpload($file);
    },
    'file.delete:after' => function ($file) {
      onZipDelete($file);
    },
    'page.update:after' => function ($newPage, $oldPage) {
      enforceOnlyOneDefault($newPage, $oldPage);
    },
    'page.create:after' => function ($page) {
      if ($page->intendedTemplate()->name() === 'project') {
        $page->update([
          'created' => date('Y-m-d'),
        ]);
      }
    }
  ]
];
