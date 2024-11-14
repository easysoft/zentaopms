<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

/* 检查是否需要确认撤销/移除。*/
/* Build confirmeObject. */
if($this->config->edition == 'ipd')
{
    $bug = $this->loadModel('story')->getAffectObject(array(), 'bug', $bug);

    if(!empty($bug->confirmeActionType)) $config->bug->actions->view['mainActions'] = array('confirmDemandRetract', 'confirmDemandUnlink');
    if(!empty($bug->confirmeActionType)) $config->bug->actions->view['suffixActions'] = array();
}

jsVar('bugID',            $bug->id);
jsVar('productID',        $bug->product);
jsVar('branchID',         $bug->branch);
jsVar('errorNoExecution', $lang->bug->noExecution);
jsVar('errorNoProject',   $lang->bug->noProject);
jsVar('isInModal',        isInModal());
jsVar('executions',       $executions);
jsVar('disableExecution', $lang->project->disableExecution);

$isInModal    = isInModal();
$canCreateBug = $this->app->tab != 'devops' && hasPriv('bug', 'create');
$canViewRepo  = hasPriv('repo', 'revision');
$canViewMR    = hasPriv('mr', 'view');
$canViewBug   = hasPriv('bug', 'view');
$operateList  = $this->loadModel('common')->buildOperateMenu($bug);

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && $canCreateBug)
{
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->bug->create,
        'data-app' => $app->tab,
        'url'  => createLink('bug', 'create', "productID={$product->id}&branch={$bug->branch}&extras=projectID={$bug->project},executionID={$bug->execution},moduleID={$bug->module}")
    );
}

/* 初始化底部操作栏。Init bottom actions. */
$actions = array();
if(!$bug->deleted)
{
    /* Construct common actions for bug. */
    $actions = $operateList['mainActions'];
    if(!empty($operateList['suffixActions'])) $actions = array_merge($actions, array(array('type' => 'divider')), $operateList['suffixActions']);

    $this->loadModel('repo');
    $hasRepo        = $this->repo->getListByProduct($bug->product, implode(',', $config->repo->gitServiceTypeList), 1);
    $isExecutionTab = $app->tab == 'execution';
    foreach($actions as $key => $action)
    {
        if($isExecutionTab && !empty($action['data-app'])) unset($actions[$key]['data-app']);
        if(!$hasRepo && isset($action['icon']) && $action['icon'] == 'treemap')
        {
            unset($actions[$key]);
            continue;
        }

        if(isset($action['id']) && $action['id'] == 'toStory')
        {
            if(!empty($project))
            {
                if(empty($project->hasProduct)) $action['data-app'] = $app->tab == 'execution' ? 'execution' : 'project';
                if($project->type == 'project' && $project->multiple == '0') $action['data-app'] = 'project';
                if($isInModal)
                {
                    $action['data-load'] = 'modal';
                    $action['data-size'] = 'lg';
                }
            }
            $actions[$key] = $action;
        }
        if(!empty($action['icon']) && $action['icon'] == 'edit' && isInModal())
        {
            $action['data-load'] = 'modal';
            $action['data-size'] = 'lg';
            $actions[$key] = $action;
        }
    }
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->bug->legendSteps)
    ->control('html')
    ->content($bug->steps);

if($bug->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $bug->files,
        'object'     => $bug,
        'padding'    => false
    );
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->bug->legendBasicInfo)
    ->control('bugBasicInfo')
    ->statusText($this->processStatus('bug', $bug));

/* 一生信息。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->bug->legendLife)
    ->control('bugLifeInfo');

$tabs[] = setting()
    ->group('related')
    ->title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->control('bugRelatedInfo');

$tabs['bugRelatedList'] = setting()
    ->group('related')
    ->title($lang->bug->legendMisc)
    ->control('bugRelatedList');

detail
(
    set::urlFormatter(array('{id}' => $bug->id, '{product}' => $bug->product, '{branch}' => $bug->branch, '{project}' => $bug->project, '{execution}' => $bug->execution, '{module}' => $bug->module, '{confirmeObjectID}' => isset($bug->confirmeObjectID) ? $bug->confirmeObjectID : 0)),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);

modal
(
    setID('toTask'),
    set::modalProps(array('title' => $lang->bug->selectProjects)),
    to::footer
    (
        div
        (
            setClass('toolbar gap-4 w-full justify-center'),
            btn($lang->bug->nextStep, setID('toTaskButton'), setClass('primary')),
            btn($lang->cancel, setID('cancelButton'), setData(array('dismiss' => 'modal')))
        )
    ),
    formPanel
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(''),
        set::showExtra(false),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->selectProjects),
                set::required(true),
                set::control('picker'),
                set::name('taskProjects'),
                set::items($projects)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->execution),
                set::required(true),
                inputGroup
                (
                    setID('executionBox'),
                    picker
                    (
                        set::name('execution'),
                        set::items($executions)
                    )
                )
            )
        )
    )
);
