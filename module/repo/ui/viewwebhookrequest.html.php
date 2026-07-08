<?php
declare(strict_types=1);
namespace zin;

modalHeader
(
    set::title($lang->repo->viewWebhookRequest),
    set::titleClass('panel-title text-lg')
);

$requestBody = json_decode(zget($execLog, 'reqBody', ''));
$requestBody = json_encode($requestBody, JSON_PRETTY_PRINT);
h::css('.codeBody {background: #eee; border: 1px solid #aaa; padding: 0.4em 0.8em; font-size: 12px; overflow: auto; color: #000; margin: 12px 0;}');

div
(
    div
    (
        h5($lang->repo->webhook->requestURL),
        h::pre(setClass('codeBody'), h::code(zget($execLog, 'reqUrl', '')))
    ),
    div
    (
        h5($lang->repo->webhook->triggerType),
        h::pre(setClass('codeBody'), h::code(zget($lang->repo->webhook->customEventList, zget($execLog, 'triggerType', ''))))
    ),
    div
    (
        h5($lang->repo->webhook->requestHeaders),
        h::pre(setClass('codeBody'), h::code(zget($execLog, 'reqHeaders', '')))
    ),
    div
    (
        h5($lang->repo->webhook->requestBody),
        h::pre(setClass('codeBody'), h::code($requestBody))
    ),
    div
    (
        h5($lang->repo->webhook->responseHeaders),
        h::pre(setClass('codeBody'), h::code(zget($execLog, 'respHeaders', '') ? zget($execLog, 'respHeaders', '') : $lang->repo->webhook->emptyData))
    ),
    div
    (
        h5($lang->repo->webhook->responseBody),
        h::pre(setClass('codeBody'), h::code(zget($execLog, 'respBody', '') ? zget($execLog, 'respBody', '') : $lang->repo->webhook->emptyData))
    )
);
