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
$lang->productplan->common     = $lang->productCommon . 'Plan';
$lang->productplan->browse     = "View";
$lang->productplan->index      = "List";
$lang->productplan->create     = "Create";
$lang->productplan->edit       = "Edit";
$lang->productplan->delete     = "Delete";
$lang->productplan->view       = "Details";
$lang->productplan->bugSummary = "<strong>%s</strong> Bugs in total on this page.";
$lang->productplan->basicInfo  = 'Basic Info';
$lang->productplan->batchEdit  = 'Batch Edit';

$lang->productplan->batchUnlink      = "Batch Unlink";
$lang->productplan->linkStory        = "Linked Story";
$lang->productplan->unlinkStory      = "Unlink Story";
$lang->productplan->batchUnlinkStory = "Batch Unlink";
$lang->productplan->linkedStories    = 'link Story';
$lang->productplan->unlinkedStories  = 'UnLinkEd';

$lang->productplan->linkBug          = "Link Bug";
$lang->productplan->unlinkBug        = "Unlink Bug";
$lang->productplan->batchUnlinkBug   = "Batch Unlink";
$lang->productplan->linkedBugs       = 'Linked Bug';
$lang->productplan->unlinkedBugs     = 'Unlinked';

$lang->productplan->confirmDelete      = "Do you want to delete this Plan?";
$lang->productplan->confirmUnlinkStory = "Do you want to unlink this Story?";
$lang->productplan->confirmUnlinkBug   = "Do you want to unlink this Bug?";

$lang->productplan->id      = 'ID';
$lang->productplan->product = $lang->productCommon;
$lang->productplan->title   = 'Title';
$lang->productplan->desc    = 'Description';
$lang->productplan->begin   = 'Begin';
$lang->productplan->end     = 'End';
$lang->productplan->last    = 'Last plan';

$lang->productplan->endList[7]   = '1 Week';
$lang->productplan->endList[14]  = '1 Weeks';
$lang->productplan->endList[31]  = '1 Month';
$lang->productplan->endList[62]  = '2 Months';
$lang->productplan->endList[93]  = '3 Months';
$lang->productplan->endList[186] = '6 Months';
$lang->productplan->endList[365] = '1 Year';

$lang->productplan->errorNoTitle = 'ID %s title should not be empty.';
$lang->productplan->errorNoBegin = 'ID %s begin time should not be empty.';
$lang->productplan->errorNoEnd   = 'ID %s end time should not be empty.';
$lang->productplan->beginGeEnd   = 'ID %s begin time value should not be greater than t.';

$lang->productplan->featureBar['browse']['unexpired'] = 'On Time';
$lang->productplan->featureBar['browse']['all']       = 'All';
$lang->productplan->featureBar['browse']['overdue']   = 'Overdue';
