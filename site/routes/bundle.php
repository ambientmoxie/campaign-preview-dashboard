<?php

use Kirby\Http\Response;

function serveBundleHtml($brand, $campaign, $version, $language) {
  // Let Kirby resolve the real page path
  $page = page("{$brand}/{$campaign}/{$version}/{$language}");
  if (!$page) {
    return new Response('Not found', 'text/plain', 404);
  }

  $indexPath = $page->root() . '/bundle/index.html';
  if (!file_exists($indexPath)) {
    return new Response('Not found', 'text/plain', 404);
  }

  $html = file_get_contents($indexPath);

  // Build absolute base for assets
  $baseHref = url("bundle-assets/{$brand}/{$campaign}/{$version}/{$language}") . '/';

  // Inject <base> right after <head> (or prepend if no <head>)
  if (preg_match('~<head[^>]*>~i', $html)) {
    $html = preg_replace('~(<head[^>]*>)~i', '$1<base href="'.$baseHref.'">', $html, 1);
  } else {
    $html = '<base href="'.$baseHref.'">' . $html;
  }

  // Optional: mark responses to help debug
  // header('X-Bundle-Base', $baseHref);

  return new Response($html, 'text/html');
}

function serveBundleAsset($brand, $campaign, $version, $language, $file) {
  $page = page("{$brand}/{$campaign}/{$version}/{$language}");
  if (!$page) return false;

  $path = $page->root() . '/bundle/' . $file;
  if (!file_exists($path)) return false;

  return new Kirby\Cms\Response(
    file_get_contents($path),
    mime_content_type($path)
  );
}
