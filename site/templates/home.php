<?php
$session = kirby()->session();

// If a Panel user is logged in, send to admin
if ($user = kirby()->user()) {
  if ($admin = page('admin')) go($admin->url());
}

// Role-based redirects
$role = $session->get('role');

if ($role === 'pm') {
  if ($admin = page('admin')) go($admin->url());
}

if ($role === 'client') {
  $slug = $session->get('projectAccess');
  if ($slug && ($project = page($slug))) {
    go($project->url());
  } else {
    // stale/invalid session: clean and send to login
    $session->remove('projectAccess');
    $session->remove('role');
    if ($login = page('login')) go($login->url());
  }
}

// Default: not logged → login page
if ($login = page('login')) go($login->url());
