<?php

/**
 * PanelBuilder is responsible for generating all dynamic dashboard elements
 * based on the current session state: dropdown selectors, banner size buttons, and iframe previews.
 * 
 * All output is returned as HTML strings and injected directly into the dashboard layout.
 */

class PanelBuilder
{

    /**
     * Generates a 3-level selector UI (campaign > version > language)
     * based on the current brand page and its child structure.
     *
     * Each level is rendered using renderDropdown().
     */
    public static function createSelectors(Kirby\Cms\Page $brandPage, ?string $campaignSlug = null, ?string $versionSlug = null, ?string $languageSlug = null): string
    {
        $output = '';

        // Resolve campaigns and selected campaign
        $campaigns = $brandPage->children()->listed();
        $selectedCampaign = self::resolveChildPage($brandPage, $campaignSlug, 'isDefaultCampaign');
        $output .= self::renderDropdown('campaign', $campaigns, $selectedCampaign);

        // Resolve versions and selected version
        $versions = $selectedCampaign?->children()->listed() ?? new Pages();
        $selectedVersion = self::resolveChildPage($selectedCampaign, $versionSlug, 'isDefaultVersion');
        $output .= self::renderDropdown('version', $versions, $selectedVersion);

        // Resolve languages and selected language
        $languages = $selectedVersion?->children()->listed() ?? new Pages();
        $selectedLanguage = self::resolveChildPage($selectedVersion, $languageSlug, 'isDefaultLanguage');
        $output .= self::renderDropdown('language', $languages, $selectedLanguage);

        return $output;
    }

    /**
     * Renders a single dropdown selector for a given level (type)
     * with all available options from the provided Pages collection.
     * Adds "--selected" class to the currently selected item.
     * Uses the corresponding "isDefault{Type}" field if available.
     */
    private static function renderDropdown(string $type, Kirby\Cms\Pages $options, ?Kirby\Cms\Page $selectedPage): string
    {
        if ($options->isEmpty()) return '';

        $selectedTitle = $selectedPage?->title()->value() ?? 'Select';
        $htmlOptions = '';

        foreach ($options as $item) {
            $title = $item->title()->value();
            $isSelected = $item->id() === $selectedPage?->id() ? ' custom-select__option--selected' : '';
            $label = esc($title);
            $key   = esc($item->slug());
            $htmlOptions .= <<<HTML
                <button type="button" class="custom-select__option{$isSelected}" data-key="{$key}">
                <span class="custom-select__icon" aria-hidden="true">
                    <svg width="8" height="8" viewBox="0 0 8 8" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.12188 0.904525C7.32188 1.10452 7.42187 1.40452 7.22187 1.60452L3.82188 6.90452C3.82188 7.00452 3.62188 7.10453 3.42188 7.10453C3.32188 7.10453 3.12188 7.10452 3.02188 7.00452L0.821875 5.00452C0.621875 4.80452 0.621875 4.50453 0.821875 4.30453C1.02188 4.10453 1.32188 4.10453 1.52188 4.30453L3.32188 5.90452L6.42187 1.10452C6.62187 0.904525 6.92188 0.804525 7.12188 1.00452V0.904525Z" />
                    </svg>
                </span>
                <span class="custom-select__label">{$label}</span>
                </button>
                HTML;
        }

        $label = ucfirst($type);

        return <<<HTML
    <div class="selectors__item">
        <div class="custom-select" data-type="{$type}">
            <button class="custom-select__header custom-select__option">
                <span class="custom-select__label">{$label}</span>
                <span class="custom-select__colon">:</span>
                <span class="custom-select__title">{$selectedTitle}</span>
                <span class="custom-select__caret">
                    <svg width="9" height="6" viewBox="0 0 9 6" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1.09983L4.53722 4.56261L1 1.02539"/>
                    </svg>

                </span>
            </button>
            <div class="custom-select__dropdown" data-type="{$type}">
                {$htmlOptions}
            </div>
        </div>
    </div>
    HTML;
    }

    /**
     * Creates a list of banner size buttons (radio-style toggles).
     * Each button has width/height in `data-` attributes and is visually styled.
     * Used in the "Display Settings" sidebar.
     */
    public static function createRadioButtons(Kirby\Cms\Page $brandPage, ?string $campaignSlug = null, ?string $versionSlug = null, ?string $languageSlug = null): string
    {
        $campaign = self::resolveChildPage($brandPage, $campaignSlug, 'isDefaultCampaign');
        if (!$campaign) return '';

        $version = self::resolveChildPage($campaign, $versionSlug, 'isDefaultVersion');
        if (!$version) return '';

        $language = self::resolveChildPage($version, $languageSlug, 'isDefaultLanguage');
        if (!$language) return '';

        $indexPath = $language->root() . '/bundle/index.html';
        if (!file_exists($indexPath)) return '';

        $html = file_get_contents($indexPath);
        preg_match('/<script[^>]*gwd-served-sizes[^>]*>(.*?)<\/script>/s', $html, $matches);
        if (!isset($matches[1])) return '';

        $sizes = json_decode($matches[1], true);
        if (!is_array($sizes)) return '';

        $output = '';
        foreach ($sizes as $i => $size) {
            [$width, $height] = explode('x', $size);
            $isSelected = $i === 0 ? '--selected' : '';
            $output .= <<<HTML
        <li class="ratio-list__item">
            <button data-width="{$width}" data-height="{$height}" class="ratio-list__button ratio-list__button{$isSelected}">
                <span class="ratio-list__box">
                </span>
                <span class="ratio-list__values">
                {$width} x {$height}
                </span>
            </button>
        </li>
        HTML;
        }

        return $output;
    }


    /**
     * Renders all iframes for the current campaign/version/language selection.
     * Each banner is wrapped in a container with a footer showing the resolution.
     * 
     * The iframe `src` is built using `$_SESSION['state']["path-to-file"]`.
     */
    public static function createBanners(Kirby\Cms\Page $brandPage, ?string $campaignSlug = null, ?string $versionSlug = null, ?string $languageSlug = null): string
    {
        $campaign = self::resolveChildPage($brandPage, $campaignSlug, 'isDefaultCampaign');
        if (!$campaign) return '';

        $version = self::resolveChildPage($campaign, $versionSlug, 'isDefaultVersion');
        if (!$version) return '';

        $language = self::resolveChildPage($version, $languageSlug, 'isDefaultLanguage');
        if (!$language) return '';

        $indexPath = $language->root() . '/bundle/index.html';
        if (!file_exists($indexPath)) return '';

        $html = file_get_contents($indexPath);
        preg_match('/<script[^>]*gwd-served-sizes[^>]*>(.*?)<\/script>/s', $html, $matches);
        if (!isset($matches[1])) return '';

        $sizes = json_decode($matches[1], true);
        if (!is_array($sizes)) return '';

        $slugPath = implode('/', [
            $brandPage->slug(),
            $campaign->slug(),
            $version->slug(),
            $language->slug(),
        ]);

        $iframeSrc = url("bundle-preview/{$slugPath}");

        $output = '';
        foreach ($sizes as $size) {
            [$width, $height] = explode('x', $size);
            $output .= <<<HTML
        <div class="banner">
            <div class="banner__wrapper"> 
                <iframe src="{$iframeSrc}" width="{$width}" height="{$height}" frameborder="0"></iframe>
            </div>
            <div class="banner__footer">
                <p>{$width} x {$height}</p>
            </div>
        </div>
        HTML;
        }

        return $output;
    }

    private static function resolveChildPage(?Kirby\Cms\Page $parent, ?string $slug, string $defaultField): ?Kirby\Cms\Page
    {
        if (!$parent) return null;

        $children = $parent->children()->listed();

        // Only use slug if it exists under this parent
        $page = $slug ? $children->find($slug) : null;

        return $page ?? $children->filterBy($defaultField, 'true')->first() ?? $children->first();
    }
}
