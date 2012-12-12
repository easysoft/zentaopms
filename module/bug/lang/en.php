<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'ID';
$lang->bug->product          = 'Product';
$lang->bug->module           = 'Module';
$lang->bug->path             = 'Path';
$lang->bug->project          = 'Project';
$lang->bug->story            = 'Story';
$lang->bug->storyVersion     = 'Story Version';
$lang->bug->task             = 'Task';
$lang->bug->title            = 'Title';
$lang->bug->severity         = 'Severity';
$lang->bug->severityAB       = 'S';
$lang->bug->pri              = 'Priority';
$lang->bug->type             = 'Type';
$lang->bug->os               = 'OS';
$lang->bug->hardware         = 'Hardware';
$lang->bug->browser          = 'Browser';
$lang->bug->machine          = 'Machine';
$lang->bug->found            = 'How found';
$lang->bug->steps            = 'Steps';
$lang->bug->status           = 'Status';
$lang->bug->statusAB         = 'Status';
$lang->bug->activatedCount   = 'Activated count';
$lang->bug->activatedCountAB = 'Activated count';
$lang->bug->confirmed        = 'Confirmed';
$lang->bug->toTask           = 'To task';
$lang->bug->toStory          = 'To story';
$lang->bug->mailto           = 'Mailto';
$lang->bug->openedBy         = 'Opened By';
$lang->bug->openedByAB       = 'Opened';
$lang->bug->openedDate       = 'Opened date';
$lang->bug->openedDateAB     = 'Date';
$lang->bug->openedBuild      = 'Opened Build';
$lang->bug->assignedTo       = 'Assigned';
$lang->bug->assignedDate     = 'Assigned Date';
$lang->bug->resolvedBy       = 'Resolved By';
$lang->bug->resolvedByAB     = 'Resolve';
$lang->bug->resolution       = 'Resolution';
$lang->bug->resolutionAB     = 'Resolution';
$lang->bug->resolvedBuild    = 'Resolved Build';
$lang->bug->resolvedDate     = 'Resolved Date';
$lang->bug->resolvedDateAB   = 'Date';
$lang->bug->closedBy         = 'Closed By';
$lang->bug->closedDate       = 'Closed Date';
$lang->bug->duplicateBug     = 'Duplicate';
$lang->bug->lastEditedBy     = 'Last Edited By';
$lang->bug->lastEditedDate   = 'Last Edited Date';
$lang->bug->linkBug          = 'Related';
$lang->bug->case             = 'Case';
$lang->bug->files            = 'Files';
$lang->bug->keywords         = 'Keywords';
$lang->bug->lastEditedByAB   = 'Edited';
$lang->bug->lastEditedDateAB = 'Edited Date';
$lang->bug->fromCase         = 'From case';
$lang->bug->toCase           = 'To case';

/* Actions. */
$lang->bug->index              = 'Index';
$lang->bug->create             = 'Create Bug';
$lang->bug->confirmBug         = 'Confirm Bug';
$lang->bug->edit               = 'Edit Bug';
$lang->bug->batchEdit          = 'Batch edit';
$lang->bug->assignTo           = 'Assign';
$lang->bug->browse             = 'Browse Bug';
$lang->bug->view               = 'Bug Info';
$lang->bug->resolve            = 'Resolve Bug';
$lang->bug->close              = 'Close Bug';
$lang->bug->activate           = 'Activate Bug';
$lang->bug->reportChart        = 'Report';
$lang->bug->export             = 'Export data';
$lang->bug->delete             = 'Delete Bug';
$lang->bug->saveTemplate       = 'Save template';
$lang->bug->deleteTemplate     = 'Delete template';
$lang->bug->customFields       = 'Custom';
$lang->bug->restoreDefault     = 'Default';
$lang->bug->ajaxGetUserBugs    = 'API: My Bugs';
$lang->bug->ajaxGetModuleOwner = 'API: Get module default owner';
$lang->bug->confirmStoryChange = 'Confirm Story Change';

/* Browse tabs. */
$lang->bug->selectProduct  = 'Select product';
$lang->bug->byModule       = 'ByModule';
$lang->bug->assignToMe     = 'MyBugs';
$lang->bug->openedByMe     = 'MyOpen';
$lang->bug->resolvedByMe   = 'MyResolve';
$lang->bug->closedByMe     = 'MyClose';
$lang->bug->assignToNull   = 'Unassigned';
$lang->bug->unResolved     = 'Unresolved';
$lang->bug->unclosed       = 'Unclosed';
$lang->bug->longLifeBugs   = 'Longlife';
$lang->bug->postponedBugs  = 'Postponed';
$lang->bug->allBugs        = 'Allbug';
$lang->bug->moduleBugs     = 'ByModule';
$lang->bug->byQuery        = 'Search';
$lang->bug->needConfirm    = 'StoryChanged';
$lang->bug->allProduct     = 'All products';

/* Labels. */
$lang->bug->lblProductAndModule         = 'Product&Module';
$lang->bug->lblProjectAndTask           = 'Project&Task';
$lang->bug->lblStory                    = 'Story';
$lang->bug->lblTypeAndSeverity          = 'Type&Severity';
$lang->bug->lblSystemBrowserAndHardware = 'OS&Browser';
$lang->bug->lblAssignedTo               = 'Assigned to';
$lang->bug->lblMailto                   = 'Mailto';
$lang->bug->lblLastEdited               = 'Last edited';
$lang->bug->lblResolved                 = 'Resolved';
$lang->bug->lblAllFields                = 'All Fields';
$lang->bug->lblCustomFields             = 'Custom Fields';

/* Legends. */
$lang->bug->legendBasicInfo    = 'Basic info';
$lang->bug->legendMailto       = 'Mailto';
$lang->bug->legendAttatch      = 'Files';
$lang->bug->legendLinkBugs     = 'Related bug';
$lang->bug->legendPrjStoryTask = 'Project, story & task';
$lang->bug->legendCases        = 'Related case';
$lang->bug->legendSteps        = 'Steps';
$lang->bug->legendAction       = 'Action';
$lang->bug->legendHistory      = 'History';
$lang->bug->legendComment      = 'Comment';
$lang->bug->legendLife         = 'Lifetime';
$lang->bug->legendMisc         = 'Misc';

/* Action buttons. */
$lang->bug->buttonConfirm        = 'Confirm';
$lang->bug->buttonCopy           = 'Copy';
$lang->bug->buttonAssign         = 'Assgin';
$lang->bug->buttonEdit           = 'Edit';
$lang->bug->buttonActivate       = 'Activate';
$lang->bug->buttonResolve        = 'Resolve';
$lang->bug->buttonClose          = 'Close';
$lang->bug->buttonToList         = 'Back';
$lang->bug->buttonCreateTestcase = 'Create case';

/* Confirm messags. */
$lang->bug->confirmChangeProduct = 'Change product will change project, task and story also, are you sure?';
$lang->bug->confirmDelete        = 'Are you sure to delete this bug?';
$lang->bug->setTemplateTitle     = 'Please input the template title:';
$lang->bug->remindTask           = 'This bug has been to be a task, update the task:%s or not?';

/* Templates. */
$lang->bug->tplStep   = "<p>[Steps]</p>";
$lang->bug->tplResult = "<p>[Result]</p>";
$lang->bug->tplExpect = "<p>[Expect]</p>";

/* Field options lists. */
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
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['winnt']   = 'Windows NT';
$lang->bug->osList['win98']   = 'Windows 98';
$lang->bug->osList['andriod'] = 'Andriod';
$lang->bug->osList['ios']     = 'IOS';
$lang->bug->osList['wp7']     = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['mac']     = 'Mac OS';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = 'Others';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = 'All';
$lang->bug->browserList['ie']       = 'IE';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['chrome']   = 'chrome';
$lang->bug->browserList['firefox']  = 'Firefox';
$lang->bug->browserList['firefox2'] = 'Firefox2';
$lang->bug->browserList['firefox3'] = 'Firefox3';
$lang->bug->browserList['firefox4'] = 'Firefox4';
$lang->bug->browserList['opera']    = 'opera';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['oprea11']  = 'opera11';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['maxthon']  = '傲游';
$lang->bug->browserList['uc']       = 'UC';
$lang->bug->browserList['other']    = 'Others';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'Code error';
$lang->bug->typeList['interface']    = 'Interface';
$lang->bug->typeList['designchange'] = 'Design change';
$lang->bug->typeList['newfeature']   = 'New feature';
$lang->bug->typeList['designdefect'] = 'Design defect';
$lang->bug->typeList['config']       = 'Config';
$lang->bug->typeList['install']      = 'Install';
$lang->bug->typeList['security']     = 'Security';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'Standard';
$lang->bug->typeList['automation']   = 'Automation';
$lang->bug->typeList['trackthings']  = 'Tracking';
$lang->bug->typeList['others']       = 'Others';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Active';
$lang->bug->statusList['resolved'] = 'Resolved';
$lang->bug->statusList['closed']   = 'Closed';

$lang->bug->confirmedList[1] = 'Confirmed';
$lang->bug->confirmedList[0] = 'Unconfirmed';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'By design';
$lang->bug->resolutionList['duplicate']  = 'Duplicate';
$lang->bug->resolutionList['external']   = 'External';
$lang->bug->resolutionList['fixed']      = 'Fixed';
$lang->bug->resolutionList['notrepro']   = 'Not reproduce';
$lang->bug->resolutionList['postponed']  = 'Postponed';
$lang->bug->resolutionList['willnotfix'] = "Won't fix";
$lang->bug->resolutionList['tostory']    = 'To story';

/* Report. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Report';
$lang->bug->report->select = 'Select';
$lang->bug->report->create = 'Create';

$lang->bug->report->charts['bugsPerProject']        = 'Project bugs';
$lang->bug->report->charts['bugsPerBuild']          = 'Build bugs';
$lang->bug->report->charts['bugsPerModule']         = 'Module bugs';
$lang->bug->report->charts['openedBugsPerDay']      = 'Opened bugs per day';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Resolved bugs per day';
$lang->bug->report->charts['closedBugsPerDay']      = 'Closed bugs per day';
$lang->bug->report->charts['openedBugsPerUser']     = 'Opened bugs per user';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Resolved bugs per user';
$lang->bug->report->charts['closedBugsPerUser']     = 'Closed bugs per user';
$lang->bug->report->charts['bugsPerSeverity']       = 'Severity';
$lang->bug->report->charts['bugsPerResolution']     = 'Resolution';
$lang->bug->report->charts['bugsPerStatus']         = 'Status';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Activated count';
$lang->bug->report->charts['bugsPerType']           = 'Type';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'AssignedTo';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph = new stdclass();
$lang->bug->report->options->swf                     = 'pie2d';
$lang->bug->report->options->width                   = 'auto';
$lang->bug->report->options->height                  = 300;
$lang->bug->report->options->graph->baseFontSize     = 12;
$lang->bug->report->options->graph->showNames        = 1;
$lang->bug->report->options->graph->formatNumber     = 1;
$lang->bug->report->options->graph->decimalPrecision = 0;
$lang->bug->report->options->graph->animation        = 0;
$lang->bug->report->options->graph->rotateNames      = 0;
$lang->bug->report->options->graph->yAxisName        = 'COUNT';
$lang->bug->report->options->graph->pieRadius        = 100;
$lang->bug->report->options->graph->showColumnShadow = 0;

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
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerProject->graph->xAxisName     = 'Project';
$lang->bug->report->bugsPerBuild->graph->xAxisName       = 'Build';
$lang->bug->report->bugsPerModule->graph->xAxisName      = 'Module';

$lang->bug->report->openedBugsPerDay->swf                = 'column2d';
$lang->bug->report->openedBugsPerDay->height             = 400;
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = 'Date';
$lang->bug->report->openedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->resolvedBugsPerDay->swf              = 'column2d';
$lang->bug->report->resolvedBugsPerDay->height           = 400;
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Date';
$lang->bug->report->resolvedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->closedBugsPerDay->swf                = 'column2d';
$lang->bug->report->closedBugsPerDay->height             = 400;
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = 'Date';
$lang->bug->report->closedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = 'User';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= 'User';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = 'User';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Severity';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Resolution';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Status';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Activated count';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Type';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'AssignedTo';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Live days';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Histories';

/* 操作记录。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved = array('main' => '$date, Resolved by <strong>$actor</strong>, resolution is <strong>$extra</strong>.', 'extra' => $lang->bug->resolutionList);
$lang->bug->action->tostory  = array('main' => '$date, To story by <strong>$actor</strong>, ID is <strong>$extra</strong>.');
$lang->bug->action->totask   = array('main' => '$date, To task by <strong>$actor</strong>, ID is <strong>$extra</strong>.');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->mailto   = 'you can input the user account to select users to mail to.';
$lang->bug->placeholder->keywords = 'keywords';
