<?php
declare(strict_types=1);

namespace zin;

h::globalJS(<<< JAVASCRIPT
requestAnimationFrame(() =>
{
    setTimeout(() =>
    {
        const currentURL = new URL($.apps.getLastApp().currentUrl, location.href);
        const formURL    = new URL(`$formLocation`, location.href);

        /* Prevent unnecessary refresh. */
        const isRegen = window.sessionStorage.getItem('ai-prompt-regen') === 'true';
        if(currentURL.href === formURL.href && !isRegen) return;

        /* Go back to prompts page when entering audit, prevent showing a blank page. */
        if($.apps.currentCode == 'admin') goBack();

        openUrl(`{$formLocation}`);
    }, 1000);
});
JAVASCRIPT);
