<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.edit', 'program');

$isLongTime = data('program.end') && data('program.end') == LONG_TIME;
$fields->field('dateRange')
    ->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => $isLongTime))
    ->controlBegin('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->project->begin)
    ->beginValue(data('program.begin'))
    ->endName('end')
    ->endPlaceholder($lang->project->end)
    ->endValue(data('program.end'))
    ->endDisabled($isLongTime)
    ->endList($lang->execution->endList)
    ->controlEnd()
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'))
    ->tipClass('text-warning hidden');

$fields->field('acl')
    ->items(data('program.parent') ? $lang->program->subAclList : $lang->program->aclList)
    ->value(data('program.acl'));

$fields->field('whitelist')
    ->hidden(data('program.acl') == 'open');
