<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common    = 'Release';
$lang->release->create    = "Create";
$lang->release->edit      = "Edit";
$lang->release->linkStory = "Link Story";
$lang->release->linkBug   = "Link Bug";
$lang->release->delete    = "Delete";
$lang->release->deleted   = 'Deleted';
$lang->release->view      = "Info";
$lang->release->browse    = "Browse";
$lang->release->changeStatus     = "Change Status";
$lang->release->batchUnlink      = "Batch Unlink";
$lang->release->batchUnlinkStory = "Batch Unlink Story";
$lang->release->batchUnlinkBug   = "Batch unlink Bug";

$lang->release->confirmDelete      = "Do you want to delete this Release?";
$lang->release->confirmUnlinkStory = "Do you want to remove this Story?";
$lang->release->confirmUnlinkBug   = "Do you want to remove this Bug?";

$lang->release->basicInfo = 'Basic Info';

$lang->release->id                    = 'ID';
$lang->release->product               = $lang->productCommon;
$lang->release->build                 = 'Build';
$lang->release->name                  = 'Name';
$lang->release->date                  = 'Date';
$lang->release->desc                  = 'Description';
$lang->release->status                = 'Status';
$lang->release->last                  = 'Last Release';
$lang->release->unlinkStory           = 'Remove Story';
$lang->release->unlinkBug             = 'Remove Bug';
$lang->release->stories               = 'Finished Story';
$lang->release->bugs                  = 'Solved Bug';
$lang->release->generatedBugs         = 'Generated Bug';
$lang->release->finishStories         = 'Finished %s Story';
$lang->release->resolvedBugs          = 'Solved %s Bug';
$lang->release->createdBugs           = 'Generated %s Bug';
$lang->release->export                = 'Export as HTML';

$lang->release->filePath = 'Download : ';
$lang->release->scmPath  = 'SCM Path : ';

$lang->release->exportTypeList['all']     = 'All';
$lang->release->exportTypeList['story']   = 'Resolved Story';
$lang->release->exportTypeList['bug']     = 'Resolved Bug';
$lang->release->exportTypeList['leftbug'] = 'Generated Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = 'Normal';
$lang->release->statusList['terminate'] = 'Terminate';

$lang->release->changeStatusList['normal']    = 'Activate';
$lang->release->changeStatusList['terminate'] = 'Terminate';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
