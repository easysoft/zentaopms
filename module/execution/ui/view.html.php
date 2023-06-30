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

if(isset($execution->type) && $execution->type != 'kanban')
{
    $blocks = array(
        array(
            'id'    => 1,
            'size'  => 'sm',
            'fetch' => createLink('execution', 'ajaxGetBurn', "executionID={$execution->id}")
        ),
        array(
            'id'    => 2,
            'size'  => 'sm',
            'domID' => 'dynamicBlock'
        ),
        array(
            'id'   => 3,
            'size' => 'xl',
            'domID' => 'basicBlock'
        ),
        array(
            'id'    => 4,
            'size'  => 'sm',
            'domID' => 'memberBlock'
        ),
        array(
            'id'    => 5,
            'size'  => 'sm',
            'domID' => 'docBlock'
        ),
        array(
            'id'    => 6,
            'size'  => 'smWide',
            'domID' => 'historyBlock',
        )
    );
}
else
{
    $blocks = array(
        array(
            'id'    => 1,
            'size'  => 'sm',
            'domID' => 'cfdBlock'
        ),
        array(
            'id'    => 2,
            'size'  => 'sm',
            'domID' => 'memberBlock'
        ),
        array(
            'id'   => 3,
            'size' => 'xl',
            'domID' => 'basicBlock'
        ),
        array(
            'id'    => 4,
            'size'  => 'smWide',
            'domID' => 'dynamicBlock'
        ),
        array(
            'id'    => 5,
            'size'  => 'smWide',
            'domID' => 'historyBlock',
        )
    );
}

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

jsVar('blocks', $blocks);

dashboard
(
    setID('executionDashBoard'),
    set::blocks($blocks),
    set::blockMenu(false)
);

div
(
    setClass('w-2/3 text-center fixed actions-menu'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($execution)
    )
);

div
(
    setID('cfdBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $execution->name . $lang->execution->CFD,
            )
        ),
        to::headingActions
        (
            common::hasPriv('execution', 'cfd') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', 'cfd', "executionID={$execution->id}")),
                $lang->more
            ) : null
        ),

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
            common::hasPriv('execution', 'dynamic') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', 'dynamic', "executionID={$execution->id}&type=all")),
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
    if(empty($execution->$field)) continue;

    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        icon('person', setClass('mr-2')),
        zget($users, $execution->$field),
        span
        (
            setClass('text-gray ml-2'),
            "( {$lang->execution->$field} )"
        )
    );

    unset($teamMembers[$execution->$field]);
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

if(common::hasPriv('execution', 'manageMembers'))
{
    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        a
        (
            setClass('ghost text-gray'),
            icon('plus', setClass('bg-primary-50 text-primary mr-2')),
            span($lang->execution->manageMembers),
            set::href(createLink('execution', 'manageMembers', "executionID={$execution->id}"))
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
            common::hasPriv('execution', 'team') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', 'team', "executionID={$execution->id}")),
                $lang->more
            ) : null
        ),
        set::bodyClass('flex flex-wrap pt-0'),
        $membersDom
    )
);

$docsDom = null;
if(common::hasPriv('execution', 'doc'))
{
    $docLibCount = 0;
    foreach($docLibs as $libID => $docLib)
    {
        if($docLibCount > 8) break;
        $docsDom[] = div
            (
                setClass('flex-initial w-1/2 py-1'),
                icon('folder', setClass('mr-2 text-warning')),
                a
                (
                    $docLib->name,
                    set::href($libID == 'files' ? $this->createLink('doc', 'showFiles', "type=execution&objectID={$execution->id}") : $this->createLink('execution', 'doc', "objectID={$execution->id}&libID={$libID}")),
                )
            );

        $docLibCount ++;
    }
}

if($canBeChanged && common::hasPriv('doc', 'createLib'))
{
    $docsDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        a
        (
            setClass('ghost text-gray'),
            icon('plus', setClass('bg-primary-50 text-primary mr-2')),
            span($lang->doc->createLib),
            set::href(createLink('doc', 'createLib', "type=execution&objectID={$execution->id}")),
            set('data-toggle', 'modal'),
        )
    );
}

div
(
    setID('docBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $lang->execution->doclib
            )
        ),
        to::headingActions
        (
            common::hasPriv('execution', 'doc') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', 'doc', "executionID={$execution->id}")),
                $lang->more
            ) : null
        ),
        set::bodyClass('flex flex-wrap pt-0'),
        $docsDom
    )
);

/* History list. */
div
(
    setID('historyBlock'),
    setClass('hidden'),
    div
    (
        setClass('overflow-y-auto h-full'),
        history()
    )
);

$programDom = null;
if($execution->projectInfo->grade > 1)
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

$productsDom = null;
if($execution->projectInfo->hasProduct)
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
                    set::href(createLink('product', 'browse', "productID={$productID}&branch={$branchID}")),
                    span($product->name . $branchName)
                )
            );
        }
    }
}

$plansDom = null;
if($features['plan'])
{
    foreach($products as $productID => $product)
    {
        foreach($product->plans as $planIDList)
        {
            $planIDList = explode(',', $planIDList);
            foreach($planIDList as $planID)
            {
                if(!isset($planGroups[$productID][$planID])) continue;
                $plansDom[] = div
                    (
                        setClass('flex-initial py-1'),
                        icon('calendar mr-2'),
                        a
                        (
                            set::href(createLink('productplan', 'view', "planID={$planID}")),
                            span($product->name . '/' . $planGroups[$productID][$planID])
                        )
                    );
            }
        }
    }
}

$progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
$hoursDom = div
    (
        div
        (
            setClass('flex flex-nowrap items-center'),
            span($lang->execution->progress . ' ' . $progress . $lang->percent),
            div
            (
                setClass('progress flex-auto ml-2 h-2'),
                div
                (
                    setClass('progress-bar'),
                    set('role', 'progressbar'),
                    setStyle(['width' => $progress . $lang->percent]),
                )
            )
        ),
        tableData
        (
            set::useTable(false),
            item
            (
                set::name($lang->execution->begin),
                $execution->begin
            ),
            item
            (
                set::name($lang->execution->realBeganAB),
                helper::isZeroDate($execution->realBegan) ? '' : $execution->realBegan
            ),
            item
            (
                set::name($lang->execution->end),
                $execution->end
            ),
            item
            (
                set::name($lang->execution->realEndAB),
                helper::isZeroDate($execution->realEndAB) ? '' : $execution->realEndAB
            ),
            item
            (
                set::name($lang->execution->totalEstimate),
                (float)$execution->totalEstimate . $lang->execution->workHour
            ),
            item
            (
                set::name($lang->execution->totalDays),
                $execution->days . $lang->execution->day
            ),
            item
            (
                set::name($lang->execution->totalConsumed),
                (float)$execution->totalConsumed . $lang->execution->workHour
            ),
            item
            (
                set::name($lang->execution->totalHours),
                (float)$execution->totalHours . $lang->execution->workHour
            ),
            item
            (
                set::name($lang->execution->totalLeft),
                (float)$execution->totalLeft . $lang->execution->workHour
            ),
        )
    );

$basicInfoDom = tableData
    (
        set::useTable(false),
        $features['story'] ? item
        (
            set::name($lang->story->common),
            $statData->storyCount
        ) : null,
        item
        (
            set::name($lang->task->common),
            $statData->taskCount
        ),
        $features['qa'] ? item
        (
            set::name($lang->bug->common),
            $statData->bugCount
        ) : null,
    );


/* Baseic information. */
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
                    $execution->id
                ),
                !empty($config->setCode) ? label
                (
                    setClass('dark-outline text-dark mx-2'),
                    $execution->code
                ) : null,
                span(setClass('article-h2'), $execution->name),
            ),
            div
            (
                set::class('detail-content article-content'),
                span($execution->desc),
            ),
            div
            (
                setClass('mt-4'),
                $execution->deleted ? label
                (
                    setClass('danger-outline text-danger'),
                    $lang->execution->deleted
                ) : null,
                !empty($execution->lifetime) && $execution->type != 'kanban' && $project->model != 'waterfall' &&  $project->model != 'waterfallplus' ? label
                (
                    setClass('secondary-outline text-primary'),
                    setClass($execution->deleted ? 'ml-2' : ''),
                    zget($lang->execution->lifeTimeList, $execution->lifetime, '')
                ) : null,
                isset($execution->delay) ? label
                (
                    setClass('danger-pale ring-danger ml-2'),
                    $lang->execution->delayed
                ) : label
                (
                    setClass("success-pale ring-success ml-2"),
                    $this->processStatus('execution', $execution)
                ),
            )
        ),
        $config->systemMode == 'ALM' ? section
        (
            setClass('border-b pb-4'),
            set::title($lang->project->parent),
            $programDom
        ) : null,
        section
        (
            setClass('border-b pb-4'),
            set::title($lang->project->project),
            div
            (
                icon('project mr-2'),
                html
                (
                    common::hasPriv('project', 'index') ? html::a
                    (
                        $this->createLink('project', 'index', "projectID={$execution->project}"),
                        $execution->projectInfo->name
                    ) : span($execution->projectInfo->name),
                )
            )
        ),
        $execution->projectInfo->hasProduct ? section
        (
            setClass('border-b pb-4 linked-products'),
            set::title($lang->execution->manageProducts),
            common::hasPriv('execution', 'manageproducts') && $execution->type != 'stage' && $project->model != 'waterfallplus' ? to::actions(btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', 'manageproducts', "projectID={$execution->id}")),
                $lang->more
            )) : null,
            div
            (
                setClass('flex flex-wrap'),
                $productsDom
            )
        ) : null,
        $features['plan'] ? section
        (
            setClass('border-b pb-4 linked-plans'),
            set::title($lang->execution->linkPlan),
            div
            (
                setClass('flex flex-wrap'),
                $plansDom
            )
        ) : null,
        section
        (
            setClass('border-b pb-4 execution-statitic-hours'),
            set::title($lang->execution->lblStats),
            $hoursDom,
        ),
        section
        (
            setClass('border-b pb-4 execution-basic-info'),
            set::title($lang->execution->basicInfo),
            $basicInfoDom
        ),
        section
        (
            setClass('pb-4'),
            set::title($lang->execution->acl),
            $lang->execution->aclList[$execution->acl]
        ),
    )
);

/* ====== Render page ====== */
render();
