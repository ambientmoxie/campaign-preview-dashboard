<?php

$session = kirby()->session();
$role    = $session->get('role');
if ($role && $role !== 'portfolio' || kirby()->user()) {
    echo '<button class="logout" type="button">logout</button>';
}