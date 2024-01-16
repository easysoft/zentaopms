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

$fields->field('begin');

$fields->field('budget')
    ->control('inputGroup')
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future))
    ->itemBegin('budget')->control('input')->prefix(data('parentProgram') ? data('parentProgram.budgetUnit') : '')->prefixWidth(20)->itemEnd()
    ->item(data('parentProgram') ? field('budgetUnit')->hidden(true)->value(data('parentProgram.budgetUnit')) : field('budgetUnit')->required()->control('picker')->name('budgetUnit')->items(data('budgetUnitList'))->value($config->project->defaultCurrency));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control('radioList')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList);

$fields->field('whitelist')
    ->hidden(true)
    ->width('full')
    ->control('whitelist')
    ->items(data('users'));
