<?php

function logout()
{
    $kirby   = kirby();
    $session = $kirby->session();

    // Clear app roles
    $session->remove('role');
    $session->remove('projectAccess');
    // Log out kirby panel user
    if ($user = $kirby->user()) $user->logout();
    return go('login');
}
