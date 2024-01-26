<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.edit', 'program');

$isLongTime = data('program.end') && data('program.end') == LONG_TIME;
$fields->field('begin')
    ->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => $isLongTime))
    ->itemBegin('begin')->require()->type('datePicker')->value(data('program.begin'))->placeholder($lang->project->begin)->itemEnd()
    ->itemBegin()->type('addon')->label($lang->project->to)->text($lang->colon)->itemEnd()
    ->itemBegin('end')->require()->type('datePicker')->value(data('program.end'))->placeholder($lang->project->end)->disabled($isLongTime)
    ->menu(array('items' => jsRaw('window.getDateMenu')))
    ->itemEnd();

$fields->field('acl')
    ->items(data('program.parent') ? $lang->program->subAclList : $lang->program->aclList)
    ->value(data('program.acl'));

$fields->field('whitelist')
    ->hidden(data('program.acl') == 'open');
