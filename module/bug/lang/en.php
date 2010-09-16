<?php
/**
 * The bug module English file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common         = 'Bug';
$lang->bug->id             = 'ID';
$lang->bug->product        = 'Product';
$lang->bug->module         = 'Module';
$lang->bug->path           = 'Path';
$lang->bug->project        = 'Project';
$lang->bug->story          = 'Story';
$lang->bug->storyVersion   = 'Story Version';
$lang->bug->task           = 'Task';
$lang->bug->title          = 'Title';
$lang->bug->severity       = 'Severity';
$lang->bug->severityAB     = 'S';
$lang->bug->pri            = 'Priority';
$lang->bug->type           = 'Type';
$lang->bug->os             = 'OS';
$lang->bug->hardware       = 'Hardware';
$lang->bug->browser        = 'Browser';
$lang->bug->machine        = 'Machine';
$lang->bug->found          = 'How found';
$lang->bug->steps          = 'Steps';
$lang->bug->status         = 'Status';
$lang->bug->mailto         = 'Mailto';
$lang->bug->openedBy       = 'Opened By';
$lang->bug->openedByAB     = 'Opened';
$lang->bug->openedDate     = 'Opened date';
$lang->bug->openedBuild    = 'Opened Build';
$lang->bug->assignedTo     = 'Assigned';
$lang->bug->assignedDate   = 'Assigned Date';
$lang->bug->resolvedBy     = 'Resolved By';
$lang->bug->resolvedByAB   = 'Resolve';
$lang->bug->resolution     = 'Resolution';
$lang->bug->resolutionAB   = 'Resolution';
$lang->bug->resolvedBuild  = 'Resolved Build';
$lang->bug->resolvedDate   = 'Resolved Date';
$lang->bug->closedBy       = 'Closed By';
$lang->bug->closedDate     = 'Closed Date';
$lang->bug->duplicateBug   = 'Duplicate';
$lang->bug->lastEditedBy   = 'Last Edited By';
$lang->bug->lastEditedDate = 'Last Edited Date';
$lang->bug->linkBug        = 'Related';
$lang->bug->case           = 'Case';
$lang->bug->files          = 'Files';
$lang->bug->keywords       = 'Keywords';
$lang->bug->lastEditedByAB   = 'Edited';
$lang->bug->lastEditedDateAB = 'Edited Date';

/* 方法列表。*/
$lang->bug->index          = 'Index';
$lang->bug->create         = 'Create Bug';
$lang->bug->edit           = 'Edit Bug';
$lang->bug->browse         = 'Browse Bug';
$lang->bug->view           = 'Bug Info';
$lang->bug->resolve        = 'Resolve Bug';
$lang->bug->close          = 'Close Bug';
$lang->bug->activate       = 'Activate Bug';
$lang->bug->reportChart    = 'Report';
$lang->bug->delete         = 'Delete Bug';
$lang->bug->saveTemplate   = 'Save template';
$lang->bug->deleteTemplate = 'Delete template';
$lang->bug->customFields   = 'Custom Field';
$lang->bug->restoreDefault = 'Default';
$lang->bug->ajaxGetUserBugs    = 'API: My Bugs';
$lang->bug->ajaxGetModuleOwner = 'API: Get module default owner';
$lang->bug->confirmStoryChange = 'Confirm Story Change';

/* 查询条件列表。*/
$lang->bug->selectProduct  = 'Select product';
$lang->bug->byModule       = 'ByModule';
$lang->bug->assignToMe     = 'Tome';
$lang->bug->openedByMe     = 'MyOpen';
$lang->bug->resolvedByMe   = 'MyResolve';
$lang->bug->assignToNull   = 'Assign null';
$lang->bug->longLifeBugs   = 'Long life';
$lang->bug->postponedBugs  = 'Postponed';
$lang->bug->allBugs        = 'All bug';
$lang->bug->moduleBugs     = 'By module';
$lang->bug->byQuery        = 'Search';
$lang->bug->needConfirm    = 'Story changed';
$lang->bug->allProduct     = 'All products';

/* 页面标签。*/
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

/* legend列表。*/
$lang->bug->legendBasicInfo   = 'Basic info';
$lang->bug->legendMailto      = 'Mailto';
$lang->bug->legendAttatch     = 'Files';
$lang->bug->legendLinkBugs    = 'Related bug';
$lang->bug->legendPrjStoryTask= 'Project, story & task';
$lang->bug->legendCases       = 'Related case';
$lang->bug->legendSteps       = 'Steps';
$lang->bug->legendAction      = 'Action';
$lang->bug->legendHistory     = 'History';
$lang->bug->legendComment     = 'Comment';
$lang->bug->legendLife        = 'Lifetime';
$lang->bug->legendMisc        = 'Misc';

/* 功能按钮。*/
$lang->bug->buttonCopy     = 'Copy';
$lang->bug->buttonEdit     = 'Edit';
$lang->bug->buttonActivate = 'Activate';
$lang->bug->buttonResolve  = 'Resolve';
$lang->bug->buttonClose    = 'Close';
$lang->bug->buttonToList   = 'Back';

/* 交互提示。*/
$lang->bug->confirmChangeProduct = 'Change product will change project, task and story also, are you sure?';
$lang->bug->confirmDelete        = 'Are you sure to delete this bug?';
$lang->bug->setTemplateTitle     = 'Please input the template title:';

/* 模板。*/
$lang->bug->tplStep        = "<p>[Steps]</p>";
$lang->bug->tplResult      = "<p>[Result]</p>";
$lang->bug->tplExpect      = "<p>[Expect]</p>";

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
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['winnt']   = 'Windows NT';
$lang->bug->osList['win98']   = 'Windows 98';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = 'Others';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = 'All';
$lang->bug->browserList['ie']       = 'IE';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['firefox']  = 'Firefox';
$lang->bug->browserList['firefox2'] = 'Firefox2';
$lang->bug->browserList['firefx3']  = 'Firefox3';
$lang->bug->browserList['opera']    = 'opera';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['chrome']   = 'chrome';
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
$lang->bug->typeList['Others']       = 'Others';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Active';
$lang->bug->statusList['resolved'] = 'Resolved';
$lang->bug->statusList['closed']   = 'Closed';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'By design';
$lang->bug->resolutionList['duplicate']  = 'Duplicate';
$lang->bug->resolutionList['external']   = 'External';
$lang->bug->resolutionList['fixed']      = 'Fixed';
$lang->bug->resolutionList['notrepro']   = 'Not reproduce';
$lang->bug->resolutionList['postponed']  = 'Postponed';
$lang->bug->resolutionList['willnotfix'] = "Won't fix";

/* 统计报表。*/
$lang->bug->report->common        = 'Report';
$lang->bug->report->select        = 'Select';
$lang->bug->report->create        = 'Create';
$lang->bug->report->selectAll     = 'All';
$lang->bug->report->selectReverse = 'Reverse';

$lang->bug->report->charts['bugsPerProject']     = 'Project bugs';
$lang->bug->report->charts['bugsPerModule']      = 'Module bugs';
$lang->bug->report->charts['openedBugsPerDay']   = 'Opened bugs per day';
$lang->bug->report->charts['resolvedBugsPerDay'] = 'Resolved bugs per day';
$lang->bug->report->charts['closedBugsPerDay']   = 'Closed bugs per day';
$lang->bug->report->charts['openedBugsPerUser']  = 'Opened bugs per user';
$lang->bug->report->charts['resolvedBugsPerUser']= 'Resolved bugs per user';
$lang->bug->report->charts['closedBugsPerUser']  = 'Closed bugs per user';
$lang->bug->report->charts['bugsPerSeverity']    = 'Severity';
$lang->bug->report->charts['bugsPerResolution']  = 'Resolution';
$lang->bug->report->charts['bugsPerStatus']      = 'Status';
$lang->bug->report->charts['bugsPerType']        = 'Type';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

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
$lang->bug->report->options->graph->pieRadius        = 100; // 饼图直径。
$lang->bug->report->options->graph->showColumnShadow = 0;   // 是否显示柱状图阴影。

$lang->bug->report->bugsPerProject->graph->xAxisName     = 'Project';
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

$lang->bug->report->bugsPerSeverity->graph->xAxisName    = 'Severity';
$lang->bug->report->bugsPerResolution->graph->xAxisName  = 'Resolution';
$lang->bug->report->bugsPerStatus->graph->xAxisName      = 'Status';
$lang->bug->report->bugsPerType->graph->xAxisName        = 'Type';
$lang->bug->report->bugLiveDays->graph->xAxisName        = 'Live days';
$lang->bug->report->bugHistories->graph->xAxisName       = 'Histories';

/* 操作记录。*/
$lang->bug->action->resolved = array('main' => '$date, Resolved by <strong>$actor</strong>, resolution is <strong>$extra</strong>.', 'extra' => $lang->bug->resolutionList);
