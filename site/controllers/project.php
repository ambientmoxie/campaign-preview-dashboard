<?php
return function ($page) {
    $session = kirby()->session();

    // Panel users: full access
    if (kirby()->user()) return [];

    $role = $session->get('role');

    // PM: full access
    if ($role === 'pm') return [];

    // Client: only their own project
    if ($role === 'client') {
        if ($session->get('projectAccess') === $page->slug()) return [];
        if ($allowedPage = page($session->get('projectAccess'))) go($allowedPage->url());
        // stale session
        $session->remove('projectAccess');
        $session->remove('role');
        go(page('login')->url());
    }

    // Portfolio visitor: only projects listed on portfolio
    if ($role === 'portfolio') {
        $allowed = (array) $session->get('portfolioAccess', []);
        if (in_array($page->slug(), $allowed, true)) return [];
        // Not allowed → back to portfolio
        if ($portfolio = page('portfolio')) go($portfolio->url());
        go('/'); // fallback
    }

    // No role → login or portfolio (your choice)
    if ($login = page('login')) go($login->url());
    return [];
};
