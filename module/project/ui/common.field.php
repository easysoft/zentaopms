<?php
declare(strict_types=1);
namespace zin;
global $lang, $config, $app;

$app->loadLang('program');
$fields = defineFieldList('project');

$model          = data('model');
$hasCode        = !empty($config->setCode);
$currency       = data('parentProgram') ? data('parentProgram.budgetUnit') : $config->project->defaultCurrency;
$disableStageBy = !empty(data('executions')) ? true : false;

$fields->field('parent')
    ->control('picker')
    ->required()
    ->labelHint($lang->program->tips)
    ->hidden(data('globalDisableProgram'))
    ->items(data('programList'));

$fields->field('model')->control('hidden')->value($model);

if(!$hasCode)
{
    $fields->field('hasProduct')
        ->label($lang->project->category)
        ->control('radioListInline')
        ->items($lang->project->projectTypeList);
}

$fields->field('name')
    ->wrapBefore()
    ->required()
    ->control('input');

if($hasCode)
{
    $fields->field('hasProduct')
        ->control('radioListInline')
        ->label($lang->project->category)
        ->items($lang->project->projectTypeList);

    $fields->field('code')->control('input')->required();
}

$fields->field('PM')->control('picker')->items(data('PMUsers'));

$fields->field('begin')
    ->required()
    ->control('inputGroup')
    ->itemBegin('begin')->control('datePicker')->placeholder($lang->project->begin)->value(data('project.begin') ? data('project.begin') : date('Y-m-d'))->required(true)->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('end')->control('datePicker')->placeholder($lang->project->end)->required(true)->value(data('project.end'))->itemEnd();

$fields->field('days')->label($lang->project->days . $lang->project->daysUnit)->control('input');

$fields->field('productsBox')
    ->width('full')
    ->control(array
    (
        'control'        => 'productsBox',
        'productItems'   => data('allProducts'),
        'branchGroups'   => data('branchGroups'),
        'planGroups'     => data('productPlans'),
        'linkedProducts' => data('linkedProducts'),
        'linkedBranches' => data('linkedBranches'),
        'project'        => data('project') ? data('project') : data('copyProject'),
        'isStage'        => data('isStage')
    ));

if($model == 'waterfall' || $model == 'waterfallplus')
{
    $fields->field('stageBy')
        ->control('radioListInline')
        ->labelHint($lang->project->stageByTips)
        ->label($lang->project->stageBy)
        ->value('project')
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

$fields->field('budget')
    ->label($lang->project->budget . $lang->project->budgetUnit)
    ->foldable()
    ->checkbox(array('text' => $lang->project->future, 'name' => 'future', 'checked' => $budgetFuture))
    ->control('inputControl', array('control' => 'input', 'name' => 'budget', 'prefix' => array('control' => 'dropdown', 'name' => 'budgetUnit', 'items' => $budgetItemList, 'widget' => true, 'text' => zget($lang->project->currencySymbol, data('project.budgetUnit') ? data('project.budgetUnit') : $currency), 'className' => 'btn ghost' . ($budgetFuture ? ' disabled pointer-events-none' : '')), 'prefixWidth' => 34, 'disabled' => $budgetFuture))
    ->tip(sprintf($lang->project->budgetOverrun, zget($lang->project->currencySymbol, $currency) . data('program.budget')))
    ->tipProps(array('id' => 'budgetTip'))
    ->tipClass('text-warning hidden');

$fields->field('budgetUnit')->control('hidden')->value($currency);

$fields->field('acl')
    ->width('full')
    ->wrapBefore();

$fields->field('auth')
    ->width('full')
    ->wrapBefore()
    ->control('radioList')
    ->items($lang->project->authList);
