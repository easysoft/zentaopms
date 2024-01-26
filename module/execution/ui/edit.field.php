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
            ->label($lang->execution->projectName)
            ->control(array(
                'control'  => 'picker',
                'required' => true,
                'items'    => data('allProjects'),
                'value'    => data('execution.project')
            ));
    }
    elseif($project->model == 'kanban')
    {
        $fields->field('project')
            ->className('hidden')
            ->value(data('execution.project'));
    }
    elseif($project->model == 'agileplus')
    {
        $fields->field('project')
            ->label($lang->execution->method)
            ->disabled()
            ->value(zget($lang->execution->typeList, data('execution.type')));
    }
    elseif($app->tab == 'project' && $project->model == 'waterfallplus')
    {
        $fields->field('parent')
            ->label($lang->programplan->parent)
            ->items(data('parentStageList'))
            ->value(data('execution.parent'));
    }

    if(in_array($project->model, array('waterfall', 'waterfallplus')))
    {
        $fields->field('attribute')
            ->label($lang->stage->type)
            ->wrapAfter()
            ->labelHint($lang->execution->typeTip);

        if(data('enableOptionalAttr'))
        {
            $fields->field('attribute')
                ->required()
                ->items($lang->stage->typeList)
                ->value(data('execution.attribute'));
        }
        else
        {
            $fields->field('attribute')
                ->disabled()
                ->value(zget($lang->stage->typeList, data('execution.attribute')));
        }
    }
    elseif($execution->type != 'kanban' && $project->model != 'ipd')
    {
        $fields->field('lifetime')
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
    ->value(data('execution.name'))
    ->wrapAfter(empty($config->setCode) && $project->model == 'ipd');

if(!empty($config->setCode))
{
    $fields->field('code')
        ->label($lang->execution->code)
        ->value(data('execution.code'))
        ->required(in_array('code', explode(',', $config->execution->edit->requiredFields)))
        ->width('1/4');
}

if($project && $project->model != 'ipd')
{
    $fields->field('status')
        ->required()
        ->label($lang->execution->status)
        ->items($lang->execution->statusList)
        ->value(data('execution.status'))
        ->width(empty($config->setCode) ? '1/2' : '1/4');
}

$fields->field('planDate')
    ->width('1/2')
    ->control('inputGroup')
    ->label($lang->execution->dateRange)
    ->itemBegin('begin')->control('datePicker')->value(data('execution.begin'))->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('end')->control('datePicker')->value(data('execution.end'))->itemEnd();

$hasPercent = data('execution.type') == 'stage' && isset($config->setPercent) && $config->setPercent == 1;
$fields->field('days')
    ->label("{$lang->execution->days} ({$lang->execution->day})")
    ->required(strpos(",{$config->execution->edit->requiredFields},", ",days,") !== false)
    ->value(data('execution.days'))
    ->width($hasPercent ? '1/4' : '1/2');

if($hasPercent)
{
    $fields->field('percent')
        ->required()
        ->label($lang->stage->percent)
        ->control('inputGroup')
        ->itemBegin('percent')->control('input')->value(data('execution.percent'))->itemEnd()
        ->itemBegin()->control('addon')->text('%')->itemEnd()
        ->width('1/4');
}

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
        'isStage'        => isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus'))
    ));

$fields->field('PO')
    ->label($lang->execution->PO)
    ->required(in_array('PO', explode(',', $config->execution->edit->requiredFields)))
    ->items(data('poUsers'))
    ->value(data('execution.PO'));

$fields->field('QD')
    ->label($lang->execution->QD)
    ->required(in_array('QD', explode(',', $config->execution->edit->requiredFields)))
    ->items(data('qdUsers'))
    ->value(data('execution.QD'));

$fields->field('PM')
    ->label($lang->execution->PM)
    ->required(in_array('PM', explode(',', $config->execution->edit->requiredFields)))
    ->items(data('pmUsers'))
    ->value(data('execution.PM'));

$fields->field('RD')
    ->label($lang->execution->RD)
    ->required(in_array('RD', explode(',', $config->execution->edit->requiredFields)))
    ->items(data('rdUsers'))
    ->value(data('execution.RD'));

$fields->field('team')
    ->width('full')
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
    ->required(in_array('desc', explode(',', $config->execution->edit->requiredFields)))
    ->control('editor')
    ->value(data('execution.desc'));

$fields->field('acl')
    ->foldable()
    ->width('full')
    ->control(array(
        'type' => 'aclBox',
        'aclItems' => $lang->execution->aclList,
        'whitelistLabel' => $lang->whitelist,
        'userItems' => data('users')
    ));
