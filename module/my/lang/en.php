<?php
$lang->my->common = 'Dashboard';

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
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My Projects";
$lang->my->execution       = "My {$lang->execution->common}s";
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
$lang->my->storyConcept    = 'Story Concept';
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

$lang->my->myExecutions = "My Stage/Sprint/Iteration";
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = 'Project';
$lang->my->executions   = $lang->execution->common;

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = 'Undone';
$lang->my->executionMenu->done   = 'Done';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'AssignedToMe';
$lang->my->taskMenu->openedByMe   = 'CreatedByMe';
$lang->my->taskMenu->finishedByMe = 'FinishedByMe';
$lang->my->taskMenu->closedByMe   = 'ClosedByMe';
$lang->my->taskMenu->canceledByMe = 'CancelledByMe';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'AssignedToMe';
$lang->my->storyMenu->openedByMe   = 'CreatedByMe';
$lang->my->storyMenu->reviewedByMe = 'ReviewedByMe';
$lang->my->storyMenu->closedByMe   = 'ClosedByMe';

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = 'Doing';
$lang->my->projectMenu->wait       = 'Waiting';
$lang->my->projectMenu->suspended  = 'Suspended';
$lang->my->projectMenu->closed     = 'Closed';
$lang->my->projectMenu->openedbyme = 'CreatedByMe';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Dynamics';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->executionCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Create {$lang->executionCommon}";
$lang->my->home->createProduct = "Create {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Help</a>";
$lang->my->home->noProductsTip = "No {$lang->productCommon} found here.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';

$lang->my->programLink = 'Program Default Page';
$lang->my->productLink = 'Product Default Page'; 
$lang->my->projectLink = 'Project Default Page';

$lang->my->programLinkList = array();
//$lang->my->programLinkList['program-home']     = 'The default access to the program home page, you can understand the company’s overall strategic planning status';
$lang->my->programLinkList['program-pgmbrowse']  = 'By default, you go to the program list, where you can view all of the programs';
//$lang->my->programLinkList['program-pgmindex'] = 'By default, you go to the most recent program dashboard to see the current program overview';
$lang->my->programLinkList['program-pgmproject'] = 'By default, you go to the list of items in the most recent program, and you can view all items under the current program';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = 'The default access to the product home page, you can understand the company’s overall product status';
$lang->my->productLinkList['product-all']       = 'By default, you go to the product list, where you can view all the products';
$lang->my->productLinkList['product-dashboard'] = 'By default, go to the latest product dashboard to see the current product overview';
$lang->my->productLinkList['product-browse']    = 'By default, go to the list of requirements for the most recent product and see the requirements under the current product';

$lang->my->projectLinkList = array();
//$lang->my->projectLinkList['program-home']    = 'The default access to the project home page, you can understand the overall project status of the company';
$lang->my->projectLinkList['program-prjbrowse'] = 'By default, you go to the project list, where you can view all the projects';
$lang->my->projectLinkList['program-index']     = 'By default, go to the most recent project dashboard to see the current project overview';
$lang->my->projectLinkList['project-task']      = 'By default, you go to the task list for the most recent project iteration to see the task information for the current iteration';
