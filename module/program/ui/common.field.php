<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('program');

$fields->field('parent')
    ->items(data('parents'))
    ->value(data('parentProgram.id'))
    ->data('parentBegin', data('parentProgram.begin'))
    ->data('parentEnd', data('parentProgram.end'));

$fields->field('name')
    ->wrapBefore(true);

$fields->field('PM')
    ->items(data('pmUsers'));

$fields->field('begin')
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'))
    ->tipClass('text-warning hidden');

$budgetItemList = array();
$budgetUnitList = data('budgetUnitList') ? data('budgetUnitList') : array();
foreach($budgetUnitList as $key => $value)
{
    $budgetItemList[] = array('text' => $value, 'value' => $key, 'url' => "javascript:toggleBudgetUnit('{$key}')");
}

$currency           = data('parentProgram.budgetUnit') ? data('parentProgram.budgetUnit') : $config->project->defaultCurrency;
$budgetDisabled     = (data('parentProgram.budget') !== null && data('parentProgram.budget') == 0) || (data('program.budget') !== null && data('program.budget') == 0);
$budgetUnitDisabled = data('parentProgram.budgetUnit') ? true : false;

$fields->field('budget')
    ->label($lang->project->budget)
    ->checkbox(array('name' => 'future', 'text' => $lang->project->future, 'checked' => $budgetDisabled ? true : false))
    ->control('inputControl', array('control' => 'input', 'name' => 'budget', 'prefix' => array('control' => 'dropdown', 'name' => 'budgetUnit', 'items' => $budgetItemList, 'widget' => true, 'text' => zget($lang->project->currencySymbol, $currency), 'className' => 'btn ghost', 'disabled' => $budgetUnitDisabled), 'prefixWidth' => 34, 'disabled' => $budgetDisabled));

if(data('parentProgram.budget'))
{
    $fields->field('budget')
        ->tip(sprintf($lang->project->budgetOverrun, zget($lang->project->currencySymbol, $currency) . data('parentProgram.budget')))
        ->tipProps(array('id' => 'budgetTip'))
        ->tipClass('text-warning hidden');
}

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control('radioList');

$fields->field('whitelist')
    ->width('full')
    ->control('whitelist')
    ->items(data('users'));
