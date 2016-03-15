<?php
/**
 * The release module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common    = '發佈';
$lang->release->create    = "創建發佈";
$lang->release->edit      = "編輯發佈";
$lang->release->linkStory = "關聯需求";
$lang->release->linkBug   = "關聯Bug";
$lang->release->delete    = "刪除發佈";
$lang->release->deleted   = '已刪除';
$lang->release->view      = "發佈詳情";
$lang->release->browse    = "瀏覽發佈";
$lang->release->changeStatus     = "修改狀態";
$lang->release->batchUnlink      = "批量移除";
$lang->release->batchUnlinkStory = "批量移除需求";
$lang->release->batchUnlinkBug   = "批量移除Bug";

$lang->release->confirmDelete      = "您確認刪除該release嗎？";
$lang->release->confirmUnlinkStory = "您確認移除該需求嗎？";
$lang->release->confirmUnlinkBug   = "您確認移除該Bug嗎？";

$lang->release->basicInfo = '基本信息';

$lang->release->id                    = 'ID';
$lang->release->product               = $lang->productCommon;
$lang->release->build                 = '版本';
$lang->release->name                  = '發佈名稱';
$lang->release->date                  = '發佈日期';
$lang->release->desc                  = '描述';
$lang->release->status                = '狀態';
$lang->release->last                  = '上次發佈';
$lang->release->unlinkStory           = '移除需求';
$lang->release->unlinkBug             = '移除Bug';
$lang->release->stories               = '完成的需求';
$lang->release->bugs                  = '解決的Bug';
$lang->release->generatedBugs         = '遺留的Bug';
$lang->release->finishStories         = '本次共完成 %s 個需求';
$lang->release->resolvedBugs          = '本次共解決 %s 個Bug';
$lang->release->createdBugs           = '本次共遺留 %s 個Bug';
$lang->release->export                = '導出HTML';

$lang->release->filePath = '下載地址：';
$lang->release->scmPath  = '版本庫地址：';

$lang->release->exportTypeList['all']     = '所有';
$lang->release->exportTypeList['story']   = '需求';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = '遺留Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = '正常';
$lang->release->statusList['terminate'] = '停止維護';

$lang->release->changeStatusList['normal']    = '激活';
$lang->release->changeStatusList['terminate'] = '停止維護';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
