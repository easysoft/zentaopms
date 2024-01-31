<?php
declare(strict_types=1);
namespace zin;
global $lang, $config, $app;

$app->loadLang('program');
$fields = defineFieldList('project');

$model          = data('model');
$hasCode        = !empty($config->setCode);
$currency       = data('parentProgram') ? data('parentProgram.budgetUnit') : $config->project->defaultCurrency;
$disableStageBy = !empty(data('executions')) || data('app.rawMethod') == 'edit' ? true : false;

$fields->field('parent')
    ->control('picker', array('required' => true))
    ->labelHint($lang->program->tips)
    ->hidden(data('globalDisableProgram'))
    ->items(data('programList'));

$fields->field('model')->control('hidden')->value($model);

if(!$hasCode)
{
    $fields->field('hasProduct')
        ->label($lang->project->category)
        ->control('checkBtnGroup')
        ->items($lang->project->projectTypeList);
}

$fields->field('name')
    ->wrapBefore()
    ->required()
    ->control('input');

if($hasCode)
{
    $fields->field('hasProduct')
        ->control('checkBtnGroup')
        ->label($lang->project->category)
        ->items($lang->project->projectTypeList);

    $fields->field('code')->control('input');
}

$fields->field('PM')->control('picker')->items(data('PMUsers'));

$isLongTime = data('project.end') == LONG_TIME;
$fields->field('begin')
    ->label($lang->project->planDate)
    ->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => $isLongTime))
    ->required()
    ->controlBegin('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->project->begin)
    ->beginValue(data('project.begin') ? data('project.begin') : date('Y-m-d'))
    ->endName('end')
    ->endPlaceholder($lang->project->end)
    ->endValue(data('project.end') == LONG_TIME ? '' : data('project.end'))
    ->endDisabled($isLongTime)
    ->endList($lang->project->endList)
    ->controlEnd()
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'))
    ->tipClass('text-warning' . (data('copyProject') ? '' : ' hidden'));

$fields->field('days')->label($lang->project->days . $lang->project->daysUnit)->control('input')->disabled($isLongTime)->value(!empty(data('project.days')) ? data('project.days') : '');

$fields->field('productsBox')
    ->width('full')
    ->required(data('copyProject.parent') || data('parentProgram.id') || data('project.parent'))
    ->control(array
    (
        'control'           => 'productsBox',
        'productItems'      => data('allProducts'),
        'branchGroups'      => data('branchGroups'),
        'planGroups'        => data('productPlans'),
        'productPlans'      => data('productPlans'),
        'linkedProducts'    => data('linkedProducts'),
        'linkedBranches'    => data('linkedBranches'),
        'project'           => data('project') ? data('project') : data('copyProject'),
        'hasNewProduct'     => data('app.rawMethod') == 'create',
        'isStage'           => data('isStage'),
        'errorSameProducts' => $lang->project->errorSameProducts,
    ));

if($model == 'waterfall' || $model == 'waterfallplus')
{
    $fields->field('stageBy')
        ->className('stageByBox', data('linkedProducts') && count(data('linkedProducts')) > 1 ? '' : 'hidden')
        ->control('radioListInline')
        ->labelHint($lang->project->stageByTips)
        ->label($lang->project->stageBy)
        ->value(data('copyProject') ? data('copyProject.stageBy') : (data('project') ? data('project.stageBy') : 'project'))
        ->disabled($disableStageBy)
        ->items($lang->project->stageByList);
}

$fields->field('desc')
    ->width('full')
    ->control('editor');

$budgetFuture   = data('project.budget') !== null && !data('project.budget');
$budgetItemList = array();
$budgetUnitList = data('budgetUnitList') ? data('budgetUnitList') : array();
foreach($budgetUnitList as $key => $value)
{
    $budgetItemList[] = array('text' => $value, 'value' => $key, 'url' => "javascript:toggleBudgetUnit('{$key}')");
}

$budgetHidden = isset($config->project->{$app->rawMethod}->requiredFields) && strpos($config->project->{$app->rawMethod}->requiredFields, 'budget') !== false;
$budgetFuture = data('project.budget') !== null && !data('project.budget') && !$budgetHidden;
$fields->field('budget')
    ->label($lang->project->budget)
    ->foldable()
    ->control('inputControl', array('control' => 'input', 'name' => 'budget', 'prefix' => array('control' => 'dropdown', 'name' => 'budgetUnit', 'items' => $budgetItemList, 'widget' => true, 'text' => zget($lang->project->currencySymbol, data('project.budgetUnit') ? data('project.budgetUnit') : $currency), 'className' => 'btn ghost' . ($budgetFuture || data('parentProgram') ? ' disabled pointer-events-none' : '')), 'prefixWidth' => 34, 'disabled' => $budgetFuture))
    ->placeholder(data('parentProgram') && !empty(data('parentProgram.budget')) ? $lang->project->parentBudget . zget($lang->project->currencySymbol, $currency) . data('parentProgram.budget') : '')
    ->tip(' ')
    ->tipProps(array('id' => 'budgetTip'))
    ->tipClass('text-danger');
if(!$budgetHidden) $fields->field('budget')->checkbox(array('text' => $lang->project->future, 'name' => 'future', 'checked' => $budgetFuture));

$fields->field('budgetUnit')->control('hidden')->value($currency);

$fields->field('acl')
    ->width('full')
    ->wrapBefore();

$fields->field('auth')
    ->width('full')
    ->wrapBefore()
    ->control('radioList')
    ->items($lang->project->authList);
