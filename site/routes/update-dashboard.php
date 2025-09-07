<?php

use Kirby\Http\Response;

function updateDashboard()
{
    $defaults = json_decode(get('defaults') ?? '{}', true);

    $campaign = $defaults['campaign'] ?? null;
    $version = $defaults['version'] ?? null;
    $language = $defaults['language'] ?? null;
    $brand = $defaults['brand'] ?? null;

    $brandPage = page($brand);
    if (!$brandPage || !$brandPage->isPage()) {
        return Response::json(['error' => 'Brand not found'], 404);
    }

    error_log($campaign);
    error_log($version);
    error_log($language);
    error_log($brand);

    return Response::json([
        'selectors' => PanelBuilder::createSelectors($brandPage, $campaign, $version, $language),
        'ratios'    => PanelBuilder::createRadioButtons($brandPage, $campaign, $version, $language),
        'banners'   => PanelBuilder::createBanners($brandPage, $campaign, $version, $language),
    ]);
}