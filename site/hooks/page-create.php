<?php

function onProjectCreate(Kirby\Cms\Page $page)
{
  if ($page->intendedTemplate()->name() === 'project') {
    $page = $page->changeStatus('listed');

    $campaignSlug = 'Name this campaign';
    $versionSlug  = 'Name this version';
    $languageSlug = 'Name this language';

    $campaignPage = $page->createChild([
      'slug'     => Str::slug($campaignSlug),
      'template' => 'campaign',
      'content'  => [
        'title' => ucfirst($campaignSlug)
      ]
    ])->changeStatus('listed');

    $versionPage = $campaignPage->createChild([
      'slug'     => Str::slug($versionSlug),
      'template' => 'version',
      'content'  => [
        'title' => ucfirst($versionSlug)
      ]
    ])->changeStatus('listed');

    $versionPage->createChild([
      'slug'     => Str::slug($languageSlug),
      'template' => 'language',
      'content'  => [
        'title'    => ucfirst($languageSlug),
        'language' => $languageSlug
      ]
    ])->changeStatus('listed');
  }
}

function onZipDelete(Kirby\Cms\File $file)
{
  $parent = $file->parent();

  if (
    $file->extension() === 'zip' &&
    $parent &&
    $parent->intendedTemplate()->name() === 'language'
  ) {
    $bundlePath = $parent->root() . '/bundle';

    if (is_dir($bundlePath)) {
      Dir::remove($bundlePath);
      error_log('Bundle folder deleted: ' . $bundlePath);
    }
  }
}