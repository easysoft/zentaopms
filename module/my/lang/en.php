<?php
global $config;

/* Method List。*/
$lang->my->index           = 'Home';
$lang->my->data            = 'My Data';
$lang->my->todo            = 'My To-do';
$lang->my->todoAction      = 'Schedule List';
$lang->my->calendar        = 'Schedule';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribution';
$lang->my->task            = 'My Tasks';
$lang->my->bug             = 'My Bugs';
$lang->my->myTestTask      = 'My Test Requests';
$lang->my->myTestCase      = 'My Test Cases';
$lang->my->story           = 'My Stories';
$lang->my->doc             = "My Docs";
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My {$lang->projectCommon}s";
$lang->my->execution       = "My {$lang->execution->common}s";
$lang->my->audit           = 'Reviews';
$lang->my->issue           = 'My Issues';
$lang->my->risk            = 'My Risks';
$lang->my->reviewissue     = 'My Review Issue';
$lang->my->profile         = 'My Profile';
$lang->my->dynamic         = 'My Recents';
$lang->my->team            = 'Team';
$lang->my->editProfile     = 'Edit Profile';
$lang->my->changePassword  = 'Change Oassword';
$lang->my->preference      = 'Preference';
$lang->my->unbind          = 'Unbind ZDOO';
$lang->my->manageContacts  = 'Manage Contacts';
$lang->my->createContacts  = 'Create Contact';
$lang->my->deleteContacts  = 'Delete Contact';
$lang->my->viewContacts    = 'View Contact';
$lang->my->shareContacts   = 'Public Contacts';
$lang->my->limited         = 'Permission restricted. You can only edit your own data.';
$lang->my->score           = 'My Points';
$lang->my->scoreRule       = 'Point Rules';
$lang->my->noTodo          = 'No To-do yet.';
$lang->my->noData          = 'No %s yet.';
$lang->my->storyChanged    = "Story Changed";
$lang->my->hours           = "Hours/day";
$lang->my->uploadAvatar    = 'Update Avatar';
$lang->my->epic            = "My {$lang->ERCommon}";
$lang->my->requirement     = "My {$lang->URCommon}";
$lang->my->testtask        = 'Mt Test Request';
$lang->my->testcase        = 'My Test Case';
$lang->my->storyConcept    = 'Story Concept';
$lang->my->pri             = 'Priority';
$lang->my->alert           = 'You can click your avatar in the upper-right corner and select Preference to update your information.';
$lang->my->assignedToMe    = 'Assigned to Me';
$lang->my->byQuery         = 'Search';
$lang->my->contactHolder   = 'Contacts';
$lang->my->contactList     = 'Contact List';
$lang->my->myContact       = 'My';
$lang->my->publicContact   = 'Public';
$lang->my->manageSelf      = 'You can only edit contacts you created.';
$lang->my->adminView       = 'System administrators have permission to delete public contacts.';

$lang->my->indexAction      = 'Dashboard Overview';
$lang->my->calendarAction   = 'My Calendar';
$lang->my->workAction       = 'My Work';
$lang->my->contributeAction = 'My Contribution';
$lang->my->profileAction    = 'Profile';
$lang->my->dynamicAction    = 'Recents';

$lang->my->myExecutions = "My Executions";
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = "{$lang->projectCommon}s";
$lang->my->executions   = 'Executions';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'Assigned to Me';
$lang->my->taskMenu->openedByMe   = 'Created by Me';
$lang->my->taskMenu->finishedByMe = 'Completed by Me';
$lang->my->taskMenu->closedByMe   = 'Closed by Me';
$lang->my->taskMenu->canceledByMe = 'Cancelled by Me';
$lang->my->taskMenu->assignedByMe = 'Assigned by Me';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Assigned to Me';
$lang->my->storyMenu->reviewByMe   = 'My Pending Review';
$lang->my->storyMenu->openedByMe   = 'Created by Me';
$lang->my->storyMenu->reviewedByMe = 'Reviewed by Me';
$lang->my->storyMenu->closedByMe   = 'Closed by Me';
$lang->my->storyMenu->assignedByMe = 'Assigned by Me';

$lang->my->auditField = new stdclass();
$lang->my->auditField->title  = 'Title';
$lang->my->auditField->time   = 'Submission Time';
$lang->my->auditField->type   = 'Type';
$lang->my->auditField->result = 'Result';
$lang->my->auditField->status = 'Status';

$lang->my->auditField->oaTitle['attend']   = '%ss Attendance Request: %s';
$lang->my->auditField->oaTitle['leave']    = '%ss Leave Request: %s';
$lang->my->auditField->oaTitle['makeup']   = '%ss Make-up Work Request: %s';
$lang->my->auditField->oaTitle['overtime'] = '%ss Overtime Request: %s';
$lang->my->auditField->oaTitle['lieu']     = '%ss Compensatory Leave Request: %s';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basic Info';
$lang->my->form->lblContact = 'Contact Info';
$lang->my->form->lblAccount = 'Account Info';

$lang->my->programLink   = 'Program Default Page';
$lang->my->productLink   = $lang->productCommon . ' Default Page';
$lang->my->projectLink   = $lang->projectCommon . ' Default Page';
$lang->my->executionLink = 'Execution Default Page';
$lang->my->docLink       = 'Document Default Page';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = 'Program List / View all programs.';
$lang->my->programLinkList['program-kanban']  = 'Program Kanban / Visualize the progress of all programs.';
$lang->my->programLinkList['program-project'] = "Recent Programs {$lang->projectCommon} List / View all {$lang->projectCommon} under the current program.";

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-all']       = "{$lang->productCommon} List / View all {$lang->productCommon}.";
$lang->my->productLinkList['product-kanban']    = "{$lang->productCommon} Kanban / Visualize the progress of all {$lang->productCommon}.";
$lang->my->productLinkList['product-index']     = "All {$lang->productCommon} Dashboard / View statistics, summaries, and overviews of all {$lang->productCommon}.";
$lang->my->productLinkList['product-dashboard'] = "Recent {$lang->productCommon} Dashboard / View the most recently accessed {$lang->productCommon} dashboard.";
$lang->my->productLinkList['product-browse']    = "Recent {$lang->productCommon}s Story List / Access the story list under the most recently viewed {$lang->productCommon}.";

$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = "{$lang->projectCommon} List / View all {$lang->projectCommon}.";
$lang->my->projectLinkList['project-kanban']    = "{$lang->projectCommon} Kanban / Visualize the progress of all {$lang->projectCommon}.";
$lang->my->projectLinkList['project-execution'] = "Recent {$lang->projectCommon} Execution List / View all execution lists under the {$lang->projectCommon}.";
$lang->my->projectLinkList['project-index']     = "Recent {$lang->projectCommon} Dashboard / Access the dashboard of the most recently viewed {$lang->projectCommon}.";

$lang->my->executionLinkList = array();
$lang->my->executionLinkList['execution-all']             = 'Execution List / View all executions.';
$lang->my->executionLinkList['execution-executionkanban'] = 'Execution Kanban / Visualize the progress of all executions.';
$lang->my->executionLinkList['execution-task']            = 'Recent Execution Task List / View the tasks under the most recently created execution.';

$lang->my->docLinkList = array();
$lang->my->docLinkList['doc-lastViewedSpaceHome'] = 'The most recently viewed space homepage';
$lang->my->docLinkList['doc-lastViewedSpace']     = 'The most recently viewed space';
$lang->my->docLinkList['doc-lastViewedLib']       = 'The most recently viewed library';

$lang->my->confirmReview['pass'] = 'Are you sure you want to pass it?';
$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'><span style='color: #0c60e1'>Young Blue</span>theme is available now!</p>
<div>
<p>With just one step, you can experience the brand new theme! Go ahead and set it up now!</p>
<p>Simply hover over<span style='color: #0c60e1'>【Avatar - Theme - Young Blue】</span>, click on Young Blue, and you're all set!</p>
</div>
EOT;

$lang->my->featureBar['todo']['all']       = 'Assigned to Me';
$lang->my->featureBar['todo']['undone']    = 'Uncompleted';
$lang->my->featureBar['todo']['future']    = 'TBD';
$lang->my->featureBar['todo']['today']     = 'Today';
$lang->my->featureBar['todo']['thisWeek']  = 'This Week';
$lang->my->featureBar['todo']['thisMonth'] = 'This Month';
$lang->my->featureBar['todo']['more']      = 'More';

$lang->my->moreSelects['todo']['more']['thisYear']        = 'This Year';
$lang->my->moreSelects['todo']['more']['assignedToOther'] = 'Assigned to Others';
$lang->my->moreSelects['todo']['more']['cycle']           = 'Duration';

$lang->my->featureBar['audit']['all']         = 'All';
$lang->my->featureBar['audit']['demand']      = 'Stories from Story Pool';
$lang->my->featureBar['audit']['story']       = $lang->SRCommon;
$lang->my->featureBar['audit']['requirement'] = $lang->URCommon;
$lang->my->featureBar['audit']['epic']        = $lang->ERCommon;
$lang->my->featureBar['audit']['testcase']    = 'Test case';
$lang->my->featureBar['audit']['mr']          = 'Merge request';
if(in_array($config->edition, array('max', 'ipd')) and (helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus'))) $lang->my->featureBar['audit']['project'] = $lang->projectCommon;
if($config->edition != 'open') $lang->my->featureBar['audit']['feedback'] = 'Feedback';
if($config->edition != 'open' and helper::hasFeature('OA')) $lang->my->featureBar['audit']['oa'] = 'OA';

$lang->my->featureBar['project']['doing']      = 'Doing';
$lang->my->featureBar['project']['wait']       = 'Waiting';
$lang->my->featureBar['project']['suspended']  = 'On Hold';
$lang->my->featureBar['project']['delayed']    = 'Delayed';
$lang->my->featureBar['project']['closed']     = 'Closed';
$lang->my->featureBar['project']['openedbyme'] = 'Created by Me';

$lang->my->featureBar['execution']['undone']  = 'Uncompleted';
$lang->my->featureBar['execution']['done']    = 'Done';
$lang->my->featureBar['execution']['delayed'] = 'Delayed';

$lang->my->featureBar['dynamic']['all']       = 'All';
$lang->my->featureBar['dynamic']['today']     = 'Today';
$lang->my->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->my->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->my->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->my->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->my->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->my->featureBar['work']['task']['assignedTo']     = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testcase']['assigntome'] = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testtask']['assignedTo'] = 'Managed by Me';

$lang->my->featureBar['work']['epic'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['epic']['reviewBy'] = 'My Pending Review';

$lang->my->featureBar['work']['requirement'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['requirement']['reviewBy'] = 'My Pending Review';

$lang->my->featureBar['work']['story'] = $lang->my->featureBar['work']['requirement'];
$lang->my->featureBar['work']['bug']   = $lang->my->featureBar['work']['task'];

$lang->my->featureBar['contribute']['task']['openedBy']   = 'Created by Me';
$lang->my->featureBar['contribute']['task']['finishedBy'] = 'Completed by Me';
$lang->my->featureBar['contribute']['task']['myInvolved'] = 'My Involvement';
$lang->my->featureBar['contribute']['task']['closedBy']   = 'Closed by Me';
$lang->my->featureBar['contribute']['task']['canceledBy'] = 'Cancelled by Me';
$lang->my->featureBar['contribute']['task']['assignedBy'] = 'Assigned by Me';

$lang->my->featureBar['contribute']['epic']['openedBy']   = 'Created by Me';
$lang->my->featureBar['contribute']['epic']['reviewedBy'] = 'Reviewed by Me';
$lang->my->featureBar['contribute']['epic']['closedBy']   = 'Closed by Me';
$lang->my->featureBar['contribute']['epic']['assignedBy'] = 'Assigned by Me';

$lang->my->featureBar['contribute']['requirement']['openedBy']   = 'Created by Me';
$lang->my->featureBar['contribute']['requirement']['reviewedBy'] = 'Reviewed by Me';
$lang->my->featureBar['contribute']['requirement']['closedBy']   = 'Closed by Me';
$lang->my->featureBar['contribute']['requirement']['assignedBy'] = 'Assigned by Me';

$lang->my->featureBar['contribute']['bug']['openedBy']   = 'Created by Me';
$lang->my->featureBar['contribute']['bug']['resolvedBy'] = 'Fixed by Me';
$lang->my->featureBar['contribute']['bug']['closedBy']   = 'Closed by Me';
$lang->my->featureBar['contribute']['bug']['assignedBy'] = 'Assigned by Me';

$lang->my->featureBar['contribute']['story'] = $lang->my->featureBar['contribute']['requirement'];

$lang->my->featureBar['contribute']['testcase']['openedbyme'] = 'Created by Me';

$lang->my->featureBar['contribute']['testtask']['done'] = 'Tested Test Requests';

$lang->my->featureBar['contribute']['audit']['reviewedbyme'] = 'Reviewed by Me';
$lang->my->featureBar['contribute']['audit']['createdbyme']  = 'Created by Me';

$lang->my->featureBar['contribute']['doc']['openedbyme'] = 'Created by Me';
$lang->my->featureBar['contribute']['doc']['editedbyme'] = 'Edited by Me';

$lang->my->featureBar['score']['all'] = 'My Points';

$lang->my->reviewResultList['pass'] = 'Pass';
$lang->my->reviewResultList['fail'] = 'Reject';
