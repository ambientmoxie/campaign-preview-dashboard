<?php
return function ($page) {
  $session = kirby()->session();

  // Build the list of projects shown on the portfolio page
  $projects = site()->pages()
    ->listed()
    ->filterBy('intendedTemplate', 'project')
    ->filterBy('Showinportfolio', 'true');

  // Save allowed slugs for this visitor
  $session->set('portfolioAccess', $projects->pluck('slug'));

  // If the visitor has no role yet, mark them as "portfolio" (don’t overwrite pm/client/panel)
  if (!$session->get('role') && !kirby()->user()) {
    $session->set('role', 'portfolio');
  }

  // You can pass data to the template/snippets if needed
  return [];
};
