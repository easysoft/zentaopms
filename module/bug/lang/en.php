<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: en.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        https://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->plural           = 'Bugs';
$lang->bug->id               = 'ID';
$lang->bug->product          = $lang->productCommon;
$lang->bug->branch           = 'Branch/Platform';
$lang->bug->module           = 'Module';
$lang->bug->project          = $lang->projectCommon;
$lang->bug->execution        = $lang->execution->common;
$lang->bug->kanban           = 'Kanban';
$lang->bug->storyVersion     = "{$lang->SRCommon} Version";
$lang->bug->color            = 'Color';
$lang->bug->title            = 'Name';
$lang->bug->severity         = 'Severity';
$lang->bug->pri              = 'Priority';
$lang->bug->type             = 'Type';
$lang->bug->os               = 'Operating System';
$lang->bug->browser          = 'Web Browser';
$lang->bug->hardware         = 'Hardware';
$lang->bug->result           = 'Result';
$lang->bug->repo             = 'Linked Repository';
$lang->bug->mr               = 'Merge Request';
$lang->bug->entry            = 'Code Path';
$lang->bug->lines            = 'Code Line';
$lang->bug->v1               = 'Version 1';
$lang->bug->v2               = 'Version 2';
$lang->bug->issueKey         = 'Sonarqube Issue Key';
$lang->bug->repoType         = 'Repository Type';
$lang->bug->steps            = 'Repro Steps';
$lang->bug->status           = 'Status';
$lang->bug->subStatus        = 'Sub-status';
$lang->bug->activatedCount   = 'Activation Count';
$lang->bug->activatedDate    = 'Activated on';
$lang->bug->confirmed        = 'Confirmed';
$lang->bug->toTask           = 'To Task';
$lang->bug->toStory          = "To {$lang->SRCommon}";
$lang->bug->feedbackBy       = 'Discovered by';
$lang->bug->notifyEmail      = 'Discoverer Email';
$lang->bug->mailto           = 'Mail to';
$lang->bug->openedBy         = 'Reporter';
$lang->bug->openedDate       = 'Reported on';
$lang->bug->openedBuild      = 'Affected Version';
$lang->bug->assignedTo       = 'Assigned to';
$lang->bug->assignedToMe     = 'Assigned to Me';
$lang->bug->assignedDate     = 'Assigned on';
$lang->bug->resolvedBy       = 'Resolved by';
$lang->bug->resolution       = 'Resolution';
$lang->bug->resolvedBuild    = 'Fixed Build';
$lang->bug->resolvedDate     = 'Resolved on';
$lang->bug->deadline         = 'Due Date';
$lang->bug->plan             = 'Linked Plan';
$lang->bug->closedBy         = 'Closed by';
$lang->bug->closedDate       = 'Closed on';
$lang->bug->duplicateBug     = 'Duplicated Bug';
$lang->bug->lastEditedBy     = 'Last Edited by';
$lang->bug->caseVersion      = 'Test Case Version';
$lang->bug->testtask         = 'Test Request';
$lang->bug->files            = 'File';
$lang->bug->keywords         = 'Keywords';
$lang->bug->lastEditedDate   = 'Edited on';
$lang->bug->fromCase         = 'Source Test Case';
$lang->bug->toCase           = 'Generate Test Case';
$lang->bug->colorTag         = 'Color Tag';
$lang->bug->fixedRate        = 'Fix Rate';
$lang->bug->noticefeedbackBy = 'Notify Reporter';
$lang->bug->selectProjects   = "Select {$lang->projectCommon}s";
$lang->bug->nextStep         = 'Next';
$lang->bug->noProject        = "No {$lang->projectCommon}s selected.";
$lang->bug->noExecution      = "No {$lang->execution->common}s selected.";
$lang->bug->story            = 'Story';
$lang->bug->task             = 'Task';
$lang->bug->relatedBug       = 'Related Bugs';
$lang->bug->case             = 'Test Case';
$lang->bug->linkMR           = 'Related MRs';
$lang->bug->linkPR           = 'Related PRs';
$lang->bug->linkCommit       = 'Related PRs';
$lang->bug->productplan      = $lang->bug->plan;
$lang->bug->codeBranch       = 'Code branch';
$lang->bug->unlinkBranch     = 'Unlink code branch';
$lang->bug->branchName       = 'Branch Name';
$lang->bug->branchFrom       = 'Create from';
$lang->bug->codeRepo         = 'Code Repository';

$lang->bug->abbr = new stdclass();
$lang->bug->abbr->module         = 'Module';
$lang->bug->abbr->severity       = 'Severity';
$lang->bug->abbr->status         = 'Status';
$lang->bug->abbr->activatedCount = 'Activation Count';
$lang->bug->abbr->confirmed      = 'Confirm';
$lang->bug->abbr->openedBy       = 'Reporter';
$lang->bug->abbr->openedDate     = 'Reported on';
$lang->bug->abbr->assignedTo     = 'Assigned to';
$lang->bug->abbr->resolvedBy     = 'Resolve';
$lang->bug->abbr->resolution     = 'Resolution';
$lang->bug->abbr->resolvedDate   = 'Resolved on';
$lang->bug->abbr->deadline       = 'Due Date';
$lang->bug->abbr->lastEditedBy   = 'Edited by';
$lang->bug->abbr->lastEditedDate = 'Edited on';
$lang->bug->abbr->assignToMe     = 'Assigned to Me';
$lang->bug->abbr->openedByMe     = 'Reported by Me';
$lang->bug->abbr->resolvedByMe   = 'Resolved by Me';

/* Method list. */
$lang->bug->index              = 'Bug Home';
$lang->bug->browse             = 'Bug List';
$lang->bug->create             = 'Report Bug';
$lang->bug->batchCreate        = 'Batch Report';
$lang->bug->createCase         = 'Create Test Case';
$lang->bug->copy               = 'Copy Bug';
$lang->bug->edit               = 'Edit Bug';
$lang->bug->batchEdit          = 'Batch Edit';
$lang->bug->view               = 'Bug Details';
$lang->bug->delete             = 'Delete';
$lang->bug->deleteAction       = 'Delete Bug';
$lang->bug->confirm            = 'Confirm';
$lang->bug->confirmAction      = 'Confirm Bug';
$lang->bug->batchConfirm       = 'Batch Confirm';
$lang->bug->assignTo           = 'Assign';
$lang->bug->assignAction       = 'Assigned to';
$lang->bug->batchAssignTo      = 'Batch Assign';
$lang->bug->resolve            = 'Resolve';
$lang->bug->resolveAction      = 'Resolve Bug';
$lang->bug->batchResolve       = 'Batch Resolve';
$lang->bug->createAB           = 'Add';
$lang->bug->close              = 'Close';
$lang->bug->closeAction        = 'Close Bug';
$lang->bug->batchClose         = 'Batch Close';
$lang->bug->activate           = 'Activate';
$lang->bug->activateAction     = 'Activate Bug';
$lang->bug->batchActivate      = 'Batch Activate';
$lang->bug->reportChart        = 'Report';
$lang->bug->reportAction       = 'Bug Report';
$lang->bug->export             = 'Export Data';
$lang->bug->exportAction       = 'Export Bugs';
$lang->bug->confirmStoryChange = "Confirm {$lang->SRCommon} Change";
$lang->bug->search             = 'Search';
$lang->bug->batchChangeModule  = 'Batch Edit Modules';
$lang->bug->batchChangeBranch  = 'Batch Edit Branches';
$lang->bug->batchChangePlan    = 'Batch Edit Plans';
$lang->bug->linkBugs           = 'Link Bug';
$lang->bug->unlinkBug          = 'Unlink Bug';

/* Query condition list. */
$lang->bug->assignToMe         = 'Assigned to Me';
$lang->bug->openedByMe         = 'Reported by Me';
$lang->bug->resolvedByMe       = 'Resolved by Me';
$lang->bug->closedByMe         = 'Closed by Me';
$lang->bug->assignedByMe       = 'Assigned by Me';
$lang->bug->assignToNull       = 'Unassigned';
$lang->bug->unResolved         = 'Unresolved';
$lang->bug->toClosed           = 'To Be Closed';
$lang->bug->unclosed           = 'Open';
$lang->bug->unconfirmed        = 'Unconfirmed';
$lang->bug->longLifeBugs       = 'Stale';
$lang->bug->postponedBugs      = 'Delayed';
$lang->bug->overdueBugs        = 'Overdue Bug';
$lang->bug->allBugs            = 'All';
$lang->bug->byQuery            = 'Search';
$lang->bug->needConfirm        = 'Story Changed';
$lang->bug->allProject         = 'All Projects';
$lang->bug->allProduct         = 'All ' . $lang->productCommon . 's';
$lang->bug->my                 = 'My';
$lang->bug->yesterdayResolved  = 'Bugs Resolved Yesterday ';
$lang->bug->yesterdayConfirmed = 'Bugs Confirmed Yesterday ';
$lang->bug->yesterdayClosed    = 'Bugs Closed Yesterday ';

$lang->bug->deleted        = 'Deleted';
$lang->bug->labelConfirmed = 'Confirmed';
$lang->bug->labelPostponed = 'Delayed';
$lang->bug->changed        = 'Changed';
$lang->bug->storyChanged   = 'Story Changed';
$lang->bug->ditto          = 'Ditto';

/* Page tags. */
$lang->bug->lblAssignedTo = 'AssignTo';
$lang->bug->lblMailto     = 'Mail to';
$lang->bug->lblLastEdited = 'Last Edited by';
$lang->bug->lblResolved   = 'Resolved by';
$lang->bug->loadAll       = 'Load All';
$lang->bug->createBuild   = 'New';

global $config;
/* Legend list. */
$lang->bug->legendBasicInfo             = 'Basic Info';
$lang->bug->legendAttach                = 'File';
$lang->bug->legendPRJExecStoryTask      = "{$lang->SRCommon}/{$lang->executionCommon}/Story/Task";
$lang->bug->legendExecStoryTask         = "{$lang->SRCommon}/Story/Task";
$lang->bug->lblTypeAndSeverity          = 'Type/Severity';
$lang->bug->lblSystemBrowserAndHardware = 'System/Browser';
$lang->bug->legendSteps                 = 'Repro Steps';
$lang->bug->legendComment               = 'Comment';
$lang->bug->legendLife                  = 'Bug Life';
$lang->bug->legendMisc                  = 'Misc';
$lang->bug->legendRelated               = 'Related Info';
$lang->bug->legendThisWeekCreated       = 'New This Week';

/* Template. */
$lang->bug->tplStep   = "<p>[Steps]</p><p></p>";
$lang->bug->tplResult = "<p>[Results]</p><p></p>";
$lang->bug->tplExpect = "<p>[Expectations]</p><p></p>";

/* Value list for each field. */
$lang->bug->severityList[0] = '';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']         = '';
$lang->bug->osList['all']      = 'All';
$lang->bug->osList['windows']  = 'Windows';
$lang->bug->osList['win11']    = 'Windows 11';
$lang->bug->osList['win10']    = 'Windows 10';
$lang->bug->osList['win8']     = 'Windows 8';
$lang->bug->osList['win7']     = 'Windows 7';
$lang->bug->osList['winxp']    = 'Windows XP';
$lang->bug->osList['osx']      = 'Mac OS';
$lang->bug->osList['android']  = 'Android';
$lang->bug->osList['ios']      = 'IOS';
$lang->bug->osList['linux']    = 'Linux';
$lang->bug->osList['ubuntu']   = 'Ubuntu';
$lang->bug->osList['chromeos'] = 'Chrome OS';
$lang->bug->osList['fedora']   = 'Fedora';
$lang->bug->osList['unix']     = 'Unix';
$lang->bug->osList['others']   = 'Others';

$lang->bug->browserList['']        = '';
$lang->bug->browserList['all']     = 'All';
$lang->bug->browserList['chrome']  = 'Chrome';
$lang->bug->browserList['edge']    = 'Edge';
$lang->bug->browserList['ie']      = 'IE series';
$lang->bug->browserList['ie11']    = 'IE11';
$lang->bug->browserList['ie10']    = 'IE10';
$lang->bug->browserList['ie9']     = 'IE9';
$lang->bug->browserList['ie8']     = 'IE8';
$lang->bug->browserList['firefox'] = 'Firefox series';
$lang->bug->browserList['opera']   = 'Opera series';
$lang->bug->browserList['safari']  = 'Safari';
$lang->bug->browserList['360']     = '360 series';
$lang->bug->browserList['qq']      = 'QQ series';
$lang->bug->browserList['other']   = 'Others';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'Code Error';
$lang->bug->typeList['config']       = 'Configuration';
$lang->bug->typeList['install']      = 'Installation';
$lang->bug->typeList['security']     = 'Security';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'Standard Specification';
$lang->bug->typeList['automation']   = 'Test Script';
$lang->bug->typeList['designdefect'] = 'Design Defect';
$lang->bug->typeList['others']       = 'Others';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Activated';
$lang->bug->statusList['resolved'] = 'Resolved';
$lang->bug->statusList['closed']   = 'Closed';

$lang->bug->confirmedList[''] = '';
$lang->bug->confirmedList[1] = 'Yes';
$lang->bug->confirmedList[0] = 'No';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'As Designed';
$lang->bug->resolutionList['duplicate']  = 'Duplicate';
$lang->bug->resolutionList['external']   = '	External Caus';
$lang->bug->resolutionList['fixed']      = 'Resolved';
$lang->bug->resolutionList['notrepro']   = 'Cannot Reproduce';
$lang->bug->resolutionList['postponed']  = 'Postponed';
$lang->bug->resolutionList['willnotfix'] = 'Won’t Fix';
$lang->bug->resolutionList['tostory']    = 'Convert to Story';

/* Statistical statement. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Report';
$lang->bug->report->select = 'Select Report Type';
$lang->bug->report->create = 'Create Report';

$lang->bug->report->charts['bugsPerExecution']      = $lang->executionCommon . ' Bug Count';
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs by Build';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs by Module';
$lang->bug->report->charts['openedBugsPerDay']      = 'New Bugs per Day';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Resolved Bugs Per Day';
$lang->bug->report->charts['closedBugsPerDay']      = 'Closed Bugs Per Day';
$lang->bug->report->charts['openedBugsPerUser']     = 'Bugs Reported per User';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Bugs Resolved per User';
$lang->bug->report->charts['closedBugsPerUser']     = 'Bugs Closed per User';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bugs by Severity';
$lang->bug->report->charts['bugsPerResolution']     = 'Bugs by Resolution';
$lang->bug->report->charts['bugsPerStatus']         = 'Bugs by Status';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bugs by Ativation Count';
$lang->bug->report->charts['bugsPerPri']            = 'Bugs by Priority';
$lang->bug->report->charts['bugsPerType']           = 'Bugs by Type';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bugs by Assignee';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug Handling Time Report';
//$lang->bug->report->charts['bugHistories']       = 'Bug Handling Steps Report';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerExecution      = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerExecution->graph      = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerExecution->graph->xAxisName = $lang->executionCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName     = 'Build';
$lang->bug->report->bugsPerModule->graph->xAxisName    = 'Module';

$lang->bug->report->openedBugsPerDay->type             = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = 'User';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'User';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = 'User';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Severity';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Resolution';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Status';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Activation Count';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Priority';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Type';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'Assignee';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Resolution Time';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Resolution Steps';

/* Operating record. */
$lang->bug->action = new stdclass();
$lang->bug->action->resolved             = array('main' => '$date, resolved by <strong>$actor</strong> and the resolution is <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
$lang->bug->action->tostory              = array('main' => '$date, converted to <strong>Story</strong> with ID <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->totask               = array('main' => '$date, imported as <strong>Task</strong> with ID <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->converttotask        = array('main' => '$date, converted to <strong>Task</strong> with ID <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->linked2plan          = array('main' => '$date, linked to Plan <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->unlinkedfromplan     = array('main' => '$date, unlinked from Plan <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->linked2build         = array('main' => '$date, linked to Build <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->unlinkedfrombuild    = array('main' => '$date, unlinked from Build <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->unlinkedfromrelease  = array('main' => '$date, unlinked from Release <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->linked2release       = array('main' => '$date, linked to Release <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->linked2revision      = array('main' => '$date, linked to Commit <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->unlinkedfromrevision = array('main' => '$date, unlinked from Commit <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->linkrelatedbug       = array('main' => '$date, linked to Bug <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->bug->action->unlinkrelatedbug     = array('main' => '$date, unlinked from Bug <strong>$extra</strong> by <strong>$actor</strong>.');

$lang->bug->featureBar['browse']['all']          = 'All';
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['assignedbyme'] = $lang->bug->assignedByMe;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['browse']['more']['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['browse']['more']['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['browse']['more']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['browse']['more']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['browse']['more']['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['browse']['more']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['browse']['more']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['browse']['more']['needconfirm']   = $lang->bug->needConfirm;

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = 'Select Build';
$lang->bug->placeholder->newBuildName = 'New Build Name';
$lang->bug->placeholder->duplicate    = 'Please enter the keywords.';

/* Interactive prompt. */
$lang->bug->notice = new stdclass();
$lang->bug->notice->summary               = "<strong>%s</strong> Bug(s) on this page, <strong>%s</strong> Unresolved.";
$lang->bug->notice->unClosedSummary       = "Total %s Bug(s) on this page, %s Open.";
$lang->bug->notice->checkedSummary        = "{checked} item(s) selected. Total {total} items.";
$lang->bug->notice->confirmChangeProduct  = "Changing the {$lang->productCommon} will result in changes to the linked {$lang->executionCommon}, {$lang->SRCommon}, and tasks. Are you sure to continue?";
$lang->bug->notice->confirmDelete         = 'Are you sure you want to delete this Bug?';
$lang->bug->notice->remindTask            = 'This Bug has been converted to a task. Do you want to update the status of the task (ID: %s)?';
$lang->bug->notice->skipClose             = 'Bug %s is not in "Resolved" status and cannot be closed. It will be automatically skipped.';
$lang->bug->notice->executionAccessDenied = "You do not have permission to access the {$lang->executionCommon} to which this Bug belongs.";
$lang->bug->notice->confirmUnlinkBuild    = "Changing the Fixed Build will remove the link to the previous build. Are you sure you want to unlink this Bug from %s?";
$lang->bug->notice->noSwitchBranch        = 'The module for Bug %s is not in the current branch. It will be automatically skipped.';
$lang->bug->notice->confirmToStory        = 'The Bug will be automatically closed after being converted to a story. Closure reason: Converted to Story.';
$lang->bug->notice->productDitto          = "This Bug and the previous Bug do not belong to the same {$lang->productCommon}!";
$lang->bug->notice->noBug                 = 'No bugs yet.';
$lang->bug->notice->noModule              = '<div>No module information available.</div><div>Please set up test modules.</div>';
$lang->bug->notice->delayWarning          = "<strong class='text-danger'>Pospone by %s day(s)</strong>";
$lang->bug->notice->skipNotActive         = "Bug %s is already Resolved or Closed and will not be modified.";

$lang->bug->error = new stdclass();
$lang->bug->error->notExist             = "Bug not found.";
$lang->bug->error->cannotActivate       = 'Bugs that are not in "Resolved" or "Closed" status cannot be activated.';
$lang->bug->error->stepsNotEmpty        = "Reproduction steps cannot be empty.";
$lang->bug->error->duplicateBugNotExist = 'Duplicate Bug does not exist.';
