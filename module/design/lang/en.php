<?php
/**
 * The English file of design module.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: en.php 4729 2020-09-01 07:53:55Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
/* 字段列表. */
$lang->design->id            = 'ID';
$lang->design->name          = 'Name';
$lang->design->story         = 'Story';
$lang->design->type          = 'Type';
$lang->design->ditto         = 'Ditto';
$lang->design->submission    = 'Commit';
$lang->design->version       = 'Version';
$lang->design->assignedTo    = 'Assigned to';
$lang->design->actions       = 'Actions';
$lang->design->byQuery       = 'Search';
$lang->design->products      = "Linked {$lang->productCommon}";
$lang->design->story         = 'Linked Story';
$lang->design->file          = 'Attachment';
$lang->design->desc          = 'Description';
$lang->design->range          = 'Impact';
$lang->design->product       = "Linked {$lang->productCommon}";
$lang->design->basicInfo     = 'Basic Info';
$lang->design->commitBy      = 'Committed by';
$lang->design->commitDate    = 'Committed on';
$lang->design->affectedStory = "Impacted $lang->SRCommon}";
$lang->design->affectedTasks = 'Impacted Task';
$lang->design->reviewObject  = 'Review Item';
$lang->design->createdBy     = 'Creator';
$lang->design->createdByAB   = 'Creator';
$lang->design->createdDate   = 'Created on';
$lang->design->basicInfo     = 'Basic Info';
$lang->design->noAssigned    = 'Unassigned';
$lang->design->comment       = 'Comment';
$lang->design->more          = 'More';
$lang->design->project       = 'Project';

/* 动作列表. */
$lang->design->common             = 'Design';
$lang->design->create             = 'Create Design';
$lang->design->batchCreate        = 'Batch Create';
$lang->design->edit               = 'Change';
$lang->design->delete             = 'Delete';
$lang->design->view               = 'View Details';
$lang->design->browse             = 'Design List';
$lang->design->viewCommit         = 'View Commit';
$lang->design->linkCommit         = 'Link Commit';
$lang->design->unlinkCommit       = 'Unlink Commit';
$lang->design->submit             = 'Submit Review';
$lang->design->assignTo           = 'Assign';
$lang->design->assignAction       = 'Assign';
$lang->design->revision           = 'Related Code';
$lang->design->confirmStoryChange = 'Confirm';

$lang->design->browseAction = 'Design List';

/* 字段取值. */
$lang->design->typeList         = array();
$lang->design->typeList['']     = '';
$lang->design->typeList['HLDS'] = 'High-Level Design';
$lang->design->typeList['DDS']  = 'Low-Level Design';
$lang->design->typeList['DBDS'] = 'Database Design';
$lang->design->typeList['ADS']  = 'API Design';

$lang->design->plusTypeList = $lang->design->typeList;

$lang->design->rangeList           = array();
$lang->design->rangeList['all']    = 'All';
$lang->design->rangeList['assign'] = 'Selected';

/* 提示信息. */
$lang->design->errorSelection = 'No record selected!';
$lang->design->noDesign       = 'No records yet.';
$lang->design->noCommit       = 'No submission records.';
$lang->design->confirmDelete  = 'Are you sure you want to delete this design?';
$lang->design->confirmUnlink  = 'Are you sure you want to remove this submission?';
$lang->design->errorDate      = 'Start date cannot be later than end date.';
$lang->design->deleted        = 'Deleted';
$lang->design->frozenTip      = 'After the design are baselined, %s is not allowed.';
