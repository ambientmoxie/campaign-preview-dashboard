<?php

/**
 * Detect Draft / Unlisted Pages
 *
 * The dashboard UI requires that all pages be public (listed).
 * This tool generates a quick report of any pages that are still
 * in draft or unlisted state, grouped under their brand (top-level page).
 *
 * Having unlisted or draft pages in the hierarchy can cause issues
 * in the UI selection logic, so this route makes it easy to spot
 * and fix them. Bundle pages are excluded since they are expected
 * to remain non-public.
 */

use Kirby\Cms\Page;
use Kirby\Cms\Pages;

// Collect all direct + deep drafts under a page, excluding "bundle" pages.
function draftsRecursive(Page $page): Pages
{
    $col = new Pages();

    foreach ($page->drafts() as $draft) {
        if ($draft->intendedTemplate()->name() !== 'bundle' && $draft->slug() !== 'bundle') {
            $col->add($draft);
        }
    }
    foreach ($page->children() as $child) {
        foreach (draftsRecursive($child) as $d) {
            $col->add($d);
        }
    }

    return $col;
}


// Return an array keyed by root (brand) id: [ 'brand-id' => Pages (non-public under that brand), ... ]
function nonPublicByRoot(): array
{
    $result = [];

    foreach (site()->children()->listed() as $root) {
        $col = new Pages();

        // All unlisted descendants under this listed root
        foreach ($root->index()->unlisted() as $u) {
            if ($u->intendedTemplate()->name() !== 'bundle' && $u->slug() !== 'bundle') {
                $col->add($u);
            }
        }

        // All drafts anywhere below this listed root
        foreach (draftsRecursive($root) as $d) {
            $col->add($d);
        }

        $result[$root->id()] = $col->sortBy('id', 'asc');
    }

    return $result;
}

// Shows per-brand lists of non-public pages (excluding "bundle").
function detectDraft(): string
{
    $groups = nonPublicByRoot();

    $html = '<div style="font-family:monospace">';
    $html .= '<h2>Non-public pages by brand</h2>';

    foreach (site()->children()->listed() as $root) {
        $brandTitle = htmlspecialchars($root->title()->value(), ENT_QUOTES, 'UTF-8');
        $brandId    = $root->id();
        $pages      = $groups[$brandId] ?? new Pages();

        $html .= '<section style="margin-block:1rem 1.5rem">';
        $html .= '<h3 style="margin:0 0 .5rem 0;">' . $brandTitle . '</h3>';

        if ($pages->isEmpty()) {
            $html .= '<p style="margin:.25rem 0 .75rem 0;"><em>All public under this brand.</em></p>';
        } else {
            $html .= '<ul style="margin:.25rem 0 .75rem 1rem;">';
            foreach ($pages as $p) {
                $status = $p->isDraft() ? 'draft' : 'unlisted';
                $title  = htmlspecialchars($p->title()->value(), ENT_QUOTES, 'UTF-8');
                $html  .= '<li><strong>' . $title . '</strong> — <code>' . $status . '</code></li>';
            }
            $html .= '</ul>';
        }

        $html .= '</section>';
    }

    return $html;
}
