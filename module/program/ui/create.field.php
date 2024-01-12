<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.create', 'program');

$fields->field('budget')
    ->control('inputGroup')
    ->label($lang->project->budget)
    ->item(field('budget'))
    ->checkbox(true)
    ->checkboxProps(array('field' => 'future', 'label' => $lang->project->future))
    ->checked(false);

$fields->field('acl')
    ->width('full')
    ->control('radioList')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');
