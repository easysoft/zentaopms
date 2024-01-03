<?php
global $config;

/* Method List。*/
$lang->my->index           = 'Home';
$lang->my->data            = 'My Data';
$lang->my->todo            = 'My Todos';
$lang->my->todoAction      = 'Schedule List';
$lang->my->calendar        = 'Schedule';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribute';
$lang->my->task            = 'My Tasks';
$lang->my->bug             = 'My Bugs';
$lang->my->myTestTask      = 'My Builds';
$lang->my->myTestCase      = 'My Cases';
$lang->my->story           = 'My Stories';
$lang->my->doc             = "My Docs";
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My {$lang->projectCommon}s";
$lang->my->execution       = "My {$lang->execution->common}s";
$lang->my->audit           = 'Review';
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
$lang->my->createContacts  = 'Create Contact';
$lang->my->deleteContacts  = 'Delete Contact';
$lang->my->viewContacts    = 'View Contact';
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
$lang->my->pri             = 'Priority';
$lang->my->alert           = 'You can click on your profile at the top right and select "Preference" to modify your information. ';
$lang->my->assignedToMe    = 'AssignedToMe';
$lang->my->byQuery         = 'Search';
$lang->my->contactList     = 'Contact List';
$lang->my->myContact       = 'My Contact';
$lang->my->publicContact   = 'Public Contact';
$lang->my->manageSelf      = 'Only can manage contacts created by your self.';

$lang->my->indexAction      = 'My Index';
$lang->my->calendarAction   = 'My Calendar';
$lang->my->workAction       = 'My Work';
$lang->my->contributeAction = 'My Contribute';
$lang->my->profileAction    = 'Profile';
$lang->my->dynamicAction    = 'Dynamic';

$lang->my->myExecutions = "My Executions";
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = "{$lang->projectCommon}s";
$lang->my->executions   = 'Executions';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'AssignedToMe';
$lang->my->taskMenu->openedByMe   = 'CreatedByMe';
$lang->my->taskMenu->finishedByMe = 'FinishedByMe';
$lang->my->taskMenu->closedByMe   = 'ClosedByMe';
$lang->my->taskMenu->canceledByMe = 'CancelledByMe';
$lang->my->taskMenu->assignedByMe = 'AssignedByMe';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'AssignedToMe';
$lang->my->storyMenu->reviewByMe   = 'ReviewByMe';
$lang->my->storyMenu->openedByMe   = 'CreatedByMe';
$lang->my->storyMenu->reviewedByMe = 'ReviewedByMe';
$lang->my->storyMenu->closedByMe   = 'ClosedByMe';
$lang->my->storyMenu->assignedByMe = 'AssignedByMe';

$lang->my->auditField = new stdclass();
$lang->my->auditField->title  = 'Title';
$lang->my->auditField->time   = 'Time';
$lang->my->auditField->type   = 'Type';
$lang->my->auditField->result = 'Result';
$lang->my->auditField->status = 'Status';

$lang->my->auditField->oaTitle['attend']   = '%s application for attend: %s';
$lang->my->auditField->oaTitle['leave']    = '%s application for leave: %s';
$lang->my->auditField->oaTitle['makeup']   = '%s application for makeup:%s';
$lang->my->auditField->oaTitle['overtime'] = '%s application for overtime: %s';
$lang->my->auditField->oaTitle['lieu']     = '%s application for lieu: %s';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';

$lang->my->programLink   = 'Program Default Page';
$lang->my->productLink   = $lang->productCommon . ' Default Page';
$lang->my->projectLink   = $lang->projectCommon . ' Default Page';
$lang->my->executionLink = 'Execution Default Page';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = 'Project Set List/View all project sets';
$lang->my->programLinkList['program-kanban']  = 'Project Set Kanban/You can visually view the progress of all project sets';
$lang->my->programLinkList['program-project'] = "{$lang->projectCommon} list of the most recent program/You can view all items under the current program";

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-all']       = "{$lang->productCommon} List/Can view all {$lang->productCommon}s";
$lang->my->productLinkList['product-kanban']    = "{$lang->productCommon} Kanban/You can visually view the progress of all {$lang->productCommon}s";
$lang->my->productLinkList['product-index']     = "All {$lang->productCommon} dashboards/You can view the statistics of all {$lang->productCommon}s";
$lang->my->productLinkList['product-dashboard'] = "Last {$lang->productCommon} dashboard/You can view the current {$lang->productCommon} overview";
$lang->my->productLinkList['product-browse']    = "Demand list of the latest {$lang->productCommon}/You can view the demand information under the current {$lang->productCommon}";

$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = "{$lang->projectCommon} List/Can view all items";
$lang->my->projectLinkList['project-kanban']    = "{$lang->projectCommon} Kanban/The {$lang->projectCommon} board can visually view the progress of all {$lang->projectCommon}s";
$lang->my->projectLinkList['project-execution'] = "All execution lists under the {$lang->projectCommon}/View all execution information";
$lang->my->projectLinkList['project-index']     = "Recent Project Dashboard/You can view the current {$lang->projectCommon} overview";

$lang->my->executionLinkList = array();
$lang->my->executionLinkList['execution-all']             = 'Execution list/You can view all executions';
$lang->my->executionLinkList['execution-executionkanban'] = 'Execute Kanban/You can view the implementation status of projects in progress';
$lang->my->executionLinkList['execution-task']            = 'List of recently executed tasks/You can view the task information under the current iteration';

$lang->my->confirmReview['pass'] = 'Do you want to pass it?';
$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'><span style='color: #0c60e1'>"Young Blue"</span> theme is available now!</p>
<div>
  <p>Just one step and you will have it.</p>
  <p>Click <span style='color: #0c60e1'>Avatar-Theme-Young Blue</span>. It is done!</p>
</div>
EOT;

$lang->my->featureBar['todo']['all']       = 'Assigned To Yourself';
$lang->my->featureBar['todo']['before']    = 'Unfinished';
$lang->my->featureBar['todo']['future']    = 'TBD';
$lang->my->featureBar['todo']['today']     = 'Today';
$lang->my->featureBar['todo']['thisWeek']  = 'This Week';
$lang->my->featureBar['todo']['thisMonth'] = 'This Month';
$lang->my->featureBar['todo']['more']      = 'More';

$lang->my->moreSelects['todo']['more']['thisYear']        = 'This Year';
$lang->my->moreSelects['todo']['more']['assignedToOther'] = 'Assigned To Other';
$lang->my->moreSelects['todo']['more']['cycle']           = 'Recurrence';

$lang->my->featureBar['audit']['all']      = 'All';
$lang->my->featureBar['audit']['demand']   = 'Demand';
$lang->my->featureBar['audit']['story']    = 'Story';
$lang->my->featureBar['audit']['testcase'] = 'Test case';
if(in_array($config->edition, array('max', 'ipd')) and (helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus'))) $lang->my->featureBar['audit']['project'] = $lang->projectCommon;
if($config->edition != 'open') $lang->my->featureBar['audit']['feedback'] = 'Feedback';
if($config->edition != 'open' and helper::hasFeature('OA')) $lang->my->featureBar['audit']['oa'] = 'OA';

$lang->my->featureBar['project']['doing']      = 'Doing';
$lang->my->featureBar['project']['wait']       = 'Waiting';
$lang->my->featureBar['project']['suspended']  = 'Suspended';
$lang->my->featureBar['project']['closed']     = 'Closed';
$lang->my->featureBar['project']['openedbyme'] = 'CreatedByMe';

$lang->my->featureBar['execution']['undone'] = 'Undone';
$lang->my->featureBar['execution']['done']   = 'Done';

$lang->my->featureBar['dynamic']['all']       = 'All';
$lang->my->featureBar['dynamic']['today']     = 'Today';
$lang->my->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->my->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->my->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->my->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->my->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->my->featureBar['work']['task']['assignedTo']     = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testcase']['assigntome'] = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testtask']['assignedTo'] = 'Test task';

$lang->my->featureBar['work']['requirement'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['requirement']['reviewBy'] = 'ReviewByMe';

$lang->my->featureBar['work']['story'] = $lang->my->featureBar['work']['requirement'];
$lang->my->featureBar['work']['bug']   = $lang->my->featureBar['work']['task'];

$lang->my->featureBar['contribute']['task']['openedBy']   = 'CreatedByMe';
$lang->my->featureBar['contribute']['task']['finishedBy'] = 'FinishedByMe';
$lang->my->featureBar['contribute']['task']['closedBy']   = 'ClosedByMe';
$lang->my->featureBar['contribute']['task']['canceledBy'] = 'CancelledByMe';
$lang->my->featureBar['contribute']['task']['assignedBy'] = 'AssignedByMe';

$lang->my->featureBar['contribute']['requirement']['openedBy']   = 'CreatedByMe';
$lang->my->featureBar['contribute']['requirement']['reviewedBy'] = 'ReviewedByMe';
$lang->my->featureBar['contribute']['requirement']['closedBy']   = 'ClosedByMe';
$lang->my->featureBar['contribute']['requirement']['assignedBy'] = 'AssignedByMe';

$lang->my->featureBar['contribute']['bug']['openedBy']   = 'CreatedByMe';
$lang->my->featureBar['contribute']['bug']['resolvedBy'] = 'ResolvedByMe';
$lang->my->featureBar['contribute']['bug']['closedBy']   = 'ClosedByMe';
$lang->my->featureBar['contribute']['bug']['assignedBy'] = 'AssignedByMe';

$lang->my->featureBar['contribute']['story'] = $lang->my->featureBar['contribute']['requirement'];

$lang->my->featureBar['contribute']['testcase']['openedbyme'] = 'CreatedByMe';

$lang->my->featureBar['contribute']['testtask']['done'] = 'Tested';

$lang->my->featureBar['contribute']['audit']['reviewedbyme'] = 'ReviewedByMe';
$lang->my->featureBar['contribute']['audit']['createdbyme']  = 'CreatedByMe';

$lang->my->featureBar['contribute']['doc']['openedbyme'] = 'CreatedByMe';
$lang->my->featureBar['contribute']['doc']['editedbyme'] = 'EditedByMe';

$lang->my->featureBar['score']['all'] = 'My Score';
