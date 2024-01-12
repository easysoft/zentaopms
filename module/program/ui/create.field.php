<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.create', 'program');

$fields->field('name')
    ->wrapBefore(true);

$fields->field('budget')
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future));

$fields->field('acl')
    ->width('full')
    ->control('radioList')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');
