<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.edit', 'program');

$fields->field('begin')
    ->control('inputGroup')
    ->required()
    ->label($lang->project->dateRange)
    ->itemBegin('begin')->require()->type('datePicker')->value(data('program.begin'))->placeholder($lang->project->begin)->itemEnd()
    ->itemBegin()->type('addon')->label($lang->project->to)->text($lang->colon)->itemEnd()
    ->itemBegin('end')->require()->type('datePicker')->value(data('program.end'))->placeholder($lang->project->end)->itemEnd();

$fields->field('acl')
    ->items(data('program.parent') ? $lang->program->subAclList : $lang->program->aclList)
    ->value(data('program.acl'));

$fields->field('whitelist')
    ->hidden(data('program.acl') == 'open');
