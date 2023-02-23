<?php
global $config;

/* Method List。*/
$lang->my->index           = 'Home';
$lang->my->todo            = 'My Todos';
$lang->my->calendar        = 'Schedule';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribute';
$lang->my->task            = 'My Tasks';
$lang->my->bug             = 'My Bugs';
$lang->my->testTask        = 'My Builds';
$lang->my->testCase        = 'My Cases';
$lang->my->story           = 'My Stories';
$lang->my->doc             = "My Docs";
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My Projects";
$lang->my->execution       = "My {$lang->executionCommon}s";
$lang->my->issue           = 'My Issues';
$lang->my->risk            = 'My Risks';
$lang->my->profile         = 'My Profile';
$lang->my->dynamic         = 'My Dynamics';
$lang->my->team            = 'My Team';
$lang->my->editProfile     = 'Edit';
$lang->my->changePassword  = 'Edit Password';
$lang->my->preference      = 'Preference';
$lang->my->unbind          = 'Unbind from Zdoo';
$lang->my->manageContacts  = 'Manage Contact';
$lang->my->deleteContacts  = 'Delete Contact';
$lang->my->shareContacts   = 'Public';
$lang->my->limited         = 'Limited Actions (Users can only edit what involves them.)';
$lang->my->score           = 'My Points';
$lang->my->scoreRule       = 'Point Rules';
$lang->my->noTodo          = 'No todos yet. ';
$lang->my->noData          = 'No %s yet. ';
$lang->my->storyChanged    = "Story Changed";
$lang->my->hours           = "Hours/day";
$lang->my->uploadAvatar    = 'Upload Avatar';
$lang->my->requirement     = "My {$lang->URCommon}";
$lang->my->testtask        = 'My Test Task';
$lang->my->testcase        = 'My Case';
$lang->my->storyConcept    = 'Story Concept';

$lang->my->myExecutions = "My Stage/Sprint/Iteration";
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = 'Project';
$lang->my->executions   = 'Executions';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'AssignedToMe';
$lang->my->taskMenu->openedByMe   = 'CreatedByMe';
$lang->my->taskMenu->finishedByMe = 'FinishedByMe';
$lang->my->taskMenu->closedByMe   = 'ClosedByMe';
$lang->my->taskMenu->canceledByMe = 'CancelledByMe';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'AssignedToMe';
$lang->my->storyMenu->reviewByMe   = 'ReviewByMe';
$lang->my->storyMenu->openedByMe   = 'CreatedByMe';
$lang->my->storyMenu->reviewedByMe = 'ReviewedByMe';
$lang->my->storyMenu->closedByMe   = 'ClosedByMe';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';

$lang->my->programLink   = 'Program Default Page';
$lang->my->productLink   = 'Product Default Page';
$lang->my->projectLink   = 'Project Default Page';
$lang->my->executionLink = 'Execution Default Page';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = 'By default, you go to the program list, where you can view all of the programs';
$lang->my->programLinkList['program-project'] = 'By default, you go to the list of items in the most recent program, and you can view all items under the current program';
$lang->my->programLinkList['program-kanban'] = 'Enter the Program Kanban by default, and check the progress of all Programs';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = 'The default access to the product home page, you can understand the company’s overall product status';
$lang->my->productLinkList['product-all']       = 'By default, you go to the product list, where you can view all the products';
$lang->my->productLinkList['product-dashboard'] = 'By default, go to the latest product dashboard to see the current product overview';
$lang->my->productLinkList['product-browse']    = 'By default, go to the list of requirements for the most recent product and see the requirements under the current product';
$lang->my->productLinkList['product-kanban']    = 'Enter the Product Kanban by default, and check the progress of all Products';

$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = 'By default, you go to the project list, where you can view all the projects';
$lang->my->projectLinkList['project-execution'] = 'Go to Project-Exection by default. You can check all information in Execution';
$lang->my->projectLinkList['project-index']     = 'By default, go to the most recent project dashboard to see the current project overview';
$lang->my->projectLinkList['project-kanban'] = 'Enter the Project Kanban by default, and check the progress of all Projects';

$lang->my->executionLinkList = array();
$lang->my->executionLinkList['execution-all']             = 'Enter the execution list by default, you can view all executions';
$lang->my->executionLinkList['execution-task']            = 'By default, enter the list of the most recently executed task, and you can view the task information under the current iteration';
$lang->my->executionLinkList['execution-executionkanban'] = 'By default, you can enter the execution Kanban to view the execution status of projects in progress';

$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'><span style='color: #0c60e1'>"Young Blue"</span> theme is available now!</p>
<div>
  <p>Just one step and you will have it.</p>
  <p>Click <span style='color: #0c60e1'>Avatar-Theme-Young Blue</span>. It is done!</p>
</div>
EOT;
