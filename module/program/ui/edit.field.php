<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.edit', 'program');

$isLongTime = data('program.end') && data('program.end') == LONG_TIME;
$fields->field('dateRange')
    ->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => $isLongTime))
    ->beginValue(data('program.begin'))
    ->endValue(data('program.end'))
    ->endDisabled($isLongTime);

$fields->field('acl')
    ->items(data('program.parent') ? $lang->program->subAclList : $lang->program->aclList)
    ->value(data('program.acl'));

$fields->field('whitelist')
    ->hidden(data('program.acl') == 'open');
