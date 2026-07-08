<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */

include (dirname(__FILE__) . '/common.php');

global $config;

$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = ': ';
$lang->hyphen    = '-';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' on ';
$lang->downArrow = '↓';
$lang->null      = 'Null';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';
$lang->slash     = '/';
$lang->and       = 'and';
$lang->to        = 'To';
$lang->minus     = ' - ';
$lang->in        = 'In';

$lang->zentaoPMS      = 'ZenTao';
$lang->pmsName        = 'ALM';
$lang->proName        = 'Pro';
$lang->bizName        = 'Biz';
$lang->maxName        = 'Max';
$lang->liteName       = 'Lite';
$lang->devopsPrefix   = 'ZenTao DevOps Platform';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome        = "%s Project Management System";
$lang->logout         = 'Log out';
$lang->login          = 'Log in';
$lang->help           = 'Help';
$lang->aboutZenTao    = 'About ZenTao';
$lang->ztWebsite      = 'ZenTao Website';
$lang->profile        = 'My Profile';
$lang->changePassword = 'Change Password';
$lang->unfoldMenu     = 'Expand Sidebar';
$lang->collapseMenu   = 'Collapse Sidebar';
$lang->preference     = 'Preferences';
$lang->tutorialAB     = 'Tutorial';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "I have read and agreed to the terms and conditions. <strong class='ml-3'>Without authorization, I should not remove, hide or cover any logos/links of ZenTao.</strong>";
$lang->designedByAIUX = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux listitem item-inner menu-item-inner state' target='_blank'><i class='icon icon-aiux item-icon'></i><div class='item-content text'>AIUX</div></a>";
$lang->bizVersion     = '<a href="https://www.zentao.net/page/enterprise.html" target="_blank">Upgrade to Standard</a>';
$lang->bizVersionINT  = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Upgrade to Standard</a>';

$lang->reset              = 'Reset';
$lang->cancel             = 'Cancel';
$lang->refresh            = 'Refresh';
$lang->refreshIcon        = "<i title='$lang->refresh' class='icon icon-refresh'></i>";
$lang->create             = 'Create';
$lang->edit               = 'Edit';
$lang->delete             = 'Delete';
$lang->activate           = 'Activate';
$lang->close              = 'Close';
$lang->unlink             = 'Remove';
$lang->import             = 'Import';
$lang->export             = 'Export';
$lang->setFileName        = 'File name:';
$lang->submitting         = 'Saving...';
$lang->save               = 'Save';
$lang->confirm            = 'Confirm';
$lang->preview            = 'View';
$lang->goback             = 'Back';
$lang->goPC               = 'Desktop';
$lang->more               = 'More';
$lang->moreLink           = 'MORE';
$lang->day                = 'days';
$lang->today              = 'Today';
$lang->yesterday          = 'Yesterday';
$lang->number             = 'Items';
$lang->customConfig       = 'Custom';
$lang->public             = 'Public';
$lang->trunk              = 'Trunk';
$lang->sort               = 'Sort';
$lang->required           = 'Required';
$lang->noData             = 'No data';
$lang->noDesc             = 'No description';
$lang->fullscreen         = 'Full screen';
$lang->retrack            = 'Collapse';
$lang->whitelist          = 'Access whitelist';
$lang->whitelistNotNeed   = 'Public objects don\'t need a whitelist.';
$lang->globalSetting      = 'General';
$lang->waterfallModel     = 'Waterfall';
$lang->scrumModel         = 'Scrum';
$lang->agilePlusModel     = 'Agile Plus';
$lang->waterfallPlusModel = 'Waterfall Plus';
$lang->all                = 'All';
$lang->viewDetails        = 'View Details';
$lang->childrenAB         = 'Child';
$lang->branchName         = 'Branch/Platform';
$lang->recommend          = 'Recommend';
$lang->schedule           = 'Calendar';
$lang->basicInfo          = 'Basic Info';

$lang->actions         = 'Actions';
$lang->restore         = 'Restore defaults';
$lang->confirmDraft    = 'Restore unsaved %name%?';
$lang->resume          = 'Restore';
$lang->comment         = 'Note';
$lang->history         = 'History';
$lang->attach          = 'Attachments';
$lang->reverse         = 'Reverse';
$lang->switchDisplay   = 'Toggle view';
$lang->switchTo        = 'Switch workspace';
$lang->expand          = 'Expand all';
$lang->collapse        = 'Collapse';
$lang->liteMode        = 'Lite Mode';
$lang->fullMode        = 'Full Mode';
$lang->showMoreInfo    = 'Show more';
$lang->hideMoreInfo    = 'Show less';
$lang->saveSuccess     = 'Saved';
$lang->importSuccess   = 'Imported';
$lang->fail            = 'Failed';
$lang->addFiles        = 'Uploaded';
$lang->delFiles        = 'Deleted Files ';
$lang->deleteSuccess   = 'Deleted';
$lang->confirmDelete   = 'Do you want to delete it?';
$lang->deleteing       = 'Deleting...';
$lang->deleted         = 'Deleted';
$lang->files           = 'Attachments';
$lang->pasteText       = 'Bulk entry';
$lang->uploadImages    = 'Bulk upload images';
$lang->uploadImagesTip = 'File names will be used as titles and images as content.';
$lang->timeout         = 'Connection timed out. Check your network or try again.';
$lang->repairTable     = 'Database table may be corrupted. Repair using phpMyAdmin or myisamchk.';
$lang->duplicate       = 'A %s with this title already exists.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Your IP address is restricted. Contact your administrator for access.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Set as homepage';
$lang->noviceTutorial  = 'ZenTao Tutorial';
$lang->changeLog       = 'Changelog';
$lang->manual          = 'User Manual';
$lang->site            = 'Site Manage';
$lang->customMenu      = 'Custom Menu';
$lang->customField     = 'Custom Fields';
$lang->lineNumber      = 'Line Number';
$lang->tutorialConfirm = 'You are still in tutorial mode. Would you like to exit now?';
$lang->levelExceeded   = 'The display limit has been exceeded. Please view on the desktop version or use the search function for more details.';
$lang->noticeOkFile    = '<p class="font-bold mb-2">For security reasons, your admin identity must be confirmed.</p>
    <p class="mb-2">Please log in to the server and create the <strong>%s</strong> file.</p>
    <p class="mb-2">You can run this command: <code>echo "" > %s</code></p>
    <p class="mb-2">Note:</p>
    <ul class="mb-2 pl-4" style="list-style: decimal">
        <li>Please keep the file empty.</li>
        <li>If the file already exists, please delete it and recreate it.</li>
    </ul>';
$lang->noticeDrag      = 'Click or drag files here to upload. Maximum size: %s.';
$lang->allProgress     = 'Progress';
$lang->hasReviewed     = 'This content has already been reviewed. No further action is required.';
$lang->appNotFound     = 'You do not have permission to access this application. Please check your permission settings.';
$lang->uploadFiles     = 'Upload Files';

$lang->fieldDisplaySetting = 'Field display settings';
$lang->fieldSettingTip     = 'The following fields are collapsed by default. Please click "Show more fields" to expand them, or use the pin icon to keep specific fields visible.';

$lang->serviceAgreement = "Terms of Service";
$lang->privacyPolicy    = "Privacy Policy";
$lang->needAgreePrivacy = "Please read the Terms of Service and Privacy Policy first.";
$lang->iAgreedPrivacy   = "I have read and agree";
$lang->inMaintenance    = "The system is currently under maintenance.";
$lang->maintainReason   = "Reason: %s";
$lang->systemMaintainer = "If you have any questions, please contact the administrator.";
$lang->unknown          = "Unknown";

$lang->preShortcutKey    = '[Shortcut: ←]';
$lang->nextShortcutKey   = '[Shortcut: →]';
$lang->backShortcutKey   = '[Shortcut: Alt+↑]';
$lang->shortcutOperation = 'Quick Actions';

$lang->select        = 'Select';
$lang->selectAll     = 'Select All';
$lang->cancelSelect  = 'Deselect';
$lang->selectReverse = 'Invert selection';
$lang->loading       = 'Loading...';
$lang->notFound      = 'The item you are trying to access does not exist or has been removed.';
$lang->notPage       = 'This feature is currently under development and will be available in a future update.';
$lang->showAll       = '[[Show All]]';
$lang->selectedItems = '<strong>{0}</strong> item(s) selected';
$lang->noAssigned    = 'Unassigned';

$lang->future      = 'Pending';
$lang->year        = 'year(s)';
$lang->month       = 'month(s)';
$lang->hour        = 'Hour';
$lang->minute      = 'Minute';
$lang->second      = 'Second';
$lang->workingHour = 'Working Hours';

$lang->idAB         = 'ID';
$lang->priAB        = 'Priority';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Creator';
$lang->assignedToAB = 'Assignee';
$lang->typeAB       = 'Type';
$lang->nameAB       = 'Name';
$lang->code         = 'Code';

$lang->pri     = 'Priority';
$lang->delayed = 'Delayed';

$lang->contactUs = new stdClass();
$lang->contactUs->common = 'Please contact us if you need help.';
$lang->contactUs->phone  = 'Phone';
$lang->contactUs->email  = 'Email';
$lang->contactUs->qq     = 'QQ';
$lang->contactUs->wechat = 'Wechat';

$lang->userSelector = new stdClass();
$lang->userSelector->title         = 'Select Users';
$lang->userSelector->deptTitle     = 'Filter by Department';
$lang->userSelector->userTitle     = 'Select Users';
$lang->userSelector->selectedTitle = 'Selected';
$lang->userSelector->allText       = 'All Users';
$lang->userSelector->emptyText     = 'No users available';

$lang->common->common       = 'Common Module';
$lang->common->story        = 'Story';
$lang->common->stories      = 'Stories';
$lang->cache->common        = 'Cache';
$lang->my->common           = 'Dashboard';
$lang->todo->common         = 'To-do';
$lang->block->common        = 'Block';
$lang->program->common      = 'Program';
$lang->product->common      = $lang->productCommon;
$lang->project->common      = $lang->projectCommon;
$lang->execution->common    = 'Execution';
$lang->kanban->common       = 'Kanban';
$lang->qa->common           = 'Test';
$lang->devops->common       = 'CI&CD';
$lang->devops->configure    = 'Configuration';
$lang->devops->monitor      = 'Monitoring';
$lang->doc->common          = 'Document';
$lang->repo->common         = 'Code';
$lang->repo->commit         = 'Commit';
$lang->repo->tag            = 'Tag';
$lang->repo->branch         = 'Branch';
$lang->repo->codeRepo       = 'Code Repository';
$lang->bi->common           = 'Report';
$lang->screen->common       = 'Screen';
$lang->pivot->common        = 'Pivot Table';
$lang->chart->common        = 'Chart';
$lang->metric->common       = 'Metric';
$lang->report->common       = 'Report';
$lang->system->common       = 'Company';
$lang->admin->common        = 'Admin';
$lang->epic->common         = $lang->ERCommon;
$lang->story->common        = $lang->SRCommon;
$lang->task->common         = 'Task';
$lang->bug->common          = 'Bug';
$lang->testcase->common     = 'Test Case';
$lang->testtask->common     = 'Test Request';
$lang->score->common        = 'My Points';
$lang->build->common        = 'Build';
$lang->testreport->common   = 'Test Report';
$lang->automation->common   = 'Automation';
$lang->autotest->common     = 'Automation';
$lang->team->common         = 'Team';
$lang->user->common         = 'User';
$lang->custom->common       = 'Custom';
$lang->custom->mode         = 'Mode';
$lang->custom->flow         = 'Workflow';
$lang->extension->common    = 'Extension';
$lang->company->common      = 'Company';
$lang->dept->common         = 'Department';
$lang->upgrade->common      = 'Upgrade';
$lang->editor->common       = 'Editor';
$lang->program->list        = 'Program List';
$lang->program->kanban      = 'Program Kanban';
$lang->program->projectView = 'Project View';
$lang->program->productView = 'Product View';
$lang->design->common       = 'Design';
$lang->design->HLDS         = 'High-level Design';
$lang->design->DDS          = 'Detailed Design';
$lang->design->DBDS         = 'Database Design';
$lang->design->ADS          = 'API Design';
$lang->stage->common        = 'Phase';
$lang->stage->type          = 'Phase Type';
$lang->stage->list          = 'Phase List';
$lang->stage->percent       = 'Workload Ratio';
$lang->execution->list      = "{$lang->executionCommon} List";
$lang->execution->CFD       = "Cumulative Flow Diagram";
$lang->kanban->common       = 'Kanban';
$lang->backup->common       = 'Backup';
$lang->action->trash        = 'Trash';
$lang->app->common          = 'Service';
$lang->app->store           = 'App Store';
$lang->app->serverLink      = 'Server Link';
$lang->review->common       = 'Approval';
$lang->zahost->common       = 'ZAhost';
$lang->zanode->common       = 'ZAnode';
$lang->zanode->instruction  = 'Instructions';
$lang->dimension->common    = 'Dimension';
$lang->contact->common      = 'Contacts';
$lang->space->common        = 'Space';
$lang->store->common        = 'App Store';
$lang->instance->common     = 'Instance';
$lang->ai->common           = 'AI';
$lang->aiapp->common        = 'Agent';
$lang->product->system      = 'Application';
$lang->configure->common    = 'Configuration';

$lang->programstakeholder->common   = 'Stakeholders';
$lang->featureswitch->common        = 'Feature toggles';
$lang->importdata->common           = 'Data import';
$lang->systemsetting->common        = 'System settings';
$lang->staffmanage->common          = 'User management';
$lang->featureconfig->common        = 'Feature settings';
$lang->doctemplate->common          = 'Document templates';
$lang->notifysetting->common        = 'Notification settings';
$lang->bidesign->common             = 'BI design';
$lang->personalsettings->common     = 'Personal settings';
$lang->projectsettings->common      = 'Settings';
$lang->dataaccess->common           = 'Data Permissions';
$lang->executiongantt->common       = 'Gantt chart';
$lang->executionkanban->common      = 'Kanban';
$lang->executionburn->common        = 'Burndown chart';
$lang->executioncfd->common         = 'Cumulative Flow Diagram';
$lang->executionstory->common       = 'Story';
$lang->executionqa->common          = 'Test';
$lang->executionbuild->common       = 'Build';
$lang->executionsettings->common    = 'Settings';
$lang->generalcomment->common       = 'Comments';
$lang->generalping->common          = 'Keep-alive';
$lang->generaltemplate->common      = 'Templates';
$lang->generaleffort->common        = 'General logs';
$lang->productsettings->common      = 'Product settings';
$lang->projectreview->common        = 'Reviews';
$lang->projecttrack->common         = 'Matrix';
$lang->projectqa->common            = 'Test';
$lang->holidayseason->common        = 'Holidays';
$lang->codereview->common           = 'Issues';
$lang->repocode->common             = 'Code';
$lang->deliverable->common          = 'Deliverable';
$lang->projectDeliverable->common   = 'Project deliverables';
$lang->executionDeliverable->common = 'Execution deliverables';
$lang->projectTemplate->common      = 'Project templates';

$lang->personnel->common     = 'Members';
$lang->personnel->invest     = 'Allocated members';
$lang->personnel->accessible = 'Accessible members';

$lang->stakeholder->common = 'Stakeholders';
$lang->release->common     = 'Release';
$lang->message->common     = 'System Notification';
$lang->mail->common        = 'Email';

$lang->my->shortCommon          = 'Dashboard';
$lang->testcase->shortCommon    = 'Test Case';
$lang->productplan->shortCommon = 'Plan';
$lang->score->shortCommon       = 'Points';
$lang->testreport->shortCommon  = 'Report';
$lang->qa->shortCommon          = 'QA';
$lang->researchplan->common     = 'Research';
$lang->workestimation->common   = 'Estimation';
$lang->gapanalysis->common      = 'Training';
$lang->executionview->common    = 'View';
$lang->managespace->common      = 'Space management';
$lang->systemteam->common       = 'System team';
$lang->systemschedule->common   = 'System calendar';
$lang->systemeffort->common     = 'System effort';
$lang->systemdynamic->common    = 'System activities';
$lang->systemcompany->common    = 'Company';
$lang->pipeline->common         = 'Pipelines';
$lang->devopssetting->common    = 'Settings';
$lang->deployment->common       = 'Host';
$lang->repoSettings->common     = 'Settings';
$lang->devops->branchType       = 'Branch Type';
$lang->devops->reviewFlow       = 'Review Flow';
$lang->devops->reporeviewflow   = 'Review Flow';
$lang->devops->execution        = 'Execution';
$lang->artifact->common         = 'Artifact Repository';
$lang->ssh->common              = 'SSH Key';
$lang->codeReview->common       = 'Code Review';
$lang->runner->common           = 'Runner';
$lang->repobranchrule->common   = 'Branch Rule';

$lang->dashboard       = 'Overview';
$lang->contribute      = 'Contribution';
$lang->dynamic         = 'Recents';
$lang->whitelist       = 'Whitelist';
$lang->roadmap         = 'Roadmap';
$lang->track           = 'Matrix';
$lang->settings        = 'Settings';
$lang->overview        = 'Overview';
$lang->module          = 'Modules';
$lang->priv            = 'Permission';
$lang->other           = 'Others';
$lang->estimation      = 'Estimation';
$lang->measure         = 'Metrics';
$lang->treeView        = 'Tree View';
$lang->groupView       = 'Group View';
$lang->executionKanban = 'Kanban';
$lang->burn            = 'Burndown';
$lang->view            = 'View';
$lang->intro           = 'Introduction';
$lang->indexPage       = 'Homepage';
$lang->model           = 'Model';
$lang->redev           = 'Custom Development';
$lang->browser         = 'Browser';
$lang->db              = 'Database';
$lang->langItem        = 'Language Items';
$lang->api->doc        = 'API Documentation';
$lang->database        = 'Data Dictionary';
$lang->timezone        = 'Time Zone';
$lang->security        = 'Security';
$lang->calendar        = 'Calendar';

$lang->my->work = 'My Work';

$lang->project->list   = $lang->projectCommon . ' List';
$lang->project->kanban = $lang->projectCommon . ' Kanban';

$lang->execution->executionKanban = "{$lang->execution->common} Kanban";
$lang->execution->all             = "{$lang->execution->common} List";

$lang->doc->recent        = 'Recent documents';
$lang->doc->my            = 'My documents';
$lang->doc->favorite      = 'Favorites';
$lang->doc->product       = $lang->productCommon;
$lang->doc->project       = $lang->projectCommon;
$lang->doc->api           = 'API Library';
$lang->doc->execution     = $lang->execution->common;
$lang->doc->custom        = 'Custom Library';
$lang->doc->wiki          = 'Wiki';
$lang->doc->apiDoc        = 'API Documentation';
$lang->doc->apiStruct     = 'Data Structures';
$lang->doc->quick         = 'Quick Access';
$lang->doc->mySpace       = 'My Space';
$lang->doc->productSpace  = "{$lang->productCommon} Space";
$lang->doc->projectSpace  = "{$lang->projectCommon} Space";
$lang->doc->apiSpace      = 'API Space';
$lang->doc->teamSpace     = 'Team Space';
$lang->doc->template      = 'Document Templates';

$lang->product->list   = $lang->productCommon . ' List';
$lang->product->kanban = $lang->productCommon . ' Kanban';

$lang->project->report = 'Report';

$lang->report->weekly       = 'Weekly Report';
$lang->report->notice       = new stdclass();
$lang->report->notice->help = '<i class="icon icon-help text-warning text-xl mr-2"></i>Report data originates from your list page search results. Please conduct a search on the list page prior to report generation. Example: If you search for open stories, the report will perform statistics specifically on the resulting dataset.';

$lang->testcase->case      = 'Test Case';
$lang->testcase->testsuite = 'Test Suite';
$lang->testcase->caselib   = 'Case Library';

$lang->devops->compile      = 'Pipelines';
$lang->devops->ppm          = 'Code Review';
$lang->devops->repo         = 'Repositories';
$lang->devops->rules        = 'Rules';
$lang->devops->settings     = 'Merge Request Settings';
$lang->devops->platform     = 'Platforms';
$lang->devops->set          = 'Settings';
$lang->devops->environment  = 'Environments';
$lang->devops->resource     = 'Resources';
$lang->devops->dblist       = 'Databases';
$lang->devops->domain       = 'Domains';
$lang->devops->oss          = 'Object Storage Service';
$lang->devops->host         = 'Hosts';
$lang->devops->serverroom   = 'Data Center';
$lang->devops->deploy       = 'Deployments';
$lang->devops->provider     = 'Service Provider';
$lang->devops->city         = 'Location';
$lang->devops->os           = 'OS Version';
$lang->devops->service      = 'Services';
$lang->devops->platform     = 'Platforms';
$lang->devops->components   = 'Components';
$lang->devops->spaceSetting = 'Setting';
$lang->devops->member       = 'Member';
$lang->devops->group        = 'Privilege';
$lang->devops->overview     = 'Overview';
$lang->devops->codescan     = 'Code Scan';
$lang->devops->scanRule     = 'Rule';
$lang->devops->scanSolution = 'Scan Solution';
$lang->devops->scanPlan     = 'Scan Plan';
$lang->devops->scanTask     = 'Scan Task';
$lang->devops->reviewIssue  = 'Review Issue';
$lang->devops->scanIssue    = 'Scan Issue';
$lang->devops->ruleSet      = 'Rule Set';
$lang->devops->system       = 'Applications';
$lang->systemManage->common = 'Applications';
$lang->codeScan->common     = 'Scan Rules';
$lang->ruleset->common      = 'Ruleset';
$lang->scansolution->common = 'Scan Solution';
$lang->scanplan->common     = 'Scan Plan';
$lang->scantask->common     = 'Scan Task';
$lang->scanissue->common    = 'Scan Issue';
$lang->scanOverview->common = 'Scan Overview';
$lang->provider->common     = 'Service';

$lang->admin->module      = 'Feature Settings';
$lang->admin->system      = 'System';
$lang->admin->entry       = 'Integrations';
$lang->admin->data        = 'Data';
$lang->admin->cron        = 'Cron Job';
$lang->admin->buildIndex  = 'Rebuild Index';
$lang->admin->tableEngine = 'Table Engine';

$lang->convert->importJira = 'Import Jira Data';

$lang->storyConcept  = 'Story Concept';
$lang->defaultERName = 'Epic';

$lang->searchTips = '';
$lang->searchAB   = 'Search';

/* Object list in search form. */
$lang->searchObjects['all']         = 'All';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = $lang->SRCommon;
if($config->enableER) $lang->searchObjects['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->searchObjects['requirement'] = $lang->URCommon;
$lang->searchObjects['task']        = 'Task';
$lang->searchObjects['testcase']    = 'Case';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . ' Plan';
$lang->searchObjects['testtask']    = 'Test Request';
$lang->searchObjects['doc']         = 'Document';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'Test Report';
$lang->searchObjects['program']     = 'Program';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['execution']   = $lang->execution->common;
$lang->searchObjects['user']        = 'User';
$lang->searchObjects['aiapp']       = 'AI';
$lang->searchTips                   = 'ID (ctrl+g)';

/* Code formats for import. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list for export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'All Data';
$lang->exportTypeList['selected'] = 'Selected Data';

$lang->visionList = array();
$lang->visionList['rnd']    = 'Full Feature Interface';
$lang->visionList['lite']   = 'Operations Interface';
//$lang->visionList['devops'] = 'DevOps Interface';

$lang->createObjects['todo']        = 'To-Do';
$lang->createObjects['effort']      = 'Effort';
$lang->createObjects['bug']         = 'Bug';
$lang->createObjects['story']       = $lang->SRCommon;
$lang->createObjects['task']        = 'Task';
$lang->createObjects['testcase']    = 'Case';
$lang->createObjects['execution']   = $lang->execution->common;
$lang->createObjects['project']     = $lang->projectCommon;
$lang->createObjects['product']     = $lang->productCommon;
$lang->createObjects['program']     = 'Program';
$lang->createObjects['doc']         = 'Document';
$lang->createObjects['board']       = 'Whiteboard';
$lang->createObjects['kanbanspace'] = 'Space';
$lang->createObjects['kanban']      = 'Kanban';

/* Language. */
$lang->lang    = 'Language';
$lang->setLang = 'Language Settings';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Classic Blue';
$lang->themes['blue']       = 'Blue';
$lang->themes['green']      = 'Green';
$lang->themes['red']        = 'Red';
$lang->themes['purple']     = 'Purple';
$lang->themes['blackberry'] = 'Blackberry';

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "Company for domain %s not found.";
$lang->error->length          = array("Length of 『%s』 should be 『%s』.", "Length of 『%s』 must be between 『%s』 and 『%s』.");
$lang->error->reg             = "Format of 『%s』 is invalid. It should be: 『%s』.";
$lang->error->unique          = "『%s』 already has a record for 『%s』. If deleted, restore it from Admin > System > Trash.";
$lang->error->repeat          = "『%s』 already has a record for 『%s』.";
$lang->error->gt              = "『%s』 must be greater than 『%s』.";
$lang->error->ge              = "『%s』 must be greater than or equal to 『%s』.";
$lang->error->lt              = "『%s』 must be less than 『%s』.";
$lang->error->le              = "『%s』 must be less than or equal to 『%s』.";
$lang->error->in              = "『%s』 must be one of 『%s』.";
$lang->error->notempty        = "『%s』 cannot be empty.";
$lang->error->empty           = "『%s』 must be empty.";
$lang->error->equal           = "『%s』 must be 『%s』.";
$lang->error->int             = array("『%s』 must be a number.", "『%s』 must be between 『%s』 and 『%s』.");
$lang->error->float           = "『%s』 must be a number or decimal.";
$lang->error->email           = "『%s』 must be a valid email.";
$lang->error->phone           = "『%s』 must be a valid phone number.";
$lang->error->mobile          = "『%s』 must be a valid mobile number.";
$lang->error->URL             = "『%s』 must be a valid URL.";
$lang->error->date            = "『%s』 must be a valid date.";
$lang->error->datetime        = "『%s』 must be a valid date and time.";
$lang->error->code            = "『%s』 must be alphanumeric.";
$lang->error->account         = "『%s』 must be at least 3 characters and contain only letters, numbers, or underscores.";
$lang->error->passwordsame    = "Passwords must match.";
$lang->error->passwordrule    = "Password must be at least 6 characters long and meet the requirements.";
$lang->error->accessDenied    = 'Access denied.';
$lang->error->unsupportedReq  = 'Unsupported request type.';
$lang->error->pasteImg        = 'Your browser does not support pasting images.';
$lang->error->noData          = 'No data available.';
$lang->error->editedByOther   = 'This record may have been modified by someone else. Please refresh the page and try again.';
$lang->error->tutorialData    = 'Data cannot be inserted in tutorial mode. Please exit tutorial mode first.';
$lang->error->noCurlExt       = 'The server does not have the Curl module installed.';
$lang->error->loginTimeout    = 'Session timed out. Please log in again.';
$lang->error->httpServerError = 'Server error.';
$lang->error->action          = 'The conditions for executing this operation have not been met, and therefore the operation cannot be executed.';

/* Page info. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No records found.";
$lang->pager->digest       = "Total: <strong>%s</strong> item(s), %s <strong>%s/%s</strong> &nbsp;";
$lang->pager->recPerPage   = "<strong>%s</strong> per page";
$lang->pager->first        = "<i class='icon-step-backward' title='First Page'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='Previous Page'></i>";
$lang->pager->next         = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate       = "Go";
$lang->pager->previousPage = "Previous";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong> items";
$lang->pager->pageOfText   = "Page {0}";
$lang->pager->firstPage    = "First";
$lang->pager->lastPage     = "Last";
$lang->pager->goto         = "Go to";
$lang->pager->pageOf       = "Page <strong>{page}</strong>";
$lang->pager->totalPage    = "<strong>{totalPage}</strong> pages";
$lang->pager->totalCount   = "Total: <strong>{recTotal}</strong> item(s)";
$lang->pager->pageSize     = "<strong>{recPerPage}</strong> per page";
$lang->pager->itemsRange   = "Items <strong>{start}</strong> - <strong>{end}</strong>";
$lang->pager->pageOfTotal  = "Page <strong>{page}</strong> of <strong>{totalPage}</strong>";
$lang->pager->totalCountAB = "{recTotal} item(s)";
$lang->pager->pageSizeAB   = "{recPerPage} per page";

$lang->pager->shortPageSize = '<strong>{recPerPage}</strong> / Page';

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = 'Invalid color value.';

$lang->downNotify     = "Download Desktop Notifier";
$lang->clientName     = "ZenTao IM";
$lang->downloadClient = "Download ZenTao IM";
$lang->downloadMobile = "Download mobile app";
$lang->clientHelp     = "ZenTao IM User Guide";
$lang->clientHelpLink = "https://www.zentao.pm/book/zentaomanual/scrum-tool-im-integration-206.html";
$lang->website        = "https://www.zentao.pm";

$lang->suhosinInfo     = "Warning: Data limit exceeded. Please modify <font color=red>suhosin.post.max_vars</font> and <font color=red>suhosin.request.max_vars</font> (to a value > %s) in php.ini. Save and restart Apache or php-fpm, or some data may not be saved.";
$lang->maxVarsInfo     = "Warning: Data limit exceeded. Please modify <font color=red>max_input_vars</font> (to a value > %s) in php.ini. Save and restart Apache or php-fpm, or some data may not be saved.";
$lang->pasteTextInfo   = "Paste text here. Each line will become a separate title.";
$lang->noticeImport    = "The imported file contains existing data. Please choose whether to overwrite it or insert it as new records.";
$lang->importConfirm   = "Confirm Import";
$lang->importAndCover  = "Overwrite";
$lang->importAndInsert = "Insert as new";

$lang->noResultsMatch     = "No results found.";
$lang->searchMore         = "More results for this keyword:";
$lang->chooseUsersToMail  = "Select users to notify. ";
$lang->noticePasteImg     = "You can paste images directly into the editor.";
$lang->pasteImgFail       = "Failed to paste image. Please try again later.";
$lang->pasteImgUploading  = "Uploading image, please wait...";

/* Work visions. */
$lang->visionTips      = "You can switch vision here";
$lang->IKnow           = "Got it";
$lang->switchVision    = 'Switch to vision';
$lang->workspaceAbbr   = 'Space';
$lang->switchWorkspace = 'Switch to workspace';
$lang->enterWorkspace  = 'Enter space';
$lang->exitWorkspace   = 'Exit space';

/* Workspace list. */
$lang->workspaceList = [];
$lang->workspaceList['product']   = "{$lang->product->common} space";
$lang->workspaceList['project']   = "{$lang->project->common} space";
$lang->workspaceList['execution'] = "{$lang->execution->common} space";

/* Time formats settings. */
if(!defined('DT_DATETIME1'))  define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))  define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1')) define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2')) define('DT_MONTHTIME2', 'n/d H:i');
if(!defined('DT_DATE1'))      define('DT_DATE1',      'Y-m-d');
if(!defined('DT_DATE2'))      define('DT_DATE2',      'Ymd');
if(!defined('DT_DATE3'))      define('DT_DATE3',      'Y/m/d');
if(!defined('DT_DATE4'))      define('DT_DATE4',      'M d');
if(!defined('DT_DATE5'))      define('DT_DATE5',      'j/n');
if(!defined('DT_TIME1'))      define('DT_TIME1',      'H:i:s');
if(!defined('DT_TIME2'))      define('DT_TIME2',      'H:i');

/* Datepicker. */
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'or ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Last Year';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Last Month';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Last Week';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Yesterday';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'This Month';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'This Week';
$lang->datepicker->dpText->TEXT_TODAY       = 'Today';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Next Year';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Next Month';
$lang->datepicker->dpText->TEXT_NEXT_WEEK   = 'Next Week';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Close';
$lang->datepicker->dpText->TEXT_DATE        = 'Date';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Select date';

$lang->datepicker->dayNames     = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$lang->datepicker->abbrDayNames = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
$lang->datepicker->monthNames   = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

/* AI */
$lang->aiapp->conversation = 'Conversation';
$lang->aiapp->zentaoAgent  = 'ZenTao Agent';
$lang->aiapp->generalAgent = 'General Agent';
$lang->aiapp->models       = 'Models';
$lang->aiapp->config       = 'ZAI Settings';
$lang->aiapp->toolkit      = 'Toolkit';

if(!helper::hasFeature('program')) unset($lang->searchObjects['program'], $lang->createObjects['program']);
if(!helper::hasFeature('caselib')) unset($lang->searchObjects['caselib']);
if(!helper::hasFeature('kanban') ) unset($lang->createObjects['kanban'], $lang->createObjects['kanbanspace']);

include (dirname(__FILE__) . '/menu.php');
