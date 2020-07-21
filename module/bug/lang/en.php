<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: en.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        http://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'ID';
$lang->bug->product          = $lang->productCommon;
$lang->bug->branch           = 'Branch/Platform';
$lang->bug->productplan      = 'Plan';
$lang->bug->module           = 'Module';
$lang->bug->moduleAB         = 'Module';
$lang->bug->project          = $lang->projectCommon;
$lang->bug->story            = 'Story';
$lang->bug->storyVersion     = 'Story Version';
$lang->bug->color            = 'Color';
$lang->bug->task             = 'Task';
$lang->bug->title            = 'Title';
$lang->bug->severity         = 'Severity';
$lang->bug->severityAB       = 'S';
$lang->bug->pri              = 'Priority';
$lang->bug->type             = 'Type';
$lang->bug->os               = 'OS';
$lang->bug->browser          = 'Browser';
$lang->bug->steps            = 'Repro Steps';
$lang->bug->status           = 'Status';
$lang->bug->statusAB         = 'Status';
$lang->bug->subStatus        = 'Sub Status';
$lang->bug->activatedCount   = 'Activated Times';
$lang->bug->activatedCountAB = 'Active';
$lang->bug->activatedDate    = 'ActivatedDate';
$lang->bug->confirmed        = 'Confirmed';
$lang->bug->confirmedAB      = 'C';
$lang->bug->toTask           = 'Convert to Task';
$lang->bug->toStory          = 'Convert to Story';
$lang->bug->mailto           = 'Mailto';
$lang->bug->openedBy         = 'ReportedBy';
$lang->bug->openedByAB       = 'Reporter';
$lang->bug->openedDate       = 'ReportedDate';
$lang->bug->openedDateAB     = 'Reported';
$lang->bug->openedBuild      = 'Open Build';
$lang->bug->assignedTo       = 'AssignTo';
$lang->bug->assignBug        = 'AssignedTo';
$lang->bug->assignedToAB     = 'AssignedTo';
$lang->bug->assignedDate     = 'AssignedDate';
$lang->bug->resolvedBy       = 'ResolvedBy';
$lang->bug->resolvedByAB     = 'ResolvedBy';
$lang->bug->resolution       = 'Resolution';
$lang->bug->resolutionAB     = 'Resolution';
$lang->bug->resolvedBuild    = 'Build';
$lang->bug->resolvedDate     = 'Resolved Date';
$lang->bug->resolvedDateAB   = 'ResolvedDate';
$lang->bug->deadline         = 'Deadline';
$lang->bug->plan             = 'Plan';
$lang->bug->closedBy         = 'ClosedBy';
$lang->bug->closedDate       = 'ClosedDate';
$lang->bug->duplicateBug     = 'Duplicated Bug ID';
$lang->bug->lastEditedBy     = 'EditedBy';
$lang->bug->linkBug          = 'Linked Bugs';
$lang->bug->linkBugs         = 'Link Bug';
$lang->bug->unlinkBug        = 'Unlink';
$lang->bug->case             = 'Cases';
$lang->bug->caseVersion      = 'Case Version';
$lang->bug->testtask         = 'Request';
$lang->bug->files            = 'Files';
$lang->bug->keywords         = 'Tags';
$lang->bug->lastEditedByAB   = 'EditedBy';
$lang->bug->lastEditedDateAB = 'EditedDate';
$lang->bug->lastEditedDate   = 'EditedDate';
$lang->bug->fromCase         = 'From Case';
$lang->bug->toCase           = 'To Case';
$lang->bug->colorTag         = 'Color';

/* Method list. */
$lang->bug->index              = 'Bug Home';
$lang->bug->create             = 'Report Bug';
$lang->bug->batchCreate        = 'Batch Report';
$lang->bug->confirmBug         = 'Confirm';
$lang->bug->confirmAction      = 'Confirm Bug';
$lang->bug->batchConfirm       = 'Batch Confirm';
$lang->bug->edit               = 'Edit Bug';
$lang->bug->batchEdit          = 'Batch Edit';
$lang->bug->batchChangeModule  = 'Batch Edit Modules';
$lang->bug->batchChangeBranch  = 'Batch Edit Branches';
$lang->bug->batchClose         = 'Batch Close';
$lang->bug->assignTo           = 'Assign';
$lang->bug->assignAction       = 'Assign Bug';
$lang->bug->batchAssignTo      = 'Batch Assign';
$lang->bug->browse             = 'Bug List';
$lang->bug->view               = 'Bug Detail';
$lang->bug->resolve            = 'Resolve';
$lang->bug->resolveAction      = 'Resolve Bug';
$lang->bug->batchResolve       = 'Batch Resolve';
$lang->bug->close              = 'Close';
$lang->bug->closeAction        = 'Close Bug';
$lang->bug->activate           = 'Activate';
$lang->bug->activateAction     = 'Activate Bug';
$lang->bug->batchActivate      = 'Batch Activate';
$lang->bug->reportChart        = 'Report';
$lang->bug->reportAction       = 'Bug Report';
$lang->bug->export             = 'Export Data';
$lang->bug->exportAction       = 'Export Bug';
$lang->bug->delete             = 'Delete';
$lang->bug->deleteAction       = 'Delete Bug';
$lang->bug->deleted            = 'Deleted';
$lang->bug->confirmStoryChange = 'Confirm Story Change';
$lang->bug->copy               = 'Copy';
$lang->bug->search             = 'Search';

/* Query condition list. */
$lang->bug->assignToMe         = 'AssignedToMe';
$lang->bug->openedByMe         = 'ReportedByMe';
$lang->bug->resolvedByMe       = 'ResolvedByMe';
$lang->bug->closedByMe         = 'ClosedByMe';
$lang->bug->assignToNull       = 'Unassigned';
$lang->bug->unResolved         = 'Active';
$lang->bug->toClosed           = 'ToBeClosed';
$lang->bug->unclosed           = 'Unclosed';
$lang->bug->unconfirmed        = 'Unconfirmed';
$lang->bug->longLifeBugs       = 'Stalled';
$lang->bug->postponedBugs      = 'Postponed';
$lang->bug->overdueBugs        = 'Overdue';
$lang->bug->allBugs            = 'All Bugs';
$lang->bug->byQuery            = 'Search';
$lang->bug->needConfirm        = 'Story Changed';
$lang->bug->allProduct         = 'All' . $lang->productCommon . 's';
$lang->bug->my                 = 'My';
$lang->bug->yesterdayResolved  = 'Bug Resolved Yesterday ';
$lang->bug->yesterdayConfirmed = 'Bug Confirmed Yesterday ';
$lang->bug->yesterdayClosed    = 'Bug Closed Yesterday ';

$lang->bug->assignToMeAB   = 'AssignedToMe';
$lang->bug->openedByMeAB   = 'ReportedByMe';
$lang->bug->resolvedByMeAB = 'ResolvedByMe';

$lang->bug->ditto        = 'Ditto';
$lang->bug->dittoNotice  = 'This bug is not linked to the same product as the last one is!';
$lang->bug->noAssigned   = 'Unassigned';
$lang->bug->noBug        = 'No bugs yet.';
$lang->bug->noModule     = '<div>You have no modules.</div><div>Manage now</div>';
$lang->bug->delayWarning = " <strong class='text-danger'> Delay %s days </strong>";

/* Page tags. */
$lang->bug->lblAssignedTo = 'AssignTo';
$lang->bug->lblMailto     = 'Mailto';
$lang->bug->lblLastEdited = 'EditedBy';
$lang->bug->lblResolved   = 'ResolvedBy';
$lang->bug->allUsers      = 'Load All Users';
$lang->bug->allBuilds     = 'All Builds';
$lang->bug->createBuild   = 'New';

/* Legend list。*/
$lang->bug->legendBasicInfo             = 'Basic Info';
$lang->bug->legendAttatch               = 'Files';
$lang->bug->legendPrjStoryTask          = $lang->projectCommon . '/Story/Task';
$lang->bug->lblTypeAndSeverity          = 'Type/Severity';
$lang->bug->lblSystemBrowserAndHardware = 'System/Browser';
$lang->bug->legendSteps                 = 'Repro Steps';
$lang->bug->legendComment               = 'Note';
$lang->bug->legendLife                  = 'Bug Life';
$lang->bug->legendMisc                  = 'Misc.';
$lang->bug->legendRelated               = 'Related Info';

/* Button. */
$lang->bug->buttonConfirm = 'Confirm';

/* Interactive prompt. */
$lang->bug->summary               = "Total <strong>%s</strong> bugs on this page, and <strong>%s</strong> Active.";
$lang->bug->confirmChangeProduct  = "Any change to {$lang->productCommon} will cause linked {$lang->projectCommon}s, stories and tasks change. Do you want to do this?";
$lang->bug->confirmDelete         = 'Do you want to delete this bug?';
$lang->bug->remindTask            = 'This bug has been converted to a task. Do you want to update the status of Task(ID %s)?';
$lang->bug->skipClose             = 'Bug %s is active. You cannot close it.';
$lang->bug->projectAccessDenied   = "You access to the {$lang->projectCommon} to which this bug belongs is denied!";

/* Template. */
$lang->bug->tplStep   = "<p>[Steps]</p><br/>";
$lang->bug->tplResult = "<p>[Results]</p><br/>";
$lang->bug->tplExpect = "<p>[Expectations]</p><br/>";

/* Value list for each field. */
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']        = '';
$lang->bug->osList['all']     = 'All';
$lang->bug->osList['windows'] = 'Windows';
$lang->bug->osList['win10']   = 'Windows 10';
$lang->bug->osList['win8']    = 'Windows 8';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win2012'] = 'Windows 2012';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['android'] = 'Android';
$lang->bug->osList['ios']     = 'IOS';
$lang->bug->osList['wp8']     = 'WP8';
$lang->bug->osList['wp7']     = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['osx']     = 'OS X';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = 'Others';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = 'All';
$lang->bug->browserList['ie']       = 'IE series';
$lang->bug->browserList['ie11']     = 'IE11';
$lang->bug->browserList['ie10']     = 'IE10';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['chrome']   = 'Chrome';
$lang->bug->browserList['firefox']  = 'Firefox series';
$lang->bug->browserList['firefox4'] = 'Firefox4';
$lang->bug->browserList['firefox3'] = 'Firefox3';
$lang->bug->browserList['firefox2'] = 'Firefox2';
$lang->bug->browserList['opera']    = 'Opera series';
$lang->bug->browserList['oprea11']  = 'Opera11';
$lang->bug->browserList['oprea10']  = 'Opera10';
$lang->bug->browserList['opera9']   = 'Opera9';
$lang->bug->browserList['safari']   = 'Safari';
$lang->bug->browserList['maxthon']  = 'Maxthon';
$lang->bug->browserList['uc']       = 'UC';
$lang->bug->browserList['other']    = 'Others';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'CodeError';
$lang->bug->typeList['config']       = 'Configuration';
$lang->bug->typeList['install']      = 'Installation';
$lang->bug->typeList['security']     = 'Security';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'CodingConventions';
$lang->bug->typeList['automation']   = 'TestScript';
$lang->bug->typeList['designdefect'] = 'DesignDefect';
$lang->bug->typeList['others']       = 'Others';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Active';
$lang->bug->statusList['resolved'] = 'Resolved';
$lang->bug->statusList['closed']   = 'Closed';

$lang->bug->confirmedList[1] = 'Yes';
$lang->bug->confirmedList[0] = 'No';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'As Designed';
$lang->bug->resolutionList['duplicate']  = 'Duplicate';
$lang->bug->resolutionList['external']   = 'External';
$lang->bug->resolutionList['fixed']      = 'Resolved';
$lang->bug->resolutionList['notrepro']   = 'Irreproducible';
$lang->bug->resolutionList['postponed']  = 'Postponed';
$lang->bug->resolutionList['willnotfix'] = "Won't Fix";
$lang->bug->resolutionList['tostory']    = 'Convert to Story';

/* Statistical statement. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Report';
$lang->bug->report->select = 'Select Report Type';
$lang->bug->report->create = 'Create Report';

$lang->bug->report->charts['bugsPerProject']        = $lang->projectCommon . ' Bugs';
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs Per Build';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs Per Module';
$lang->bug->report->charts['openedBugsPerDay']      = 'Reported Bugs Per Day';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Resolved Bugs Per Day';
$lang->bug->report->charts['closedBugsPerDay']      = 'Closed Bugs Per Day';
$lang->bug->report->charts['openedBugsPerUser']     = 'Reported Bugs Per User';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Resolved Bugs Per User';
$lang->bug->report->charts['closedBugsPerUser']     = 'Closed Bugs Per User';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bug Severity';
$lang->bug->report->charts['bugsPerResolution']     = 'Bug Resolution';
$lang->bug->report->charts['bugsPerStatus']         = 'Bug Status';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bug Activation Times';
$lang->bug->report->charts['bugsPerPri']            = 'Bug Priority';
$lang->bug->report->charts['bugsPerType']           = 'Bug Type';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bug Assignment';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug Handling Time Report';
//$lang->bug->report->charts['bugHistories']       = 'Bug Handling Steps Report';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerProject        = new stdclass();
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

$lang->bug->report->bugsPerProject->graph        = new stdclass();
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

$lang->bug->report->bugsPerProject->graph->xAxisName     = $lang->projectCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName       = 'Build';
$lang->bug->report->bugsPerModule->graph->xAxisName      = 'Module';

$lang->bug->report->openedBugsPerDay->type               = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = 'Date';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->closedBugsPerDay->type               = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = 'Date';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = 'User';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'User';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = 'User';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Priority';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Resolution';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Status';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Activation Times';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Priority';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Type';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'AssignTo';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Handling Time';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Handling Steps';

/* Operating record. */
$lang->bug->action = new stdclass();
$lang->bug->action->resolved            = array('main' => '$date, resolved by <strong>$actor</strong> and the resolution is <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
$lang->bug->action->tostory             = array('main' => '$date, converted by <strong>$actor</strong> to <strong>Story</strong> with ID <strong>$extra</strong>.');
$lang->bug->action->totask              = array('main' => '$date, imported by <strong>$actor</strong> as <strong>Task</strong> with ID <strong>$extra</strong>.');
$lang->bug->action->linked2plan         = array('main' => '$date, linked by <strong>$actor</strong> to Plan <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromplan    = array('main' => '$date, deleted by <strong>$actor</strong> from Plan <strong>$extra</strong>.');
$lang->bug->action->linked2build        = array('main' => '$date, linked by <strong>$actor</strong> to Build <strong>$extra</strong>.');
$lang->bug->action->unlinkedfrombuild   = array('main' => '$date, unlinked by <strong>$actor</strong> from Build <strong>$extra</strong>.');
$lang->bug->action->linked2release      = array('main' => '$date, linked by <strong>$actor</strong> to Release <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrelease = array('main' => '$date, unlinked by <strong>$actor</strong> from Release <strong>$extra</strong>.');
$lang->bug->action->linkrelatedbug      = array('main' => '$date, linked by <strong>$actor</strong> to Bug <strong>$extra</strong>.');
$lang->bug->action->unlinkrelatedbug    = array('main' => '$date, unlinked by <strong>$actor</strong> from Bug <strong>$extra</strong>.');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = 'Select Build';
$lang->bug->placeholder->newBuildName = 'New Build Name';

$lang->bug->featureBar['browse']['all']          = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;


$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['needconfirm']   = $lang->bug->needConfirm;
