<?php
declare(strict_types=1);
/**
 * The view view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
$isKanban = isset($execution->type) && $execution->type == 'kanban';
$chartURL = createLink('execution', $isKanban ? 'ajaxGetCFD' : 'ajaxGetBurn', "executionID={$execution->id}");

/* Construct suitable actions for the current execution. */
$execution->rawID = $execution->id;
$operateMenus = array();
foreach($config->execution->view->operateList['main'] as $operate)
{
    if(!common::hasPriv('execution', $operate)) continue;
    if(!$this->execution->isClickable($execution, $operate)) continue;

    $operateMenus[] = $config->execution->actionList[$operate];
}

/* Construct common actions for execution. */
$commonActions = array();
foreach($config->execution->view->operateList['common'] as $operate)
{
    if(!common::hasPriv('execution', $operate)) continue;

    $settings = $config->execution->actionList[$operate];
    $settings['text'] = '';
    if($operate == 'edit') unset($settings['data-toggle']);

    $commonActions[] = $settings;
}

$programDom = null;
if($config->systemMode == 'ALM' && $execution->projectInfo->grade > 1)
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
        else
        {
            $programList[$programID] = span($name);
        }
    }

    $programDom = div
    (
        icon('program mr-2'),
        html(implode('/ ', $programList))
    );
}

$relatedProducts = null;
if($execution->projectInfo->hasProduct || $features['plan'])
{
    foreach($products as $productID => $product)
    {
        $productDom = null;
        $planDom    = null;
        $branches   = array();
        if($execution->projectInfo->hasProduct)
        {
            foreach($product->branches as $branchID)
            {
                $branchName = isset($branchGroups[$productID][$branchID]) ? '/' . $branchGroups[$productID][$branchID] : '';
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
        }

        if($features['plan'])
        {
            $plans = array();
            foreach($product->plans as $planIDList)
            {
                $planIDList = explode(',', $planIDList);
                foreach($planIDList as $planID)
                {
                    if(!isset($planGroups[$productID][$planID])) continue;

                    $class = '';
                    if(count($plans) > 2)      $class .= ' mt-2';
                    if(count($plans) % 3 != 0) $class .= ' pl-4';
                    $plans[] = div
                    (
                        setClass("flex-none w-1/3 clip {$class}"),
                        icon('calendar mr-2'),
                        a
                        (
                            set::title($product->name . '/' . $planGroups[$productID][$planID]),
                            set('data-app', $execution->projectInfo->hasProduct ? '' : 'project'),
                            hasPriv('productplan', 'view') ? set::href(createLink('productplan', 'view', "planID={$planID}")) : null,
                            span($product->name . '/' . $planGroups[$productID][$planID])
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
        }

        $relatedProducts[] = h::tr(setClass('border-r'), $productDom, $planDom);
    }
}

div
(
    setClass('flex w-full'),
    div
    (
        setClass('flex-auto canvas flex p-4 w-1/2'),
        div
        (
            setClass('text-center w-1/3 flex flex-col justify-center items-center'),
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
                                toggle::tooltip(array('title' => $lang->execution->progressTip)),
                                set('data-placement', 'bottom'),
                                set('data-type', 'white'),
                                set('data-class-name', 'text-gray border border-light'),
                                setClass('text-gray')
                            )
                        )
                    )
                )
            ),
            div
            (
                setClass('border w-3/4 flex justify-center items-center pl-4 py-2'),
                $features['story'] ? div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('text-lg font-bold'),
                        $statData->storyCount
                    ),
                    $lang->story->common
                ) : null,
                div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('text-lg font-bold'),
                        $statData->taskCount
                    ),
                    $lang->task->common
                ),
                $features['qa'] ? div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('text-lg font-bold'),
                        $statData->bugCount
                    ),
                    $lang->bug->common
                ) : null
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
                    $execution->id
                ),
                div
                (
                    setClass('text-md font-bold ml-2 clip'),
                    set::title($execution->name),
                    $execution->name
                ),
                !empty($config->setCode) && !empty($execution->code) ? label
                (
                    setClass('light-outline mx-2 flex-none'),
                    $execution->code
                ) : null,
                $execution->deleted ? label
                (
                    setClass('danger-outline text-dange flex-noner'),
                    $lang->execution->deleted
                ) : null,
                isset($execution->delay) ? label
                (
                    setClass('danger-pale ring-danger ml-2 flex-none'),
                    $lang->execution->delayed
                ) : label
                (
                    setClass("status-{$execution->status} ml-2 flex-none"),
                    $this->processStatus('execution', $execution)
                ),
                span
                (
                    setClass('ml-2 flex-none mb-1'),
                    $lang->execution->kanbanAclList[$execution->acl],
                    icon
                    (
                        'help',
                        toggle::tooltip(array('title' => $lang->execution->aclList[$execution->acl])),
                        set('data-placement', 'right'),
                        set('data-type', 'white'),
                        set('data-class-name', 'text-gray border border-light'),
                        setClass('ml-2 mt-2 text-gray')
                    )
                )
            ),
            div
            (
                setClass('flex mt-4'),
                $config->systemMode == 'ALM' ? div
                (
                    setClass('clip w-1/2'),
                    $programDom
                ) : null,
                div
                (
                    setClass('clip w-1/2'),
                    icon('project mr-1'),
                    common::hasPriv('project', 'index') ? a
                    (
                        setClass('clip'),
                        set::href($this->createLink('project', 'index', "projectID={$execution->project}")),
                        $execution->projectInfo->name
                    ) : span
                    (
                        setClass('clip w-full'),
                        $execution->projectInfo->name
                    )
                )
            ),
            div
            (
                set::className('detail-content mt-4'),
                html($execution->desc)
            )
        )
    ),
    div
    (
        setClass('flex-none w-1/3 canvas ml-4'),
        $isKanban ? to::heading
        (
            div
            (
                setClass('panel-title nowrap overflow-hidden'),
                set::title($execution->name . $lang->execution->CFD),
                $execution->name . $lang->execution->CFD
            )
        ) : null,
        div
        (
            setID('chartLine'),
            h::js("loadTarget('{$chartURL}', '#chartLine')")
        )
    )
);

$membersDom = array();
foreach(array('PM', 'PO', 'QD', 'RD') as $field)
{
    if(empty($execution->$field)) continue;

    $user = isset($userList[$execution->$field]) ? $userList[$execution->$field] : null;
    if($user)
    {
        $membersDom[] = div
        (
            setClass('w-1/8 center-y'),
            avatar
            (
                setClass('primary-outline'),
                set::size('36'),
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
                $lang->execution->$field
            )
        );
    }

    unset($teamMembers[$execution->$field]);
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
            set::size('36'),
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
            $lang->execution->member
        )
    );
    $memberCount ++;
}

if(common::hasPriv('execution', 'manageMembers'))
{
    $membersDom[] = a
    (
        set::href(createLink('execution', 'manageMembers', "executionID={$execution->id}")),
        setClass('w-1/8 center-y'),
        avatar
        (
            setClass('mb-2'),
            set::size('36'),
            set::foreColor('var(--color-primary-500-rgb)'),
            set::background('var(--menu-active-bg)'),
            icon('plus')
        ),
        $lang->project->manage
    );
}

$docLibDom = array();
if(common::hasPriv('execution', 'doc'))
{
    $docLibCount = 0;
    foreach($docLibs as $libID => $docLib)
    {
        if($docLibCount > 4) break;

        $docLibDom[] = div
        (
            setClass('flex-none w-1/5 py-1 clip pl-4'),
            icon('wiki-lib mr-1'),
            a
            (
                $docLib->name,
                set('data-app', $app->tab == 'search' ? 'execution' : $app->tab),
                set::href($libID == 'files' ? $this->createLink('doc', 'showFiles', "type=execution&objectID={$execution->id}") : $this->createLink('execution', 'doc', "objectID={$execution->id}&libID={$libID}"))
            )
        );

        $docLibCount ++;
    }
}

if($canBeChanged && common::hasPriv('doc', 'createLib'))
{
    $docLibDom[] = div
    (
        setClass('flex-none w-1/5 py-1 pl-4'),
        a
        (
            setClass('ghost text-gray'),
            icon('plus', setClass('bg-primary-50 text-primary mr-2')),
            span($lang->doc->createLib),
            set::href(createLink('doc', 'createLib', "type=execution&objectID={$execution->id}")),
            set('data-toggle', 'modal'),
            set('data-app', $app->tab)
        )
    );
}

div
(
    setClass('my-4 flex w-full items-start'),
    ($execution->projectInfo->hasProduct || $features['plan']) ? div
    (
        setClass('w-2/3 canvas p-4 flex-auto'),
        div
        (
            /* Linked product and plan.  */
            h::table
            (
                setClass('table condensed bordered'),
                h::thead
                (
                    h::tr
                    (
                        $execution->projectInfo->hasProduct ? h::th
                        (
                            setClass('w-1/3'),
                            div
                            (
                                setClass('flex items-center justify-between'),
                                div
                                (
                                    setClass('flex'),
                                    h::img(set::src('static/svg/product.svg'), setClass('mr-2')),
                                    $lang->execution->manageProducts
                                ),
                                common::hasPriv('execution', 'manageproducts') && $execution->type != 'stage' && $project->model != 'waterfallplus' ? btn
                                (
                                    setClass('ghost text-gray font-normal'),
                                    set::icon('link text-primary'),
                                    set::url(createLink('execution', 'manageproducts', "projectID={$execution->id}")),
                                    $lang->more
                                ) : null
                            )
                        ) : null,
                        $features['plan'] ? h::th
                        (
                            div
                            (
                                setClass('flex'),
                                h::img(set::src('static/svg/productplan.svg'), setClass('mr-2')),
                                $lang->execution->linkPlan
                            )
                        ) : null
                    )
                ),
                h::tbody($relatedProducts)
            ),

            /* Execution team. */
            h::table
            (
                setClass('table condensed bordered mt-4'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span($lang->execution->team),
                                hasPriv('execution', 'team') ? btn
                                (
                                    setClass('ghost text-gray font-normal'),
                                    set::trailingIcon('caret-right pb-0.5'),
                                    set::url(createLink('execution', 'team', "executionID={$execution->id}")),
                                    $lang->more
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
                setClass('table condensed bordered mt-4'),
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
                                        $lang->execution->begin
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $execution->begin
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->end
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        $execution->end
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->realBeganAB
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        helper::isZeroDate($execution->realBegan) ? '' : $execution->realBegan
                                    )
                                ),
                                div
                                (
                                    setClass('w-1/4'),
                                    span
                                    (
                                        setClass('text-gray'),
                                        $lang->execution->realEndAB
                                    ),
                                    span
                                    (
                                        setClass('ml-2'),
                                        helper::isZeroDate($execution->realEnd) ? '' : $execution->realEnd
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            h::table
            (
                setClass('table condensed bordered mt-4'),
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
                                        $execution->totalEstimate . $lang->execution->workHourUnit
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
                                        $execution->totalConsumed . $lang->execution->workHourUnit
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
                                        $execution->totalLeft . $lang->execution->workHourUnit
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
                                        $execution->days
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
                                        $execution->totalHours . $lang->execution->workHourUnit
                                    )
                                )
                            )
                        )
                    )
                )
            ),

            /* Execution doc lib. */
            hasPriv('execution', 'doc') && !$isKanban ? h::table
            (
                setClass('table condensed bordered mt-4'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            div
                            (
                                setClass('flex items-center justify-between'),
                                span($lang->execution->doclib),
                                hasPriv('execution', 'doc') ? btn
                                (
                                    setClass('ghost text-gray font-normal'),
                                    set::trailingIcon('caret-right pb-0.5'),
                                    set::url(createLink('execution', 'doc', "executionID={$execution->id}")),
                                    $lang->more
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
                                setClass('flex flex-wrap pt-2'),
                                $docLibDom
                            )
                        )
                    )
                )
            ) : null
        )
    ) : null,
    div
    (
        setClass('ml-4 w-1/3 flex-none'),
        panel
        (
            to::heading
            (
                div
                (
                    set('class', 'panel-title'),
                    $lang->execution->latestDynamic
                )
            ),
            to::headingActions
            (
                common::hasPriv('execution', 'dynamic') ? btn
                (
                    setClass('ghost text-gray font-normal'),
                    set::url(createLink('execution', 'dynamic', "executionID={$execution->id}&type=all")),
                    $lang->more
                ) : null
            ),
            set::bodyClass('h-80 overflow-y-auto pt-0'),
            set::shadow(false),
            dynamic()
        ),
        div
        (
            setClass('mt-4'),
            history
            (
                set::commentUrl(createLink('action', 'comment', array('objectType' => 'execution', 'objectID' => $execution->id))),
                set::bodyClass('maxh-72 overflow-y-auto')
            )
        )
    )
);

div
(
    setClass('center w-2/3'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($execution)
    )
);
