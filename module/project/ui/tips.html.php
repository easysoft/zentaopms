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
$showBackToExecutionList = $multiple;

$backUrl = createLink('project', 'execution', "status=undone&projectID={$projectID}");
if(!$isKanbanProject && !$multiple) $backUrl = createLink('execution', 'task', "executionID={$executionID}");
if($isKanbanProject) $backUrl = $multiple ? createLink('project', 'index', "projectID={$projectID}") : createLink('execution', 'kanban', "executionID={$executionID}");

panel
(
    setID('tipsModal'),
    set::title($lang->project->tips),
);
