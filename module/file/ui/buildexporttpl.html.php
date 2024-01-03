<?php
declare(strict_types=1);

namespace zin;

$templateList = array();
foreach($templates as $template) $templateList[] = span(setID("template{$template->id}"), setClass('hidden'), set('data-public', $template->public), set('data-title', $template->title), $template->content);

formGroup
(
    set::label($lang->file->tplTitleAB),
    inputGroup
    (
        picker(set::name('template'), set::items($templatePairs), set::value($templateID), set::required(true), on::change('setTemplate(e.target)')),
        span
        (
            setClass('input-group-addon'),
            checkbox(setID('showCustomFieldsBox'), set::checked(true), on::change('setExportTPL'), $lang->file->setExportTPL)
        )
    ),
    $templateList ? $templateList : null
);

render();
