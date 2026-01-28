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
$lang->tutorial->desc             = 'You can know how to use ZenTao by doing tasks, and you can quit anytime.';
$lang->tutorial->start            = "Start";
$lang->tutorial->continue         = 'Continue';
$lang->tutorial->exit             = 'Quit';
$lang->tutorial->exitStep         = 'Quit';
$lang->tutorial->finish           = 'Finish';
$lang->tutorial->congratulation   = 'Congratulations! You have completed all tasks.';
$lang->tutorial->restart          = 'Restart';
$lang->tutorial->currentTask      = 'Current Task';
$lang->tutorial->allTasks         = 'All Tasks';
$lang->tutorial->previous         = 'Previous';
$lang->tutorial->nextTask         = 'Next';
$lang->tutorial->nextGuide        = 'Next Guide';
$lang->tutorial->nextStep         = 'Next Step';
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
$lang->tutorial->clickTipFormat   = "Click %s";
$lang->tutorial->clickAndOpenIt   = "Click %s to open %s.";

$lang->tutorial->guideTypes        = array();
$lang->tutorial->guideTypes['starter'] = 'Getting Started';
$lang->tutorial->guideTypes['basic']   = 'Basic Tutorial';
$lang->tutorial->guideTypes['advance'] = 'Advance Tutorial';

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

$lang->tutorial->starter = new stdClass();
$lang->tutorial->starter->title = 'Quick Start Tutorial';

$lang->tutorial->starter->createAccount = new stdClass();
$lang->tutorial->starter->createAccount->title = 'Create Account';

$lang->tutorial->starter->createAccount->step1 = new stdClass();
$lang->tutorial->starter->createAccount->step1->name = 'Click on Admin';
$lang->tutorial->starter->createAccount->step1->desc = 'Here, you can maintain and manage accounts, and configure various settings.';

$lang->tutorial->starter->createAccount->step2 = new stdClass();
$lang->tutorial->starter->createAccount->step2->name = 'Click on User';
$lang->tutorial->starter->createAccount->step2->desc = 'Here, you can manage departments, add personnel, and configure group permissions.';

$lang->tutorial->starter->createAccount->step3 = new stdClass();
$lang->tutorial->starter->createAccount->step3->name = 'Click on User';
$lang->tutorial->starter->createAccount->step3->desc = 'Here, you can maintain company personnel.';

$lang->tutorial->starter->createAccount->step4 = new stdClass();
$lang->tutorial->starter->createAccount->step4->name = 'Click on Add User';
$lang->tutorial->starter->createAccount->step4->desc = 'Click to add company personnel.';

$lang->tutorial->starter->createAccount->step5 = new stdClass();
$lang->tutorial->starter->createAccount->step5->name = 'Fill out the form';

$lang->tutorial->starter->createAccount->step6 = new stdClass();
$lang->tutorial->starter->createAccount->step6->name = 'Save the form';
$lang->tutorial->starter->createAccount->step6->desc = 'After saving, you can view it in the personnel list.';

$lang->tutorial->starter->createProgram = new stdClass();
$lang->tutorial->starter->createProgram->title = 'Create Program';

$lang->tutorial->starter->createProgram->step1 = new stdClass();
$lang->tutorial->starter->createProgram->step1->name = 'Click on Program';
$lang->tutorial->starter->createProgram->step1->desc = 'Here you can maintain and manage program sets';

$lang->tutorial->starter->createProgram->step2 = new stdClass();
$lang->tutorial->starter->createProgram->step2->name = 'Click on Create Program';
$lang->tutorial->starter->createProgram->step2->desc = 'Click to create a program';

$lang->tutorial->starter->createProgram->step3 = new stdClass();
$lang->tutorial->starter->createProgram->step3->name = 'Fill out the form';

$lang->tutorial->starter->createProgram->step4 = new stdClass();
$lang->tutorial->starter->createProgram->step4->name = 'Save the form';
$lang->tutorial->starter->createProgram->step4->desc = 'After saving, you can view it in the program and product list views';

$lang->tutorial->starter->createProduct = new stdClass();
$lang->tutorial->starter->createProduct->title = 'Create Product';

$lang->tutorial->starter->createProduct->step1 = new stdClass();
$lang->tutorial->starter->createProduct->step1->name = 'Click on Product';
$lang->tutorial->starter->createProduct->step1->desc = 'Here you can maintain and manage products';

$lang->tutorial->starter->createProduct->step2 = new stdClass();
$lang->tutorial->starter->createProduct->step2->name = 'Click on Create Product';
$lang->tutorial->starter->createProduct->step2->desc = 'You can create products here';

$lang->tutorial->starter->createProduct->step3 = new stdClass();
$lang->tutorial->starter->createProduct->step3->name = 'Fill out the form';

$lang->tutorial->starter->createProduct->step4 = new stdClass();
$lang->tutorial->starter->createProduct->step4->name = 'Save the form';
$lang->tutorial->starter->createProduct->step4->desc = 'After saving, you can view it in the product list';

$lang->tutorial->starter->createStory = new stdClass();
$lang->tutorial->starter->createStory->title = 'Create Story';

$lang->tutorial->starter->createStory->step1 = new stdClass();
$lang->tutorial->starter->createStory->step1->name = 'Click on Product';
$lang->tutorial->starter->createStory->step1->desc = 'Here you can maintain and manage products';

$lang->tutorial->starter->createStory->step2 = new stdClass();
$lang->tutorial->starter->createStory->step2->name = 'Click on Product Name';
$lang->tutorial->starter->createStory->step2->desc = 'Click to enter the product and view detailed information about the product.';

$lang->tutorial->starter->createStory->step3 = new stdClass();
$lang->tutorial->starter->createStory->step3->name = 'Click to Create Story';
$lang->tutorial->starter->createStory->step3->desc = 'Here you can create story';

$lang->tutorial->starter->createStory->step4 = new stdClass();
$lang->tutorial->starter->createStory->step4->name = 'Fill out the form';

$lang->tutorial->starter->createStory->step5 = new stdClass();
$lang->tutorial->starter->createStory->step5->name = 'Save the form';
$lang->tutorial->starter->createStory->step5->desc = 'After saving, you can view it in the product story list';

$lang->tutorial->starter->createProject = new stdClass();
$lang->tutorial->starter->createProject->title = 'Create Project';

$lang->tutorial->starter->createProject->step1 = new stdClass();
$lang->tutorial->starter->createProject->step1->name = 'Click on Project';
$lang->tutorial->starter->createProject->step1->desc = 'Here you can create projects';

$lang->tutorial->starter->createProject->step2 = new stdClass();
$lang->tutorial->starter->createProject->step2->name = 'Click on Create Project';
$lang->tutorial->starter->createProject->step2->desc = 'You can choose different project management methods to create different types of projects here';

$lang->tutorial->starter->createProject->step3 = new stdClass();
$lang->tutorial->starter->createProject->step3->name = 'Click on Scrum Project';
$lang->tutorial->starter->createProject->step3->desc = 'Please click on Scrum to create a Scrum project';

$lang->tutorial->starter->createProject->step4 = new stdClass();
$lang->tutorial->starter->createProject->step4->name = 'Fill out the form';

$lang->tutorial->starter->createProject->step5 = new stdClass();
$lang->tutorial->starter->createProject->step5->name = 'Save the form';
$lang->tutorial->starter->createProject->step5->desc = 'After saving, it will be displayed in the project list';

$lang->tutorial->starter->manageTeam = new stdClass();
$lang->tutorial->starter->manageTeam->title = 'Manage Project Team';

$lang->tutorial->starter->manageTeam->step1 = new stdClass();
$lang->tutorial->starter->manageTeam->step1->name = 'Click on Project';
$lang->tutorial->starter->manageTeam->step1->desc = 'Here you can maintain and manage the project';

$lang->tutorial->starter->manageTeam->step2 = new stdClass();
$lang->tutorial->starter->manageTeam->step2->name = 'Click on Project Name';
$lang->tutorial->starter->manageTeam->step2->desc = 'Click on the project name to enter the project';

$lang->tutorial->starter->manageTeam->step3 = new stdClass();
$lang->tutorial->starter->manageTeam->step3->name = 'Click on Settings';
$lang->tutorial->starter->manageTeam->step3->desc = 'Click on settings to start maintaining the team';

$lang->tutorial->starter->manageTeam->step4 = new stdClass();
$lang->tutorial->starter->manageTeam->step4->name = 'Click on Team';
$lang->tutorial->starter->manageTeam->step4->desc = 'Clicking on the team allows you to view the team members in the project';

$lang->tutorial->starter->manageTeam->step5 = new stdClass();
$lang->tutorial->starter->manageTeam->step5->name = 'Click on Manage Team';
$lang->tutorial->starter->manageTeam->step5->desc = 'Clicking on team management allows you to maintain the team members for the current project';

$lang->tutorial->starter->manageTeam->step6 = new stdClass();
$lang->tutorial->starter->manageTeam->step6->name = 'Fill out the form';

$lang->tutorial->starter->manageTeam->step7 = new stdClass();
$lang->tutorial->starter->manageTeam->step7->name = 'Save the form';
$lang->tutorial->starter->manageTeam->step7->desc = 'After saving, you can view team members in the team';

$lang->tutorial->starter->createProjectExecution = new stdClass();
$lang->tutorial->starter->createProjectExecution->title = 'Create Execution';

$lang->tutorial->starter->createProjectExecution->step1 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step1->name = 'Click on Project';
$lang->tutorial->starter->createProjectExecution->step1->desc = 'Here you can maintain and manage the project';

$lang->tutorial->starter->createProjectExecution->step2 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step2->name = 'Click on Project Name';
$lang->tutorial->starter->createProjectExecution->step2->desc = 'Click on the project name to enter the project';

$lang->tutorial->starter->createProjectExecution->step3 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step3->name = 'Click on Iteration';
$lang->tutorial->starter->createProjectExecution->step3->desc = 'Click on iteration to start adding a new iteration';

$lang->tutorial->starter->createProjectExecution->step4 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step4->name = 'Click on Create Iteration';
$lang->tutorial->starter->createProjectExecution->step4->desc = 'Here you can create iterations';

$lang->tutorial->starter->createProjectExecution->step5 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step5->name = 'Fill out the form';

$lang->tutorial->starter->createProjectExecution->step6 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step6->name = 'Save the form';
$lang->tutorial->starter->createProjectExecution->step6->desc = 'After saving, you can choose to set up a team, link requirements, create tasks, return to the task list, and return to the execution list';

$lang->tutorial->starter->linkStory = new stdClass();
$lang->tutorial->starter->linkStory->title = "Associate {$lang->SRCommon}";

$lang->tutorial->starter->linkStory->step1 = new stdClass();
$lang->tutorial->starter->linkStory->step1->name = 'Click Iteration';
$lang->tutorial->starter->linkStory->step1->desc = 'You can maintain and manage iterations here';

$lang->tutorial->starter->linkStory->step2 = new stdClass();
$lang->tutorial->starter->linkStory->step2->name = 'Click Story';
$lang->tutorial->starter->linkStory->step2->desc = 'Click on Story to view associated stories';

$lang->tutorial->starter->linkStory->step3 = new stdClass();
$lang->tutorial->starter->linkStory->step3->name = 'Click Link Story';
$lang->tutorial->starter->linkStory->step3->desc = 'Click Link Story to enter the linked story list';

$lang->tutorial->starter->linkStory->step4 = new stdClass();
$lang->tutorial->starter->linkStory->step4->name = 'Select Story';

$lang->tutorial->starter->linkStory->step5 = new stdClass();
$lang->tutorial->starter->linkStory->step5->name = 'Click Save';
$lang->tutorial->starter->linkStory->step5->desc = 'Clicking Save will associate the story with the story list and return to the story list';

$lang->tutorial->starter->createTask = new stdClass();
$lang->tutorial->starter->createTask->title = 'Decompose Tasks';

$lang->tutorial->starter->createTask->step1 = new stdClass();
$lang->tutorial->starter->createTask->step1->name = 'Click Execution';
$lang->tutorial->starter->createTask->step1->desc = 'You can maintain and manage iterations here';

$lang->tutorial->starter->createTask->step2 = new stdClass();
$lang->tutorial->starter->createTask->step2->name = 'Click Story';
$lang->tutorial->starter->createTask->step2->desc = 'Enter the story list, where you can see previously associated stories';

$lang->tutorial->starter->createTask->step3 = new stdClass();
$lang->tutorial->starter->createTask->step3->name = 'Decompose Tasks';
$lang->tutorial->starter->createTask->step3->desc = 'You can decompose stories into tasks here, supporting batch decomposition';

$lang->tutorial->starter->createTask->step4 = new stdClass();
$lang->tutorial->starter->createTask->step4->name = 'Fill out the form';

$lang->tutorial->starter->createTask->step5 = new stdClass();
$lang->tutorial->starter->createTask->step5->name = 'Save the form';
$lang->tutorial->starter->createTask->step5->desc = 'After saving, you can view the decomposed tasks in the task list';

$lang->tutorial->starter->createBug = new stdClass();
$lang->tutorial->starter->createBug->title = 'Create Bug';

$lang->tutorial->starter->createBug->step1 = new stdClass();
$lang->tutorial->starter->createBug->step1->name = 'Click QA';
$lang->tutorial->starter->createBug->step1->desc = 'You can manage testing here';

$lang->tutorial->starter->createBug->step2 = new stdClass();
$lang->tutorial->starter->createBug->step2->name = 'Click Bug';
$lang->tutorial->starter->createBug->step2->desc = 'You can manage bugs here';

$lang->tutorial->starter->createBug->step3 = new stdClass();
$lang->tutorial->starter->createBug->step3->name = 'Click Report Bug';
$lang->tutorial->starter->createBug->step3->desc = 'You can create a bug here';

$lang->tutorial->starter->createBug->step4 = new stdClass();
$lang->tutorial->starter->createBug->step4->name = 'Fill out the form';

$lang->tutorial->starter->createBug->step5 = new stdClass();
$lang->tutorial->starter->createBug->step5->name = 'Save the form';
$lang->tutorial->starter->createBug->step5->desc = 'After saving, you will enter the Bug list';

$lang->tutorial->scrumProjectManage = new stdClass();
$lang->tutorial->scrumProjectManage->title = 'Scrum Project Management Tutorial';

$lang->tutorial->scrumProjectManage->manageProject = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->title = 'Project Management';

$lang->tutorial->scrumProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step1->name = 'Click on Project';
$lang->tutorial->scrumProjectManage->manageProject->step1->desc = 'You can create a project here';

$lang->tutorial->scrumProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step2->name = 'Click Create Project';
$lang->tutorial->scrumProjectManage->manageProject->step2->desc = 'You can choose different project management methods to create different types of projects';

$lang->tutorial->scrumProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step3->name = 'Click Scrum Project';
$lang->tutorial->scrumProjectManage->manageProject->step3->desc = 'Please click on Scrum to create a Scrum project';

$lang->tutorial->scrumProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step4->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step5->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageProject->step5->desc = 'After saving, it will be displayed in the project list';

$lang->tutorial->scrumProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step6->name = 'Click on Project Name';
$lang->tutorial->scrumProjectManage->manageProject->step6->desc = 'Click on the project name to enter the project';

$lang->tutorial->scrumProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step7->name = 'Click Settings';
$lang->tutorial->scrumProjectManage->manageProject->step7->desc = 'Click on Settings to start maintaining the team';

$lang->tutorial->scrumProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step8->name = 'Click on Team';
$lang->tutorial->scrumProjectManage->manageProject->step8->desc = 'Clicking on Team allows you to view team members in the project';

$lang->tutorial->scrumProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step9->name = 'Click Manage Team';
$lang->tutorial->scrumProjectManage->manageProject->step9->desc = 'Clicking on Team Management allows you to maintain team members for the current project';

$lang->tutorial->scrumProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step10->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step11->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageProject->step11->desc = 'After saving, you can view team members in the team';

$lang->tutorial->scrumProjectManage->manageExecution = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->title = 'Iteration Management';

$lang->tutorial->scrumProjectManage->manageExecution->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step1->name = 'Click on Iteration';
$lang->tutorial->scrumProjectManage->manageExecution->step1->desc = 'Click on Iteration to start adding a new iteration';

$lang->tutorial->scrumProjectManage->manageExecution->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step2->name = 'Click Create Iteration';
$lang->tutorial->scrumProjectManage->manageExecution->step2->desc = 'You can add iterations here';

$lang->tutorial->scrumProjectManage->manageExecution->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step3->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageExecution->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step4->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageExecution->step4->desc = 'After saving, you can choose to set the team, associate stories, create tasks, return to the task list, and return to the execution list';

$lang->tutorial->scrumProjectManage->manageExecution->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step5->name = 'Click on Iteration';
$lang->tutorial->scrumProjectManage->manageExecution->step5->desc = 'Click on the iteration name to enter the iteration';

$lang->tutorial->scrumProjectManage->manageExecution->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step6->name = 'Click on Story';
$lang->tutorial->scrumProjectManage->manageExecution->step6->desc = 'You can maintain stories here';

$lang->tutorial->scrumProjectManage->manageExecution->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step7->name = 'Click on Link Story';
$lang->tutorial->scrumProjectManage->manageExecution->step7->desc = 'You can associate stories with the iteration';

$lang->tutorial->scrumProjectManage->manageExecution->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step8->name = 'Select Stories';

$lang->tutorial->scrumProjectManage->manageExecution->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step9->name = 'Click Save';
$lang->tutorial->scrumProjectManage->manageExecution->step9->desc = 'Clicking Save will associate the stories with the stories list and return to the stories list';

$lang->tutorial->scrumProjectManage->manageExecution->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step10->name = 'Click on Burndown';
$lang->tutorial->scrumProjectManage->manageExecution->step10->desc = 'Clicking on the Burn-down Chart allows you to view the iteration burn-down chart';

$lang->tutorial->scrumProjectManage->manageTask = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->title = 'Task Management';

$lang->tutorial->scrumProjectManage->manageTask->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step1->name = 'Click on Story';
$lang->tutorial->scrumProjectManage->manageTask->step1->desc = 'Enter the story list where you can see previously associated stories';

$lang->tutorial->scrumProjectManage->manageTask->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step3->name = 'Break Down Tasks';
$lang->tutorial->scrumProjectManage->manageTask->step3->desc = 'You can break down stories into tasks here, supporting bulk decomposition';

$lang->tutorial->scrumProjectManage->manageTask->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step4->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step5->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step5->desc = 'After saving, you can view the decomposed tasks in the task list';

$lang->tutorial->scrumProjectManage->manageTask->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step6->name = 'Click Assign To';
$lang->tutorial->scrumProjectManage->manageTask->step6->desc = 'You can assign tasks to the corresponding users here';

$lang->tutorial->scrumProjectManage->manageTask->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step7->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step8->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step8->desc = 'After saving, the assigned field in the task list will display the assigned user';

$lang->tutorial->scrumProjectManage->manageTask->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step9->name = 'Click Start Task';
$lang->tutorial->scrumProjectManage->manageTask->step9->desc = 'You can start tasks here and record time spent and remaining hours';

$lang->tutorial->scrumProjectManage->manageTask->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step10->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step11->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step11->desc = 'After saving, return to the task list';

$lang->tutorial->scrumProjectManage->manageTask->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step12->name = 'Click Record Time';
$lang->tutorial->scrumProjectManage->manageTask->step12->desc = 'You can record time spent and remaining hours here; when the remaining hours reach 0, the task will automatically be completed';

$lang->tutorial->scrumProjectManage->manageTask->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step13->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step14->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step14->desc = 'After saving, return to the task list';

$lang->tutorial->scrumProjectManage->manageTask->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step15->name = 'Click Finish Task';
$lang->tutorial->scrumProjectManage->manageTask->step15->desc = 'You can complete tasks here';

$lang->tutorial->scrumProjectManage->manageTask->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step16->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step17->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step17->desc = 'After saving, return to the task list';

$lang->tutorial->scrumProjectManage->manageTask->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step18->name = 'Click on Build';
$lang->tutorial->scrumProjectManage->manageTask->step18->desc = 'Enter the build module where you can create builds';

$lang->tutorial->scrumProjectManage->manageTask->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step19->name = 'Click Create Build';
$lang->tutorial->scrumProjectManage->manageTask->step19->desc = 'You can create new versions here';

$lang->tutorial->scrumProjectManage->manageTask->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step20->name = 'Fill out the form';

$lang->tutorial->scrumProjectManage->manageTask->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step21->name = 'Save the form';
$lang->tutorial->scrumProjectManage->manageTask->step21->desc = 'After saving, enter version details';

$lang->tutorial->scrumProjectManage->manageTask->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step22->name = 'Associate Stories';
$lang->tutorial->scrumProjectManage->manageTask->step22->desc = 'You can associate completed development stories in the version';

$lang->tutorial->scrumProjectManage->manageTask->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step23->name = 'Select Stories';
$lang->tutorial->scrumProjectManage->manageTask->step23->desc = 'Here, you can select the stories you want to associate';

$lang->tutorial->scrumProjectManage->manageTask->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step24->name = 'Save Associated Stories';
$lang->tutorial->scrumProjectManage->manageTask->step24->desc = 'You can associate completed stories with the current version';

$lang->tutorial->scrumProjectManage->manageTest = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->title = 'Test Management';

$lang->tutorial->scrumProjectManage->manageTest->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step1->name = 'Click QA';
$lang->tutorial->scrumProjectManage->manageTest->step1->desc = 'You can manage tests here.';

$lang->tutorial->scrumProjectManage->manageTest->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step2->name = 'Click Case';
$lang->tutorial->scrumProjectManage->manageTest->step2->desc = 'You can view cases here.';

$lang->tutorial->scrumProjectManage->manageTest->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step3->name = 'Click Create Case';
$lang->tutorial->scrumProjectManage->manageTest->step3->desc = 'You can create cases here.';

$lang->tutorial->scrumProjectManage->manageTest->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step4->name = 'Fill out form';

$lang->tutorial->scrumProjectManage->manageTest->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step5->name = 'Save form';
$lang->tutorial->scrumProjectManage->manageTest->step5->desc = 'After saving, you will be taken to the list of cases.';

$lang->tutorial->scrumProjectManage->manageTest->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step6->name = 'Click Run';
$lang->tutorial->scrumProjectManage->manageTest->step6->desc = 'Clicking execute will run the case.';

$lang->tutorial->scrumProjectManage->manageTest->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step7->name = 'Fill out form';

$lang->tutorial->scrumProjectManage->manageTest->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step8->name = 'Save form';
$lang->tutorial->scrumProjectManage->manageTest->step8->desc = 'After saving, return to the list of cases.';

$lang->tutorial->scrumProjectManage->manageTest->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step9->name = 'Click Results';
$lang->tutorial->scrumProjectManage->manageTest->step9->desc = 'Click here to view case execution results.';

$lang->tutorial->scrumProjectManage->manageTest->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step10->name = 'Select Step';

$lang->tutorial->scrumProjectManage->manageTest->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step11->name = 'Click Report Bug';
$lang->tutorial->scrumProjectManage->manageTest->step11->desc = 'You can convert failed results to bug reports.';

$lang->tutorial->scrumProjectManage->manageTest->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step12->name = 'Fill out form';

$lang->tutorial->scrumProjectManage->manageTest->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step13->name = 'Save form';

$lang->tutorial->scrumProjectManage->manageTest->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step14->name = 'Click Request';
$lang->tutorial->scrumProjectManage->manageTest->step14->desc = 'Click to maintain the test sheet.';

$lang->tutorial->scrumProjectManage->manageTest->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step15->name = 'Click Submit Request';
$lang->tutorial->scrumProjectManage->manageTest->step15->desc = 'You can create test sheets here.';

$lang->tutorial->scrumProjectManage->manageTest->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step16->name = 'Fill out form';

$lang->tutorial->scrumProjectManage->manageTest->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step17->name = 'Save form';
$lang->tutorial->scrumProjectManage->manageTest->step17->desc = 'After saving, return to the test sheet list.';

$lang->tutorial->scrumProjectManage->manageTest->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step18->name = 'Click Request Name';
$lang->tutorial->scrumProjectManage->manageTest->step18->desc = 'View details of the test sheet here.';

$lang->tutorial->scrumProjectManage->manageTest->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step19->name = 'Click Link Case';
$lang->tutorial->scrumProjectManage->manageTest->step19->desc = 'You can associate cases here.';

$lang->tutorial->scrumProjectManage->manageTest->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step20->name = 'Select Cases to Associate';

$lang->tutorial->scrumProjectManage->manageTest->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step21->name = 'Save form';
$lang->tutorial->scrumProjectManage->manageTest->step21->desc = 'Associate cases with the test sheet and view the cases available for association.';

$lang->tutorial->scrumProjectManage->manageTest->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step22->name = 'Click Request';
$lang->tutorial->scrumProjectManage->manageTest->step22->desc = 'Return to the test sheet list.';

$lang->tutorial->scrumProjectManage->manageTest->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step23->name = 'Select Test Sheet';

$lang->tutorial->scrumProjectManage->manageTest->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step24->name = 'Click Testing Report';
$lang->tutorial->scrumProjectManage->manageTest->step24->desc = 'Generate test reports here.';

$lang->tutorial->scrumProjectManage->manageTest->step25 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step25->name = 'Fill out form';

$lang->tutorial->scrumProjectManage->manageTest->step26 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step26->name = 'Save form';
$lang->tutorial->scrumProjectManage->manageTest->step26->desc = 'After saving, you can generate test reports.';

$lang->tutorial->scrumProjectManage->manageTest->step27 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step27->name = 'Click Report';
$lang->tutorial->scrumProjectManage->manageTest->step27->desc = 'View test report lists here.';

$lang->tutorial->scrumProjectManage->manageBug = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->title = 'Bug Management';

$lang->tutorial->scrumProjectManage->manageBug->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step1->name = 'Click to QA';
$lang->tutorial->scrumProjectManage->manageBug->step1->desc = 'Manage bugs here';

$lang->tutorial->scrumProjectManage->manageBug->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step2->name = 'Click to Report Bug';
$lang->tutorial->scrumProjectManage->manageBug->step2->desc = 'Create bugs here';

$lang->tutorial->scrumProjectManage->manageBug->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step3->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageBug->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step4->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageBug->step4->desc = 'Navigate to Bug List after saving';

$lang->tutorial->scrumProjectManage->manageBug->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step5->name = 'Confirm Bug';
$lang->tutorial->scrumProjectManage->manageBug->step5->desc = 'Confirm bugs here';

$lang->tutorial->scrumProjectManage->manageBug->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step6->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageBug->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step7->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageBug->step7->desc = 'Navigate to Bug List after saving';

$lang->tutorial->scrumProjectManage->manageBug->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step8->name = 'Resolve Bug';
$lang->tutorial->scrumProjectManage->manageBug->step8->desc = 'Resolve bugs here';

$lang->tutorial->scrumProjectManage->manageBug->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step9->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageBug->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step10->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageBug->step10->desc = 'Verify resolved bugs after saving';

$lang->tutorial->scrumProjectManage->manageBug->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step11->name = 'Close Bug';
$lang->tutorial->scrumProjectManage->manageBug->step11->desc = 'Close bugs here';

$lang->tutorial->scrumProjectManage->manageBug->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step12->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageBug->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step13->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageBug->step13->desc = 'Close verified bugs after saving';

$lang->tutorial->scrumProjectManage->manageIssue = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->title = 'Issue Management';

$lang->tutorial->scrumProjectManage->manageIssue->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step1->name = 'Click Others';

$lang->tutorial->scrumProjectManage->manageIssue->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step2->name = 'Click Issues';
$lang->tutorial->scrumProjectManage->manageIssue->step2->desc = 'Manage issues here';

$lang->tutorial->scrumProjectManage->manageIssue->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step3->name = 'Click Create Issue';
$lang->tutorial->scrumProjectManage->manageIssue->step3->desc = 'Create issues here, supports batch creation';

$lang->tutorial->scrumProjectManage->manageIssue->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step4->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageIssue->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step5->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageIssue->step5->desc = 'Navigate to Issue List after saving';

$lang->tutorial->scrumProjectManage->manageIssue->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step6->name = 'Confirm Issue';
$lang->tutorial->scrumProjectManage->manageIssue->step6->desc = 'Confirm issues for the current project here';

$lang->tutorial->scrumProjectManage->manageIssue->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step7->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageIssue->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step8->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageIssue->step8->desc = 'Return to Issue List after confirmation';

$lang->tutorial->scrumProjectManage->manageIssue->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step9->name = 'Resolve Issue';
$lang->tutorial->scrumProjectManage->manageIssue->step9->desc = 'Resolve issues here';

$lang->tutorial->scrumProjectManage->manageIssue->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step10->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageIssue->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step11->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageIssue->step11->desc = 'Return to Issue List after saving';

$lang->tutorial->scrumProjectManage->manageIssue->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step12->name = 'Close Issue';
$lang->tutorial->scrumProjectManage->manageIssue->step12->desc = 'Close resolved issues here';

$lang->tutorial->scrumProjectManage->manageIssue->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step13->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageIssue->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step14->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageIssue->step14->desc = 'Close issues here';

$lang->tutorial->scrumProjectManage->manageRisk = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->title = 'Risk Management';

$lang->tutorial->scrumProjectManage->manageRisk->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step1->name = 'Click Others';

$lang->tutorial->scrumProjectManage->manageRisk->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step2->name = 'Click Risks';
$lang->tutorial->scrumProjectManage->manageRisk->step2->desc = 'Manage risks here';

$lang->tutorial->scrumProjectManage->manageRisk->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step3->name = 'Click Add Risk';
$lang->tutorial->scrumProjectManage->manageRisk->step3->desc = 'Add risks for the current project here, supports batch creation';

$lang->tutorial->scrumProjectManage->manageRisk->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step4->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageRisk->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step5->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageRisk->step5->desc = 'Add risks to the risk list here';

$lang->tutorial->scrumProjectManage->manageRisk->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step6->name = 'Track Risks';
$lang->tutorial->scrumProjectManage->manageRisk->step6->desc = 'Track risks here';

$lang->tutorial->scrumProjectManage->manageRisk->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step7->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageRisk->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step8->name = 'Save Form';
$lang->tutorial->scrumProjectManage->manageRisk->step8->desc = 'Return to the risk list after saving';

$lang->tutorial->scrumProjectManage->manageRisk->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step9->name = 'Close Risk';
$lang->tutorial->scrumProjectManage->manageRisk->step9->desc = 'Close risks here';

$lang->tutorial->scrumProjectManage->manageRisk->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step10->name = 'Fill out Form';

$lang->tutorial->scrumProjectManage->manageRisk->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step11->name = 'Save Form';

$lang->tutorial->waterfallProjectManage = new stdClass();
$lang->tutorial->waterfallProjectManage->title = 'Waterfall Project Management Tutorial';

$lang->tutorial->waterfallProjectManage->manageProject = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->title = 'Manage Projects';

$lang->tutorial->waterfallProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step1->name = 'Click Project';
$lang->tutorial->waterfallProjectManage->manageProject->step1->desc = 'You can create projects here';

$lang->tutorial->waterfallProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step2->name = 'Click Create Project';
$lang->tutorial->waterfallProjectManage->manageProject->step2->desc = 'You can choose different project management methods to create different types of projects';

$lang->tutorial->waterfallProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step3->name = 'Click Waterfall Project';
$lang->tutorial->waterfallProjectManage->manageProject->step3->desc = 'Create waterfall projects here';

$lang->tutorial->waterfallProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step4->name = 'Fill out Form';

$lang->tutorial->waterfallProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step5->name = 'Save Form';
$lang->tutorial->waterfallProjectManage->manageProject->step5->desc = 'After saving, it will be displayed in the project list';

$lang->tutorial->waterfallProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step6->name = 'Click Project Name';
$lang->tutorial->waterfallProjectManage->manageProject->step6->desc = 'Click on the project name to enter the waterfall project';

$lang->tutorial->waterfallProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step7->name = 'Click Settings';
$lang->tutorial->waterfallProjectManage->manageProject->step7->desc = 'Click Settings to start maintaining the team';

$lang->tutorial->waterfallProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step8->name = 'Click Team';
$lang->tutorial->waterfallProjectManage->manageProject->step8->desc = 'Click on Team to view team members in the project';

$lang->tutorial->waterfallProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step9->name = 'Click Manage Team';
$lang->tutorial->waterfallProjectManage->manageProject->step9->desc = 'Click Team Management to maintain team members for the current project';

$lang->tutorial->waterfallProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step10->name = 'Fill out Form';

$lang->tutorial->waterfallProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step11->name = 'Save Form';
$lang->tutorial->waterfallProjectManage->manageProject->step11->desc = 'After saving, you can view team members in the team';

$lang->tutorial->waterfallProjectManage->setStage = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->title = 'Set Stages';

$lang->tutorial->waterfallProjectManage->setStage->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step1->name = 'Click Stages';
$lang->tutorial->waterfallProjectManage->setStage->step1->desc = 'Maintain stages here';

$lang->tutorial->waterfallProjectManage->setStage->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step2->name = 'Click Set Stage';
$lang->tutorial->waterfallProjectManage->setStage->step2->desc = 'Click Set Stage to determine the stages of the project. Setting a stage as a milestone allows you to view related milestone reports.';

$lang->tutorial->waterfallProjectManage->setStage->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step3->name = 'Fill out Form';

$lang->tutorial->waterfallProjectManage->setStage->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step4->name = 'Save Form';
$lang->tutorial->waterfallProjectManage->setStage->step4->desc = 'You can set start and end dates for each stage, and save them to view all stages in the stage list';

$lang->tutorial->waterfallProjectManage->setStage->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step5->name = 'Switch View';
$lang->tutorial->waterfallProjectManage->setStage->step5->desc = 'You can switch to a list view to see stages';

$lang->tutorial->waterfallProjectManage->setStage->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step6->name = 'Click Development Stage';
$lang->tutorial->waterfallProjectManage->setStage->step6->desc = 'Allocate resources and tasks in each stage here';

$lang->tutorial->waterfallProjectManage->setStage->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step7->name = 'Click Burndown';
$lang->tutorial->waterfallProjectManage->setStage->step7->desc = 'Viewing the Burn Down Chart allows you to track progress in stages';

$lang->tutorial->waterfallProjectManage->manageTask = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTask = $lang->tutorial->scrumProjectManage->manageTask;

$lang->tutorial->waterfallProjectManage->manageTest = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTest = $lang->tutorial->scrumProjectManage->manageTest;

$lang->tutorial->waterfallProjectManage->manageBug = new stdClass();
$lang->tutorial->waterfallProjectManage->manageBug = $lang->tutorial->scrumProjectManage->manageBug;

$lang->tutorial->waterfallProjectManage->design = new stdClass();
$lang->tutorial->waterfallProjectManage->design->title = 'Design Management';

$lang->tutorial->waterfallProjectManage->design->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step1->name = 'Click on Design';
$lang->tutorial->waterfallProjectManage->design->step1->desc = 'You can manage designs here.';

$lang->tutorial->waterfallProjectManage->design->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step2->name = 'Click to Create Design';
$lang->tutorial->waterfallProjectManage->design->step2->desc = 'You can create design here.';

$lang->tutorial->waterfallProjectManage->design->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step3->name = 'Fill out the form';

$lang->tutorial->waterfallProjectManage->design->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step4->name = 'Save the form';
$lang->tutorial->waterfallProjectManage->design->step4->desc = 'After saving, you can view all designs in the design list.';

$lang->tutorial->waterfallProjectManage->design->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step5->name = 'Click on Design Name';
$lang->tutorial->waterfallProjectManage->design->step5->desc = 'You can enter design details here.';

$lang->tutorial->waterfallProjectManage->design->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step6->name = 'Click to Link Commit';
$lang->tutorial->waterfallProjectManage->design->step6->desc = 'You can associate submissions here.';

$lang->tutorial->waterfallProjectManage->design->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step7->name = 'Select Commit';

$lang->tutorial->waterfallProjectManage->design->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step8->name = 'Save the form';
$lang->tutorial->waterfallProjectManage->design->step8->desc = 'After saving, you can view associated submissions in the design details.';

$lang->tutorial->waterfallProjectManage->review = new stdClass();
$lang->tutorial->waterfallProjectManage->review->title = 'Review and Configuration Management';

$lang->tutorial->waterfallProjectManage->review->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step1->name = 'Click on Review';
$lang->tutorial->waterfallProjectManage->review->step1->desc = 'You can manage reviews here.';

$lang->tutorial->waterfallProjectManage->review->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step2->name = 'Click on Review List';
$lang->tutorial->waterfallProjectManage->review->step2->desc = 'You can view all review items here.';

$lang->tutorial->waterfallProjectManage->review->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step3->name = 'Click to Create';
$lang->tutorial->waterfallProjectManage->review->step3->desc = 'You can initiate a review here.';

$lang->tutorial->waterfallProjectManage->review->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step4->name = 'Fill out the form';

$lang->tutorial->waterfallProjectManage->review->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step5->name = 'Save the form';
$lang->tutorial->waterfallProjectManage->review->step5->desc = 'After saving, you can view it in the baseline review list. Templates can be configured in the backend to create templates and reference template fields.';

$lang->tutorial->waterfallProjectManage->review->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step6->name = 'Click to Submit Audit';
$lang->tutorial->waterfallProjectManage->review->step6->desc = 'You can submit an audit here. For reviews that did not pass, you can view and add issues in the issue list.';

$lang->tutorial->waterfallProjectManage->review->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step7->name = 'Fill out the form';

$lang->tutorial->waterfallProjectManage->review->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step8->name = 'Save the form';
$lang->tutorial->waterfallProjectManage->review->step8->desc = 'After saving, return to the baseline review list.';

$lang->tutorial->waterfallProjectManage->manageIssue = new stdClass();
$lang->tutorial->waterfallProjectManage->manageIssue = $lang->tutorial->scrumProjectManage->manageIssue;

$lang->tutorial->waterfallProjectManage->manageRisk = new stdClass();
$lang->tutorial->waterfallProjectManage->manageRisk = $lang->tutorial->scrumProjectManage->manageRisk;

$lang->tutorial->kanbanProjectManage = new stdClass();
$lang->tutorial->kanbanProjectManage->title = 'Kanban Project Management Tutorial';

$lang->tutorial->kanbanProjectManage->manageProject = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject = clone $lang->tutorial->scrumProjectManage->manageProject;

$lang->tutorial->kanbanProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject->step3->name = 'Click on Kanban';
$lang->tutorial->kanbanProjectManage->manageProject->step3->desc = 'You can create a Kanban project here';

$lang->tutorial->kanbanProjectManage->manageKanban = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->title = 'Kanban Management';

$lang->tutorial->kanbanProjectManage->manageKanban->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step1->name = 'Click to Create Kanban';
$lang->tutorial->kanbanProjectManage->manageKanban->step1->desc = 'You can add a Kanban board here';

$lang->tutorial->kanbanProjectManage->manageKanban->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step2->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step3->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageKanban->step3->desc = 'You can complete the creation of the Kanban board here';

$lang->tutorial->kanbanProjectManage->manageKanban->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step4->name = 'Click More';

$lang->tutorial->kanbanProjectManage->manageKanban->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step5->name = 'Click to Create Region';
$lang->tutorial->kanbanProjectManage->manageKanban->step5->desc = 'You can add a new area here';

$lang->tutorial->kanbanProjectManage->manageKanban->step6 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step6->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step7 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step7->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageKanban->step7->desc = 'You can add the area to the Kanban project';

$lang->tutorial->kanbanProjectManage->manageKanban->step8 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step8->name = 'Click Create';
$lang->tutorial->kanbanProjectManage->manageKanban->step8->desc = 'You can choose to associate/create requirements';

$lang->tutorial->kanbanProjectManage->manageKanban->step9 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step9->name = 'Click to Link Stories';
$lang->tutorial->kanbanProjectManage->manageKanban->step9->desc = 'You can link/create stories in the stories lane';

$lang->tutorial->kanbanProjectManage->manageKanban->step10 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step10->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step11 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step11->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageKanban->step11->desc = 'You can associate requirements to the requirements lane';

$lang->tutorial->kanbanProjectManage->manageKanban->step12 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step12->name = 'Click More';

$lang->tutorial->kanbanProjectManage->manageKanban->step13 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step13->name = 'Click to Create Task';
$lang->tutorial->kanbanProjectManage->manageKanban->step13->desc = 'You can break down storeis into tasks';

$lang->tutorial->kanbanProjectManage->manageKanban->step14 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step14->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step15 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step15->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageKanban->step15->desc = 'You can add tasks to the task lane';

$lang->tutorial->kanbanProjectManage->manageKanban->step16 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step16->name = 'Click Create';

$lang->tutorial->kanbanProjectManage->manageKanban->step17 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step17->name = 'Click to Report Bug';
$lang->tutorial->kanbanProjectManage->manageKanban->step17->desc = 'You can report a bug here';

$lang->tutorial->kanbanProjectManage->manageKanban->step18 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step18->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step19 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step19->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageKanban->step19->desc = 'You can add the bug to the bug lane';

$lang->tutorial->kanbanProjectManage->manageKanban->step20 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step20->name = 'Click More';

$lang->tutorial->kanbanProjectManage->manageKanban->step21 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step21->name = 'Click on WIP Settings';
$lang->tutorial->kanbanProjectManage->manageKanban->step21->desc = 'You can flexibly set the number of work in progress items';

$lang->tutorial->kanbanProjectManage->manageKanban->step22 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step22->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageKanban->step23 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step23->name = 'Save the Form';

$lang->tutorial->kanbanProjectManage->manageBuild = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->title = 'Build Management';

$lang->tutorial->kanbanProjectManage->manageBuild->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step1->name = 'Click on Build';
$lang->tutorial->kanbanProjectManage->manageBuild->step1->desc = 'You can manage builds here';

$lang->tutorial->kanbanProjectManage->manageBuild->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step2->name = 'Click to Create Build';
$lang->tutorial->kanbanProjectManage->manageBuild->step2->desc = 'You can create a new build here';

$lang->tutorial->kanbanProjectManage->manageBuild->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step3->name = 'Fill out the Form';

$lang->tutorial->kanbanProjectManage->manageBuild->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step4->name = 'Save the Form';
$lang->tutorial->kanbanProjectManage->manageBuild->step4->desc = 'It will be displayed in the build list after saving';

$lang->tutorial->kanbanProjectManage->manageBuild->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step5->name = 'Click on Cumulative Flow Diagrams';
$lang->tutorial->kanbanProjectManage->manageBuild->step5->desc = 'You can view the cumulative flow diagram here for Kanban tracking';

$lang->tutorial->taskManage = new stdClass();
$lang->tutorial->taskManage->title = 'Task Management Tutorial';

$lang->tutorial->taskManage->step1 = new stdClass();
$lang->tutorial->taskManage->step1->name = 'Click on Project';
$lang->tutorial->taskManage->step1->desc = 'Click to enter the project and manage the project and its tasks';

$lang->tutorial->taskManage->step2 = new stdClass();
$lang->tutorial->taskManage->step2->name = 'Click Create Project';
$lang->tutorial->taskManage->step2->desc = 'Click to create a project management task without iterations';

$lang->tutorial->taskManage->step3 = new stdClass();
$lang->tutorial->taskManage->step3->name = 'Click on Scrum Project';
$lang->tutorial->taskManage->step3->desc = 'Click to create a project without iterations';

$lang->tutorial->taskManage->step4 = new stdClass();
$lang->tutorial->taskManage->step4->name = 'Fill out the form';
$lang->tutorial->taskManage->step4->desc = 'Select Category and uncheck Multi Iteration to create a project without iterations';

$lang->tutorial->taskManage->step5 = new stdClass();
$lang->tutorial->taskManage->step5->name = 'Save the form';
$lang->tutorial->taskManage->step5->desc = 'After saving, view in the project list';

$lang->tutorial->taskManage->step6 = new stdClass();
$lang->tutorial->taskManage->step6->name = 'Click on Project Name';
$lang->tutorial->taskManage->step6->desc = 'Click on the project name to enter the project';

$lang->tutorial->taskManage->step7 = new stdClass();
$lang->tutorial->taskManage->step7->name = 'Click Create Task';
$lang->tutorial->taskManage->step7->desc = 'Click to create tasks for the project';

$lang->tutorial->taskManage->step8 = new stdClass();
$lang->tutorial->taskManage->step8->name = 'Fill out the form';

$lang->tutorial->taskManage->step9 = new stdClass();
$lang->tutorial->taskManage->step9->name = 'Save the form';
$lang->tutorial->taskManage->step9->desc = 'After saving, view tasks in the task list';

$lang->tutorial->taskManage->step10 = new stdClass();
$lang->tutorial->taskManage->step10->name = 'Click Assign To';
$lang->tutorial->taskManage->step10->desc = 'Click to assign tasks to individuals';

$lang->tutorial->taskManage->step11 = new stdClass();
$lang->tutorial->taskManage->step11->name = 'Fill out the form';

$lang->tutorial->taskManage->step12 = new stdClass();
$lang->tutorial->taskManage->step12->name = 'Save the form';
$lang->tutorial->taskManage->step12->desc = 'After saving, the assigned user will be displayed in the task list under "Assign To"';

$lang->tutorial->taskManage->step13 = new stdClass();
$lang->tutorial->taskManage->step13->name = 'Click Start Task';
$lang->tutorial->taskManage->step13->desc = 'Start a task here and record time spent and remaining hours';

$lang->tutorial->taskManage->step14 = new stdClass();
$lang->tutorial->taskManage->step14->name = 'Fill out the form';

$lang->tutorial->taskManage->step15 = new stdClass();
$lang->tutorial->taskManage->step15->name = 'Save the form';
$lang->tutorial->taskManage->step15->desc = 'After saving, the task status changes to "In Progress"';

$lang->tutorial->taskManage->step16 = new stdClass();
$lang->tutorial->taskManage->step16->name = 'Click Record Hours';
$lang->tutorial->taskManage->step16->desc = 'Record time spent and remaining hours here. When remaining hours reach 0, the task will automatically be completed';

$lang->tutorial->taskManage->step17 = new stdClass();
$lang->tutorial->taskManage->step17->name = 'Fill out the form';

$lang->tutorial->taskManage->step18 = new stdClass();
$lang->tutorial->taskManage->step18->name = 'Save the form';
$lang->tutorial->taskManage->step18->desc = 'After saving, return to the task list';

$lang->tutorial->taskManage->step19 = new stdClass();
$lang->tutorial->taskManage->step19->name = 'Click Finish Task';
$lang->tutorial->taskManage->step19->desc = 'Complete a task here';

$lang->tutorial->taskManage->step20 = new stdClass();
$lang->tutorial->taskManage->step20->name = 'Fill out the form';

$lang->tutorial->taskManage->step21 = new stdClass();
$lang->tutorial->taskManage->step21->name = 'Save the form';
$lang->tutorial->taskManage->step21->desc = 'After saving, the task status changes to "Completed"';

$lang->tutorial->taskManage->step22 = new stdClass();
$lang->tutorial->taskManage->step22->name = 'Click Close Task';
$lang->tutorial->taskManage->step22->desc = 'Click to close the task after confirming its completion';

$lang->tutorial->taskManage->step23 = new stdClass();
$lang->tutorial->taskManage->step23->name = 'Fill out the form';

$lang->tutorial->taskManage->step24 = new stdClass();
$lang->tutorial->taskManage->step24->name = 'Save the form';
$lang->tutorial->taskManage->step24->desc = 'After saving, the task status changes to "Closed"';

$lang->tutorial->testManage = new stdClass();
$lang->tutorial->testManage->title = 'Test Management Tutorial';

$lang->tutorial->testManage->step1 = new stdClass();
$lang->tutorial->testManage->step1->name = 'Click QA';
$lang->tutorial->testManage->step1->desc = 'Click to manage tests';

$lang->tutorial->testManage->step2 = new stdClass();
$lang->tutorial->testManage->step2->name = 'Click Case';
$lang->tutorial->testManage->step2->desc = 'Click to manage cases';

$lang->tutorial->testManage->step3 = new stdClass();
$lang->tutorial->testManage->step3->name = 'Click Add Case';
$lang->tutorial->testManage->step3->desc = 'Create cases here';

$lang->tutorial->testManage->step4 = new stdClass();
$lang->tutorial->testManage->step4->name = 'Fill out form';

$lang->tutorial->testManage->step5 = new stdClass();
$lang->tutorial->testManage->step5->name = 'Save form';
$lang->tutorial->testManage->step5->desc = 'View cases in the case list after saving';

$lang->tutorial->testManage->step6 = new stdClass();
$lang->tutorial->testManage->step6->name = 'Click Request';
$lang->tutorial->testManage->step6->desc = 'Click to maintain test sheet information';

$lang->tutorial->testManage->step7 = new stdClass();
$lang->tutorial->testManage->step7->name = 'Click Submit Request';
$lang->tutorial->testManage->step7->desc = 'Clicking submit test sheet will generate a test sheet';

$lang->tutorial->testManage->step8 = new stdClass();
$lang->tutorial->testManage->step8->name = 'Fill out form';

$lang->tutorial->testManage->step9 = new stdClass();
$lang->tutorial->testManage->step9->name = 'Save form';
$lang->tutorial->testManage->step9->desc = 'View test sheets in the test sheet list after saving';

$lang->tutorial->testManage->step10 = new stdClass();
$lang->tutorial->testManage->step10->name = 'Click Request Name';
$lang->tutorial->testManage->step10->desc = 'Click to view test sheet details';

$lang->tutorial->testManage->step11 = new stdClass();
$lang->tutorial->testManage->step11->name = 'Click Link Case';
$lang->tutorial->testManage->step11->desc = 'Associate cases with the test sheet by clicking';

$lang->tutorial->testManage->step12 = new stdClass();
$lang->tutorial->testManage->step12->name = 'Check Cases';
$lang->tutorial->testManage->step12->desc = 'You can associate cases with the test sheet';

$lang->tutorial->testManage->step13 = new stdClass();
$lang->tutorial->testManage->step13->name = 'Click Save';
$lang->tutorial->testManage->step13->desc = 'Successfully associate cases with the test sheet after saving';

$lang->tutorial->testManage->step14 = new stdClass();
$lang->tutorial->testManage->step14->name = 'Click Run';
$lang->tutorial->testManage->step14->desc = 'Click to execute cases';

$lang->tutorial->testManage->step15 = new stdClass();
$lang->tutorial->testManage->step15->name = 'Fill out form';

$lang->tutorial->testManage->step16 = new stdClass();
$lang->tutorial->testManage->step16->name = 'Save form';
$lang->tutorial->testManage->step16->desc = 'Complete case execution after saving';

$lang->tutorial->testManage->step17 = new stdClass();
$lang->tutorial->testManage->step17->name = 'Click Results';
$lang->tutorial->testManage->step17->desc = 'Execute cases here';

$lang->tutorial->testManage->step18 = new stdClass();
$lang->tutorial->testManage->step18->name = 'Select Case Step';

$lang->tutorial->testManage->step19 = new stdClass();
$lang->tutorial->testManage->step19->name = 'Click Report Bug';
$lang->tutorial->testManage->step19->desc = 'Convert failed case steps to bugs';

$lang->tutorial->testManage->step20 = new stdClass();
$lang->tutorial->testManage->step20->name = 'Fill out form';

$lang->tutorial->testManage->step21 = new stdClass();
$lang->tutorial->testManage->step21->name = 'Save form';

$lang->tutorial->testManage->step22 = new stdClass();
$lang->tutorial->testManage->step22->name = 'Click Report';

$lang->tutorial->testManage->step23 = new stdClass();
$lang->tutorial->testManage->step23->name = 'Create Report';
$lang->tutorial->testManage->step23->desc = 'Generate test reports here';

$lang->tutorial->testManage->step24 = new stdClass();
$lang->tutorial->testManage->step24->name = 'Fill out form';

$lang->tutorial->testManage->step25 = new stdClass();
$lang->tutorial->testManage->step25->name = 'Save form';
$lang->tutorial->testManage->step25->desc = 'Generate test report after saving';

$lang->tutorial->accountManage = new stdClass();
$lang->tutorial->accountManage->title = 'Account Management Tutorial';

$lang->tutorial->accountManage->deptManage = new stdClass();
$lang->tutorial->accountManage->deptManage->title = 'Department Management';

$lang->tutorial->accountManage->deptManage->step1 = new stdClass();
$lang->tutorial->accountManage->deptManage->step1->name = 'Click on Admin';
$lang->tutorial->accountManage->deptManage->step1->desc = 'You can maintain and manage accounts here, and configure various settings.';

$lang->tutorial->accountManage->deptManage->step2 = new stdClass();
$lang->tutorial->accountManage->deptManage->step2->name = 'Click on User';
$lang->tutorial->accountManage->deptManage->step2->desc = 'You can maintain departments, add personnel, and configure group permissions here.';

$lang->tutorial->accountManage->deptManage->step3 = new stdClass();
$lang->tutorial->accountManage->deptManage->step3->name = 'Click on Dept';
$lang->tutorial->accountManage->deptManage->step3->desc = 'You can click here to maintain departments.';

$lang->tutorial->accountManage->deptManage->step4 = new stdClass();
$lang->tutorial->accountManage->deptManage->step4->name = 'Fill out the form';

$lang->tutorial->accountManage->deptManage->step5 = new stdClass();
$lang->tutorial->accountManage->deptManage->step5->name = 'Save the form';
$lang->tutorial->accountManage->deptManage->step5->desc = 'After saving, you can see it in the left directory.';

$lang->tutorial->accountManage->addUser = new stdClass();
$lang->tutorial->accountManage->addUser->title = 'Add User';

$lang->tutorial->accountManage->addUser->step1 = new stdClass();
$lang->tutorial->accountManage->addUser->step1->name = 'Click on Users';
$lang->tutorial->accountManage->addUser->step1->desc = 'You can maintain company personnel here.';

$lang->tutorial->accountManage->addUser->step2 = new stdClass();
$lang->tutorial->accountManage->addUser->step2->name = 'Click on Add User';
$lang->tutorial->accountManage->addUser->step2->desc = 'Click to add company personnel.';

$lang->tutorial->accountManage->addUser->step3 = new stdClass();
$lang->tutorial->accountManage->addUser->step3->name = 'Fill out the form';

$lang->tutorial->accountManage->addUser->step4 = new stdClass();
$lang->tutorial->accountManage->addUser->step4->name = 'Save the form';
$lang->tutorial->accountManage->addUser->step4->desc = 'After saving, you can view it in the personnel list.';

$lang->tutorial->accountManage->privManage = new stdClass();
$lang->tutorial->accountManage->privManage->title = 'Permission Management';

$lang->tutorial->accountManage->privManage->step1 = new stdClass();
$lang->tutorial->accountManage->privManage->step1->name = 'Click on Privilege';
$lang->tutorial->accountManage->privManage->step1->desc = 'You can view group members and maintain member permissions here.';

$lang->tutorial->accountManage->privManage->step2 = new stdClass();
$lang->tutorial->accountManage->privManage->step2->name = 'Click on Create Group';
$lang->tutorial->accountManage->privManage->step2->desc = 'Click to add a new group of members.';

$lang->tutorial->accountManage->privManage->step3 = new stdClass();
$lang->tutorial->accountManage->privManage->step3->name = 'Fill out the form';

$lang->tutorial->accountManage->privManage->step4 = new stdClass();
$lang->tutorial->accountManage->privManage->step4->name = 'Save the form';
$lang->tutorial->accountManage->privManage->step4->desc = 'After saving, you can view it in the personnel list.';

$lang->tutorial->accountManage->privManage->step5 = new stdClass();
$lang->tutorial->accountManage->privManage->step5->name = 'Click on Manage Member';
$lang->tutorial->accountManage->privManage->step5->desc = 'You can add company members to the permission group for future group authorization.';

$lang->tutorial->accountManage->privManage->step6 = new stdClass();
$lang->tutorial->accountManage->privManage->step6->name = 'Fill out the form';

$lang->tutorial->accountManage->privManage->step7 = new stdClass();
$lang->tutorial->accountManage->privManage->step7->name = 'Save the form';
$lang->tutorial->accountManage->privManage->step7->desc = 'After saving, you can view it in the personnel list.';

$lang->tutorial->accountManage->privManage->step8 = new stdClass();
$lang->tutorial->accountManage->privManage->step8->name = 'Click on Assign Privileges';
$lang->tutorial->accountManage->privManage->step8->desc = 'Click to manage permissions for the user group.';

$lang->tutorial->accountManage->privManage->step9 = new stdClass();
$lang->tutorial->accountManage->privManage->step9->name = 'Click on the Expand button for Permission Package';
$lang->tutorial->accountManage->privManage->step9->desc = 'Click to view the permissions under the permission package.';

$lang->tutorial->accountManage->privManage->step10 = new stdClass();
$lang->tutorial->accountManage->privManage->step10->name = 'Save the form';
$lang->tutorial->accountManage->privManage->step10->desc = 'After saving, members of this group will have the allocated permissions.';

$lang->tutorial->productManage = new stdClass();
$lang->tutorial->productManage->title = 'Product Management Tutorial';

$lang->tutorial->productManage->addProduct = new stdClass();
$lang->tutorial->productManage->addProduct->title = 'Product Maintenance';

$lang->tutorial->productManage->addProduct->step1 = new stdClass();
$lang->tutorial->productManage->addProduct->step1->name = 'Click to Create Product';
$lang->tutorial->productManage->addProduct->step1->desc = 'Click to add a product';

$lang->tutorial->productManage->addProduct->step2 = new stdClass();
$lang->tutorial->productManage->addProduct->step2->name = 'Fill in the Form';

$lang->tutorial->productManage->addProduct->step3 = new stdClass();
$lang->tutorial->productManage->addProduct->step3->name = 'Save the Form';
$lang->tutorial->productManage->addProduct->step3->desc = 'After saving, you can view it in the product list';

$lang->tutorial->productManage->moduleManage = new stdClass();
$lang->tutorial->productManage->moduleManage->title = 'Product Module Maintenance';

$lang->tutorial->productManage->moduleManage->step1 = new stdClass();
$lang->tutorial->productManage->moduleManage->step1->name = 'Click on the Product Name';
$lang->tutorial->productManage->moduleManage->step1->desc = 'Click to enter the product and view detailed information.';

$lang->tutorial->productManage->moduleManage->step2 = new stdClass();
$lang->tutorial->productManage->moduleManage->step2->name = 'Click on Set Module';
$lang->tutorial->productManage->moduleManage->step2->desc = 'Click to maintain the product modules';

$lang->tutorial->productManage->moduleManage->step3 = new stdClass();
$lang->tutorial->productManage->moduleManage->step3->name = 'Fill in the Form';

$lang->tutorial->productManage->moduleManage->step4 = new stdClass();
$lang->tutorial->productManage->moduleManage->step4->name = 'Save the Form';
$lang->tutorial->productManage->moduleManage->step4->desc = 'After saving, you can classify modules when creating requirements';

$lang->tutorial->productManage->storyManage = new stdClass();
$lang->tutorial->productManage->storyManage->title = 'Story Management';

$lang->tutorial->productManage->storyManage->step1 = new stdClass();
$lang->tutorial->productManage->storyManage->step1->name = 'Click on Epic';
$lang->tutorial->productManage->storyManage->step1->desc = 'Here you can manage the business requirements of the product';

$lang->tutorial->productManage->storyManage->step2 = new stdClass();
$lang->tutorial->productManage->storyManage->step2->name = 'Click to Create Epic';
$lang->tutorial->productManage->storyManage->step2->desc = 'Click to submit business requirements';

$lang->tutorial->productManage->storyManage->step3 = new stdClass();
$lang->tutorial->productManage->storyManage->step3->name = 'Fill in the Form';

$lang->tutorial->productManage->storyManage->step4 = new stdClass();
$lang->tutorial->productManage->storyManage->step4->name = 'Save the Form';
$lang->tutorial->productManage->storyManage->step4->desc = 'After saving, you can view it in the business requirements list';

$lang->tutorial->productManage->storyManage->step5 = new stdClass();
$lang->tutorial->productManage->storyManage->step5->name = 'Click to Split Epic';
$lang->tutorial->productManage->storyManage->step5->desc = 'Click to split business requirements into user requirements';

$lang->tutorial->productManage->storyManage->step6 = new stdClass();
$lang->tutorial->productManage->storyManage->step6->name = 'Fill in the Form';

$lang->tutorial->productManage->storyManage->step7 = new stdClass();
$lang->tutorial->productManage->storyManage->step7->name = 'Save the Form';
$lang->tutorial->productManage->storyManage->step7->desc = 'After saving, you can view it in the requirements list';

$lang->tutorial->productManage->storyManage->step8 = new stdClass();
$lang->tutorial->productManage->storyManage->step8->name = 'Click to Split Requirements';
$lang->tutorial->productManage->storyManage->step8->desc = 'Click to split user requirements into development requirements';

$lang->tutorial->productManage->storyManage->step9 = new stdClass();
$lang->tutorial->productManage->storyManage->step9->name = 'Fill in the Form';

$lang->tutorial->productManage->storyManage->step10 = new stdClass();
$lang->tutorial->productManage->storyManage->step10->name = 'Save the Form';
$lang->tutorial->productManage->storyManage->step10->desc = 'After saving, you can view it in the requirements list';

$lang->tutorial->productManage->storyManage->step11 = new stdClass();
$lang->tutorial->productManage->storyManage->step11->name = 'Click on Review';
$lang->tutorial->productManage->storyManage->step11->desc = 'Click to review the requirements';

$lang->tutorial->productManage->storyManage->step12 = new stdClass();
$lang->tutorial->productManage->storyManage->step12->name = 'Fill in the Form';

$lang->tutorial->productManage->storyManage->step13 = new stdClass();
$lang->tutorial->productManage->storyManage->step13->name = 'Save the Form';
$lang->tutorial->productManage->storyManage->step13->desc = 'After saving, the status of the requirements changes based on the review result';

$lang->tutorial->productManage->storyManage->step14 = new stdClass();
$lang->tutorial->productManage->storyManage->step14->name = 'Click on Change';
$lang->tutorial->productManage->storyManage->step14->desc = 'Click to make changes to the requirements';

$lang->tutorial->productManage->storyManage->step15 = new stdClass();
$lang->tutorial->productManage->storyManage->step15->name = 'Fill in the Form';

$lang->tutorial->productManage->storyManage->step16 = new stdClass();
$lang->tutorial->productManage->storyManage->step16->name = 'Save the Form';
$lang->tutorial->productManage->storyManage->step16->desc = 'After saving, the requirement changes are completed';

$lang->tutorial->productManage->storyManage->step17 = new stdClass();
$lang->tutorial->productManage->storyManage->step17->name = 'Click on Track';
$lang->tutorial->productManage->storyManage->step17->desc = 'Here you can track the progress of requirements';

$lang->tutorial->productManage->planManage = new stdClass();
$lang->tutorial->productManage->planManage->title = 'Plan Management';

$lang->tutorial->productManage->planManage->step1 = new stdClass();
$lang->tutorial->productManage->planManage->step1->name = 'Click on Plan';
$lang->tutorial->productManage->planManage->step1->desc = 'Here you can maintain and manage product plans.';

$lang->tutorial->productManage->planManage->step2 = new stdClass();
$lang->tutorial->productManage->planManage->step2->name = 'Click to Create Plan';
$lang->tutorial->productManage->planManage->step2->desc = 'Click to create a plan for the product.';

$lang->tutorial->productManage->planManage->step3 = new stdClass();
$lang->tutorial->productManage->planManage->step3->name = 'Fill out the form';

$lang->tutorial->productManage->planManage->step4 = new stdClass();
$lang->tutorial->productManage->planManage->step4->name = 'Save the form';
$lang->tutorial->productManage->planManage->step4->desc = 'After saving, you can view it in the plan list.';

$lang->tutorial->productManage->planManage->step5 = new stdClass();
$lang->tutorial->productManage->planManage->step5->name = 'Click on Plan Name';
$lang->tutorial->productManage->planManage->step5->desc = 'Click to enter the details of the plan and manage its information.';

$lang->tutorial->productManage->planManage->step6 = new stdClass();
$lang->tutorial->productManage->planManage->step6->name = 'Click to Link Story';
$lang->tutorial->productManage->planManage->step6->desc = 'Associate the stories that the plan needs to fulfill.';

$lang->tutorial->productManage->planManage->step7 = new stdClass();
$lang->tutorial->productManage->planManage->step7->name = 'Check Stories';

$lang->tutorial->productManage->planManage->step8 = new stdClass();
$lang->tutorial->productManage->planManage->step8->name = 'Click Save';
$lang->tutorial->productManage->planManage->step8->desc = 'After saving, the requirements are successfully associated with the plan.';

$lang->tutorial->productManage->planManage->step9 = new stdClass();
$lang->tutorial->productManage->planManage->step9->name = 'Click on Bug';
$lang->tutorial->productManage->planManage->step9->desc = 'Associate the bugs that the plan needs to resolve.';

$lang->tutorial->productManage->planManage->step10 = new stdClass();
$lang->tutorial->productManage->planManage->step10->name = 'Click to Link Bug';
$lang->tutorial->productManage->planManage->step10->desc = 'Click to associate the bugs that need to be resolved with the plan.';

$lang->tutorial->productManage->planManage->step11 = new stdClass();
$lang->tutorial->productManage->planManage->step11->name = 'Check Bug';

$lang->tutorial->productManage->planManage->step12 = new stdClass();
$lang->tutorial->productManage->planManage->step12->name = 'Click Save';
$lang->tutorial->productManage->planManage->step12->desc = 'After saving, the bugs are successfully associated with the plan.';

$lang->tutorial->productManage->releaseManage = new stdClass();
$lang->tutorial->productManage->releaseManage->title = 'Release Management';

$lang->tutorial->productManage->releaseManage->step1 = new stdClass();
$lang->tutorial->productManage->releaseManage->step1->name = 'Click on Release';
$lang->tutorial->productManage->releaseManage->step1->desc = 'Here you can maintain and manage the release information of the product.';

$lang->tutorial->productManage->releaseManage->step2 = new stdClass();
$lang->tutorial->productManage->releaseManage->step2->name = 'Click to Create Release';
$lang->tutorial->productManage->releaseManage->step2->desc = 'Click to create a release for the product.';

$lang->tutorial->productManage->releaseManage->step3 = new stdClass();
$lang->tutorial->productManage->releaseManage->step3->name = 'Fill out the form';

$lang->tutorial->productManage->releaseManage->step4 = new stdClass();
$lang->tutorial->productManage->releaseManage->step4->name = 'Save the form';
$lang->tutorial->productManage->releaseManage->step4->desc = 'After saving, you can view it in the release list.';

$lang->tutorial->productManage->releaseManage->step5 = new stdClass();
$lang->tutorial->productManage->releaseManage->step5->name = 'Click on Release Name';
$lang->tutorial->productManage->releaseManage->step5->desc = 'Click to enter the release, view and manage detailed release information.';

$lang->tutorial->productManage->releaseManage->step6 = new stdClass();
$lang->tutorial->productManage->releaseManage->step6->name = 'Click to Link Story';
$lang->tutorial->productManage->releaseManage->step6->desc = 'Click to associate the development requirements to be released this time.';

$lang->tutorial->productManage->releaseManage->step7 = new stdClass();
$lang->tutorial->productManage->releaseManage->step7->name = 'Check Stories';

$lang->tutorial->productManage->releaseManage->step8 = new stdClass();
$lang->tutorial->productManage->releaseManage->step8->name = 'Click Save';
$lang->tutorial->productManage->releaseManage->step8->desc = 'After saving, the requirements are successfully associated with the release.';

$lang->tutorial->productManage->releaseManage->step9 = new stdClass();
$lang->tutorial->productManage->releaseManage->step9->name = 'Click on Resolved Bug';
$lang->tutorial->productManage->releaseManage->step9->desc = 'Click to view and manage the bugs resolved in this release.';

$lang->tutorial->productManage->releaseManage->step10 = new stdClass();
$lang->tutorial->productManage->releaseManage->step10->name = 'Click to Link Bug';
$lang->tutorial->productManage->releaseManage->step10->desc = 'Click to associate the bugs resolved in this release with the release.';

$lang->tutorial->productManage->releaseManage->step11 = new stdClass();
$lang->tutorial->productManage->releaseManage->step11->name = 'Check Bugs';

$lang->tutorial->productManage->releaseManage->step12 = new stdClass();
$lang->tutorial->productManage->releaseManage->step12->name = 'Click Save';
$lang->tutorial->productManage->releaseManage->step12->desc = 'After saving, the bugs are successfully associated with the release.';

$lang->tutorial->productManage->releaseManage->step13 = new stdClass();
$lang->tutorial->productManage->releaseManage->step13->name = 'Click on Active Bug';
$lang->tutorial->productManage->releaseManage->step13->desc = 'Click to view and manage the bugs remaining unresolved in this release.';

$lang->tutorial->productManage->releaseManage->step14 = new stdClass();
$lang->tutorial->productManage->releaseManage->step14->name = 'Click to Link Bug';
$lang->tutorial->productManage->releaseManage->step14->desc = 'Click to associate the unresolved bugs in this release with the release.';

$lang->tutorial->productManage->releaseManage->step15 = new stdClass();
$lang->tutorial->productManage->releaseManage->step15->name = 'Check Bugs';

$lang->tutorial->productManage->releaseManage->step16 = new stdClass();
$lang->tutorial->productManage->releaseManage->step16->name = 'Click Save';
$lang->tutorial->productManage->releaseManage->step16->desc = 'After saving, the bugs are successfully associated with the release.';

$lang->tutorial->productManage->releaseManage->step17 = new stdClass();
$lang->tutorial->productManage->releaseManage->step17->name = 'Click on Publish Button';
$lang->tutorial->productManage->releaseManage->step17->desc = 'Click to proceed with the release.';

$lang->tutorial->productManage->releaseManage->step18 = new stdClass();
$lang->tutorial->productManage->releaseManage->step18->name = 'Fill out the form';

$lang->tutorial->productManage->releaseManage->step19 = new stdClass();
$lang->tutorial->productManage->releaseManage->step19->name = 'Save the form';
$lang->tutorial->productManage->releaseManage->step19->desc = 'After saving, the requirements will change stages based on the release status.';

$lang->tutorial->productManage->releaseManage->step20 = new stdClass();
$lang->tutorial->productManage->releaseManage->step20->name = 'Click on Manage Application';
$lang->tutorial->productManage->releaseManage->step20->desc = 'Here you can maintain and manage the application information of the product.';

$lang->tutorial->productManage->releaseManage->step21 = new stdClass();
$lang->tutorial->productManage->releaseManage->step21->name = 'Click to Create Application';
$lang->tutorial->productManage->releaseManage->step21->desc = 'Click to create an application for the product. ';

$lang->tutorial->productManage->releaseManage->step22 = new stdClass();
$lang->tutorial->productManage->releaseManage->step22->name = 'Fill out the form';

$lang->tutorial->productManage->releaseManage->step23 = new stdClass();
$lang->tutorial->productManage->releaseManage->step23->name = 'Save the form';
$lang->tutorial->productManage->releaseManage->step23->desc = 'After saving, the application is successfully created. ';

$lang->tutorial->productManage->releaseManage->step24 = new stdClass();
$lang->tutorial->productManage->releaseManage->step24->name = 'Click on Back';
$lang->tutorial->productManage->releaseManage->step24->desc = 'Click to return to the previous page. ';

$lang->tutorial->productManage->lineManage = new stdClass();
$lang->tutorial->productManage->lineManage->title = 'Product Line Management';

$lang->tutorial->productManage->lineManage->step1 = new stdClass();
$lang->tutorial->productManage->lineManage->step1->name = 'Click on Product';
$lang->tutorial->productManage->lineManage->step1->desc = 'Here you can maintain and manage products.';

$lang->tutorial->productManage->lineManage->step2 = new stdClass();
$lang->tutorial->productManage->lineManage->step2->name = 'Click on Product Line';
$lang->tutorial->productManage->lineManage->step2->desc = 'Click to maintain product lines.';

$lang->tutorial->productManage->lineManage->step3 = new stdClass();
$lang->tutorial->productManage->lineManage->step3->name = 'Fill out the form';

$lang->tutorial->productManage->lineManage->step4 = new stdClass();
$lang->tutorial->productManage->lineManage->step4->name = 'Save the form';
$lang->tutorial->productManage->lineManage->step4->desc = 'After saving, you can choose the corresponding product line when maintaining products.';

$lang->tutorial->productManage->branchManage = new stdClass();
$lang->tutorial->productManage->branchManage->title = 'Multiple Branch/Platform Management';

$lang->tutorial->productManage->branchManage->step1 = new stdClass();
$lang->tutorial->productManage->branchManage->step1->name = 'Click on Product';
$lang->tutorial->productManage->branchManage->step1->desc = 'Here you can maintain and manage products.';

$lang->tutorial->productManage->branchManage->step2 = new stdClass();
$lang->tutorial->productManage->branchManage->step2->name = 'Click on Create Product';
$lang->tutorial->productManage->branchManage->step2->desc = 'Click to add a product.';

$lang->tutorial->productManage->branchManage->step3 = new stdClass();
$lang->tutorial->productManage->branchManage->step3->name = 'Fill out the form';

$lang->tutorial->productManage->branchManage->step4 = new stdClass();
$lang->tutorial->productManage->branchManage->step4->name = 'Save the form';

$lang->tutorial->productManage->branchManage->step5 = new stdClass();
$lang->tutorial->productManage->branchManage->step5->name = 'Click on Settings';
$lang->tutorial->productManage->branchManage->step5->desc = 'Click to maintain product information.';

$lang->tutorial->productManage->branchManage->step6 = new stdClass();
$lang->tutorial->productManage->branchManage->step6->name = 'Click on Branch';
$lang->tutorial->productManage->branchManage->step6->desc = 'Click to maintain product branches.';

$lang->tutorial->productManage->branchManage->step7 = new stdClass();
$lang->tutorial->productManage->branchManage->step7->name = 'Click on Create Branch';
$lang->tutorial->productManage->branchManage->step7->desc = 'Click to add a new branch to the product.';

$lang->tutorial->productManage->branchManage->step8 = new stdClass();
$lang->tutorial->productManage->branchManage->step8->name = 'Fill out the form';

$lang->tutorial->productManage->branchManage->step9 = new stdClass();
$lang->tutorial->productManage->branchManage->step9->name = 'Save the form';
$lang->tutorial->productManage->branchManage->step9->desc = 'After saving, view the branches in the branch list.';

$lang->tutorial->productManage->branchManage->step10 = new stdClass();
$lang->tutorial->productManage->branchManage->step10->name = 'Check the Branch';

$lang->tutorial->productManage->branchManage->step11 = new stdClass();
$lang->tutorial->productManage->branchManage->step11->name = 'Click on Merge';

$lang->tutorial->productManage->branchManage->step12 = new stdClass();
$lang->tutorial->productManage->branchManage->step12->name = 'Select a Branch';

$lang->tutorial->productManage->branchManage->step13 = new stdClass();
$lang->tutorial->productManage->branchManage->step13->name = 'Save the form';
$lang->tutorial->productManage->branchManage->step13->desc = 'After saving, all items under the branch (such as releases, plans, versions, modules, requirements, bugs, and test cases) are merged into the new branch.';

$lang->tutorial->productManage->branchManage->step14 = new stdClass();
$lang->tutorial->productManage->branchManage->step14->name = 'Click on Story';
$lang->tutorial->productManage->branchManage->step14->desc = 'Here you can manage the R&D requirements of the product.';

$lang->tutorial->productManage->branchManage->step15 = new stdClass();
$lang->tutorial->productManage->branchManage->step15->name = 'Click on Create Story';
$lang->tutorial->productManage->branchManage->step15->desc = 'Click to create a twin requirement.';

$lang->tutorial->productManage->branchManage->step16 = new stdClass();
$lang->tutorial->productManage->branchManage->step16->name = 'Fill out the form';

$lang->tutorial->productManage->branchManage->step17 = new stdClass();
$lang->tutorial->productManage->branchManage->step17->name = 'Save the form';
$lang->tutorial->productManage->branchManage->step17->desc = 'After saving, each branch will have a corresponding requirement, and these requirements will be twinned. The twin requirements will stay synchronized except for the product, branch, module, plan, and stage fields. You can unlink the twin requirements on the requirement details page.';

$lang->tutorial->programManage = new stdClass();
$lang->tutorial->programManage->title = 'Program Portfolio Management Tutorial';

$lang->tutorial->programManage->addProgram = new stdClass();
$lang->tutorial->programManage->addProgram->title = 'Adding Programs';

$lang->tutorial->programManage->addProgram->step1 = new stdClass();
$lang->tutorial->programManage->addProgram->step1->name = 'Click on Program';
$lang->tutorial->programManage->addProgram->step1->desc = 'You can manage program portfolios here.';

$lang->tutorial->programManage->addProgram->step2 = new stdClass();
$lang->tutorial->programManage->addProgram->step2->name = 'Click on Create Program';
$lang->tutorial->programManage->addProgram->step2->desc = 'Click to add a new program.';

$lang->tutorial->programManage->addProgram->step3 = new stdClass();
$lang->tutorial->programManage->addProgram->step3->name = 'Fill out the form';

$lang->tutorial->programManage->addProgram->step4 = new stdClass();
$lang->tutorial->programManage->addProgram->step4->name = 'Save the form';
$lang->tutorial->programManage->addProgram->step4->desc = 'Once saved, you can view it in the program and product perspectives lists.';

$lang->tutorial->programManage->addProgram->step5 = new stdClass();
$lang->tutorial->programManage->addProgram->step5->name = 'Click on Create Project';
$lang->tutorial->programManage->addProgram->step5->desc = 'Click to manage projects under the program portfolio.';

$lang->tutorial->programManage->addProgram->step6 = new stdClass();
$lang->tutorial->programManage->addProgram->step6->name = 'Click on Scrum';
$lang->tutorial->programManage->addProgram->step6->desc = 'You can add projects to this program portfolio here.';

$lang->tutorial->programManage->addProgram->step7 = new stdClass();
$lang->tutorial->programManage->addProgram->step7->name = 'Fill out the form';

$lang->tutorial->programManage->addProgram->step8 = new stdClass();
$lang->tutorial->programManage->addProgram->step8->name = 'Save the form';
$lang->tutorial->programManage->addProgram->step8->desc = 'Once saved, you can view it in the project perspective list.';

$lang->tutorial->programManage->addProgram->step9 = new stdClass();
$lang->tutorial->programManage->addProgram->step9->name = 'Click on Product View';
$lang->tutorial->programManage->addProgram->step9->desc = 'Here you can view the relationship between managed programs and products.';

$lang->tutorial->programManage->addProgram->step10 = new stdClass();
$lang->tutorial->programManage->addProgram->step10->name = 'Click to Expand';

$lang->tutorial->programManage->addProgram->step11 = new stdClass();
$lang->tutorial->programManage->addProgram->step11->name = 'Click on Create Product';
$lang->tutorial->programManage->addProgram->step11->desc = 'Click to manage products under the program portfolio.';

$lang->tutorial->programManage->addProgram->step12 = new stdClass();
$lang->tutorial->programManage->addProgram->step12->name = 'Fill out the form';

$lang->tutorial->programManage->addProgram->step13 = new stdClass();
$lang->tutorial->programManage->addProgram->step13->name = 'Save the form';
$lang->tutorial->programManage->addProgram->step13->desc = 'Once saved, you can view it in the product perspective list.';

$lang->tutorial->programManage->whitelistManage = new stdClass();
$lang->tutorial->programManage->whitelistManage->title = 'Whitelist Management';

$lang->tutorial->programManage->whitelistManage->step1 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step1->name = 'Click on Program Name';
$lang->tutorial->programManage->whitelistManage->step1->desc = 'Click to enter the program portfolio and view detailed information.';

$lang->tutorial->programManage->whitelistManage->step2 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step2->name = 'Click on Member';
$lang->tutorial->programManage->whitelistManage->step2->desc = 'Click to view personnel involved in the program, accessible personnel, and whitelist information.';

$lang->tutorial->programManage->whitelistManage->step3 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step3->name = 'Click on Whitelist';
$lang->tutorial->programManage->whitelistManage->step3->desc = 'Click to manage the whitelist for the program portfolio.';

$lang->tutorial->programManage->whitelistManage->step4 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step4->name = 'Click on Add Whitelist';
$lang->tutorial->programManage->whitelistManage->step4->desc = 'Click to manage personnel on the whitelist.';

$lang->tutorial->programManage->whitelistManage->step5 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step5->name = 'Fill out the form';

$lang->tutorial->programManage->whitelistManage->step6 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step6->name = 'Save the form';
$lang->tutorial->programManage->whitelistManage->step6->desc = 'Once saved, whitelisted personnel can view the program portfolio.';

$lang->tutorial->programManage->addStakeholder = new stdClass();
$lang->tutorial->programManage->addStakeholder->title = 'Create Stakeholders';

$lang->tutorial->programManage->addStakeholder->step1 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step1->name = 'Click on Stakeholder';
$lang->tutorial->programManage->addStakeholder->step1->desc = 'Click to manage stakeholders of the program portfolio.';

$lang->tutorial->programManage->addStakeholder->step2 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step2->name = 'Click on Create Stakeholder';
$lang->tutorial->programManage->addStakeholder->step2->desc = 'Click to add internal and external stakeholders of the program portfolio.';

$lang->tutorial->programManage->addStakeholder->step3 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step3->name = 'Fill out the form';

$lang->tutorial->programManage->addStakeholder->step4 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step4->name = 'Save the form';
$lang->tutorial->programManage->addStakeholder->step4->desc = 'Once saved, stakeholders can view the program portfolio.';

$lang->tutorial->feedbackManage = new stdClass();
$lang->tutorial->feedbackManage->title = 'Feedback Management Tutorial';

$lang->tutorial->feedbackManage->feedback = new stdClass();
$lang->tutorial->feedbackManage->feedback->title = 'Feedback Management';

$lang->tutorial->feedbackManage->feedback->step1 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step1->name = 'Click on Feedback';
$lang->tutorial->feedbackManage->feedback->step1->desc = 'Here you can add and manage feedback entries.';

$lang->tutorial->feedbackManage->feedback->step2 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step2->name = 'Click on Create Feedback';
$lang->tutorial->feedbackManage->feedback->step2->desc = 'Click to provide feedback for a specific product.';

$lang->tutorial->feedbackManage->feedback->step3 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step3->name = 'Fill out the form';

$lang->tutorial->feedbackManage->feedback->step4 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step4->name = 'Save the form';
$lang->tutorial->feedbackManage->feedback->step4->desc = 'Once saved, the feedback entry will be listed for follow-up and processing progress.';

$lang->tutorial->feedbackManage->feedback->step5 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step5->name = 'Click on Review';
$lang->tutorial->feedbackManage->feedback->step5->desc = 'Click to review and evaluate the feedback entry.';

$lang->tutorial->feedbackManage->feedback->step6 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step6->name = 'Fill out the form';

$lang->tutorial->feedbackManage->feedback->step7 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step7->name = 'Save the form';
$lang->tutorial->feedbackManage->feedback->step7->desc = 'Saving the review form will update the status of the feedback entry accordingly.';

$lang->tutorial->feedbackManage->feedback->step8 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step8->name = 'Click on Convert to Bug';
$lang->tutorial->feedbackManage->feedback->step8->desc = 'Click to choose the handling method for the feedback entry.';

$lang->tutorial->feedbackManage->feedback->step9 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step9->name = 'Fill out the form';

$lang->tutorial->feedbackManage->feedback->step10 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step10->name = 'Save the form';
$lang->tutorial->feedbackManage->feedback->step10->desc = 'Saving will change the status of the feedback entry to "In Progress". The status will be updated to "Resolved" once the related tasks or requirements are completed.';

$lang->tutorial->feedbackManage->feedback->step11 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step11->name = 'Close Feedback';
$lang->tutorial->feedbackManage->feedback->step11->desc = 'Click to close feedback entries that have been successfully processed.';

$lang->tutorial->feedbackManage->feedback->step12 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step12->name = 'Fill out the form';

$lang->tutorial->feedbackManage->feedback->step13 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step13->name = 'Save the form';

$lang->tutorial->docManage = new stdClass();
$lang->tutorial->docManage->title = 'Document Management Tutorial';

$lang->tutorial->docManage->step1 = new stdClass();
$lang->tutorial->docManage->step1->name = 'Click on Doc';
$lang->tutorial->docManage->step1->desc = 'Here you can manage documents for products, projects, teams, and individuals.';

$lang->tutorial->docManage->step2 = new stdClass();
$lang->tutorial->docManage->step2->name = 'Click on Team Space';
$lang->tutorial->docManage->step2->desc = 'Product spaces manage documents under each product, project spaces manage documents under each project, team spaces manage organizational team documents, and interface spaces specifically manage interface documents. Please click on Team Space to enter.';

$lang->tutorial->docManage->step3 = new stdClass();
$lang->tutorial->docManage->step3->name = 'Click on More';

$lang->tutorial->docManage->step4 = new stdClass();
$lang->tutorial->docManage->step4->name = 'Click on Create Space';

$lang->tutorial->docManage->step5 = new stdClass();
$lang->tutorial->docManage->step5->name = 'Fill out the form';

$lang->tutorial->docManage->step6 = new stdClass();
$lang->tutorial->docManage->step6->name = 'Save the form';
$lang->tutorial->docManage->step6->desc = 'After saving, you can manage libraries and documents in the space.';

$lang->tutorial->docManage->step7 = new stdClass();
$lang->tutorial->docManage->step7->name = 'Click on Create Library';
$lang->tutorial->docManage->step7->desc = 'Click to create a document library.';

$lang->tutorial->docManage->step8 = new stdClass();
$lang->tutorial->docManage->step8->name = 'Fill out the form';

$lang->tutorial->docManage->step9 = new stdClass();
$lang->tutorial->docManage->step9->name = 'Save the form';
$lang->tutorial->docManage->step9->desc = 'After saving, you can view it in the left sidebar directory tree.';

$lang->tutorial->docManage->step10 = new stdClass();
$lang->tutorial->docManage->step10->name = 'Hover over and click More button';

$lang->tutorial->docManage->step11 = new stdClass();
$lang->tutorial->docManage->step11->name = 'Click on Add Directory';
$lang->tutorial->docManage->step11->desc = 'Click to add directories to the document library.';

$lang->tutorial->docManage->step12 = new stdClass();
$lang->tutorial->docManage->step12->name = 'Fill in the directory name';

$lang->tutorial->docManage->step13 = new stdClass();
$lang->tutorial->docManage->step13->name = 'Click on Create Document';
$lang->tutorial->docManage->step13->desc = 'Click to create a document.';

$lang->tutorial->docManage->step14 = new stdClass();
$lang->tutorial->docManage->step14->name = 'Fill out the form';

$lang->tutorial->docManage->step15 = new stdClass();
$lang->tutorial->docManage->step15->name = 'Click on Publish';

$lang->tutorial->docManage->step16 = new stdClass();
$lang->tutorial->docManage->step16->name = 'Fill out the form';

$lang->tutorial->docManage->step17 = new stdClass();
$lang->tutorial->docManage->step17->name = 'Save and Publish';
$lang->tutorial->docManage->step17->desc = 'After saving, you can view it in the document list.';

$lang->tutorial->docManage->step18 = new stdClass();
$lang->tutorial->docManage->step18->name = 'Click on Document Title';
$lang->tutorial->docManage->step18->desc = 'Click to view document details, support for bookmarking, editing, exporting documents, and viewing document history and update information.';

$lang->tutorial->docManage->step19 = new stdClass();
$lang->tutorial->docManage->step19->name = 'Click on Edit Button';
$lang->tutorial->docManage->step19->desc = 'Click to edit the document content.';

$lang->tutorial->docManage->step20 = new stdClass();
$lang->tutorial->docManage->step20->name = 'Edit Document';

$lang->tutorial->docManage->step21 = new stdClass();
$lang->tutorial->docManage->step21->name = 'Click on Publish';
$lang->tutorial->docManage->step21->desc = 'Click to save the edited content.';

$lang->tutorial->docManage->step22 = new stdClass();
$lang->tutorial->docManage->step22->name = 'Click on Versions';
$lang->tutorial->docManage->step22->desc = 'You can switch document versions here to view historical version records.';

$lang->tutorial->docManage->step23 = new stdClass();
$lang->tutorial->docManage->step23->name = 'Click on Version #1';
$lang->tutorial->docManage->step23->desc = 'View the document content of Version #1.';

$lang->tutorial->orTutorial = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->title = 'Demand Pool Management Tutorial';

$lang->tutorial->orTutorial->demandpoolManage->demandManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->title = 'Demand Management';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->name = 'Click to Create Demand';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->desc = 'Click to create a new demand';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2->name = 'Fill out the Form';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->name = 'Save the Form';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->desc = 'After saving, view the demand in the demand list';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->name = 'Click on Review Button';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->desc = 'Click to review the demand';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5->name = 'Fill out the Form';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->name = 'Save the Form';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->desc = 'After saving, the demand status changes based on the review result';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->name = 'Click on Change Button';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->desc = 'Click to make changes to the demand';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8->name = 'Fill out the Form';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->name = 'Save the Form';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->desc = 'After saving, the demand changes are completed';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->name = 'Click on Track';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->desc = 'You can track the progress of demands here';

$lang->tutorial->orTutorial->marketManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->title = 'Market Management Tutorial';

$lang->tutorial->orTutorial->marketManage->researchManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->title = 'Research Management';

$lang->tutorial->orTutorial->marketManage->researchManage->step1 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step1->name = 'Click on Market';
$lang->tutorial->orTutorial->marketManage->researchManage->step1->desc = 'Here you can manage research activities.';

$lang->tutorial->orTutorial->marketManage->researchManage->step2 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step2->name = 'Click on Research';
$lang->tutorial->orTutorial->marketManage->researchManage->step2->desc = 'Here you can manage research activities.';

$lang->tutorial->orTutorial->marketManage->researchManage->step3 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step3->name = 'Click on Create';
$lang->tutorial->orTutorial->marketManage->researchManage->step3->desc = 'Click to launch a research activity.';

$lang->tutorial->orTutorial->marketManage->researchManage->step4 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step4->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step5 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step5->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step5->desc = 'View in the research list after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step6 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step6->name = 'Click on Research Name';
$lang->tutorial->orTutorial->marketManage->researchManage->step6->desc = 'Click to manage research activities.';

$lang->tutorial->orTutorial->marketManage->researchManage->step7 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step7->name = 'Click on Create Stage';
$lang->tutorial->orTutorial->marketManage->researchManage->step7->desc = 'Click to set the stage of the research activity.';

$lang->tutorial->orTutorial->marketManage->researchManage->step8 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step8->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step9 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step9->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step9->desc = 'View in the research task list after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step10 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step10->name = 'Click on Create Task';
$lang->tutorial->orTutorial->marketManage->researchManage->step10->desc = 'Click to create tasks for the research activity.';

$lang->tutorial->orTutorial->marketManage->researchManage->step11 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step11->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step12 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step12->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step12->desc = 'View in the research task list after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step13 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step13->name = 'Start Task';
$lang->tutorial->orTutorial->marketManage->researchManage->step13->desc = 'Start tasks here and record time spent and remaining hours.';

$lang->tutorial->orTutorial->marketManage->researchManage->step14 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step14->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step15 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step15->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step15->desc = 'Task status changes to "In Progress" after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step16 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step16->name = 'Click on Effort';
$lang->tutorial->orTutorial->marketManage->researchManage->step16->desc = 'Click to record time logs for tasks.';

$lang->tutorial->orTutorial->marketManage->researchManage->step17 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step17->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step18 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step18->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step18->desc = 'Task hours will update based on the logs after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step19 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step19->name = 'Finish Task';
$lang->tutorial->orTutorial->marketManage->researchManage->step19->desc = 'Click to complete the task.';

$lang->tutorial->orTutorial->marketManage->researchManage->step20 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step20->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step21 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step21->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step21->desc = 'Task status changes to "Completed" after saving.';

$lang->tutorial->orTutorial->marketManage->researchManage->step22 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step22->name = 'Close Task';
$lang->tutorial->orTutorial->marketManage->researchManage->step22->desc = 'Click to close the completed task.';

$lang->tutorial->orTutorial->marketManage->researchManage->step23 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step23->name = 'Fill out the form';

$lang->tutorial->orTutorial->marketManage->researchManage->step24 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step24->name = 'Save the form';
$lang->tutorial->orTutorial->marketManage->researchManage->step24->desc = 'Task status changes to "Closed" after saving.';

$lang->tutorial->orTutorial->roadmapManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->title = 'Product Roadmap Management Tutorial';

$lang->tutorial->orTutorial->roadmapManage->lineManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->lineManage = clone $lang->tutorial->productManage->lineManage;

$lang->tutorial->orTutorial->roadmapManage->addProduct = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->addProduct = clone $lang->tutorial->productManage->addProduct;

$lang->tutorial->orTutorial->roadmapManage->moduleManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->moduleManage = clone $lang->tutorial->productManage->moduleManage;

$lang->tutorial->orTutorial->roadmapManage->storyManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->storyManage = clone $lang->tutorial->productManage->storyManage;

$lang->tutorial->orTutorial->roadmapManage->branchManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->branchManage = clone $lang->tutorial->productManage->branchManage;

$lang->tutorial->orTutorial->charterManage = new stdClass();
$lang->tutorial->orTutorial->charterManage->title = 'Charter Tutorial';

$lang->tutorial->orTutorial->charterManage->step1 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step1->name = "Click on Charter";
$lang->tutorial->orTutorial->charterManage->step1->desc = "You can manage Charter initiation here";

$lang->tutorial->orTutorial->charterManage->step2 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step2->name = "Click Create Charter";
$lang->tutorial->orTutorial->charterManage->step2->desc = "Click to submit the Charter initiation request";

$lang->tutorial->orTutorial->charterManage->step3 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step3->name = "Fill out the form";

$lang->tutorial->orTutorial->charterManage->step4 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step4->name = "Save the form";
$lang->tutorial->orTutorial->charterManage->step4->desc = "After saving, track the progress of the application in the initiation list";

$lang->tutorial->orTutorial->charterManage->step5 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step5->name = "Click Review";
$lang->tutorial->orTutorial->charterManage->step5->desc = "Click to review the initiation application";

$lang->tutorial->orTutorial->charterManage->step6 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step6->name = "Fill out the form";

$lang->tutorial->orTutorial->charterManage->step7 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step7->name = "Save the form";
$lang->tutorial->orTutorial->charterManage->step7->desc = "After saving, modify the initiation status based on the review results";

$lang->tutorial->orTutorial->charterManage->step8 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step8->name = "Click Close";
$lang->tutorial->orTutorial->charterManage->step8->desc = "Click the close button to close the Charter upon completion";

$lang->tutorial->orTutorial->charterManage->step9 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step9->name = "Fill out the form";

$lang->tutorial->orTutorial->charterManage->step10 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step10->name = "Save the form";
$lang->tutorial->orTutorial->charterManage->step10->desc = "After saving, the initiation status will change to closed";
