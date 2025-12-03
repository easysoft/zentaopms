<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

jsVar('delayWarning', $lang->task->delayWarning);
jsVar('todayLabel', $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);

$isInModal = isInModal();

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && hasPriv('task', 'create', $task))
{
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->task->create,
        'url'  => createLink('task', 'create', "executionID={$task->execution}"),
        'data-app' => $app->tab == 'project' ? 'project' : ''
    );
}

/* 初始化底部操作栏。Init bottom actions. */
$config->task->actionList['batchCreate']['hint'] = $config->task->actionList['batchCreate']['text'] = $lang->task->children;

/* 检查是否需要确认撤销/移除。*/
/* Build confirmeObject. */
if($this->config->edition == 'ipd')
{
    $execution->canStartExecution = $this->loadModel('execution')->checkStageStatus($execution->id, 'start');
    $task = $this->loadModel('story')->getAffectObject(array(), 'task', $task);

    if(!empty($task->confirmeActionType)) $config->task->actions->view['mainActions']   = array('confirmDemandRetract', 'confirmDemandUnlin');
    if(!empty($task->confirmeActionType)) $config->task->actions->view['suffixActions'] = array();
}

$task->executionInfo = $execution;
$task->estimate      = helper::formatHours($task->estimate);
$task->consumed      = helper::formatHours($task->consumed);
$task->left          = helper::formatHours($task->left);
$actions             = !$task->deleted && common::canModify('execution', $execution) ? $this->loadModel('common')->buildOperateMenu($task) : array();
$hasDivider          = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);
foreach($actions as $key => $action)
{
    if(isset($action['url']) && strpos($action['url'], 'createBranch') !== false && empty($hasGitRepo)) unset($actions[$key]);
    if(isset($action['url']) && strpos($action['url'], 'view') !== false && strpos($action['url'], 'review') === false && strpos($action['url'], 'delete') === false)
    {
        if($isInModal)
        {
            $actions[$key]['data-toggle'] = 'modal';
            $actions[$key]['data-size']   = 'lg';
        }
        if($task->parent == 0) unset($actions[$key]);
    }
    if($isInModal && isset($actions[$key]['data-load']))
    {
        unset($actions[$key]['data-load']);
        $actions[$key]['data-toggle'] = 'modal';
    }
    if($isInModal && isset($action['url']) && stripos($action['url'], 'batchCreate') !== false)
    {
        $actions[$key]['data-toggle'] = 'modal';
        $actions[$key]['data-size']   = 'lg';
    }
    if(isset($actions[$key]['url'])) $actions[$key]['url'] = str_replace(array('{rawStory}', '{module}', '{parent}', '{execution}'), array($task->story, $task->module, $task->parent, $task->execution), $action['url']);

    // 如果是删除按钮且是父任务，修改提示语
    if(isset($action['url']) && strpos($action['url'], 'delete') !== false && $task->isParent)
    {
        $actions[$key]['data-confirm'] = array('message' => $lang->task->confirmDeleteParent, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
    }

    if($isInModal && isset($action['icon']) && $action['icon'] == 'edit')
    {
        $actions[$key]['data-toggle'] = 'modal';
        $actions[$key]['data-size']   = 'lg';
    }
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->task->legendDesc)
    ->control('html')
    ->content(empty($task->desc) ? $lang->noDesc : $task->desc);
if($task->fromBug)
{
    $sections[] = setting()
        ->title($lang->task->fromBug)
        ->control('detailCard')
        ->url(createLink('bug', 'view', "bugID=$fromBug->id"))
        ->object($fromBug)
        ->content(setting()->title($lang->bug->steps)->control('html')->content(empty($fromBug->steps) ? $lang->noData : $fromBug->steps));
}
if(!$task->fromBug && $task->story)
{
    $disabledConirmStoryChange = false;
    $hintConirmStoryChange     = '';
    if(empty($task->team) && !empty($task->assignedTo) && $task->assignedTo != $this->app->user->account)
    {
        $disabledConirmStoryChange = true;
        $hintConirmStoryChange     = $lang->task->disabledHint->assignedConfirmStoryChange;
    }
    elseif(!empty($task->team))
    {
        $taskMembers               = !empty($task->team) ? array_column($task->team, 'account') : array();
        $disabledConirmStoryChange = !in_array($this->app->user->account, $taskMembers);
        if($disabledConirmStoryChange) $hintConirmStoryChange = $lang->task->disabledHint->memberConfirmStoryChange;
    }

    $storyDetailProps = array
    (
        'control'  => 'detailCard',
        'title'    => $task->storyTitle,
        'url'      => createLink('story', 'view', "storyID=$task->storyID") . ($execution->multiple ? '' : '#app=project'),
        'objectID' => $task->storyID,
        'color'    => '',
        'deleted'  => $task->storyDeleted,
        'toolbar'  => $task->needConfirm ? array
        (
            array('text' => $lang->task->storyChange, 'class' => 'ghost pointer-events-none'),
            array('text' => $lang->confirm, 'type' => 'primary', 'class' => 'ajax-submit', 'url' => createLink('task', 'confirmStoryChange', "taskID={$task->id}"), 'disabled' => $disabledConirmStoryChange, 'hint' => $hintConirmStoryChange)
        ) : null,
        'sections' => array
        (
            setting()->title("[{$lang->story->legendSpec}]")->control('html')->content(empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec),
            setting()->title("[{$lang->task->storyVerify}]")->control('html')->content(empty($task->storyVerify) ? $lang->noData : $task->storyVerify),
            setting()->title("[{$lang->task->storyFiles}]")->control('fileList')->files($task->storyFiles)->padding(false)->showEdit(false)->showDelete(false)
        )
    );
    $sections[] = setting()
    ->title($lang->task->story)
    ->control($storyDetailProps);
}
if(!empty($task->cases))
{
    $sections[] = setting()
        ->title($lang->task->case)
        ->control('entityList')
        ->type('testcase')
        ->items($task->cases);
}
if($task->children)
{
    foreach($task->children as $child)
    {
        $child->rawStory = $child->story;
        if($child->mode == 'multi' && strpos('done,closed', $child->status) === false)
        {
            $child->assignedTo = '';

            $taskTeam = $this->task->getTeamByTask($child->id);
            foreach($taskTeam as $teamMember)
            {
                if($this->app->user->account == $teamMember->account && $teamMember->status != 'done')
                {
                    $child->assignedTo = $this->app->user->account;
                    break;
                }
            }
        }
    }
    $children = initTableData($task->children, $config->task->dtable->children->fieldList, $this->task);
    $sections[] = setting()
        ->title($lang->task->children)
        ->control('dtable')
        ->className('ring')
        ->defaultNestedState(true)
        ->onRenderCell(jsRaw('window.renderCell'))
        ->cols(array_values($config->task->dtable->children->fieldList))
        ->userMap($users)
        ->data($children)
        ->checkable(false);
}
if($task->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $task->files,
        'object'     => $task,
        'padding'    => false,
        'showEdit'   => false,
        'showDelete' => false
    );
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->task->legendBasic)
    ->control('taskBasicInfo')
    ->statusText($this->processStatus('task', $task));

/* 一生信息。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->task->legendLife)
    ->control('taskLifeInfo');

if($task->team)
{
    $tabs[] = setting()
        ->group('basic')
        ->title($lang->task->team)
        ->control('taskTeam');
}

$tabs[] = setting()
    ->group('related')
    ->title($lang->task->legendEffort)
    ->control('taskEffortInfo');
$tabs['taskMiscInfo'] = setting()
    ->group('related')
    ->title($lang->task->legendMisc)
    ->control('taskMiscInfo');

detail
(
    $task->parent > 0 ? array
    (
        set::parentTitle($task->parentName),
        set::parentUrl(createLink('task', 'view', "taskID={$task->parent}")),
        set::parentTitleProps(array('data-load' => 'modal')),
        to::title(to::leading(label(setClass('gray-pale rounded-full'), $lang->task->childrenAB)))
    ) : null,
    set::urlFormatter(array('{id}' => $task->id, '{parent}' => $task->parent, '{execution}' => $task->execution, '{confirmeObjectID}' => isset($task->confirmeObjectID) ? $task->confirmeObjectID : 0)),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);
