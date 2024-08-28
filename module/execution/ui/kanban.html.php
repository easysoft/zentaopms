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

$canModifyExecution = common::canModify('execution', $execution);

$laneCount = 0;
foreach($kanbanList as $current => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $groupID = $group['id'];

        $group['getLane']     = jsRaw('window.getLane');
        $group['getCol']      = jsRaw('window.getCol');
        $group['getItem']     = jsRaw('window.getItem');
        $group['minColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth;
        $group['maxColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth;
        $group['colProps']    = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps']   = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps']   = array('actions' => jsRaw('window.getItemActions'));

        if($execution->displayCards > 0)
        {
            $group['minLaneHeight'] = $execution->displayCards * 70;
            $group['maxLaneHeight'] = $execution->displayCards * 70;
        }

        if($canModifyExecution)
        {
            $group['canDrop'] = jsRaw('window.canDrop');
            $group['onDrop']  = jsRaw('window.onDrop');
        }

        $kanbanList[$current]['items'][$index] = $group;
    }

    $laneCount += isset($region['laneCount']) ? $region['laneCount'] : 0;
    $links      = isset($region['links']) ? $region['links'] : array();
}

$operationMenu = array();
if($this->execution->isClickable($execution, 'start'))    $operationMenu[] = array('text' => $lang->execution->start, 'url' => inlink('start', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'start');
if($this->execution->isClickable($execution, 'putoff'))   $operationMenu[] = array('text' => $lang->execution->putoff, 'url' => inlink('putoff', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'calendar');
if($this->execution->isClickable($execution, 'suspend'))  $operationMenu[] = array('text' => $lang->execution->suspend, 'url' => inlink('suspend', "id=$execution->id"), 'data-toggle' => 'modal', 'icon'=> 'pause');
if($this->execution->isClickable($execution, 'close'))    $operationMenu[] = array('text' => $lang->execution->close, 'url' => inlink('close', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'off');
if($this->execution->isClickable($execution, 'activate')) $operationMenu[] = array('text' => $lang->execution->activate, 'url' => inlink('activate', "id=$execution->id"), 'data-toggle' => 'modal', 'icon' => 'off');
if($this->execution->isClickable($execution, 'delete'))   $operationMenu[] = array('text' => $lang->delete, 'url' => inlink('delete', "id=$execution->id&confirm=no"), 'innerClass' => 'ajax-submit', 'icon' => 'trash');

$canCreateTask      = $canModifyExecution && common::hasPriv('task', 'create');
$canBatchCreateTask = $canModifyExecution && common::hasPriv('task', 'batchCreate');
$canImportTask      = $canModifyExecution && common::hasPriv('execution', 'importTask') && $execution->multiple && $this->config->vision != 'lite';

$canCreateBug        = $features['qa'] && $canModifyExecution && common::hasPriv('bug', 'create') && $productID && $this->config->vision != 'lite';
$canBatchCreateBug   = $features['qa'] && $canModifyExecution && common::hasPriv('bug', 'batchCreate') && $execution->multiple && $productID && $this->config->vision != 'lite';
$canImportBug        = $features['qa'] && $canModifyExecution && common::hasPriv('execution', 'importBug') && $execution->multiple && $productID && $this->config->vision != 'lite';
$hasBugButton        = $canCreateBug || $canBatchCreateBug;

$canCreateStory      = $features['story'] && $canModifyExecution && common::hasPriv('story', 'create') && common::canModify('execution', $execution) && $productID && $this->config->vision != 'lite';
$canBatchCreateStory = $features['story'] && $canModifyExecution && common::hasPriv('story', 'batchCreate') && common::canModify('execution', $execution) && $productID && $this->config->vision != 'lite';

$canLinkStory        = $features['story'] && $canModifyExecution && common::hasPriv('execution', 'linkStory') && !empty($execution->hasProduct) && common::canModify('execution', $execution) && $productID && $this->config->vision != 'lite';
$canLinkStoryByPlan  = $features['story'] && $canModifyExecution && common::hasPriv('execution', 'importplanstories') && !empty($project->hasProduct) && common::canModify('execution', $execution) && $productID && $this->config->vision != 'lite';
$hasStoryButton      = $features['story'] && ($canCreateStory || $canBatchCreateStory || $canLinkStory || $canLinkStoryByPlan);

$hasTaskButton = $canCreateTask || $canBatchCreateTask || $canImportBug;

$createMenu = array();
$modal      = $productID ? 'modal' : false;
if($canCreateStory)                   $createMenu[] = array('text' => $lang->story->create, 'url' => $productID ? helper::createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id") : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($canBatchCreateStory)              $createMenu[] = array('text' => $lang->story->batchCreate, 'url' => $productID ? (count($productNames) > 1 ? '#batchCreateStory' : helper::createLink('story', 'batchCreate', "productID=$productID&branch=$branchID&moduleID=0&story=0&execution=$execution->id")) : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($canLinkStory)                     $createMenu[] = array('text' => $lang->execution->linkStory, 'url' => $productID ? helper::createLink('execution', 'linkStory', "execution=$execution->id") : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'lg', 'data-on' => 'click', 'data-call' => 'checkProducts', 'class' => 'linkStory-btn');
if($canLinkStoryByPlan)               $createMenu[] = array('text' => $lang->execution->linkStoryByPlan, 'url' => $productID ? "#linkStoryByPlan" : 'javascript:;', 'data-toggle' => $modal, 'data-size' => 'sm', 'data-on' => 'click', 'data-call' => 'checkProducts');
if($hasStoryButton && $hasTaskButton) $createMenu[] = array('type' => 'divider');
if($canCreateBug)                     $createMenu[] = array('text' => $lang->bug->create, 'url' => helper::createLink('bug', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg', 'class' => 'bug-create-btn');
if($canBatchCreateBug)
{
    if(count($productNames) > 1)
    {
        $createMenu[] = array('text' => $lang->bug->batchCreate, 'url' => '#batchCreateBug', 'data-toggle' => 'modal');
    }
    else
    {
        $createMenu[] = array('text' => $lang->bug->batchCreate, 'url' => helper::createLink('bug', 'batchCreate', "productID=$productID&branch=$branchID&executionID=$execution->id&extra="), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}
if(($hasStoryButton or $hasBugButton) and $hasTaskButton) $createMenu[] = array('type' => 'divider');
if($canCreateTask) $createMenu[] = array('text' => $lang->task->create, 'url' => helper::createLink('task', 'create', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg');
if($canImportBug)  $createMenu[] = array('text' => $lang->execution->importBug, 'url' => helper::createLink('execution', 'importBug', "execution=$execution->id"), 'data-toggle' => 'modal');
if($canImportTask) $createMenu[] = array('text' => $lang->execution->importTask, 'url' => helper::createLink('execution', 'importTask', "execution=$execution->id"));
if($canBatchCreateTask) $createMenu[] = array('text' => $lang->execution->batchCreateTask, 'url' => helper::createLink('task', 'batchCreate', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg');

jsVar('laneCount', $laneCount);
jsVar('kanbanLang', $lang->kanban);
jsVar('storyLang', $lang->story);
jsVar('executionLang', $lang->execution);
jsVar('laneLang', $lang->kanbanlane);
jsVar('cardLang', $lang->kanbancard);
jsVar('ERURColumn', array_keys($lang->kanban->ERURColumn));
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
        'canCreateTask'             => $canCreateTask,
        'canBatchCreateTask'        => $canBatchCreateTask,
        'canImportBug'              => $canImportBug,
        'canCreateBug'              => $canCreateBug,
        'canBatchCreateBug'         => $canBatchCreateBug,
        'canCreateStory'            => $canCreateStory,
        'canBatchCreateStory'       => $canBatchCreateStory,
        'canLinkStory'              => $canLinkStory,
        'canLinkStoryByPlan'        => $canLinkStoryByPlan,
        'canViewBug'                => common::hasPriv('bug', 'view'),
        'canAssignBug'              => common::hasPriv('bug', 'assignto') && $canModifyExecution,
        'canConfirmBug'             => common::hasPriv('bug', 'confirm') && $canModifyExecution,
        'canResolveBug'             => common::hasPriv('bug', 'resolve') && $canModifyExecution,
        'canCopyBug'                => common::hasPriv('bug', 'create') && $canModifyExecution,
        'canEditBug'                => common::hasPriv('bug', 'edit') && $canModifyExecution,
        'canDeleteBug'              => common::hasPriv('bug', 'delete') && $canModifyExecution,
        'canActivateBug'            => common::hasPriv('bug', 'activate') && $canModifyExecution,
        'canViewTask'               => common::hasPriv('task', 'view'),
        'canAssignTask'             => common::hasPriv('task', 'assignto') && $canModifyExecution,
        'canFinishTask'             => common::hasPriv('task', 'finish') && $canModifyExecution,
        'canPauseTask'              => common::hasPriv('task', 'pause') && $canModifyExecution,
        'canCancelTask'             => common::hasPriv('task', 'cancel') && $canModifyExecution,
        'canCloseTask'              => common::hasPriv('task', 'close'),
        'canActivateTask'           => common::hasPriv('task', 'activate') && $canModifyExecution,
        'canActivateStory'          => common::hasPriv('story', 'activate') && $canModifyExecution,
        'canStartTask'              => common::hasPriv('task', 'start') && $canModifyExecution,
        'canRestartTask'            => common::hasPriv('task', 'restart') && $canModifyExecution,
        'canEditTask'               => common::hasPriv('task', 'edit') && $canModifyExecution,
        'canDeleteTask'             => common::hasPriv('task', 'delete') && $canModifyExecution,
        'canRecordWorkhourTask'     => common::hasPriv('task', 'recordWorkhour') && $canModifyExecution,
        'canToStoryBug'             => common::hasPriv('story', 'create') && $canModifyExecution,
        'canAssignStory'            => common::hasPriv('story', 'assignto') && $canModifyExecution,
        'canEditStory'              => common::hasPriv('story', 'edit') && $canModifyExecution,
        'canDeleteStory'            => common::hasPriv('story', 'delete') && $canModifyExecution,
        'canChangeStory'            => common::hasPriv('story', 'change') && $canModifyExecution,
        'canCloseStory'             => common::hasPriv('story', 'close'),
        'canUnlinkStory'            => (common::hasPriv('execution', 'unlinkStory') && !empty($execution->hasProduct)) && $canModifyExecution,
        'canViewStory'              => common::hasPriv('execution', 'storyView')
    )
);

if(!$features['story']) unset($lang->kanban->type['story']);
if(!$features['qa'])    unset($lang->kanban->type['bug']);
unset($lang->kanban->type['epic'], $lang->kanban->type['requirement']);

$executionItems = array();
foreach($executionList as $childExecution) $executionItems[] = array('text' => $childExecution->name, 'url' => createLink('execution', 'kanban', "kanbanID={$childExecution->id}"));
featureBar
(
    $this->config->vision == 'lite' ? dropdown
    (
        btn(setClass('dropdown-btn'), $execution->name),
        set::items($executionItems),
    ) : null,
    (($features['story'] or $features['qa']) && $this->config->vision != 'lite') ? inputControl
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
    $browseType != 'all' && $this->config->vision != 'lite' ? inputControl
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
            setClass('primary btn btn-default create-btn'),
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
        set::height('calc(100vh - 120px)'),
        set::links($links),
        set::selectable(true),
        set::showLinkOnSelected(true)
    )
);

$linkStoryByPlanTips = $lang->execution->linkNormalStoryByPlanTips;
$linkStoryByPlanTips = $execution->multiple ? $linkStoryByPlanTips : str_replace($lang->execution->common, $lang->projectCommon, $linkStoryByPlanTips);

modal
(
    setID('linkStoryByPlan'),
    setData('size', '500px'),
    set::modalProps(array('title' => $lang->execution->linkStoryByPlan)),
    div
    (
        setClass('flex-auto'),
        icon('info-sign', setClass('warning-pale rounded-full mr-1')),
        $linkStoryByPlanTips
    ),
    form
    (
        setClass('text-center', 'py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->execution->linkStory),
        formGroup
        (
            set::label($lang->execution->selectStoryPlan),
            set::required(true),
            setClass('text-left'),
            picker
            (
                set::name('plan'),
                set::required(true),
                set::items($allPlans)
            )
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
                set::url(createLink('story', 'batchCreate', 'productID=' . key($productNames) . '&branch=0&moduleID=0&storyID=0&executionID=' . $executionID)),
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
    set::title($lang->bug->product),
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
