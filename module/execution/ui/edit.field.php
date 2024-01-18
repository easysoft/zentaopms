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

if($project->model != 'waterfall' && $project->model != 'waterfallplus')
{
    if(!empty($project->hasProduct) && $linkedProducts)
    {
        $i = 0;
        foreach($linkedProducts as $product)
        {
            $plans     = isset($productPlans[$product->id]) ? $productPlans[$product->id] : array();
            $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();
            $hasBranch = $product->type != 'normal' && !empty($branches);
            $fields->field("products[{$i}]")
                ->width($hasBranch ? '1/4' : '1/2')
                ->className($hidden ? 'hidden' : '')
                ->label($i == 0 ? $lang->project->manageProducts : '')
                ->items(data('allProducts'))
                ->value($product->id);

            $fields->field("branch[{$i}][]")
                ->width('1/4')
                ->multiple()
                ->className($hidden || !$hasBranch ? 'hidden' : '')
                ->label($i == 0 ? $lang->product->branchName['branch'] : '')
                ->items($branches)
                ->value($productBranches ? implode(',', $productBranches) : array());

            $fields->field("plans[$product->id][]")
                ->width('1/2')
                ->multiple()
                ->label($lang->project->associatePlan)
                ->items($plans)
                ->value($linkedPlans ? implode(',', $linkedPlans) : array());

            $i ++;
        }
    }
    elseif(empty($project->hasProduct))
    {
        $planProductID = current(array_keys($linkedProducts));
        $fields->field("plans[$planProductID][]")
            ->width('1/2')
            ->multiple()
            ->label($lang->execution->linkPlan)
            ->items(isset($productPlans[$planProductID]) ? $productPlans[$planProductID] : array())
            ->value(isset($linkedProducts[$planProductID]) ? $linkedProducts[$planProductID]->plans : '');

        $fields->field('products[0]')->type('hidden')->value($planProductID);
        $fields->field('branch[0]')->type('hidden')->value('');
    }
    else
    {
        $fields->field('products[0]')
            ->width('1/2')
            ->className($hidden ? 'hidden' : '')
            ->label($lang->project->manageProducts)
            ->items(data('allProducts'))
            ->value('');

        $fields->field('branch[0][]')
            ->width('1/4')
            ->className('hidden')
            ->multiple()
            ->label($lang->product->branchName['branch'])
            ->items(array())
            ->value('');

        $fields->field('plans[0][]')
            ->width('1/2')
            ->multiple()
            ->label($lang->project->associatePlan)
            ->items(array())
            ->value('');
    }
}
elseif(!empty($project->hasProduct))
{
    $i = 0;
    foreach($linkedProducts as $product)
    {
        $plans     = isset($productPlans[$product->id]) ? $productPlans[$product->id] : array();
        $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();
        $hasBranch = $product->type != 'normal' && !empty($branches);
        $fields->field("products[{$i}]")
            ->width($hasBranch ? '1/4' : '1/2')
            ->className($hidden ? 'hidden' : '')
            ->label($i == 0 ? $lang->project->manageProducts : '')
            ->items(data('allProducts'))
            ->value(data('product.id'))
            ->disabled(in_array($project->model, array('waterfall', 'waterfallplus')))
            ->required(in_array($project->model, array('waterfall', 'waterfallplus')));

        $fields->field("branch[{$i}][]")
            ->width('1/4')
            ->multiple()
            ->className($hidden || !$hasBranch ? 'hidden' : '')
            ->label($i == 0 ? $lang->product->branchName['branch'] : '')
            ->disabled($project->model == 'waterfall' || $project->model == 'waterfallplus')
            ->items($branches)
            ->value($productBranches ? implode(',', $productBranches) : '0');

        $fields->field("plans[$product->id][]")
            ->width('1/2')
            ->multiple()
            ->label($lang->project->associatePlan)
            ->items($plans)
            ->value($linkedPlans ? implode(',', $linkedPlans) : array());

        $i ++;
    }

    if(empty($linkedProducts))
    {
        $fields->field('products[0]')->type('hidden')->value(array());
        $fields->field('branch[][]')->type('hidden')->items(array())->value('')->multiple();
    }
}
else
{
    $fields->field('products[0]')->type('hidden')->value(key($linkedProducts));
    $fields->field('branch[][]')
        ->className('hidden')
        ->items(isset($linkedBranches[key($linkedProducts)]) ? $linkedBranches[key($linkedProducts)] : array())
        ->value(isset($linkedBranches[key($linkedProducts)]) ? implode(',', $linkedBranches[key($linkedProducts)]) : '')
        ->multiple();
}

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
