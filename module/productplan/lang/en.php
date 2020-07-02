<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: en.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . ' Plan';
$lang->productplan->browse     = "Plan List";
$lang->productplan->index      = "List";
$lang->productplan->create     = "Create Plan";
$lang->productplan->edit       = "Edit Plan";
$lang->productplan->delete     = "Delete Plan";
$lang->productplan->view       = "Plan Detail";
$lang->productplan->bugSummary = "Total <strong>%s</strong> Bugs on this page.";
$lang->productplan->basicInfo  = 'Basic Info';
$lang->productplan->batchEdit  = 'Batch Edit';

$lang->productplan->batchUnlink      = "Batch Unlink";
$lang->productplan->linkStory        = "Link Story";
$lang->productplan->unlinkStory      = "Unlink Story";
$lang->productplan->unlinkStoryAB    = "Unlink";
$lang->productplan->batchUnlinkStory = "Batch Unlink";
$lang->productplan->linkedStories    = 'Linked Stories';
$lang->productplan->unlinkedStories  = 'Unlinked Stories';
$lang->productplan->updateOrder      = 'Order';
$lang->productplan->createChildren   = "Create Child Plans";

$lang->productplan->linkBug          = "Link Bug";
$lang->productplan->unlinkBug        = "Unlink Bug";
$lang->productplan->batchUnlinkBug   = "Batch Unlink Bugs";
$lang->productplan->linkedBugs       = 'Linked Bugs';
$lang->productplan->unlinkedBugs     = 'Unlinked Bugs';
$lang->productplan->unexpired        = 'Unexpired Plans';
$lang->productplan->all              = 'All Plans';

$lang->productplan->confirmDelete      = "Do you want to delete this plan?";
$lang->productplan->confirmUnlinkStory = "Do you want to unlink this story?";
$lang->productplan->confirmUnlinkBug   = "Do you want to unlink this bug?";
$lang->productplan->noPlan             = 'No plans yet. ';
$lang->productplan->cannotDeleteParent = 'Cannot delete parent plan';

$lang->productplan->id         = 'ID';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = 'Platform/Branch';
$lang->productplan->title      = 'Title';
$lang->productplan->desc       = 'Description';
$lang->productplan->begin      = 'Begin';
$lang->productplan->end        = 'End';
$lang->productplan->last       = 'Last Plan';
$lang->productplan->future     = 'TBD';
$lang->productplan->stories    = 'Story';
$lang->productplan->bugs       = 'Bug';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->parent     = "Parent Plan";
$lang->productplan->parentAB   = "Parent";
$lang->productplan->children   = "Child Plan";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Order";
$lang->productplan->deleted    = "Deleted";

$lang->productplan->endList[7]    = '1 Week';
$lang->productplan->endList[14]   = '2 Weeks';
$lang->productplan->endList[31]   = '1 Month';
$lang->productplan->endList[62]   = '2 Months';
$lang->productplan->endList[93]   = '3 Months';
$lang->productplan->endList[186]  = '6 Months';
$lang->productplan->endList[365]  = '1 Year';

$lang->productplan->errorNoTitle = 'ID %s title should not be empty.';
$lang->productplan->errorNoBegin = 'ID %s begin time should not be empty.';
$lang->productplan->errorNoEnd   = 'ID %s end time should not be empty.';
$lang->productplan->beginGeEnd   = 'ID %s begin time should not be >= end time.';

$lang->productplan->featureBar['browse']['all']       = 'All';
$lang->productplan->featureBar['browse']['unexpired'] = 'Unexpired';
$lang->productplan->featureBar['browse']['overdue']   = 'Expired';
