<?php

function onZipUpload(Kirby\Cms\File $file)
{
  $parent = $file->parent();

  if (
    $file->extension() === 'zip' &&
    $parent &&
    $parent->intendedTemplate()->name() === 'language'
  ) {
    try {
      $zip = new ZipArchive;
      $zipPath = $file->root();
      $extractPath = $parent->root() . '/bundle';

      if ($zip->open($zipPath) === true) {
        Dir::make($extractPath);
        $zip->extractTo($extractPath);
        $zip->close();

        error_log('ZIP extracted to: ' . $extractPath);
      } else {
        throw new Exception('Failed to open ZIP archive');
      }
    } catch (Exception $e) {
      error_log('Unzip error: ' . $e->getMessage());
    }
  }
}
