<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        https://www.zentao.pm
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
$lang->tutorial->openTargetPage   = 'Open <strong class="task-page-name">target</strong>';
$lang->tutorial->atTargetPage     = 'On <strong class="task-page-name">target</strong>';
$lang->tutorial->reloadTargetPage = 'Reload';
$lang->tutorial->target           = 'Target';
$lang->tutorial->targetPageTip     = 'Open【%s】page by following this instruction.';
$lang->tutorial->requiredTip      = '【%s】is required.';
$lang->tutorial->congratulateTask = 'Congratulations! You have finished【<span class="task-name-current"></span>】!';
$lang->tutorial->serverErrorTip   = 'Error!';
$lang->tutorial->ajaxSetError     = 'Finished task must be defined. If you want to reset the Task, please set its value as null.';
$lang->tutorial->novice           = "For a quick start, let's go through a two-minute Tutorial.";
$lang->tutorial->dataNotSave      = "Data generated in this Tutorial will not be saved!";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => 'Create a User');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'browseUser', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-user-btn', 'targetPageName' => 'Add User');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>Create a User: </p><ul><li data-target='nav'>Open <span class='task-nav'>Company <i class='icon icon-angle-right'></i> Users<i class='icon icon-angle-right'></i> New;</span></li><li data-target='form'>Fill the form with user information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => 'Create a ' . $lang->productCommon);
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' => $lang->projectCommon);
$lang->tutorial->tasks['createProduct']['desc'] = "<p>Create a {$lang->productCommon}: </p><ul><li data-target='nav'> Open <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> New;</span></li><li data-target='form'>Fill the form with {$lang->productCommon} information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => 'Create a Story');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Story');
$lang->tutorial->tasks['createStory']['desc'] = "<p>Create a story: </p><ul><li data-target='nav'>Open <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i>Story <i class='icon icon-angle-right'></i>Create;</span></li><li data-target='form'>Fill the form with story information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => 'Create a ' . $lang->projectCommon);
$lang->tutorial->tasks['createProject']['nav']  = array('module' => 'project', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => 'Create ' . $lang->projectCommon);
$lang->tutorial->tasks['createProject']['desc'] = "<p>Create a {$lang->projectCommon}: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> New</span> Page;</li><li data-target='form'>Fill the form with {$lang->projectCommon} information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => "Manage {$lang->projectCommon} Team");
$lang->tutorial->tasks['manageTeam']['nav']  = array('module' => 'project', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => 'Manage team members');
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>Manage {$lang->projectCommon} team members: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> Team <i class='icon icon-angle-right'></i> Manage Team Members</span> Page；</li><li data-target='form'>Choose users for the team.</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => 'Link a Story');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'project', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => 'Relate Story');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>Link a Story to {$lang->projectCommon}: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i>Relate Story;</span></li><li data-target='form'>Select stories from story list to relate;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => 'Task Breakdown');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'project', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Task');
$lang->tutorial->tasks['createTask']['desc'] = "<p>Task breakdown for a story: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> WBS;</span></li><li data-target='form'>Fill the form with task information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => 'Report Bug');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Report  Bug');
$lang->tutorial->tasks['createBug']['desc'] = "<p>Report a Bug: </p><ul><li data-target='nav'>Open <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Report Bug</span>；</li><li data-target='form'>Fill the form with bug information:</li><li data-target='submit'>Save</li></ul>";
