<?php
namespace zin;
global $lang;

$fields = defineFieldList('program');

$fields->field('parent')
    ->required()
    ->items(data('parents'))
    ->value(data('parentProgram.id'));

$fields->field('name')
    ->wrapBefore(true);

$fields->field('PM')
    ->items(data('pmUsers'));

$fields->field('begin');

$fields->field('budget')
    ->control('inputGroup')
    ->label($lang->project->budget . $lang->project->budgetUnit)
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control('radioList');

$fields->field('whitelist')
    ->width('full')
    ->control('whitelist')
    ->items(data('users'));
