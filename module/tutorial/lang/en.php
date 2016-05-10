<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */

$lang->tutorial = new stdclass();
$lang->tutorial->common           = 'Novice tutorial';
$lang->tutorial->desc             = 'Learn zentao through a series of tasks. This may cost you 10 minutes, you can quit anytime.';
$lang->tutorial->start            = 'Let\'s go!';
$lang->tutorial->exit             = 'Quit';
$lang->tutorial->congratulation   = 'Congratulations! You have finished all tasks.';
$lang->tutorial->restart          = 'Restart';
$lang->tutorial->currentTask      = 'Current task';
$lang->tutorial->allTasks         = 'All tasks';
$lang->tutorial->previous         = 'Previous one';
$lang->tutorial->nextTask         = 'Next task';
$lang->tutorial->openTargetPage   = 'Open <strong class="task-page-name">target</strong> page';
$lang->tutorial->atTargetPage     = 'In <strong class="task-page-name">target</strong> page';
$lang->tutorial->reloadTargetPage = 'Reload target page';
$lang->tutorial->target           = 'Target';
$lang->tutorial->targetPageTip     = 'Open【%s】page by this instruction';
$lang->tutorial->requiredTip      = '【%s】is necessary';
$lang->tutorial->congratulateTask = 'Congratulations! You have finished task【<span class="task-name-current"></span>】!';
$lang->tutorial->serverErrorTip   = 'Some error has occurred.';
$lang->tutorial->ajaxSetError     = 'Finished task must be defined. Set empty value to reset tasks.';
$lang->tutorial->novice           = "You may use the ZenTao first, whether to enter the novice tutorial?";
$lang->tutorial->dataNotSave      = "Data is not save in the task of tutorial.";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => 'Create a account');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'addUser', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Add User');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>Create a new account in Zentao:</p><ul><li data-target='nav'>Open <span class='task-nav'>Company <i class='icon icon-angle-right'></i> Users<i class='icon icon-angle-right'></i> Add User;</span></li><li data-target='form'>Fill the form with user information;</li><li data-target='submit'>Save user information.</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => 'Create a product');
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => 'create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Product');
$lang->tutorial->tasks['createProduct']['desc'] = "<p>Create a new product:</p><ul><li data-target='nav'>Open <span class='task-nav'>Product <i class='icon icon-angle-right'></i> New;</span></li><li data-target='form'>Fill the form with product information;</li><li data-target='submit'>Save product information.</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => 'Create a story');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Story');
$lang->tutorial->tasks['createStory']['desc'] = "<p>Create a new story:</p><ul><li data-target='nav'>Open <span class='task-nav'>Product <i class='icon icon-angle-right'></i>Story <i class='icon icon-angle-right'></i>Create;</span></li><li data-target='form'>Fill the form with story information;</li><li data-target='submit'>Save story information.</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => 'Create a project');
$lang->tutorial->tasks['createProject']['nav']  = array('module' => 'project', 'method' => 'create', 'menu' => 'create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Project');
$lang->tutorial->tasks['createProject']['desc'] = "<p>Create a new project:</p><ul><li data-target='nav'>Open <span class='task-nav'> Project <i class='icon icon-angle-right'></i> New</span> Page;</li><li data-target='form'>Fill the form with project information;</li><li data-target='submit'>Save project information.</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => 'Manage project team');
$lang->tutorial->tasks['manageTeam']['nav']  = array('module' => 'project', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => 'Manage team members');
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>Manage project team members:</p><ul><li data-target='nav'>Open <span class='task-nav'> Project <i class='icon icon-angle-right'></i> Team <i class='icon icon-angle-right'></i> Manage team members</span> Page；</li><li data-target='form'>Choose users for the team.</li><li data-target='submit'>Save team members information.</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => 'Link story');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'project', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => 'Link Story');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>Link stories to project:</p><ul><li data-target='nav'>Open <span class='task-nav'> Project <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> Link story;</span></li><li data-target='form'>Select stories in story list to link;</li><li data-target='submit'>Save linked stories.</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => 'Divide tasks');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'project', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Task');
$lang->tutorial->tasks['createTask']['desc'] = "<p>Divide tasks for story.</p><ul><li data-target='nav'>Open <span class='task-nav'> Project <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> WBS;</span></li><li data-target='form'>Fill the form with task information;</li><li data-target='submit'>Save task information.</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => 'Create a bug');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Create Bug');
$lang->tutorial->tasks['createBug']['desc'] = "<p>Create a new Bug:</p><ul><li data-target='nav'>Open <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Create Bug</span>；</li><li data-target='form'>Fill the form with bug information:</li><li data-target='submit'>Save Bug information.</li></ul>";
