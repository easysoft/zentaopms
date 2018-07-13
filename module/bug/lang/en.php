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
$lang->bug->task             = 'Task';
$lang->bug->title            = 'Title';
$lang->bug->severity         = 'Severity(S)';
$lang->bug->severityAB       = 'S';
$lang->bug->pri              = 'Priority(P)';
$lang->bug->type             = 'Type';
$lang->bug->os               = 'OS';
$lang->bug->browser          = 'Browser';
$lang->bug->steps            = 'Repro Steps';
$lang->bug->status           = 'Status';
$lang->bug->statusAB         = 'Status';
$lang->bug->activatedCount   = 'Active';
$lang->bug->activatedCountAB = 'Active';
$lang->bug->activatedDate    = 'Active On';
$lang->bug->confirmed        = 'Confirmed';
$lang->bug->toTask           = 'Convert to Task';
$lang->bug->toStory          = 'Convert to Story';
$lang->bug->mailto           = 'Mail To';
$lang->bug->openedBy         = 'Creator';
$lang->bug->openedDate       = 'Created on';
$lang->bug->openedDateAB     = 'Created';
$lang->bug->openedBuild      = 'Open Build';
$lang->bug->assignedTo       = 'To';
$lang->bug->assignBug        = 'Assign To';
$lang->bug->assignedToAB     = 'To';
$lang->bug->assignedDate     = 'Assigned On';
$lang->bug->resolvedBy       = 'Resolved';
$lang->bug->resolvedByAB     = 'Resolved';
$lang->bug->resolution       = 'Solution';
$lang->bug->resolutionAB     = 'Solution';
$lang->bug->resolvedBuild    = 'Resolved Build';
$lang->bug->resolvedDate     = 'Resolved on';
$lang->bug->resolvedDateAB   = 'Resolved';
$lang->bug->deadline         = 'Deadline';
$lang->bug->plan             = 'Plan';
$lang->bug->closedBy         = 'Closed By';
$lang->bug->closedDate       = 'Closed On';
$lang->bug->duplicateBug     = 'Duplicate';
$lang->bug->lastEditedBy     = 'Edited By';
$lang->bug->linkBug          = 'Linked Bug';
$lang->bug->linkBugs         = 'Link Bug';
$lang->bug->unlinkBug        = 'Unlink';
$lang->bug->case             = 'Case';
$lang->bug->files            = 'File';
$lang->bug->keywords         = 'Tags';
$lang->bug->lastEditedByAB   = 'Edited By';
$lang->bug->lastEditedDateAB = 'Edit On';
$lang->bug->lastEditedDate   = 'Edit On';
$lang->bug->fromCase         = 'From Case';
$lang->bug->toCase           = 'To Case';
$lang->bug->colorTag         = 'Color Tag';

/* 方法列表。*/
$lang->bug->index              = 'Home';
$lang->bug->create             = 'Report Bug';
$lang->bug->batchCreate        = 'Batch Report Bug';
$lang->bug->confirmBug         = 'Confirm';
$lang->bug->batchConfirm       = 'Batch Confirm';
$lang->bug->edit               = 'Edit';
$lang->bug->batchEdit          = 'Batch Edit';
$lang->bug->batchChangeModule  = 'Batch Modify Module';
$lang->bug->batchChangeBranch  = 'Batch Modify Branch';
$lang->bug->batchClose         = 'Batch Close';
$lang->bug->assignTo           = 'Assign';
$lang->bug->batchAssignTo      = 'Batch Assign';
$lang->bug->browse             = 'Bugs';
$lang->bug->view               = 'Bug Details';
$lang->bug->resolve            = 'Resolve';
$lang->bug->batchResolve       = 'Batch Resolve';
$lang->bug->close              = 'Close';
$lang->bug->activate           = 'Activate';
$lang->bug->batchActivate      = 'Batch Activate';
$lang->bug->reportChart        = 'Report';
$lang->bug->export             = 'Export';
$lang->bug->delete             = 'Delete';
$lang->bug->deleted            = 'Deleted';
$lang->bug->saveTemplate       = 'Save as Template';
$lang->bug->setPublic          = 'Set as Public Template';
$lang->bug->deleteTemplate     = 'Delete Template';
$lang->bug->confirmStoryChange = 'Confirm Story Change';
$lang->bug->copy               = 'Copy';
$lang->bug->search             = 'Search';

/* 查询条件列表。*/
$lang->bug->assignToMe         = 'Assigned To Me';
$lang->bug->openedByMe         = 'Created By Me';
$lang->bug->resolvedByMe       = 'Resolved By Me';
$lang->bug->closedByMe         = 'Closed By Me';
$lang->bug->assignToNull       = 'Unassigned';
$lang->bug->unResolved         = 'Unresolved';
$lang->bug->toClosed           = 'Unclosed';
$lang->bug->unclosed           = 'Active';
$lang->bug->unconfirmed        = 'UnConfirmed';
$lang->bug->longLifeBugs       = 'Pending';
$lang->bug->postponedBugs      = 'Postponed';
$lang->bug->overdueBugs        = 'Overdue';
$lang->bug->allBugs            = 'All';
$lang->bug->byQuery            = 'Search';
$lang->bug->needConfirm        = 'StoryChanged';
$lang->bug->allProduct         = 'All' . $lang->productCommon;
$lang->bug->my                 = 'My';
$lang->bug->yesterdayResolved  = 'Resolved Yesterday ';
$lang->bug->yesterdayConfirmed = 'Confirmed Yesterday ';
$lang->bug->yesterdayClosed    = 'Closed Yesterday ';

$lang->bug->assignToMeAB   = 'Assigned To Me';
$lang->bug->openedByMeAB   = 'Created By Me';
$lang->bug->resolvedByMeAB = 'Resolved By Me';

$lang->bug->ditto       = 'Ditto';
$lang->bug->dittoNotice = 'This bug is not linked to the same product as the last one!';
$lang->bug->noAssigned  = 'Assign';
$lang->bug->noBug       = 'No bugs. ';
$lang->bug->noModule    = '<div>You have no modules</div><div>Manage now</div>';

/* 页面标签。*/
$lang->bug->lblAssignedTo = 'Assignee';
$lang->bug->lblMailto     = 'Mail To';
$lang->bug->lblLastEdited = 'Last Edited';
$lang->bug->lblResolved   = 'Resolved By';
$lang->bug->allUsers      = 'Load All User';
$lang->bug->allBuilds     = 'All';
$lang->bug->createBuild   = 'New';

/* legend列表。*/
$lang->bug->legendBasicInfo             = 'Basic Info';
$lang->bug->legendAttatch               = 'Attachment';
$lang->bug->legendPrjStoryTask          = $lang->projectCommon . '/Story/Task';
$lang->bug->lblTypeAndSeverity          = 'Type/Severity';
$lang->bug->lblSystemBrowserAndHardware = 'System/Browser';
$lang->bug->legendSteps                 = 'Repro Steps';
$lang->bug->legendComment               = 'Note';
$lang->bug->legendLife                  = 'About the Bug';
$lang->bug->legendMisc                  = 'Misc';
$lang->bug->legendRelated               = 'Related Info';

/* 功能按钮。*/
$lang->bug->buttonConfirm = 'Confirm';

/* 交互提示。*/
$lang->bug->summary               = "Bugs on this page : <strong>%s</strong> Total, <strong>%s</strong> Unresolved.";
$lang->bug->confirmChangeProduct  = "Change {$lang->productCommon} will cause linked {$lang->projectCommon},Story and Task change. Do you want to do this?";
$lang->bug->confirmDelete         = 'Do you want to delete this bug?';
$lang->bug->setTemplateTitle      = 'Please enter the title of template.';
$lang->bug->remindTask            = 'This Bug has been converted to Task. Do you want to update Status of Task(ID %s)?';
$lang->bug->skipClose             = 'Bug %s is not resolved. You cannot close it.';
$lang->bug->applyTemplate         = 'Template';
$lang->bug->confirmDeleteTemplate = 'Do you want to delete this template?';
$lang->bug->hasSubmittedTest      = 'Developer has submitted test task,';
$lang->bug->nowToTest             = 'Now to test';

/* 模板。*/
$lang->bug->tplStep   = "<p>[Steps]</p><br/>";
$lang->bug->tplResult = "<p>[Results]</p><br/>";
$lang->bug->tplExpect = "<p>[Expectations]</p><br/>";

/* 各个字段取值列表。*/
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[3] = '3';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']        = '';
$lang->bug->osList['all']     = 'All';
$lang->bug->osList['windows'] = 'Windows';
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
$lang->bug->osList['others']  = 'Other';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = 'All';
$lang->bug->browserList['ie']       = 'IE series';
$lang->bug->browserList['ie11']     = 'IE11';
$lang->bug->browserList['ie10']     = 'IE10';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['chrome']   = 'chrome';
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
$lang->bug->browserList['other']    = 'Other';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'Code Error';
$lang->bug->typeList['interface']    = 'UI Optimization';
$lang->bug->typeList['config']       = 'Configuration';
$lang->bug->typeList['install']      = 'Install/Deploy';
$lang->bug->typeList['security']     = 'Security';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'Standard';
$lang->bug->typeList['automation']   = 'Testing Script';
$lang->bug->typeList['others']       = 'Other';
$lang->bug->typeList['designchange'] = 'Design Change';
$lang->bug->typeList['newfeature']   = 'New Feature';
$lang->bug->typeList['designdefect'] = 'Design Defect';
$lang->bug->typeList['trackthings']  = 'Track Work';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Active';
$lang->bug->statusList['resolved'] = 'Resolved';
$lang->bug->statusList['closed']   = 'Closed';

$lang->bug->confirmedList[1] = 'Confirmed';
$lang->bug->confirmedList[0] = 'Unconfirmed';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'As Design';
$lang->bug->resolutionList['duplicate']  = 'Duplicated Bug';
$lang->bug->resolutionList['external']   = 'External Cause';
$lang->bug->resolutionList['fixed']      = 'Resolved';
$lang->bug->resolutionList['notrepro']   = 'Irreproducible';
$lang->bug->resolutionList['postponed']  = 'Postpone';
$lang->bug->resolutionList['willnotfix'] = "Ignore";
$lang->bug->resolutionList['tostory']    = 'Convert to Story';

/* 统计报表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Report';
$lang->bug->report->select = 'Select Type ';
$lang->bug->report->create = 'Generate Report';

$lang->bug->report->charts['bugsPerProject']        = $lang->projectCommon . ' Bugs';
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs Per Build';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs Per Module';
$lang->bug->report->charts['openedBugsPerDay']      = 'Reported Bugs Per Day';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Resolved Bugs Per Day';
$lang->bug->report->charts['closedBugsPerDay']      = 'Closed Bugs Per Day';
$lang->bug->report->charts['openedBugsPerUser']     = 'Reported Bugs Per User';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Resolved Bugs Per User';
$lang->bug->report->charts['closedBugsPerUser']     = 'Closed Bugs Per User';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bug Priority Report';
$lang->bug->report->charts['bugsPerResolution']     = 'Bug Solution Report';
$lang->bug->report->charts['bugsPerStatus']         = 'Bug Status Report';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bug Activation Report';
$lang->bug->report->charts['bugsPerPri']            = 'Bug Priority Report';
$lang->bug->report->charts['bugsPerType']           = 'Bug Type Report';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bug Assignment Report';
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
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Solution';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Status';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Activation Count';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Priority';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Type';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'Assigned To';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Handling Time';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Handling Steps';

/* 操作记录。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved            = array('main' => '$date, resolved by <strong>$actor</strong> and the solution is <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
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
$lang->bug->placeholder->chooseBuilds = 'Choose Build...';
$lang->bug->placeholder->newBuildName = 'The name of new build';

$lang->bug->featureBar['browse']['all']          = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['toclosed']     = $lang->bug->toClosed;
$lang->bug->featureBar['browse']['unresolved']   = $lang->bug->unResolved;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['needconfirm']   = $lang->bug->needConfirm;
