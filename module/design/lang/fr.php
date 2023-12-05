<?php
/**
 * The English file of design module.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: en.php 4729 2020-09-01 07:53:55Z tianshujie@easycorp.ltd $
 * @link        http://www.zentao.net
 */
/* 字段列表. */
$lang->design->id            = 'ID';
$lang->design->name          = 'Name';
$lang->design->story         = 'Story';
$lang->design->type          = 'Type';
$lang->design->ditto         = 'Ditto';
$lang->design->submission    = 'Commit';
$lang->design->version       = 'Revision';
$lang->design->assignedTo    = 'AssignedTo';
$lang->design->actions       = 'Actions';
$lang->design->byQuery       = 'Search';
$lang->design->products      = "Linked {$lang->productCommon}";
$lang->design->story         = 'Story';
$lang->design->file          = 'File';
$lang->design->desc          = 'Description';
$lang->design->range          = 'Impact';
$lang->design->product       = "Linked {$lang->productCommon}";
$lang->design->basicInfo     = 'Basic Information';
$lang->design->commitBy      = 'Commit By';
$lang->design->commitDate    = 'Committed Date';
$lang->design->affectedStory = "{$lang->SRCommon}";
$lang->design->affectedTasks = 'Task';
$lang->design->reviewObject  = 'Review Object';
$lang->design->createdBy     = 'CreatedBy';
$lang->design->createdByAB   = 'CreatedBy';
$lang->design->createdDate   = 'CreatedDate';
$lang->design->basicInfo     = 'Basic Information';
$lang->design->noAssigned    = 'Unassigned';
$lang->design->comment       = 'Comment';
$lang->design->more          = 'Suite';

/* 动作列表. */
$lang->design->common       = 'Design';
$lang->design->create       = 'Create Design';
$lang->design->batchCreate  = 'Batch Create';
$lang->design->edit         = 'Edit';
$lang->design->delete       = 'Delete';
$lang->design->view         = 'View';
$lang->design->browse       = 'Design List';
$lang->design->viewCommit   = 'View Commit';
$lang->design->linkCommit   = 'Link Commit';
$lang->design->unlinkCommit = 'Unlink Commit';
$lang->design->submit       = 'Submit Review';
$lang->design->assignTo     = 'Assign';
$lang->design->assignAction = 'Assign Design';
$lang->design->revision     = 'Linked Code';

$lang->design->browseAction = 'Design List';

/* 字段取值. */
$lang->design->typeList         = array();
$lang->design->typeList['']     = '';
$lang->design->typeList['HLDS'] = 'High-Level';
$lang->design->typeList['DDS']  = 'Detailed';
$lang->design->typeList['DBDS'] = 'Database';
$lang->design->typeList['ADS']  = 'API';

$lang->design->plusTypeList = $lang->design->typeList;

$lang->design->rangeList           = array();
$lang->design->rangeList['all']    = 'All';
$lang->design->rangeList['assign'] = 'Selected';

/* 提示信息. */
$lang->design->errorSelection = 'No record selected!';
$lang->design->noDesign       = 'No record.';
$lang->design->noCommit       = 'No record commited.';
$lang->design->confirmDelete  = 'Do you want to delete this design?';
$lang->design->confirmUnlink  = 'Are you sure you want to remove this submission?';
$lang->design->errorDate      = 'The start date can not be greater than the end date.';
$lang->design->deleted        = 'Deleted';
