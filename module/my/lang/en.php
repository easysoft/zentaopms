<?php
$lang->my->common = 'Dashboard';

/* Method List。*/
$lang->my->index          = 'Home';
$lang->my->todo           = 'My Todos';
$lang->my->calendar       = 'Calendar';
$lang->my->task           = 'My Tasks';
$lang->my->bug            = 'My Bugs';
$lang->my->testTask       = 'My Builds';
$lang->my->testCase       = 'My Cases';
$lang->my->story          = 'My Stories';
$lang->my->myProject      = "My {$lang->projectCommon}s";
$lang->my->profile        = 'My Profile';
$lang->my->dynamic        = 'My Dynamics';
$lang->my->editProfile    = 'Edit Profile';
$lang->my->changePassword = 'Edit Password';
$lang->my->unbind         = 'Unbind Zdoo';
$lang->my->manageContacts = 'Manage Contact';
$lang->my->deleteContacts = 'Delete Contact';
$lang->my->shareContacts  = 'Share the list';
$lang->my->limited        = 'Limited Actions (Users can only edit the content that is involved themselves.)';
$lang->my->score          = 'My Point';
$lang->my->scoreRule      = 'Point Rule';
$lang->my->noTodo         = 'No todos. ';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'Assigned To Me';
$lang->my->taskMenu->openedByMe   = 'Created By Me';
$lang->my->taskMenu->finishedByMe = 'Finished By Me';
$lang->my->taskMenu->closedByMe   = 'Closed By Me';
$lang->my->taskMenu->canceledByMe = 'Cancelled By Me';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Assigned To Me';
$lang->my->storyMenu->openedByMe   = 'Created By Me';
$lang->my->storyMenu->reviewedByMe = 'Reviewed By Me';
$lang->my->storyMenu->closedByMe   = 'Closed By Me';

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
