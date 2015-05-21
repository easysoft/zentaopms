<?php
$lang->my->common = 'Dashboard';

/* Methods. */
$lang->my->index          = 'Index';
$lang->my->todo           = 'Todo';
$lang->my->task           = 'Task';
$lang->my->bug            = 'Bug';
$lang->my->testTask       = 'Build';
$lang->my->testCase       = 'Test case';
$lang->my->story          = 'Story';
$lang->my->myProject      = $lang->projectcommon;
$lang->my->team           = 'Team';
$lang->my->profile        = 'Profile';
$lang->my->dynamic        = 'Dynamic';
$lang->my->editProfile    = 'Edit profile';
$lang->my->changePassword = 'Change password';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'To me';
$lang->my->taskMenu->openedByMe   = 'My opened';
$lang->my->taskMenu->finishedByMe = 'My finished';
$lang->my->taskMenu->closedByMe   = 'My closed';
$lang->my->taskMenu->canceledByMe = 'My canceled';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'To me';
$lang->my->storyMenu->openedByMe   = 'My opened';
$lang->my->storyMenu->reviewedByMe = 'My reviewed';
$lang->my->storyMenu->closedByMe   = 'My closed';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Dynamic';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectcommon;
$lang->my->home->products      = $lang->productcommon;
$lang->my->home->projectHome   = "{$lang->projectcommon} home";
$lang->my->home->productHome   = "{$lang->productcommon} home";
$lang->my->home->createProject = "create a {$lang->projectcommon}?";
$lang->my->home->createProduct = "create a {$lang->productcommon}?";
$lang->my->home->help          = "<a href='http://www.zentao.net/help-read-79236.html' target='_blank'>Help Book</a>";
$lang->my->home->noProductsTip = "There is no {$lang->productcommon}.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic info';
$lang->my->form->lblContact = 'Contact info';
$lang->my->form->lblAccount = 'Account info';
