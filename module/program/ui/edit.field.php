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

$fields->field('budget')
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future, 'checked' => data('program.budget') == 0 ? true : false))
    ->itemBegin('budget')->control('input')->value(data('program.budget'))->prefix(data('parentProgram') ? data('parentProgram.budgetUnit') : '')->prefixWidth(20)->itemEnd()
    ->item(data('program.parent') ? field('budgetUnit')->hidden(true)->value(data('parentProgram.budgetUnit')) : field('budgetUnit')->required()->control('picker')->name('budgetUnit')->items(data('budgetUnitList'))->value($config->project->defaultCurrency));
    //->item('syncPRJUnit')->hidden()->value('false')
    //->item('exchangeRate')->hidden()->value('');

$fields->field('acl')
    ->items(data('program.parent') ? $lang->program->subAclList : $lang->program->aclList);

$fields->field('whitelist')
    ->hidden(data('program.acl') == 'open');
