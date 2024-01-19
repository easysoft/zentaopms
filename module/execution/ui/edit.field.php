<?php
namespace zin;

global $app, $lang, $config;

$fields = defineFieldList('execution.edit');

$project         = data('project');
$execution       = data('execution');
$productBranches = data('product.branches');
$linkedPlans     = data('product.plans');
$linkedProducts  = data('linkedProducts');
$branchGroups    = data('branchGroups');
$productPlans    = data('productPlans');
$hidden          = empty($project->hasProduct);

if($project)
{
    if($project->model == 'scrum')
    {
        $fields->field('project')
            ->width('1/2')
            ->label($lang->execution->projectName)
            ->items(data('allProjects'))
            ->value(data('execution.project'))
            ->wrapAfter(empty($config->setCode));
    }
    elseif($project->model == 'kanban')
    {
        $fields->field('project')
            ->type('hidden')
            ->value(data('execution.project'));
        $projectBox = formHidden('project', $execution->project);
    }
    elseif($project->model == 'agileplus')
    {
        $fields->field('project')
            ->label($lang->execution->method)
            ->disabled()
            ->value(zget($lang->execution->typeList, data('execution.project')));
    }
    elseif($app->tab == 'project' && $project->model == 'waterfallplus')
    {
        $fields->field('parent')
            ->width('1/2')
            ->label($lang->programplan->parent)
            ->items(data('parentStageList'))
            ->value(data('execution.parent'));
    }

    if(in_array($project->model, array('waterfall', 'waterfallplus')))
    {
        $fields->field('attribute')
            ->label($lang->stage->type)
            ->labelHint($lang->execution->typeTip);

        if(data('enableOptionalAttr'))
        {
            $fields->field('attribute')
                ->width('1/2')
                ->required()
                ->items($lang->stage->typeList)
                ->value(data('execution.attribute'));
        }
        else
        {
            $fields->field('attribute')
                ->width('1/8')
                ->disabled()
                ->value(zget($lang->stage->typeList, data('execution.attribute')));
        }
    }
    elseif($execution->type != 'kanban' && $project->model != 'ipd')
    {
        $fields->field('lifetime')
            ->width('1/2')
            ->required()
            ->id('lifetime')
            ->label($lang->execution->type)
            ->items($lang->execution->lifeTimeList)
            ->value(data('execution.lifetime'));
    }
}

$fields->field('name')
    ->required()
    ->label($lang->execution->name)
    ->value(data('execution.name'));

if(!empty($config->setCode))
{
    $fields->field('code')
        ->label($lang->execution->code)
        ->value(data('execution.code'))
        ->required()
        ->width('1/2');
}

$fields->field('planDate')
    ->width('1/2')
    ->control('inputGroup')
    ->label($lang->execution->dateRange)
    ->itemBegin('begin')->control('datePicker')->value(data('execution.begin'))->itemEnd()
    ->itemBegin()->control('addon')->text($lang->project->to)->itemEnd()
    ->itemBegin('end')->control('datePicker')->value(data('execution.end'))->itemEnd();

$fields->field('days')
    ->label("{$lang->execution->days} ({$lang->execution->day})")
    ->required(strpos(",{$config->execution->edit->requiredFields},", ",days,") !== false)
    ->value(data('execution.days'))
    ->width('1/2');

$fields->field('productsBox')
    ->width('full')
    ->control(array(
        'control'        => 'productsBox',
        'productItems'   => data('allProducts'),
        'branchGroups'   => data('branchGroups'),
        'planGroups'     => data('productPlans'),
        'linkedProducts' => data('linkedProducts'),
        'linkedBranches' => data('linkedBranches'),
        'productPlans'   => data('productPlans'),
        'project'        => data('project'),
        'isStage'        => data('isStage')
    ));

if(data('execution.type') == 'stage' && isset($config->setPercent) && $config->setPercent == 1)
{
    $fields->field('percent')
        ->required()
        ->label($lang->stage->percent)
        ->value(data('execution.percent'))
        ->width('1/2');
}

$fields->field('PO')
    ->label($lang->execution->PO)
    ->items(data('poUsers'))
    ->value(data('execution.PO'));

$fields->field('QD')
    ->label($lang->execution->QD)
    ->items(data('qdUsers'))
    ->value(data('execution.QD'));

$fields->field('PM')
    ->label($lang->execution->PM)
    ->items(data('pmUsers'))
    ->value(data('execution.PM'));

$fields->field('RD')
    ->label($lang->execution->RD)
    ->items(data('rdUsers'))
    ->value(data('execution.RD'));

if($project && $project->model != 'ipd')
{
    $fields->field('status')
        ->required()
        ->label($lang->execution->status)
        ->items($lang->execution->statusList)
        ->value(data('execution.status'))
        ->width('1/2');
}

$fields->field('team')
    ->label($lang->execution->teamName)
    ->value(data('execution.team'));

$teamMembers = data('teamMembers');
$fields->field('teamMembers')
    ->width('full')
    ->name('teamMembers[]')
    ->label($lang->execution->team)
    ->multiple()
    ->items(data('users'))
    ->value($teamMembers ? array_keys($teamMembers) : array());

$fields->field('desc')
    ->width('full')
    ->control('editor')
    ->value(data('execution.desc'));

$fields->field('acl')
    ->foldable()
    ->width('full')
    ->control(array(
        'type' => 'aclBox',
        'aclItems' => $lang->execution->aclList,
        'whitelistLabel' => $lang->whitelist,
        'userLabel' => $lang->product->users,
        'userItems' => data('users')
    ));
