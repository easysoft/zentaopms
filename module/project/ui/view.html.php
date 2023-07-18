<?php
declare(strict_types=1);
/**
 * The view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$blocks = array(
    array(
        'id'    => 1,
        'size'  => 'sm',
        'domID' => 'dynamicBlock',
    ),
    array(
        'id'    => 2,
        'size'  => 'sm',
        'domID' => 'memberBlock',
    ),
    array(
        'id'   => 3,
        'size' => 'xl',
        'domID' => 'basicBlock',
    ),
    array(
        'id'    => 4,
        'size'  => 'smWide',
        'domID' => 'historyBlock',
    )
);

jsVar('blocks', $blocks);
jsVar('confirmDeleteTip', $lang->project->confirmDelete);

/* Construct suitable actions for the current project. */
$operateMenus = array();
foreach($config->project->view->operateList['main'] as $operate)
{
    if(!common::hasPriv('project', $operate)) continue;
    if(!$this->project->isClickable($project, $operate)) continue;

    $action = $config->project->actionList[$operate];
    $action['text'] = $action['hint'];
    $operateMenus[] = $action;
}

/* Construct common actions for project. */
$commonActions = array();
foreach($config->project->view->operateList['common'] as $operate)
{
    if(!common::hasPriv('project', $operate)) continue;

    $settings = $config->project->actionList[$operate];
    $settings['text'] = '';

    $commonActions[] = $settings;
}

dashboard
(
    setID('projectDashBoard'),
    set::blocks($blocks),
    set::blockMenu(false),
);

div
(
    setClass('w-2/3 text-center fixed actions-menu'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($project)
    )
);

div
(
    setID('dynamicBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $lang->execution->latestDynamic,
            )
        ),
        to::headingActions
        (
            common::hasPriv('project', 'dynamic') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('project', 'dynamic', "projectID={$projectID}&type=all")),
                $lang->more
            ) : null
        ),
        set::bodyClass('pt-0'),
        dynamic()
    )
);

/* Related members. */
$membersDom = array();
foreach(array('PM', 'PO', 'QD', 'RD') as $field)
{
    if(empty($project->$field)) continue;

    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        icon('person', setClass('mr-2')),
        zget($users, $project->$field),
        span
        (
            setClass('text-gray ml-2'),
            "( {$lang->project->$field} )"
        )
    );

    unset($teamMembers[$project->$field]);
}
$memberCount = count($membersDom);
foreach($teamMembers as $teamMember)
{
    if($memberCount >= 10) break;

    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        icon('person', setClass('mr-2')),
        zget($users, $teamMember->account),
    );
    $memberCount ++;
}

if(common::hasPriv('project', 'manageMembers'))
{
    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        a
        (
            setClass('ghost text-gray'),
            icon('plus', setClass('bg-primary-50 text-primary mr-2')),
            span($lang->project->manageMembers),
            set::href(createLink('project', 'manageMembers', "projectID={$projectID}"))
        )
    );
}

div
(
    setID('memberBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $lang->execution->relatedMember
            )
        ),
        to::headingActions
        (
            common::hasPriv('project', 'team') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('project', 'team', "projectID={$projectID}")),
                $lang->more
            ) : null
        ),
        set::bodyClass('flex flex-wrap pt-0'),
        $membersDom
    )
);

/* History list. */
div
(
    setID('historyBlock'),
    setClass('hidden'),
    div
    (
        history()
    )
);

/* Basic info. */
$programDom = null;
if($project->grade > 1)
{
    foreach($programList as $programID => $name)
    {
        if(common::hasPriv('program', 'product'))
        {
            $programList[$programID] = html::a
            (
                $this->createLink('program', 'product', "programID={$programID}"),
                $name
            );
        }
    }

    $programDom = div
    (
        icon('program mr-2'),
        html($programList ? implode('/ ', $programList) : '')
    );
}

$productsDom = array();
if(!empty($project->hasProduct))
{
    foreach($products as $productID => $product)
    {
        foreach($product->branches as $branchID)
        {
            $branchName    = isset($branchGroups[$productID][$branchID]) ? '/' . $branchGroups[$productID][$branchID] : '';
            $productsDom[] = div
            (
                setClass('flex-initial w-1/2 py-1'),
                icon('product mr-2'),
                a
                (
                    set::href(createLink('product', 'browse', "productID=$productID&branch=$branchID")),
                    span($product->name . $branchName)
                )
            );
        }
    }
}

$plansDom = array();
foreach($products as $productID => $product)
{
    foreach($product->plans as $planIDList)
    {
        $planIDList = explode(',', $planIDList);
        foreach($planIDList as $planID)
        {
            if(isset($planGroup[$productID][$planID]))
            {
                $plansDom[] = div
                (
                    setClass('mt-2 clip'),
                    hasPriv('productplan', 'view') ? a
                    (
                        set::href(createLink('productplan', 'view', "planID={$planID}")),
                        icon('calendar text-gray mr-1'),
                        $product->name . '/' . $planGroup[$productID][$planID]
                    ) : span
                    (
                        $product->name . '/' . $planGroup[$productID][$planID]
                    )
                );
            }
        }
    }
}

$basicInfo = null;
if(empty($project->hasProduct) && !empty($config->URAndSR) && $project->model !== 'kanban' && isset($lang->project->menu->storyGroup))
{
    $basicInfo = tableData
    (
        set::useTable(false),
        item
        (
            set::name($lang->story->common),
            $statData->storyCount
        ),
        item
        (
            set::name($lang->requirement->common),
            $statData->requirementCount
        ),
        item
        (
            set::name($lang->task->common),
            $statData->taskCount
        ),
        item
        (
            set::name($lang->bug->common),
            $statData->bugCount
        ),
        item
        (
            set::name($lang->project->budget),
            $statData->budget
        ),
    );
}
else
{
    $basicInfo = tableData
    (
        set::useTable(false),
        item
        (
            set::name($lang->story->common),
            $statData->storyCount
        ),
        item
        (
            set::name($lang->task->common),
            $statData->taskCount
        ),
        item
        (
            set::name($lang->bug->common),
            $statData->bugCount
        ),
        item
        (
            set::name($lang->project->budget),
            $statData->budget
        ),
    );
}

$totalEstimate = $workhour->totalConsumed + $workhour->totalLeft;
$progress      = 0;
if($project->model == 'waterfall')
{
    $progress = $this->project->getWaterfallProgress($project->id);
}
elseif($totalEstimate > 0)
{
    $progress = floor($workhour->totalConsumed / $totalEstimate * 1000) / 1000 * 100;
}

$aclList = $project->parent ? $lang->project->subAclList : $lang->project->aclList;
div
(
    setID('basicBlock'),
    setClass('hidden'),
    sectionList
    (
        section
        (
            setClass('border-b pb-4'),
            div
            (
                label
                (
                    setClass('text-dark'),
                    $project->id
                ),
                !empty($config->setCode) ? label
                (
                    setClass('dark-outline text-dark mx-2'),
                    $project->code
                ) : null,
                span(setClass('article-h2'), $project->name),
            ),
            $project->desc ? div
            (
                setClass('mt-2'),
                $project->desc
            ) : null,
            div
            (
                setClass('mt-2'),
                $config->vision == 'rnd' ? label
                (
                    setClass('secondary-pale ring-secondary text-primary'),
                    zget($lang->project->projectTypeList, $project->hasProduct)
                ) : null,
                $project->deleted ? label
                (
                    setClass('danger-outline text-danger ml-2'),
                    $lang->project->deleted
                ) : null,
                $project->lifetime ? label
                (
                    setClass('secondary-outline text-primary ml-2'),
                    zget($lang->execution->lifeTimeList, $project->lifetime, '')
                ) : null,
                isset($project->delay) ? label
                (
                    setClass('danger-outline text-danger ml-2'),
                    $lang->project->delayed
                ) : null,
                label
                (
                    setClass("status-{$project->status} ml-2"),
                    $this->processStatus('project', $project)
                ),
            )
        ),
        section
        (
            setClass('border-b pb-4'),
            set::title($lang->project->parent),
            $programDom
        ),
        !empty($project->hasProduct) ? section
        (
            setClass('border-b pb-4 linked-products'),
            set::title($lang->project->manageProducts),
            to::actions(btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('project', 'manageproducts', "projectID={$project->id}")),
                $lang->more
            )),
            div
            (
                setClass('flex flex-wrap'),
                $productsDom
            )
        ) : null,
        section
        (
            setClass('border-b pb-4'),
            set::title($lang->execution->linkPlan),
            $plansDom
        ),
        section
        (
            setClass('border-b pb-4'),
            set::title($lang->execution->lblStats),
            div
            (
                setClass('flex flex-nowrap items-center'),
                span($lang->project->progress . ' ' . $progress . $lang->percent),
                div
                (
                    setClass('progress flex-auto ml-2 h-2'),
                    div
                    (
                        setClass('progress-bar'),
                        set('role', 'progressbar'),
                        setStyle(array('width' => $progress . $lang->percent)),
                    )
                )
            ),
            div
            (
                setClass('pt-1 project-info project-statitic-hours'),
                tableData
                (
                    set::useTable(false),
                    item
                    (
                        set::name($lang->project->begin),
                        $project->begin
                    ),
                    item
                    (
                        set::name($lang->project->realBeganAB),
                        helper::isZeroDate($project->realBegan) ? '' : $project->realBegan
                    ),
                    item
                    (
                        set::name($lang->project->end),
                        $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end
                    ),
                    item
                    (
                        set::name($lang->project->realEndAB),
                        helper::isZeroDate($project->realEnd) ? '' : $project->realEnd
                    ),
                    item
                    (
                        set::name($lang->execution->totalEstimate),
                        (float)$workhour->totalEstimate . $lang->execution->workHour
                    ),
                    item
                    (
                        set::name($lang->execution->totalDays),
                        (float)$project->days . $lang->execution->day
                    ),
                    item
                    (
                        set::name($lang->execution->totalConsumed),
                        (float)$workhour->totalConsumed . $lang->execution->workHour
                    ),
                    item
                    (
                        set::name($lang->execution->totalHours),
                        (float)$workhour->totalHours . $lang->execution->workHour
                    ),
                    item
                    (
                        set::name($lang->execution->totalLeft),
                        (float)$workhour->totalLeft . $lang->execution->workHour
                    ),
                )
            )
        ),
        section
        (
            setClass('border-b pb-4 project-info'),
            set::title($lang->execution->basicInfo),
            $basicInfo
        ),
        section
        (
            set::title($lang->project->acl),
            p($aclList[$project->acl])
        )
    )
);

render();
