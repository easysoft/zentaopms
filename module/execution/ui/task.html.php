<?php
declare(strict_types=1);
/**
 * The task view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

/* zin: Define the set::module('task') feature bar on main menu. */
if(empty($features['story'])) unset($lang->execution->featureBar['task']['needconfirm']);
$queryMenuLink = createLink('execution', 'task', "executionID={$execution->id}&status=bySearch&param={queryID}");
$isFromDoc     = $from === 'doc';

jsVar('canAssignTo', common::canModify('execution', $execution) && hasPriv('task', 'assignTo'));
if($isFromDoc)
{
    $this->app->loadLang('doc');
    $executions = $this->loadModel('execution')->getPairs();
    $executionChangeLink = createLink($app->rawModule, $app->rawMethod, "executionID={executionID}&status=$status&param=$param&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");
    $insertListLink = createLink($app->rawModule, $app->rawMethod, "executionID=$executionID&status=$status&param=$param&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}");

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['task'])),
        set::actions(array()),
        set::showExtra(false),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('execution'),
                set::label($lang->doc->execution),
                set::control(array('required' => false)),
                set::items($executions),
                set::value($executionID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="execution"]')->do("loadModal('$executionChangeLink'.replace('{executionID}', $(this).val()))")
            )
        )
    );
}
featureBar
(
    set::current($browseType),
    set::linkParams("executionID={$executionID}&status={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID"),
    set::isModal($isFromDoc),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    li(searchToggle
    (
        set::simple($isFromDoc),
        set::module('task'),
        set::open($browseType == 'bysearch'),
        $isFromDoc ? set::target('#docSearchForm') : null,
        $isFromDoc ? set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}')) : null
    ))
);

if($isFromDoc)
{
    div(setID('docSearchForm'));
}

/* zin: Define the toolbar on main menu. */
$canCreate      = common::canModify('execution', $execution) && hasPriv('task', 'create');
$canBatchCreate = common::canModify('execution', $execution) && hasPriv('task', 'batchCreate');
$canImportTask  = common::canModify('execution', $execution) && hasPriv('execution', 'importTask');
$canImportBug   = common::canModify('execution', $execution) && hasPriv('execution', 'importBug');

$this->loadModel('task');
$importItems = array();
if(common::canModify('execution', $execution))
{
    $params          = isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : "";
    $batchCreateLink = $this->createLink('task', 'batchCreate', "executionID={$execution->id}{$params}")  . ($app->tab == 'project' ? '#app=project' : '');
    $createLink      = $this->createLink('task', 'create',      "executionID={$execution->id}{$params}")  . ($app->tab == 'project' ? '#app=project' : '');
    if(commonModel::isTutorialMode())
    {
        $wizardParams   = helper::safe64Encode("executionID={$execution->id}{$params}");
        $taskCreateLink = $this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams");
    }

    $createItem      = array('text' => $lang->task->create,      'url' => $createLink);
    $batchCreateItem = array('text' => $lang->task->batchCreate, 'url' => $batchCreateLink);

    if($canImportTask && $execution->multiple) $importItems[] = array('text' => $lang->execution->importTask, 'url' => $this->createLink('execution', 'importTask', "execution={$execution->id}"));
    if($canImportBug && $execution->lifetime != 'ops' && !in_array($execution->attribute, array('request', 'review')))
    {
        $importItems[] = array('text' => $lang->execution->importBug, 'url' => $this->createLink('execution', 'importBug', "execution={$execution->id}"), 'className' => 'importBug', 'data-app' => $execution->multiple ? '' : 'project');
    }
}

$cols = $this->loadModel('datatable')->getSetting('execution');

if($isFromDoc)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'assignedTo') $cols[$key]['type'] = 'user';
        if($key == 'pri') $cols[$key]['priList'] = $lang->task->priList;
        if($key == 'name') $cols[$key]['link'] = array('url' => createLink('task', 'view', "taskID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

if($execution->type != 'stage') unset($cols['design']);

$canAssignTo = common::hasPriv('task', 'assignTo');
$tableData   = initTableData($tasks, $cols, $this->task);
$lang->task->statusList['changed'] = $lang->task->storyChange;
foreach($tableData as $task)
{
    if(!isset($task->rawStatus)) $task->rawStatus = $task->status;
    $task->status      = $this->processStatus('task', $task);
    $task->rawStory    = $task->story;
    $task->story       = $task->storyTitle;
    $task->canAssignTo = $canAssignTo ? common::hasDBPriv($task, 'task', 'assignTo') : false;
    if(helper::isZeroDate($task->deadline))   $task->deadline   = '';
    if(helper::isZeroDate($task->estStarted)) $task->estStarted = '';

    $task = $this->task->processConfirmStoryChange($task);
}

if($config->edition == 'ipd')
{
    $canStartExecution = $this->execution->checkStageStatus($execution->id, 'start');
    if(!empty($canStartExecution['disabled']))
    {
        foreach($tableData as $task)
        {
            foreach($task->actions as $key => $action)
            {
                if(in_array($action['name'], array('start', 'finish', 'recordWorkhour')))
                {
                    $tip = $action['name'] . 'Tip';
                    $task->actions[$key]['disabled'] = true;
                    $task->actions[$key]['hint']     = $lang->task->disabledTip->$tip;
                }
            }
        }
    }
}

$viewType = $this->cookie->taskViewType ? $this->cookie->taskViewType : 'tree';
toolbar
(
    setClass(array('hidden' => $isFromDoc)),
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'btn-icon switchButton' . ($viewType == 'tiled' ? ' text-primary' : ''),
            'data-type' => 'tiled',
            'hint'      => $lang->task->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->task->viewTypeList['tree']
        ))
    ))),
    hasPriv('task', 'report') && empty($execution->isTpl) ? item(set(array
    (
        'icon'     => 'bar-chart',
        'class'    => 'ghost',
        'text'     => $lang->task->report->common,
        'data-app' => $app->tab,
        'url'      => createLink('task', 'report', "execution={$execution->id}&browseType={$browseType}")
    ))) : null,
    hasPriv('task', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'class'       => 'ghost export',
        'url'         => createLink('task', 'export', "execution={$execution->id}&orderBy={$orderBy}&type={$browseType}"),
        'data-toggle' => 'modal',
        'text'        => $lang->export
    ))) : null,
    $importItems ? dropdown(
        btn(
            setClass('ghost btn btn-default'),
            set::icon('import'),
            $lang->import
        ),
        set::items($importItems),
        set::placement('bottom-end')
    ) : null,
    $canCreate && $canBatchCreate ? btngroup
    (
        btn(setClass('btn primary createTask-btn'), set::icon('plus'), set::url($createLink), $lang->task->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array_filter(array($createItem, $batchCreateItem))),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary createTask-btn', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

/* zin: Define the sidebar in main content. */
$activeKey = $browseType == 'byproduct' ? $productID : $moduleID;
if(!$isFromDoc)
{
    sidebar
    (
        moduleMenu
        (
            set::modules($moduleTree),
            set::activeKey($status == 'byProduct' ? "product-{$activeKey}" : $activeKey),
            set::settingLink(createLink('tree', 'browsetask', "rootID=$execution->id&productID=0")),
            set::settingApp($execution->multiple ? 'execution' : 'project'),
            set::closeLink(createLink('execution', 'task', "executionID={$executionID}")),
            set::app($app->tab)
        )
    );
}

$firstTask            = reset($tasks);
$canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($firstTask) ? $firstTask : null);
$canBatchClose        = common::hasPriv('task', 'batchClose', !empty($firstTask) ? $firstTask : null) && strtolower($browseType) != 'closed';
$canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($firstTask) ? $firstTask : null) && strtolower($browseType) != 'cancel';
$canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($firstTask) ? $firstTask : null);
$canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($firstTask) ? $firstTask : null);
$canBatchAction       = in_array(true, array($canBatchEdit, $canBatchClose, $canBatchCancel, $canBatchChangeModule, $canBatchAssignTo));

$footToolbar = array();
if($canBatchAction)
{
    if($canBatchClose || $canBatchCancel)
    {
        $batchCancelClass = $config->edition == 'open' ? 'ajax-btn' : 'ajax-cancel-btn';
        $batchItems = array
        (
            array('text' => $lang->close,        'innerClass' => 'batch-btn ajax-btn not-open-url', 'disabled' => !$canBatchClose, 'data-url' => createLink('task', 'batchClose')),
            array('text' => $lang->task->cancel, 'innerClass' => "batch-btn $batchCancelClass not-open-url", 'disabled' => !$canBatchCancel, 'data-url' => createLink('task', 'batchCancel'))
        );
    }

    if($canBatchChangeModule)
    {
        $moduleItems = array();
        foreach($modules as $moduleID => $module)
        {
            $moduleItems[] = array('text' => $module, 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('task', 'batchChangeModule', "moduleID=$moduleID"));
        }
    }

    if($canBatchAssignTo)
    {
        $assignedToItems = array();
        foreach ($memberPairs as $account => $name)
        {
            $assignedToItems[] = array('text' => $name, 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('task', 'batchAssignTo', "executionID={$execution->id}&assignedTo={$account}"));
        }
    }

    if($canBatchClose || $canBatchCancel || $canBatchEdit)
    {
        $editClass = $canBatchEdit ? 'batch-btn' : '';
        $footToolbar['items'][] = array(
            'type'  => 'btn-group',
            'items' => array(
                array('text' => $lang->edit, 'className' => "btn size-sm {$editClass}", 'disabled' => !$canBatchEdit , 'btnType' => 'secondary', 'data-url' => createLink('task', 'batchEdit', "executionID={$execution->id}")),
                array('caret' => 'up', 'className' => 'btn btn-caret size-sm  not-open-url', 'btnType' => 'secondary', 'items' => $batchItems, 'data-placement' => 'top-start')
            )
        );
    }

    if($canBatchChangeModule) $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->task->moduleAB,   'className' => 'btn btn-caret size-sm', 'btnType' => 'secondary', 'items' => $moduleItems,    'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true));
    if($canBatchAssignTo)     $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->task->assignedTo, 'className' => 'btn btn-caret size-sm', 'btnType' => 'secondary', 'items' => $assignedToItems,'type' => 'dropdown');
}
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#tasks', 'task', $blockID, '$insertListLink')"));

jsVar('+pageSummary',   $lang->execution->pageSummary);
jsVar('checkedSummary', $lang->execution->checkedSummary);
jsVar('multipleAB',     $lang->task->multipleAB);
jsVar('childrenAB',     $lang->task->childrenAB);
jsVar('parentAB',       $lang->task->parentAB);
jsVar('todayLabel',     $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);
jsVar('teamLang',       $lang->task->team);
jsVar('delayWarning',   $lang->task->delayWarning);

if($viewType == 'tiled') $cols['name']['nestedToggle'] = false;
dtable
(
    set::id('tasks'),
    set::groupDivider(true),
    set::userMap($users),
    set::cols($cols),
    set::data($tableData),
    set::checkable($canBatchAction || $isFromDoc),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::modules($modulePairs),
    set::footToolbar($footToolbar),
    set::isFromDoc($isFromDoc),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink('execution', 'task', "executionID={$execution->id}&status={$status}&param={$param}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}&from={$from}&blockID={$blockID}") . "#app={$app->tab}"
    ))),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    !$isFromDoc ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    !$isFromDoc ? null : set::noNestedCheck(true),
    !$isFromDoc ? null : set::height(400),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink(createLink('execution', 'task', "executionID={$execution->id}&status={$status}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    $isFromDoc ? null : set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    $isFromDoc ? null : set::createTip($lang->task->create),
    $isFromDoc ? null : set::createLink($canCreate && common::canModify('execution', $execution) ? $createLink : ''),
    set::emptyTip($lang->task->noTask)
);
