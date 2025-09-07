<?php
return function ($page, $site) {
    $error   = null;
    $session = kirby()->session();

    // 0) Already authenticated? → redirect immediately
    if (kirby()->user()) {
        if ($admin = page('admin')) go($admin->url());
    }

    $role = $session->get('role');
    if ($role === 'pm') {
        if ($admin = page('admin')) go($admin->url());
    } elseif ($role === 'client') {
        $slug = $session->get('projectAccess');
        if ($slug && ($project = page($slug))) {
            go($project->url());
        } else {
            // stale client session → clear it
            $session->remove('projectAccess');
            $session->remove('role');
        }
    }

    // 1) New login attempt
    $input = get('password'); // works for POST/GET
    if ($input !== null && trim($input) !== '') {
        $input = trim((string)$input);
        // PM login
        $adminPassword = trim((string) $site->adminPassword());
        if ($adminPassword !== '' && hash_equals($adminPassword, (string) $input)) {
            $session->set('role', 'pm');
            $session->remove('projectAccess');
            go(page('admin')->url());
        }

        // Client login (match a project by its password, case-insensitive)
        $matchingProject = site()->pages()
            ->listed()
            ->filterBy('intendedTemplate', 'project')
            ->filter(function ($p) use ($input) {
                return strcasecmp(trim((string) $p->projectPassword()), (string) $input) === 0;
            })
            ->first();

        if ($matchingProject) {
            $session->set('role', 'client');
            $session->set('projectAccess', $matchingProject->slug());
            go($matchingProject->url());
        }

        // No match
        $session->remove('role');
        $session->remove('projectAccess');
        $error = 'Invalid password. Please try again.';
    }

    return ['error' => $error];
};
