<?php

function enforceOnlyOneDefault(Kirby\Cms\Page $newPage, Kirby\Cms\Page $oldPage): void
{
    $template = $newPage->intendedTemplate()->name();

    $defaultFields = [
        'campaign' => 'isDefaultCampaign',
        'version'  => 'isDefaultVersion',
        'language' => 'isDefaultLanguage',
    ];

    if (!isset($defaultFields[$template])) {
        error_log("Hook skipped: template '$template' is not in defaultFields.");
        return;
    }

    $field = $defaultFields[$template];

    $newValue = $newPage->$field()->value();
    $oldValue = $oldPage->$field()->value();

    error_log("[$template] $field changed from '$oldValue' to '$newValue' on " . $newPage->id());

    // Only react when toggle is switched from false → true
    if ($newValue !== 'true' || $oldValue === 'true') {
        error_log("No action: toggle wasn't set to 'true'.");
        return;
    }

    foreach ($newPage->siblings()->listed() as $sibling) {
        if ($sibling->id() === $newPage->id()) continue;

        if ($sibling->$field()->value() === 'true') {
            error_log("Unsetting '$field' on sibling: " . $sibling->id());

            $result = $sibling->update([
                $field => false
            ]);

            error_log("Update result: " . print_r($result, true));
        }
    }
}
