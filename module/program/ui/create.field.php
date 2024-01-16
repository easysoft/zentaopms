<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.create', 'program');

$fields->field('begin')
    ->control('inputGroup')
    ->required()
    ->label($lang->project->dateRange)
    ->itemBegin('begin')->require()->type('datePicker')->value(date('Y-m-d'))->placeholder($lang->project->begin)->itemEnd()
    ->itemBegin()->type('addon')->label($lang->project->to)->text($lang->colon)->itemEnd()
    ->itemBegin('end')->require()->type('datePicker')->placeholder($lang->project->end)->itemEnd();

$fields->field('budget')
    ->itemBegin('budget')->control('input')->prefix(data('parentProgram') ? data('parentProgram.budgetUnit') : '')->prefixWidth(20)->itemEnd()
    ->item(data('parentProgram') ? field('budgetUnit')->hidden(true)->value(data('parentProgram.budgetUnit')) : field('budgetUnit')->required()->control('picker')->name('budgetUnit')->items(data('budgetUnitList'))->value($config->project->defaultCurrency));

$fields->field('acl')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');

$fields->field('whitelist')
    ->hidden(true);
