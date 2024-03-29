<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('program');

$fields->field('parent')
    ->control(array('control' => 'picker', 'required' => true))
    ->items(data('parents'))
    ->value(data('parentProgram.id') ? data('parentProgram.id') : 0)
    ->disabled(!empty(data('parentProgram.id')))
    ->data('parentBudget', data('parentProgram.budget'))
    ->data('parentBegin', data('parentProgram.begin'))
    ->data('parentEnd', data('parentProgram.end'));

$fields->field('name')
    ->wrapBefore(true);

$fields->field('PM')
    ->items(data('pmUsers'));

$fields->field('dateRange')
    ->required()
    ->label($lang->project->dateRange)
    ->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime'))
    ->controlBegin('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->project->begin)
    ->beginValue(date('Y-m-d'))
    ->endName('end')
    ->endPlaceholder($lang->project->end)
    ->endValue('')
    ->endList($lang->execution->endList)
    ->controlEnd()
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'))
    ->tipClass('text-warning hidden');

$budgetItemList = array();
$budgetUnitList = data('budgetUnitList') ? data('budgetUnitList') : array();
foreach($budgetUnitList as $key => $value)
{
    $budgetItemList[] = array('text' => $value, 'value' => $key, 'url' => "javascript:toggleBudgetUnit('{$key}')");
}

$currency           = data('parentProgram.budgetUnit') ? data('parentProgram.budgetUnit') : (data('program.budgetUnit') ? data('program.budgetUnit') : $config->project->defaultCurrency);
$budgetDisabled     = (data('parentProgram.budget') !== null && empty(data('parentProgram.budget'))) || (data('program.budget') !== null && empty(data('program.budget')));
$budgetUnitDisabled = data('parentProgram.budgetUnit') ? true : false;

$fields->field('budget')
    ->label($lang->project->budget)
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future, 'checked' => $budgetDisabled ? true : false))
    ->control('inputControl', array('control' => 'input', 'name' => 'budget', 'value' => (data('program.budget') !== null && data('program.budget') != 0 ? data('program.budget') : ''), 'prefix' => array('control' => 'dropdown', 'name' => 'budgetUnit', 'items' => $budgetItemList, 'widget' => true, 'text' => zget($lang->project->currencySymbol, $currency), 'className' => 'btn ghost', 'disabled' => $budgetUnitDisabled), 'prefixWidth' => 34, 'disabled' => $budgetDisabled));

if(data('parentProgram.budget'))
{
    $fields->field('budget')
        ->tip(' ')
        ->tipProps(array('id' => 'budgetTip'))
        ->tipClass('text-warning hidden');
}

$fields->field('budgetUnit')->control('hidden')->value($currency);

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control('radioList');

$fields->field('whitelist')
    ->width('full')
    ->control('whiteList')
    ->items(data('users'))
    ->hidden(true);
