<?php
declare(strict_types=1);
/**
 * The tips view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$productID = key($products);
$objectID  = !empty($executionID) ? $executionID : $projectID;

$executionLang = $lang->execution->typeList['sprint'];
if($project->model == 'kanban') $executionLang = $lang->execution->typeList['kanban'];
if($project->model == 'waterfall' || $project->model == 'waterfallplus') $executionLang = $lang->execution->typeList['stage'];

$isKanbanProject         = $project->model == 'kanban';
$hasProduct              = !empty($project->hasProduct);
$multiple                = !empty($project->multiple);
$showLinkStory           = !$isKanbanProject && $hasProduct;
$showCreateStory         = !$isKanbanProject && !$hasProduct;
$showCreateTask          = !$multiple && strpos(',scrum,agileplus,', ",{$project->model},") !== false;
$showCreateExecution     = $multiple && strpos(',scrum,agileplus,kanban,', ",{$project->model},") !== false;
$showSetDoc              = !$isKanbanProject;
$showBackToTaskList      = !$isKanbanProject && !$multiple;
$showBackToKanban        = $isKanbanProject && !$multiple;
$showBackToExecutionList = $multiple;

$backUrl = createLink('project', 'execution', "status=undone&projectID={$projectID}");
if(!$isKanbanProject && !$multiple) $backUrl = createLink('execution', 'task', "executionID={$executionID}");
if($isKanbanProject) $backUrl = $multiple ? createLink('project', 'index', "projectID={$projectID}") : createLink('execution', 'kanban', "executionID={$executionID}");

panel
(
    setID('tipsModal'),
    set::title($lang->project->tips),
    set::headingActions(array
    (
        array('url' => $backUrl, 'icon' => 'close', 'class' => 'ghost', 'data-app' => 'project')
    )),
    setClass('m-auto'),
    div
    (
        set::className('flex items-center mt-2'),
        icon('check-circle text-success icon-2x mr-2'),
        span
        (
            set::className('text-md font-bold tip-title'),
            $lang->project->afterInfo
        )
    ),
    div
    (
        setClass('mt-5 mb-5'),
        btn
        (
            set::className('mr-2 tipBtn ml-1'),
            $lang->project->setTeam,
            set::url(createLink('project', 'team', "projectID={$projectID}"))
        ),
        $showLinkStory ? btn
        (
            set::className('mr-2 tipBtn linkstory-btn'),
            $lang->project->linkStory,
            setData('app', 'project'),
            set::url(createLink($multiple ? 'projectstory' : 'execution', 'linkstory', "objectID={$objectID}"))
        ) : null,
        $showCreateStory ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->project->createStory,
            setData('app', 'project'),
            set::url(createLink('story', 'create', "productID={$productID}&branch=0&moduleID=0&storyID=0&objectID=$objectID"))
        ) : null,
        $showCreateTask ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->project->createTask,
            setData('app', 'project'),
            set::url(createLink('task', 'create', "executionID=$executionID"))
        ) : null,
        $showCreateExecution ? btn
        (
            set::className('mr-2 tipBtn'),
            sprintf($lang->project->createExecutionTip, $executionLang),
            set::url(createLink('execution', 'create', "projectID=$projectID"))
        ) : null,
        $showSetDoc ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->project->setDoc,
            setData('app', 'project'),
            set::url(createLink('doc', 'projectSpace', "objectID=$objectID"))
        ) : null,
        $showBackToTaskList ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->project->backToTaskList,
            setData('app', 'project'),
            set::url(createLink('execution', 'task', "executionID={$executionID}"))
        ) : null,
        $showBackToKanban ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->project->backToKanban,
            setData('app', 'project'),
            set::url(createLink('execution', 'kanban', "executionID={$executionID}"))
        ) : null,
        $showBackToExecutionList ? btn
        (
            set::className('mr-2 tipBtn'),
            sprintf($lang->project->backToExecutionList, $executionLang),
            set::url(createLink('project', 'execution', "status=undone&projectID={$projectID}"))
        ) : null,
        btn
        (
            set::className('tipBtn'),
            $lang->project->backToProjectList,
            set::url(createLink('project', 'browse'))
        )
    )
);
