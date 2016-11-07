<?php
$lang->my->common = 'My Zone';

/* Method List。*/
$lang->my->index          = 'Homepage';
$lang->my->todo           = 'My To-Dos';
$lang->my->task           = 'My Task';
$lang->my->bug            = 'My Bug';
$lang->my->testTask       = 'My Version';
$lang->my->testCase       = 'My Case';
$lang->my->story          = 'My Story';
$lang->my->myProject      = "My {$lang->projectCommon}";
$lang->my->profile        = 'My Profile';
$lang->my->dynamic        = 'My Dynamic';
$lang->my->editProfile    = 'Edi Profile';
$lang->my->changePassword = 'Edit Password';
$lang->my->unbind         = 'Unbind Ranger';
$lang->my->manageContacts = 'Maintain Contact';
$lang->my->deleteContacts = 'Delete Contact';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'Assigned to Me';
$lang->my->taskMenu->openedByMe   = 'Created by Me';
$lang->my->taskMenu->finishedByMe = 'Finished by Me';
$lang->my->taskMenu->closedByMe   = 'Closed by Me';
$lang->my->taskMenu->canceledByMe = 'Cancelled by Me';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Assigned to Me';
$lang->my->storyMenu->openedByMe   = 'Created by Me';
$lang->my->storyMenu->reviewedByMe = 'Reviewed by Me';
$lang->my->storyMenu->closedByMe   = 'Closed by Me';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Latest Dynamic';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Create {$lang->projectCommon}";
$lang->my->home->createProduct = "Create {$lang->productCommon}";
$lang->my->home->help          = "<a href='http://www.zentao.net/help-read-79236.html' target='_blank'>Help</a>";
$lang->my->home->noProductsTip = "No {$lang->productCommon} found here.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';
