<?php
declare(strict_types=1);

namespace zin;

h::globalJS(<<< JAVASCRIPT
requestAnimationFrame(() =>
{
    setTimeout(() =>
    {
        /* Prevent unnecessary refresh. */
        const currentURL = new URL($.apps.getLastApp().currentUrl, location.href);
        const formURL    = new URL(`$formLocation`, location.href);
        if(currentURL.href === formURL.href) return;

        openUrl(`{$formLocation}`);
    }, 1000);
});
JAVASCRIPT);
