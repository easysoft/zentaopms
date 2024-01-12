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

$fields->field('dateRange')
    ->control('inputGroup')
    ->required()
    ->label($lang->project->dateRange)
    ->itemBegin('begin')->require()->type('datePicker')->value(date('Y-m-d'))->placeholder($lang->project->begin)->itemEnd()
    ->itemBegin()->type('addon')->label($lang->project->to)->text($lang->colon)->itemEnd()
    ->itemBegin('end')->require()->type('datePicker')->placeholder($lang->project->end)->itemEnd();

$fields->field('budget')
    ->control('inputGroup')
    ->item(field('budget'))
    ->checkbox(true)
    ->checkboxProps(array('field' => 'future', 'label' => $lang->project->future))
    ->checked(false);

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control('radioList')
    ->items($lang->project->aclList)
    ->value('open');

$fields->field('whitelist')
    ->width('full')
    ->control('whitelist')
    ->items(data('users'));
