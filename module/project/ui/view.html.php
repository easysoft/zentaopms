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

jsVar('confirmDeleteTip', $lang->project->confirmDelete);

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

$totalEstimate = $workhour->totalConsumed + $workhour->totalLeft;
$progress      = 0;
if($project->model == 'waterfall')
{
    $progressList = $this->project->getWaterfallProgress(array($project->id));
    $progress     = empty($progressList[$project->id]) ? 0 : $progressList[$project->id];
}
elseif($totalEstimate > 0)
{
    $progress = floor($workhour->totalConsumed / $totalEstimate * 1000) / 1000 * 100;
}

$status = $this->processStatus('project', $project);

$relatedProducts = null;
if($project->hasProduct)
{
    foreach($products as $productID => $product)
    {
        $productDom = null;
        $branches   = array();
        foreach($product->branches as $branchID)
        {
            $branchName  = isset($branchGroups[$productID][$branchID]) ? '/' . $branchGroups[$productID][$branchID] : '';
            $branches[]  = div(
                setClass('flex clip w-full items-center'),
                icon('product mr-2'),
                a
                (
                    setClass('flex'),
                    set::title($product->name . $branchName),
                    hasPriv('product', 'browse') ? set::href(createLink('product', 'browse', "productID={$productID}&branch={$branchID}")) : null,
                    span
                    (
                        setClass('flex-1'),
                        setStyle('width', '0'),
                        $product->name . $branchName
                    )
                )
            );
        }

        $productDom = h::td
            (
                div
                (
                    setClass('flex flex-wrap'),
                    $branches
                )
            );

        $plans   = array();
        $planDom = null;
        foreach($product->plans as $planIDList)
        {
            $planIDList = explode(',', $planIDList);
            foreach($planIDList as $planID)
            {
                if(!isset($planGroup[$productID][$planID])) continue;

                $planClass  = count($plans) > 2 ? 'mt-2' : '';
                $planClass .= count($plans) % 3 != 0 ? ' pl-4' : '';
                $plans[] = div
                    (
                        setClass("flex-none w-1/3 clip {$planClass}"),
                        icon('productplan mr-2 '),
                        a
                        (
                            set::title($planGroup[$productID][$planID]),
                            hasPriv('productplan', 'view') ? set::href(createLink('productplan', 'view', "planID={$planID}")) : null,
                            span($planGroup[$productID][$planID])
                        )
                    );
            }
        }
        $planDom[] = h::td
            (
                div
                (
                    setClass('flex flex-wrap'),
                    $plans
                )
            );
        $relatedProducts[] = h::tr(setClass('border-r'), $productDom, $planDom);
    }
}

$membersDom = array();
if(!empty($project->PM))
{
    $user = isset($userList[$project->PM]) ? $userList[$project->PM] : null;
    if($user)
    {
        $membersDom[] = div
            (
                setClass('w-1/8 center-y'),
                avatar
                (
                    setClass('primary-outline'),
                    set::text($user->realname),
                    set::src($user->avatar)
                ),
                span
                (
                    setClass('my-2'),
                    $user->realname
                ),
                span
                (
                    setClass('text-gray'),
                    $lang->project->PM
                )
            );
    }

    unset($teamMembers[$project->PM]);
}


$memberCount = count($membersDom);
foreach($teamMembers as $teamMember)
{
    if($memberCount >= 7) break;

    $user = isset($userList[$teamMember->account]) ? $userList[$teamMember->account] : null;
    if(!$user) continue;
    $membersDom[] = div
        (
            setClass('w-1/8 center-y'),
            avatar
            (
                set::text($user->realname),
                set::src($user->avatar)
            ),
            span
            (
                setClass('my-2'),
                $user->realname
            ),
            span
            (
                setClass('text-gray'),
                $lang->project->member
            )
        );
    $memberCount ++;
}

if(common::hasPriv('project', 'manageMembers'))
{
    $membersDom[] = a
    (
        setClass('w-1/8 center-y cursor-pointer'),
        set::href(createLink('project', 'manageMembers', "projectID={$project->id}")),
        avatar
        (
            setClass('mb-2'),
            set::foreColor('var(--color-primary-500-rgb)'),
            set::background('var(--menu-active-bg)'),
            icon('plus')
        ),
        $lang->project->manage
    );
}

div
(
    setClass('main'),
    div
    (
        setClass('flex-auto canvas flex p-4 basic'),
        div
        (
            setClass('text-center w-1/3 flex flex-col justify-center items-center progressBox'),
            div
            (
                set('class', 'chart pie-chart'),
                echarts
                (
                    set::color(array('#2B80FF', '#E3E4E9')),
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'type'      => 'pie',
                                'radius'    => array('80%', '90%'),
                                'itemStyle' => array('borderRadius' => '40'),
                                'label'     => array('show' => false),
                                'data'      => array($progress, 100 - $progress)
                            )
                        )
                    )
                )->size(120, 120),
                div
                (
                    set::className('pie-chart-title text-center'),
                    div(span(set::className('text-2xl font-bold'), $progress . '%')),
                    div
                    (
                        span
                        (
                            setClass('text-sm text-gray'),
                            $lang->allProgress,
                            icon
                            (
                                'help ml-1',
                                set('data-toggle', 'tooltip'),
                                set('data-title', $lang->execution->progressTip),
                                set('data-placement', 'bottom'),
                                set('data-type', 'white'),
                                set('data-class-name', 'text-gray border border-light'),
                                setClass('text-light')
                            )
                        )
                    )
                )
            ),
            div
            (
                setClass('border w-3/4 flex justify-center items-center pl-4 py-2 statistics'),
                div
                (
                    setClass('w-1/3 storyCount'),
                    div
                    (
                        setClass('article-h1'),
                        $statData->storyCount
                    ),
                    span
                    (
                        setClass('text-gray'),
                        $lang->story->common
                    )
                ),
                div
                (
                    setClass('w-1/3 taskCount'),
                    div
                    (
                        setClass('article-h1'),
                        $statData->taskCount
                    ),
                    span
                    (
                        setClass('text-gray'),
                        $lang->task->common
                    )
                ),
                div
                (
                    setClass('w-1/3 bugCount'),
                    div
                    (
                        setClass('article-h1'),
                        $statData->bugCount
                    ),
                    span
                    (
                        setClass('text-gray'),
                        $lang->bug->common
                    )
                )
            )
        ),
        div
        (
            setClass('flex-none w-2/3'),
            div
            (
                setClass('flex items-center'),
                label
                (
                    setClass('rounded-full'),
                    $project->id
                ),
                span
                (
                    setClass('article-h2 ml-2 clip'),
                    set::title($project->name),
                    $project->name
                ),
                !empty($config->setCode) && !empty($project->code) ? label
                (
                    setClass('label lighter-pale ml-2 flex-none'),
                    $project->code
                ) : null,
                label
                (
                    setClass('label warning-pale ring-warning rounded-full ml-2 flex-none projectType'),
                    $lang->project->projectTypeList[$project->hasProduct]
                ),
                $project->deleted ? label
                (
                    setClass('danger-outline text-danger flex-none ml-2'),
                    $lang->project->deleted
                ) : null,
                isset($project->delay) ? label
                (
                    setClass("ml-2 flex-none danger-pale"),
                    $lang->execution->delayed
                ) : label
                (
                    setClass("ml-2 flex-none status status-{$project->status}"),
                    $status
                ),
                span
                (
                    setClass('ml-2 text-gray flex-none acl'),
                    $lang->project->shortAclList[$project->acl],
                    icon
                    (
                        'help',
                        set('data-toggle', 'tooltip'),
                        set('data-title', $lang->project->subAclList[$project->acl]),
                        set('data-placement', 'right'),
                        set('data-type', 'white'),
                        set('data-class-name', 'text-gray border border-light'),
                        setClass('ml-2 text-gray')
                    )
                )
            ),
            div
            (
                setClass('flex mt-4'),
                div
                (
                    setClass('clip text-secondary programBox'),
                    $programDom
                )
            ),
            div
            (
                set::className('detail-content mt-4'),
                html($project->desc)
            )
        )
    ),
    div
    (
        setClass('flex flex-auto p-4 mt-4 canvas'),
        div
        (
            setClass('w-full'),
            /* Linked product and plan.  */
            h::table
            (
                setClass('table condensed bordered productsBox'),
                h::thead
                (
                    h::tr
                    (
                        $project->hasProduct ? h::th
                        (
                            setClass('w-1/3'),
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span
                                (
                                    setClass('leading-8 flex'),
                                    img(set('src', 'static/svg/product.svg'), setClass('mr-2')),
                                    $lang->project->manageProducts
                                ),
                                common::hasPriv('project', 'manageproducts') ? btn
                                (
                                    setClass('ghost text-gray'),
                                    set::url(createLink('project', 'manageproducts', "projectID={$project->id}")),
                                    icon('link', setClass('text-primary')),
                                    span($lang->more, setClass('font-normal'))
                                ) : null
                            )
                        ) : null,
                        h::th
                        (
                            span
                            (
                                setClass('flex'),
                                img(set('src', 'static/svg/productplan.svg'), setClass('mr-2')),
                                $lang->execution->linkPlan
                            )
                        )
                    )
                ),
                h::tbody($relatedProducts)
            ),
            /* Project team. */
            h::table
            (
                setClass('table condensed bordered mt-4 teams'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span($lang->projectCommon . $lang->project->team),
                                hasPriv('project', 'team') ? btn
                                (
                                    setClass('ghost text-gray'),
                                    set::trailingIcon('caret-right pb-0.5'),
                                    set::url(createLink('project', 'team', "projectID={$project->id}")),
                                    span($lang->more, setClass('font-normal'))
                                ) : null
                            )
                        )
                    )
                ),
                h::tbody
                (
                    h::tr
                    (
                        h::td
                        (
                            div
                            (
                                setClass('flex flex-wrap member-list pt-2'),
                                $membersDom
                            )
                        )
                    )
                )
            ),
            /* Estimate statistics. */
            h::table
            (
                setClass('table condensed bordered mt-4 duration'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span($lang->execution->DurationStats)
                            )
                        )
                    )
                ),
                h::tbody
                (
                    h::tr
                    (
                        h::td
                        (
                            div
                            (
                                setClass('flex flex-wrap pt-2 mx-4'),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->project->begin
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $project->begin
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->project->end
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->project->realBeganAB
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        helper::isZeroDate($project->realBegan) ? '' : $project->realBegan
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->project->realEndAB
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        helper::isZeroDate($project->realEnd) ? '' : $project->realEnd
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            h::table
            (
                setClass('table condensed bordered mt-4 estimate'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span($lang->execution->lblStats)
                            )
                        )
                    )
                ),
                h::tbody
                (
                    h::tr
                    (
                        h::td
                        (
                            div
                            (
                                setClass('flex flex-wrap pt-2 mx-4'),
                                div
                                (
                                    setClass('w-1/3'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->estimateHours
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        (float)$workhour->totalEstimate . 'h'
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/3'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->consumedHours
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        (float)$workhour->totalConsumed . 'h'
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/3'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->leftHours
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        (float)$workhour->totalLeft . 'h'
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/3 mt-4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->totalDays
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $project->days
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/3 mt-4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->totalHours
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        (float)$workhour->totalLeft . 'h'
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/3 mt-4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->project->budget
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $project->budget ? '￥' . $project->budget : $lang->project->future
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);

div
(
    setClass('side ml-4'),
    panel
    (
        setID('dynamicBlock'),
        to::heading
        (
            div
            (
                set('class', 'panel-title article-h2'),
                $lang->execution->latestDynamic
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
        set::bodyClass('pt-0 h-80 overflow-y-auto'),
        set::shadow(false),
        dynamic()
    ),
    div
    (
        setID('historyBlock'),
        setClass('mt-4'),
        history
        (
            set::commentUrl(createLink('action', 'comment', array('objectType' => 'project', 'objectID' => $project->id))),
            set::bodyClass('h-80 overflow-y-auto')
        )
    )
);

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
    if($operate == 'edit') $settings['url'] = createLink('project', 'edit', "projectID={$project->id}&from=view");

    $commonActions[] = $settings;
}

div
(
    setClass('w-2/3 center fixed actions-menu'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($project)
    )
);
/* ====== Render page ====== */
render();
