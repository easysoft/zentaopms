<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('program.create', 'program');

$fields->field('begin')
    ->itemBegin('begin')->require()->type('datePicker')->value(date('Y-m-d'))->placeholder($lang->project->begin)->itemEnd()
    ->itemBegin()->type('addon')->label($lang->project->to)->text($lang->colon)->itemEnd()
    ->itemBegin('end')->require()->type('datePicker')->placeholder($lang->project->end)
    ->menu(array('items' => jsRaw('window.getDateMenu')))
    ->itemEnd();

$fields->field('acl')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');
