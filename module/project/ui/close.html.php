<?php

namespace zin;

global $lang;
global $app;

set::itemID($project->id);
set::title($project->name);

h::hr(setClass('mb-5'));

form
(
    set::url(createLink('project', 'close', ['onlybody' => 'yes', 'projectID' => $project->id])),
    formGroup
    (
        set::label($app->loadLang('program')->program->realEnd),
        set::required(true),
        set::name('realEnd'),
        set::control(['type' => 'date']),
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control(['type' => 'textarea', 'rows' => 5]),
    ),
    set::actions
    (
        [
            ['text' => $lang->project->close, 'type' => 'primary', 'btnType' => 'submit']
        ]
    )
);

h::hr(setClass('my-5'));

historyRecord();

render('modalDialog');
