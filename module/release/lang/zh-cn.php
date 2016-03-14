<?php
/**
 * The release module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common    = '发布';
$lang->release->create    = "创建发布";
$lang->release->edit      = "编辑发布";
$lang->release->linkStory = "关联需求";
$lang->release->linkBug   = "关联Bug";
$lang->release->delete    = "删除发布";
$lang->release->deleted   = '已删除';
$lang->release->view      = "发布详情";
$lang->release->browse    = "浏览发布";
$lang->release->changeStatus     = "修改状态";
$lang->release->batchUnlink      = "批量移除";
$lang->release->batchUnlinkStory = "批量移除需求";
$lang->release->batchUnlinkBug   = "批量移除Bug";

$lang->release->confirmDelete      = "您确认删除该release吗？";
$lang->release->confirmUnlinkStory = "您确认移除该需求吗？";
$lang->release->confirmUnlinkBug   = "您确认移除该Bug吗？";

$lang->release->basicInfo = '基本信息';

$lang->release->id                    = 'ID';
$lang->release->product               = $lang->productCommon;
$lang->release->build                 = '版本';
$lang->release->name                  = '发布名称';
$lang->release->date                  = '发布日期';
$lang->release->desc                  = '描述';
$lang->release->status                = '状态';
$lang->release->last                  = '上次发布';
$lang->release->unlinkStory           = '移除需求';
$lang->release->unlinkBug             = '移除Bug';
$lang->release->stories               = '完成的需求';
$lang->release->bugs                  = '解决的Bug';
$lang->release->generatedBugs         = '遗留的Bug';
$lang->release->finishStories         = '本次共完成 %s 个需求';
$lang->release->resolvedBugs          = '本次共解决 %s 个Bug';
$lang->release->createdBugs           = '本次共遗留 %s 个Bug';
$lang->release->export                = '导出HTML';

$lang->release->filePath = '下载地址：';
$lang->release->scmPath  = '版本库地址：';

$lang->release->exportTypeList['all']     = '所有';
$lang->release->exportTypeList['story']   = '需求';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = '遗留Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = '正常';
$lang->release->statusList['terminate'] = '停止维护';

$lang->release->changeStatusList['normal']    = '激活';
$lang->release->changeStatusList['terminate'] = '停止维护';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
