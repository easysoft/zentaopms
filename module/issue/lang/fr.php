<?php
/**
 * The issue module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->issue->common            = 'Issue';
$lang->issue->browse            = 'List';
$lang->issue->resolvedBy        = 'Resolved By';
$lang->issue->project           = 'Linked Project';
$lang->issue->title             = 'Title';
$lang->issue->desc              = 'Description';
$lang->issue->pri               = 'Priority';
$lang->issue->severity          = 'Severity';
$lang->issue->type              = 'Type';
$lang->issue->effectedArea      = 'Impact';
$lang->issue->activity          = 'Activities';
$lang->issue->deadline          = 'Deadline';
$lang->issue->resolution        = 'Resolution';
$lang->issue->resolutionComment = 'Comment';
$lang->issue->resolvedDate      = 'Resolved Date';
$lang->issue->status            = 'Status';
$lang->issue->createdBy         = 'CreatedBy';
$lang->issue->createdDate       = 'CreatedDate';
$lang->issue->owner             = 'Owner';
$lang->issue->editedBy          = 'EditedBy';
$lang->issue->editedDate        = 'EditedDate';
$lang->issue->activateBy        = 'ActivatedBy';
$lang->issue->activateDate      = 'ActivatedDate';
$lang->issue->closedBy          = 'ClosedBy';
$lang->issue->closedDate        = 'ClosedDate';
$lang->issue->assignedTo        = 'AssignedTo';
$lang->issue->assignedBy        = 'AssignedBy';
$lang->issue->assignedDate      = 'AssignedDate';
$lang->issue->resolve           = 'Resolved';
$lang->issue->id                = 'ID';
$lang->issue->confirm           = 'Confirm';

$lang->issue->view              = 'Issue Details';
$lang->issue->close             = 'Closed';
$lang->issue->cancel            = 'Cancel';
$lang->issue->delete            = 'Delete';
$lang->issue->search            = 'Search';
$lang->issue->basicInfo         = 'Basic Information';
$lang->issue->activate          = 'Activate';
$lang->issue->assignTo          = 'Assign';
$lang->issue->create            = 'Create Issue';
$lang->issue->edit              = 'Edit';
$lang->issue->batchCreate       = 'Batch Create';
$lang->issue->deleted           = 'Deleted';

$lang->issue->labelList['all']       = 'All';
$lang->issue->labelList['open']      = 'Open';
$lang->issue->labelList['assignto']  = 'AssignedToMe';
$lang->issue->labelList['closed']    = 'Closed';
$lang->issue->labelList['suspended'] = 'Suspended';
$lang->issue->labelList['canceled']  = 'Canceled';

$lang->issue->priList[''] = '';
$lang->issue->priList['1'] = 1;
$lang->issue->priList['2'] = 2;
$lang->issue->priList['3'] = 3;
$lang->issue->priList['4'] = 4;

$lang->issue->severityList[''] = '';
$lang->issue->severityList['1'] = '1';
$lang->issue->severityList['2'] = '2';
$lang->issue->severityList['3'] = '3';
$lang->issue->severityList['4'] = '4';

$lang->issue->typeList[''] = '';
$lang->issue->typeList['design']       = 'Design';
$lang->issue->typeList['code']         = 'Code';
$lang->issue->typeList['performance']  = 'Performance';
$lang->issue->typeList['version']      = 'Version';
$lang->issue->typeList['storyadd']     = 'New Story';
$lang->issue->typeList['storychanged'] = 'Story Change';
$lang->issue->typeList['storyremoved'] = 'Story Deleted';
$lang->issue->typeList['data']         = 'Data';

$lang->issue->resolutionList['resolved'] = 'Resolved';
$lang->issue->resolutionList['tostory']  = 'To Story';
$lang->issue->resolutionList['tobug']    = 'To Bug';
$lang->issue->resolutionList['torisk']   = 'To Risk';
$lang->issue->resolutionList['totask']   = 'To Task';

$lang->issue->statusList['unconfirmed'] = 'Unconfirmed';
$lang->issue->statusList['confirmed']   = 'Confirmed';
$lang->issue->statusList['resolved']    = 'Resolved';
$lang->issue->statusList['canceled']    = 'Canceled';
$lang->issue->statusList['closed']      = 'Closed';
$lang->issue->statusList['active']      = 'Active';

$lang->issue->resolveMethods = array();
$lang->issue->resolveMethods['resolved'] = 'Resolved';
$lang->issue->resolveMethods['totask']   = 'To Task';
$lang->issue->resolveMethods['tobug']    = 'To Bug';
$lang->issue->resolveMethods['tostory']  = 'To Story';
$lang->issue->resolveMethods['torisk']   = 'To Risk';

$lang->issue->confirmDelete = 'Do you want to delete this issue?';
$lang->issue->typeEmpty     = 'ID: %s Type cannot be empty.';
$lang->issue->titleEmpty    = 'ID: %s Title cannot be empty.';
$lang->issue->severityEmpty = 'ID: %s Severity cannot be empty.';

$lang->issue->logComments = array();
$lang->issue->logComments['totask']  = " created Task %s";
$lang->issue->logComments['tostory'] = " created Story %s";
$lang->issue->logComments['tobug']   = " created Bug %s" ;
$lang->issue->logComments['torisk']  = " created Risk %s";
