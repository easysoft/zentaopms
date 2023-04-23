<?php

namespace zin;

set::itemID($project->id);
set::title($project->name);

form
(
    set::url(createLink('project', 'close', ['projectID' => $project->id])),
    formGroup
    (
        set::label($app->loadLang('program')->program->realEnd),
        set::required(true),
        set::name('realEnd'),
        set::control('date'),
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control(['type' => 'textarea', 'rows' => 5]),
    ),
    set::submitBtnText($lang->project->close)
);

h::hr(setClass('my-5'));

historyRecord();

render('modalDialog');
