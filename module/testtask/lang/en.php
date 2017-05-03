<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: en.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "Index";
$lang->testtask->create           = "Create";
$lang->testtask->reportChart      = 'Report';
$lang->testtask->delete           = "Delete";
$lang->testtask->view             = "Info";
$lang->testtask->edit             = "Edit";
$lang->testtask->browse           = "Test Tasks";
$lang->testtask->linkCase         = "Link Cases";
$lang->testtask->selectVersion    = "Select Version";
$lang->testtask->unlinkCase       = "Unlink";
$lang->testtask->batchUnlinkCases = "Batch unlink cases";
$lang->testtask->batchAssign      = "Batch Assign";
$lang->testtask->runCase          = "Run";
$lang->testtask->batchRun         = "Batch Run";
$lang->testtask->results          = "Result";
$lang->testtask->createBug        = "Bug(+)";
$lang->testtask->assign           = 'Assign';
$lang->testtask->cases            = 'Cases';
$lang->testtask->groupCase        = "View By";
$lang->testtask->pre              = 'Previous';
$lang->testtask->next             = 'Next';
$lang->testtask->start            = "Start";
$lang->testtask->close            = "Close";
$lang->testtask->wait             = "Build to be Tested";
$lang->testtask->block            = "Block";
$lang->testtask->activate         = "Activation";
$lang->testtask->testing          = "Testing Build";
$lang->testtask->blocked          = "Blocked Build";
$lang->testtask->done             = "Tested Build";
$lang->testtask->totalStatus      = "All";
$lang->testtask->all              = "All " . $lang->productCommon;

$lang->testtask->id             = 'ID';
$lang->testtask->common         = 'Test build';
$lang->testtask->product        = $lang->productCommon;
$lang->testtask->project        = $lang->projectCommon;
$lang->testtask->build          = 'Build';
$lang->testtask->owner          = 'Owner';
$lang->testtask->pri            = 'Priority';
$lang->testtask->name           = 'Name';
$lang->testtask->begin          = 'Begin';
$lang->testtask->end            = 'End';
$lang->testtask->desc           = 'Description';
$lang->testtask->mailto         = 'Mailto';
$lang->testtask->status         = 'Status';
$lang->testtask->assignedTo     = 'Assign';
$lang->testtask->linkVersion    = 'Version';
$lang->testtask->lastRunAccount = "Run";
$lang->testtask->lastRunTime    = 'Time';
$lang->testtask->lastRunResult  = 'Result';
$lang->testtask->reportField    = 'Report';
$lang->testtask->files          = 'Upload';

$lang->testtask->legendDesc      = 'Desc';
$lang->testtask->legendReport    = 'Report';
$lang->testtask->legendBasicInfo = 'Basic Info';

$lang->testtask->statusList['wait']    = 'Wait';
$lang->testtask->statusList['doing']   = 'Doing';
$lang->testtask->statusList['done']    = 'Done';
$lang->testtask->statusList['blocked'] = 'Blocked';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'Unlinked Cases';
$lang->testtask->linkByBuild   = 'Copy by build';
$lang->testtask->linkByStory   = 'Linked by Story';
$lang->testtask->linkByBug     = 'Linked by Bug';
$lang->testtask->linkBySuite   = 'Linked by Suite';
$lang->testtask->passAll       = 'All Pass';
$lang->testtask->pass          = 'Pass';
$lang->testtask->fail          = 'Failed';
$lang->testtask->showResult    = 'Executed <span class="text-info">%s</span> times';
$lang->testtask->showFail      = 'Failed <span class="text-danger">%s</span> times';

$lang->testtask->confirmDelete     = 'Do you want to delete this test build?';
$lang->testtask->confirmUnlinkCase = 'Do you want to unlink this Case?';
$lang->testtask->noticeNoOther     = 'There are no other test task for this product';

$lang->testtask->assignedToMe  = 'Assigned to Me';
$lang->testtask->allCases      = 'All Cases';

$lang->testtask->lblCases      = 'Cases';
$lang->testtask->lblUnlinkCase = 'Unlink Case';
$lang->testtask->lblRunCase    = 'Run Case';
$lang->testtask->lblResults    = 'Results';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = 'Begin';
$lang->testtask->placeholder->end   = 'End';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s created test task #%s:%s";
$lang->testtask->mail->edit->title   = "%s finished test task #%s:%s";
$lang->testtask->mail->close->title  = "%s closed test task #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date,  <strong>$actor</strong> opened test task <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskstarted = '$date,  <strong>$actor</strong> started test task <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskclosed  = '$date,  <strong>$actor</strong> finished test task<strong>$extra</strong>.' . "\n";

$lang->testtask->unexecuted = 'Not performed';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'Report';
$lang->testtask->report->select = 'Select report type';
$lang->testtask->report->create = 'Generate';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'Result Report';
$lang->testtask->report->charts['testTaskPerType']      = 'Type Report';
$lang->testtask->report->charts['testTaskPerModule']    = 'Module Report';
$lang->testtask->report->charts['testTaskPerRunner']    = 'Runner Report';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Bug Severity Distribution';
$lang->testtask->report->charts['bugStatusGroups']      = 'Bug Status Distribution';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Bug CreatedBy Distribution';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Bug ResolvedBy Distribution';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Bug Resolution Distribution';
$lang->testtask->report->charts['bugModuleGroups']      = 'Bug Module Distribution';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;
