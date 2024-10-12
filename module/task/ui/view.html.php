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
        'url'  => createLink('task', 'create', "executionID={$task->execution}")
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
$actions             = !$task->deleted && common::canModify('execution', $execution) ? $this->loadModel('common')->buildOperateMenu($task) : array();
$hasDivider          = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);
foreach($actions as $key => $action)
{
    if(isset($action['url']) && strpos($action['url'], 'createBranch') !== false && empty($hasGitRepo)) unset($actions[$key]);
    if(isset($action['url']) && strpos($action['url'], 'view') !== false && strpos($action['url'], 'review') === false)
    {
        if($isInModal)
        {
            $actions[$key]['data-toggle'] = 'modal';
            $actions[$key]['data-size']   = 'lg';
        }
        if($task->parent == 0) unset($actions[$key]);
    }
    if(isonlybody() && isset($actions[$key]['data-load']))
    {
        unset($actions[$key]['data-load']);
        $actions[$key]['data-toggle'] = 'modal';
    }
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace(array('{story}', '{module}', '{parent}', '{execution}'), array($task->story, $task->module, $task->parent, $task->execution), $action['url']);
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
    $storyDetailProps = array
    (
        'control'  => 'detailCard',
        'title'    => $task->storyTitle,
        'url'      => createLink('story', 'view', "storyID=$task->storyID") . ($execution->multiple ? '' : '#app=project'),
        'objectID' => $task->storyID,
        'color'    => '',
        'toolbar'  => $task->needConfirm ? array
        (
            array('text' => $lang->task->storyChange, 'class' => 'ghost pointer-events-none'),
            array('text' => $lang->confirm, 'type' => 'primary', 'url' => createLink('task', 'confirmStoryChange', "taskID={$task->id}"))
        ) : null,
        'sections' => array
        (
            setting()->title("[{$lang->story->legendSpec}]")->control('html')->content(empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec),
            setting()->title("[{$lang->task->storyVerify}]")->control('html')->content(empty($task->storyVerify) ? $lang->noData : $task->storyVerify),
            setting()->title("[{$lang->task->storyFiles}]")->control('fileList')->files($task->storyFiles)->padding(false)
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
    $children = initTableData($task->children, $config->task->dtable->children->fieldList, $this->task);
    $sections[] = setting()
        ->title($lang->task->children)
        ->control('dtable')
        ->className('ring')
        ->cols(array_values($config->task->dtable->children->fieldList))
        ->userMap($users)
        ->data($children)
        ->checkable(false);
}
if($task->files)
{
    $sections[] = array
    (
        'control' => 'fileList',
        'files'   => $task->files,
        'object'  => $task,
        'padding' => false
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
$tabs[] = setting()
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
