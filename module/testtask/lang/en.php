<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->testtask->index          = "Index";
$lang->testtask->create         = "Create";
$lang->testtask->delete         = "Delete";
$lang->testtask->view           = "Info";
$lang->testtask->edit           = "Edit";
$lang->testtask->browse         = "Testtask browse";
$lang->testtask->linkCase       = "Link case";
$lang->testtask->linkCaseAB     = "Link";
$lang->testtask->unlinkCase     = "Del";
$lang->testtask->batchAssign    = "Batch Assign";
$lang->testtask->runCase        = "Run";
$lang->testtask->batchRun       = "Batch Run";
$lang->testtask->results        = "Result";
$lang->testtask->createBug      = "Bug(+)";
$lang->testtask->assign         = 'Assign';
$lang->testtask->cases          = 'Cases';

$lang->testtask->common         = 'Test task';
$lang->testtask->id             = 'ID';
$lang->testtask->product        = 'Product';
$lang->testtask->project        = 'Project';
$lang->testtask->build          = 'Build';
$lang->testtask->owner          = 'Owner';
$lang->testtask->pri            = 'Priority';
$lang->testtask->name           = 'Name';
$lang->testtask->begin          = 'Begin';
$lang->testtask->end            = 'End';
$lang->testtask->desc           = 'Desc';
$lang->testtask->status         = 'Status';
$lang->testtask->assignedTo     = 'Assign';
$lang->testtask->linkVersion    = 'Version';
$lang->testtask->lastRunAccount = "Run";
$lang->testtask->lastRunTime    = 'Time';
$lang->testtask->lastRunResult  = 'Result';

$lang->testtask->statusList['wait']    = 'Pending';
$lang->testtask->statusList['doing']   = 'In progress';
$lang->testtask->statusList['done']    = 'Done';
$lang->testtask->statusList['blocked'] = 'Blocked';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'Unlinked';
$lang->testtask->linkedCases   = 'Linked';
$lang->testtask->linkByStory   = 'Link by story';
$lang->testtask->linkByBug     = 'Link by bug';
$lang->testtask->confirmDelete = 'Are you sure to delete this test task?';
$lang->testtask->passAll       = 'Pass all';
$lang->testtask->pass          = 'Pass';
$lang->testtask->fail          = 'Fail';

$lang->testtask->byModule      = 'By module';
$lang->testtask->assignedToMe  = 'Assgined to me';
$lang->testtask->allCases      = 'All Cases';

$lang->testtask->lblCases      = 'Case list';
$lang->testtask->lblUnlinkCase = 'Remove case';
$lang->testtask->lblRunCase    = 'Run';
$lang->testtask->lblResults    = 'Result';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = 'begin date';
$lang->testtask->placeholder->end   = 'end date';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->create->title = "%s created testtask #%s:%s";
$lang->testtask->mail->edit->title   = "%s finished testtask #%s:%s";
