<?php
namespace zin;
global $lang;

$fields = defineFieldList('program.create', 'program');

$fields->field('name')
    ->wrapBefore(true);

$fields->field('budget')
    ->control('inputGroup')
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future))
    ->item(field('budget')->prefix(data('parentProgram') ? data('parentProgram.budgetUnit') : ''))
    ->item(data('parentProgram') ? field('budgetUnit')->hidden(true)->value(data('parentProgram.budgetUnit')) : field('budgetUnit')->required()->control('picker')->name('budgetUnit')->items(data('budgetUnitList'))->value($config->project->defaultCurrency));

$fields->field('acl')
    ->width('full')
    ->control('radioList')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');
