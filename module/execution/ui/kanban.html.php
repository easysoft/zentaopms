<?php
declare(strict_types=1);
/**
 * The kanban view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

$laneCount = 0;
foreach($kanbanList as $current => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $groupID = $group['id'];

        $group['getLane']     = jsRaw('window.getLane');
        $group['getCol']      = jsRaw('window.getCol');
        $group['getItem']     = jsRaw('window.getItem');
        $group['canDrop']     = jsRaw('window.canDrop');
        $group['onDrop']      = jsRaw('window.onDrop');
        $group['minColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth;
        $group['maxColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth;
        $group['colProps']    = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps']   = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps']   = array('actions' => jsRaw('window.getItemActions'));

        $kanbanList[$current]['items'][$index] = $group;
    }

    $laneCount += isset($region['laneCount']) ? $region['laneCount'] : 0;
}

$operationMenu = array();
if($this->execution->isClickable($execution, 'start'))    $operationMenu[] = array('text' => $lang->execution->start, 'url' => inlink('start', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'start');
if($this->execution->isClickable($execution, 'putoff'))   $operationMenu[] = array('text' => $lang->execution->putoff, 'url' => inlink('putoff', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'calendar');
if($this->execution->isClickable($execution, 'suspend'))  $operationMenu[] = array('text' => $lang->execution->suspend, 'url' => inlink('suspend', "id=$execution->id"), 'data-toggle' => 'modal', 'icon'=> 'pause');
if($this->execution->isClickable($execution, 'close'))    $operationMenu[] = array('text' => $lang->execution->close, 'url' => inlink('close', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'off');
if($this->execution->isClickable($execution, 'activate')) $operationMenu[] = array('text' => $lang->execution->activate, 'url' => inlink('activate', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'off');
if($this->execution->isClickable($execution, 'delete'))   $operationMenu[] = array('text' => $lang->delete, 'url' => inlink('delete', "id=$execution->id&confirm=no"), 'innerClass' => 'ajax-submit', 'icon' => 'trash');

$canCreateTask      = common::hasPriv('task', 'create');
$canBatchCreateTask = common::hasPriv('task', 'batchCreate');
$canImportTask      = common::hasPriv('execution', 'importTask') && $execution->multiple;

$canCreateBug        = $features['qa'] && common::hasPriv('bug', 'create');
$canBatchCreateBug   = $features['qa'] && common::hasPriv('bug', 'batchCreate') && $execution->multiple;
$canImportBug        = $features['qa'] && common::hasPriv('execution', 'importBug') && $execution->multiple;
$hasBugButton        = $features['qa'] && ($canCreateBug || $canBatchCreateBug);

$canCreateStory      = $features['story'] && common::hasPriv('story', 'create');
$canBatchCreateStory = $features['story'] && common::hasPriv('story', 'batchCreate');
$canLinkStory        = $features['story'] && common::hasPriv('execution', 'linkStory') && !empty($execution->hasProduct);
$canLinkStoryByPlan  = $features['story'] && common::hasPriv('execution', 'importplanstories') && !empty($project->hasProduct);
$hasStoryButton      = $features['story'] && ($canCreateStory || $canBatchCreateStory || $canLinkStory || $canLinkStoryByPlan);

$hasTaskButton = $canCreateTask || $canBatchCreateTask || $canImportBug;

$createMenu = array();
$modal      = $productID ? 'modal' : false;
if($canCreateStory) $createMenu[] = array('text' => $lang->story->create, 'url' => $productID ? helper::createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id") : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($canBatchCreateStory) $createMenu[] = array('text' => $lang->story->batchCreate, 'url' => $productID ? (count($productNames) > 1 ? '#batchCreateStory' : helper::createLink('story', 'batchCreate', "productID=$productID&branch=$branchID&moduleID=0&story=0&execution=$execution->id")) : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($canLinkStory) $createMenu[] = array('text' => $lang->execution->linkStory, 'url' => $productID ? helper::createLink('execution', 'linkStory', "execution=$execution->id") : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($canLinkStoryByPlan) $createMenu[] = array('text' => $lang->execution->linkStoryByPlan, 'url' => $productID ? "#linkStoryByPlan" : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($hasStoryButton && $hasTaskButton) $createMenu[] = array('type' => 'divider');
if($canCreateBug) $createMenu[] = array('text' => $lang->bug->create, 'url' => helper::createLink('bug', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id"), 'data-toggle' => 'modal');
if($canBatchCreateBug)
{
    if(count($productNames) > 1)
    {
        $createMenu[] = array('text' => $lang->bug->batchCreate, 'url' => '#batchCreateBug', 'data-toggle' => 'modal');
    }
    else
    {
        $createMenu[] = array('text' => $lang->bug->batchCreate, 'url' => helper::createLink('bug', 'batchCreate', "productID=$productID&branch=$branchID&extra=executionID=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}
if(($hasStoryButton or $hasBugButton) and $hasTaskButton) $createMenu[] = array('type' => 'divider');
if($canCreateTask) $createMenu[] = array('text' => $lang->task->create, 'url' => helper::createLink('task', 'create', "execution=$execution->id"), 'data-toggle' => 'modal');
if($canImportBug)  $createMenu[] = array('text' => $lang->execution->importBug, 'url' => helper::createLink('execution', 'importBug', "execution=$execution->id"), 'data-toggle' => 'modal');
if($canImportTask) $createMenu[] = array('text' => $lang->execution->importTask, 'url' => helper::createLink('execution', 'importTask', "execution=$execution->id"), 'data-toggle' => 'modal');
if($canBatchCreateTask) $createMenu[] = array('text' => $lang->execution->batchCreateTask, 'url' => helper::createLink('task', 'batchCreate', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg');

jsVar('laneCount', $laneCount);
jsVar('kanbanLang', $lang->kanban);
jsVar('storyLang', $lang->story);
jsVar('executionLang', $lang->execution);
jsVar('laneLang', $lang->kanbanlane);
jsVar('cardLang', $lang->kanbancard);
jsVar('bugLang', $lang->bug);
jsVar('taskLang', $lang->task);
jsVar('executionID', $execution->id);
jsVar('productID', $productID);
jsVar('productCount', count($productNames));
jsVar('vision', $config->vision);
jsVar('groupBy', $groupBy);
jsVar('browseType', $browseType);
jsVar('orderBy', $orderBy);
jsVar('minColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth);
jsVar('maxColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth);
jsVar('priv',
    array(
        'canCreateTask'         => $canCreateTask,
        'canBatchCreateTask'    => $canBatchCreateTask,
        'canImportBug'          => $canImportBug,
        'canCreateBug'          => $canCreateBug,
        'canBatchCreateBug'     => $canBatchCreateBug,
        'canCreateStory'        => $canCreateStory,
        'canBatchCreateStory'   => $canBatchCreateStory,
        'canLinkStory'          => $canLinkStory,
        'canLinkStoryByPlan'    => $canLinkStoryByPlan,
        'canViewBug'            => common::hasPriv('bug', 'view'),
        'canAssignBug'          => common::hasPriv('bug', 'assignto'),
        'canConfirmBug'         => common::hasPriv('bug', 'confirm'),
        'canResolveBug'         => common::hasPriv('bug', 'resolve'),
        'canCopyBug'            => common::hasPriv('bug', 'create'),
        'canEditBug'            => common::hasPriv('bug', 'edit'),
        'canDeleteBug'          => common::hasPriv('bug', 'delete'),
        'canActivateBug'        => common::hasPriv('bug', 'activate'),
        'canViewTask'           => common::hasPriv('task', 'view'),
        'canAssignTask'         => common::hasPriv('task', 'assignto'),
        'canFinishTask'         => common::hasPriv('task', 'finish'),
        'canPauseTask'          => common::hasPriv('task', 'pause'),
        'canCancelTask'         => common::hasPriv('task', 'cancel'),
        'canCloseTask'          => common::hasPriv('task', 'close'),
        'canActivateTask'       => common::hasPriv('task', 'activate'),
        'canActivateStory'      => common::hasPriv('story', 'activate'),
        'canStartTask'          => common::hasPriv('task', 'start'),
        'canRestartTask'        => common::hasPriv('task', 'restart'),
        'canEditTask'           => common::hasPriv('task', 'edit'),
        'canDeleteTask'         => common::hasPriv('task', 'delete'),
        'canRecordWorkhourTask' => common::hasPriv('task', 'recordWorkhour'),
        'canToStoryBug'         => common::hasPriv('story', 'create'),
        'canAssignStory'        => common::hasPriv('story', 'assignto'),
        'canEditStory'          => common::hasPriv('story', 'edit'),
        'canDeleteStory'        => common::hasPriv('story', 'delete'),
        'canChangeStory'        => common::hasPriv('story', 'change'),
        'canCloseStory'         => common::hasPriv('story', 'close'),
        'canUnlinkStory'        => (common::hasPriv('execution', 'unlinkStory') && !empty($execution->hasProduct)),
        'canViewStory'          => common::hasPriv('execution', 'storyView')
    )
);

if(!$features['story']) unset($lang->kanban->type['story']);
if(!$features['qa'])    unset($lang->kanban->type['bug']);
featureBar
(
    ($features['story'] or $features['qa']) ? inputControl
    (
        setClass('c-type'),
        picker
        (
            set::width('200'),
            set::name('type'),
            set::items($lang->kanban->type),
            set::value($browseType),
            set::required(true),
            set::onchange('changeBrowseType()'),
        )
    ) : null,
    $browseType != 'all' ? inputControl
    (
        setClass('c-group ml-5'),
        picker
        (
            set::width('200'),
            set::name('group'),
            set::items($lang->kanban->group->$browseType),
            set::value($groupBy),
            set::required(true),
            set::onchange('changeGroupBy()'),
        )
    ) : null,
);

$editModule = $execution->multiple ? 'execution' : 'project';
$editParams = $execution->multiple ? "executionID={$execution->id}" : "projectID={$execution->project}";

toolbar
(
    inputGroup
    (
        set::style(array('display' => 'none')),
        setID('kanbanSearch'),
        inputControl
        (
            setID('searchBox'),
            setClass('search-box'),
            input
            (
                setID('kanbanSearchInput'),
                set::name('kanbanSearchInput'),
                set::placeholder($lang->execution->pleaseInput)
            )
        )
    ),
    btn(setClass('querybox-toggle ghost btn-default'), set::onclick('toggleSearchBox()'), set::icon('search'), $lang->searchAB),
    btnGroup
    (
        btn
        (
            set
            (
                array
                (
                    'class' => 'btn ghost btn-default',
                    'url'   => 'javascript:fullScreen();',
                    'icon'  => 'fullscreen'
                )
            ),
            $lang->kanban->fullScreen
        ),
        common::hasPriv('execution', 'setKanban') ? btn
        (
            set
            (
                array
                (
                    'class' => 'btn ghost btn-default',
                    'url'   => inlink('setKanban', "id=$execution->id"),
                    'icon'  => 'cog-outline',
                    'data-toggle' => 'modal'
                )
            ),
            $lang->settings
        ) : null,
        common::hasPriv($editModule, 'edit') ? btn
        (
            set
            (
                array
                (
                    'class' => 'btn ghost btn-default',
                    'url'   => createLink($editModule, 'edit', $editParams),
                    'icon'  => 'edit',
                    'data-toggle' => 'modal',
                    'data-size' => 'lg'
                )
            ),
            $lang->edit
        ) : null
    ),
    $operationMenu ? dropdown
    (
        btn
        (
            setClass('ghost btn square btn-default'),
            set::icon('ellipsis-v'),
        ),
        set::caret(false),
        set::items($operationMenu)
    ) : null,
    $createMenu ? dropdown
    (
        btn
        (
            setClass('primary btn square btn-default'),
            set::icon('plus'),
            $lang->create,
        ),
        set::items($createMenu)
    ) : null
);

div
(
    set::id('kanbanList'),
    zui::kanbanList
    (
        set::key('kanban'),
        set::items($kanbanList),
        set::height('calc(100vh - 120px)')
    )
);

modal
(
    setID('linkStoryByPlan'),
    setData('size', '500px'),
    to::header
    (
        h4($lang->execution->linkStoryByPlan),
        "({$lang->execution->linkStoryByPlanTips})"
    ),
    inputGroup
    (
        setClass('mt-1'),
        picker
        (
            set::width(300),
            setID('plan'),
            set::name('plan'),
            set::items($allPlans)
        ),
        span
        (
            setClass('input-group-btn ml-2'),
            btn(setClass('primary'), setID('toStoryButton'), set::onclick('linkPlanStory()'), $lang->execution->linkStory)
        )
    )
);

modal
(
    setID('batchCreateStory'),
    to::header
    (
        h4($lang->bug->product)
    ),
    setData('size', '500px'),
    inputGroup
    (
        setClass('mt-3'),
        picker
        (
            set::width(300),
            set::name('productName'),
            set::items($productNames),
            set::required(true),
            set::onchange('changeStoryProduct()')
        ),
        span
        (
            setClass('input-group-btn ml-2'),
            btn
            (
                setClass('primary'),
                setID('batchCreateStoryButton'),
                set::url(createLink('story', 'batchCreate', 'productID=' . key($productNames) . '&branch=moduleID=0&storyID=0&executionID=' . $executionID)),
                set('data-toggle', 'modal'),
                set('data-dismiss', 'modal'),
                set('data-size', 'lg'),
                $lang->story->batchCreate
            )
        )
    )
);

modal
(
    setID('batchCreateBug'),
    to::header
    (
        h4($lang->bug->product)
    ),
    setData('size', '500px'),
    inputGroup
    (
        setClass('mt-3'),
        picker
        (
            set::width(300),
            set::name('productName'),
            set::items($productNames),
            set::required(true),
            set::onchange('changeBugProduct()')
        ),
        span
        (
            setClass('input-group-btn ml-2'),
            btn
            (
                setClass('primary'),
                setID('batchCreateBugButton'),
                set::url(createLink('bug', 'batchCreate', 'productID=' . key($productNames) . '&branch=&executionID=' . $executionID)),
                set('data-toggle', 'modal'),
                set('data-dismiss', 'modal'),
                set('data-size', 'lg'),
                $lang->bug->batchCreate
            )
        )
    )
);
