<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        https://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = 'Tutorial';
$lang->tutorial->desc             = 'You can know how to use ZenTao by doing tasks. It takes about 10 minutes, and you can quit anytime.';
$lang->tutorial->start            = "Let's go!";
$lang->tutorial->exit             = 'Quit';
$lang->tutorial->congratulation   = 'Congratulations! You have completed all tasks.';
$lang->tutorial->restart          = 'Restart';
$lang->tutorial->currentTask      = 'Current Task';
$lang->tutorial->allTasks         = 'All Tasks';
$lang->tutorial->previous         = 'Previous';
$lang->tutorial->nextTask         = 'Next';
$lang->tutorial->openTargetPage   = 'Open <strong class="task-page-name">%s</strong>';
$lang->tutorial->atTargetPage     = 'On <strong class="task-page-name">%s</strong>';
$lang->tutorial->reloadTargetPage = 'Reload';
$lang->tutorial->target           = 'Target';
$lang->tutorial->targetPageTip    = 'Open【%s】page by following this instruction.';
$lang->tutorial->targetAppTip     = 'Open <strong class="task-page-name">%s</strong>';
$lang->tutorial->requiredTip      = '【%s】is required.';
$lang->tutorial->congratulateTask = 'Congratulations! You have finished【<span class="task-name-current"></span>】!';
$lang->tutorial->serverErrorTip   = 'Error!';
$lang->tutorial->ajaxSetError     = 'Finished task must be defined. If you want to reset the Task, please set its value as null.';
$lang->tutorial->novice           = "For a quick start, let's go through a two-minute Tutorial.";
$lang->tutorial->dataNotSave      = "Data generated in this Tutorial will not be saved!";

$lang->tutorial->tasks = new stdClass();
$lang->tutorial->tasks->createAccount = new stdClass();

$lang->tutorial->tasks->createAccount->title          = 'Create a User';
$lang->tutorial->tasks->createAccount->targetPageName = 'Add User';
$lang->tutorial->tasks->createAccount->desc           = "<p>Create a User: </p><ul><li data-target='nav'>Open <span class='task-nav'>Admin <i class='icon icon-angle-right'></i> Company <i class='icon icon-angle-right'></i> Users<i class='icon icon-angle-right'></i> New;</span></li><li data-target='form'>Fill the form with user information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createProgram = new stdClass();

$lang->tutorial->tasks->createProgram->title          = 'Create a program';
$lang->tutorial->tasks->createProgram->targetPageName = 'Create program';
$lang->tutorial->tasks->createProgram->desc           = "<p>Create a new program：</p><ul><li data-target='nav'>Open <span class='task-nav'>Program <i class='icon icon-angle-right'></i> Program list <i class='icon icon-angle-right'></i> Create program</span>;</li><li data-target='form'>Fill the form with program information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createProduct = new stdClass();

$lang->tutorial->tasks->createProduct->title          = 'Create a product';
$lang->tutorial->tasks->createProduct->targetPageName = 'Create product';
$lang->tutorial->tasks->createProduct->desc           = "<p>Create a new product：</p><ul><li data-target='nav'>Open <span class='task-nav'>Product <i class='icon icon-angle-right'></i> Product list <i class='icon icon-angle-right'></i> Create product</span>;</li><li data-target='form'>Fill the form with product information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createStory = new stdClass();

$lang->tutorial->tasks->createStory->title          = 'Create a story';
$lang->tutorial->tasks->createStory->targetPageName = 'Create story';
$lang->tutorial->tasks->createStory->desc           = "<p>Create a story: </p><ul><li data-target='nav'>Open <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i>Story <i class='icon icon-angle-right'></i>Create;</span></li><li data-target='form'>Fill the form with story information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createProject = new stdClass();

$lang->tutorial->tasks->createProject->title          = 'Create a project';
$lang->tutorial->tasks->createProject->targetPageName = 'Create project';
$lang->tutorial->tasks->createProject->desc           = "<p>Create a project: </p><ul><li data-target='nav'>Open <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i>Project <i class='icon icon-angle-right'></i>Create;</span></li><li data-target='form'>Fill the form with project information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->manageTeam = new stdClass();
$lang->tutorial->tasks->manageTeam->title          = "Manage {$lang->projectCommon} Team";
$lang->tutorial->tasks->manageTeam->targetPageName = "Manage team members";
$lang->tutorial->tasks->manageTeam->desc           = "<p>Manage {$lang->projectCommon} team members: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> Team <i class='icon icon-angle-right'></i> Manage Team Members</span> Page；</li><li data-target='form'>Choose users for the team.</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createProjectExecution = new stdClass();
$lang->tutorial->tasks->createProjectExecution->title          = "Create a {$lang->executionCommon}";
$lang->tutorial->tasks->createProjectExecution->targetPageName = "Create {$lang->executionCommon}";
$lang->tutorial->tasks->createProjectExecution->desc           = "<p>Create a new {$lang->executionCommon}：</p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} list <i class='icon icon-angle-right'></i> Create {$lang->executionCommon}</span>;</li><li data-target='form'>Fill the form with {$lang->executionCommon} information；</li><li data-target='submit'>Save {$lang->executionCommon}</li></ul>";

$lang->tutorial->tasks->linkStory = new stdClass();
$lang->tutorial->tasks->linkStory->title          = "Link a Story";
$lang->tutorial->tasks->linkStory->targetPageName = "Link {$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->desc           = "<p>Link a Story to execution: </p><ul><li data-target='nav'>Open <span class='task-nav'> Execution <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i>Link Story;</span></li><li data-target='form'>Select stories from story list to relate;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createTask = new stdClass();
$lang->tutorial->tasks->createTask->title          = "Task Breakdown";
$lang->tutorial->tasks->createTask->targetPageName = "Create Task";
$lang->tutorial->tasks->createTask->desc           = "<p>Task breakdown for a story: </p><ul><li data-target='nav'>Open <span class='task-nav'> Execution <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> WBS;</span></li><li data-target='form'>Fill the form with task information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks->createBug = new stdClass();
$lang->tutorial->tasks->createBug->title          = "Report Bug";
$lang->tutorial->tasks->createBug->targetPageName = "Report Bug";
$lang->tutorial->tasks->createBug->desc           = "<p>Report a Bug: </p><ul><li data-target='nav'>Open <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Report Bug</span>；</li><li data-target='form'>Fill the form with bug information:</li><li data-target='submit'>Save</li></ul>";
