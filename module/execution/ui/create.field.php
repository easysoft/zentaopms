<?php
namespace zin;
global $lang, $config, $app;

$app->loadlang('stage');
$fields   = defineFieldList('execution.create');
$project  = data('project');
$from     = data('from');
$isStage  = data('isStage');
$isKanban = data('isKanban');
$isIPD    = isset($project->model) && $project->model == 'ipd' && $isStage;

$showExecutionExec = ($from == 'execution' || $from == 'doc');
$requiredFields    = ",{$config->execution->create->requiredFields},";

$fields->field('project')
    ->required()
    ->control('picker')
    ->label($lang->execution->projectName)
    ->items(data('allProjects'))
    ->value(data('projectID'));

if(!empty($project) && in_array($project->model, $config->project->waterfallList))
{
    $fields->field('parent')
        ->wrapBefore(true)
        ->required()
        ->control('picker')
        ->label($lang->execution->parentStage)
        ->items(data('parentStages'))
        ->value(data('parentStage'));
}

if(!empty($project->model) && in_array($project->model, array('agileplus', 'ipd', 'waterfallplus')))
{
    unset($lang->execution->typeList['']);
    if($project->model == 'agileplus') unset($lang->execution->typeList['stage']);

    $fields->field('method')
        ->required()
        ->name('type')
        ->label($lang->execution->method)
        ->labelHint($lang->execution->agileplusMethodTip)
        ->items($lang->execution->typeList);
}

$fields->field('name')
    ->wrapBefore(true)
    ->required()
    ->label($showExecutionExec ? $lang->execution->execName : $lang->execution->name)
    ->value(data('execution.name'));

$fields->field('code')
    ->required(strpos($requiredFields, ",code,") !== false)
    ->label($showExecutionExec ? $lang->execution->execCode : $lang->execution->code)
    ->value(data('execution.code'));

$fields->field('type')
    ->required()
    ->labelHint($isStage ? $lang->execution->typeTip : '')
    ->control($isStage ? 'picker' : 'checkBtnGroup')
    ->label($showExecutionExec ? $lang->execution->execType : $lang->execution->type)
    ->name($isStage ? 'attribute' : 'lifetime')
    ->hidden($isKanban || $isIPD)
    ->value($isStage ? 'dev' : 'short')
    ->items($isStage ? $lang->stage->typeList : $lang->execution->lifeTimeList);

$plan = data('plan');
if(!empty($project->isTpl))
{
    if(empty($plan)) $plan = new stdclass();
    $plan->begin = $project->begin;
    $plan->end   = $project->end;
}

$fields->field('dateRange')
    ->hidden(!empty($project->isTpl))
    ->required()
    ->control('inputGroup')
    ->itemBegin('dateRangePicker')
    ->control('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->execution->begin)
    ->beginValue(empty($plan->begin) ? date('Y-m-d') : $plan->begin)
    ->endName('end')
    ->endPlaceholder($lang->execution->end)
    ->endValue(empty($plan->end) ? '' : $plan->end)
    ->endList($lang->execution->endList)
    ->itemEnd();

$fields->field('days')
    ->hidden(!empty($project->isTpl))
    ->width('1/4')
    ->required(strpos($requiredFields, ",days,") !== false)
    ->label($lang->execution->days . sprintf($lang->execution->unitTemplate, $lang->execution->day))
    ->items(false)
    ->value(!empty($plan->begin) ? (helper::workDays($plan->begin, $plan->end) + 1) : '');

$fields->field('percent')
    ->width('1/4')
    ->label($lang->stage->percent . sprintf($lang->execution->unitTemplate, '%'));

$fields->field('productsBox')
    ->id('productsBox')
    ->width(!empty($project->hasProduct) ? 'full' : '1/2')
    ->control(array
    (
        'control'           => 'productsBox',
        'productItems'      => data('allProducts'),
        'branchGroups'      => data('branchGroups'),
        'planGroups'        => data('productPlans'),
        'linkedProducts'    => data('products'),
        'linkedBranches'    => data('linkedBranches'),
        'currentProduct'    => data('productID'),
        'currentPlan'       => data('planID'),
        'productPlans'      => data('productPlan'),
        'project'           => data('project'),
        'isStage'           => data('isStage'),
        'errorSameProducts' => $lang->execution->errorSameProducts,
        'from'              => 'execution'
    ));

$fields->field('desc')
    ->width('full')
    ->required(strpos($requiredFields, ",desc,") !== false)
    ->label($showExecutionExec ? $lang->execution->execDesc : $lang->execution->desc)
    ->control(array('control' => 'editor', 'templateType' => 'execution'));

$fields->field('PO')->foldable()->required(strpos($requiredFields, ",PO,") !== false)->control(array('control' => 'picker', 'required' => false))->items(data('poUsers'))->value(data('execution.PO'));
$fields->field('QD')->foldable()->required(strpos($requiredFields, ",QD,") !== false)->control(array('control' => 'picker', 'required' => false))->items(data('qdUsers'))->value(data('execution.QD'));
$fields->field('PM')->foldable()->required(strpos($requiredFields, ",PM,") !== false)->control(array('control' => 'picker', 'required' => false))->items(data('pmUsers'))->value(data('execution.PM'))->label($showExecutionExec ? $lang->execution->execPM : $lang->execution->PM);
$fields->field('RD')->foldable()->required(strpos($requiredFields, ",RD,") !== false)->control(array('control' => 'picker', 'required' => false))->items(data('rdUsers'))->value(data('execution.RD'));

$fields->field('teamName')
    ->foldable()
    ->width('full')
    ->label($lang->execution->teamName)
    ->name('team')
    ->checkbox(array('text' => $lang->execution->copyTeam, 'id' => 'copyTeam'))
    ->value(data('execution.team'));

$fields->field('teams')
    ->foldable()
    ->hidden(true)
    ->label($lang->execution->copyTeam)
    ->name('teams')
    ->items(data('teams'))
    ->set('data-placeholder', $lang->execution->copyTeamTip);

$fields->field('teamMembers')
    ->width('full')
    ->foldable()
    ->label($lang->execution->team)
    ->name('teamMembers[]')
    ->items(data('users'))
    ->multiple();

$fields->field('acl')
    ->foldable()
    ->width('full')
    ->control(array('control' => 'aclBox', 'aclItems' => $lang->execution->aclList, 'aclValue' => 'open', 'whitelistLabel' => $lang->whitelist));
