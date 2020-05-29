<?php
$lang->my->common = 'Dashboard';

/* Method List。*/
$lang->my->index          = 'Home';
$lang->my->todo           = 'My Todos';
$lang->my->calendar       = 'Schedule';
$lang->my->task           = 'My Tasks';
$lang->my->bug            = 'My Bugs';
$lang->my->testTask       = 'My Builds';
$lang->my->testCase       = 'My Cases';
$lang->my->story          = 'My Stories';
$lang->my->myProject      = "My {$lang->projectCommon}s";
$lang->my->profile        = 'My Profile';
$lang->my->dynamic        = 'My Dynamics';
$lang->my->editProfile    = 'Edit';
$lang->my->changePassword = 'Edit Password';
$lang->my->unbind         = 'Unbind from Zdoo';
$lang->my->manageContacts = 'Manage Contact';
$lang->my->deleteContacts = 'Delete Contact';
$lang->my->shareContacts  = 'Public';
$lang->my->limited        = 'Limited Actions (Users can only edit what involves them.)';
$lang->my->score          = 'My Points';
$lang->my->scoreRule      = 'Point Rules';
$lang->my->noTodo         = 'No todos yet. ';

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

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Dynamics';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Create {$lang->projectCommon}";
$lang->my->home->createProduct = "Create {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Help</a>";
$lang->my->home->noProductsTip = "No {$lang->productCommon} found here.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';
