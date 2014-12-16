<?php
/**
 * The build module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common    = "版本";
$lang->build->create    = "創建版本";
$lang->build->edit      = "編輯版本";
$lang->build->linkStory = "關聯需求";
$lang->build->linkBug   = "關聯Bug";
$lang->build->delete    = "刪除版本";
$lang->build->deleted   = "已刪除";
$lang->build->view      = "版本詳情";
$lang->build->ajaxGetProductBuilds = '介面:產品版本列表';
$lang->build->ajaxGetProjectBuilds = '介面:項目版本列表';
$lang->build->batchUnlinkStory     = '批量移除需求';
$lang->build->batchUnlinkBug       = '批量移除Bug';

$lang->build->confirmDelete      = "您確認刪除該版本嗎？";
$lang->build->confirmUnlinkStory = "您確認移除該需求嗎？";
$lang->build->confirmUnlinkBug   = "您確認移除該Bug嗎？";

$lang->build->basicInfo = '基本信息';

$lang->build->id        = 'ID';
$lang->build->product   = '產品';
$lang->build->project   = '項目';
$lang->build->name      = '名稱編號';
$lang->build->date      = '打包日期';
$lang->build->builder   = '構建者';
$lang->build->scmPath   = '原始碼地址';
$lang->build->filePath  = '下載地址';
$lang->build->desc      = '描述';
$lang->build->files     = '上傳發行包';
$lang->build->last      = '上個版本';
$lang->build->linkStoriesAndBugs = '關聯需求和Bug';
$lang->build->linkStories        = '相關需求';
$lang->build->unlinkStory        = '移除需求';
$lang->build->linkBugs           = '相關Bug';
$lang->build->unlinkBug          = '移除Bug';
$lang->build->stories            = '已關聯需求';
$lang->build->bugs               = '已關聯Bug';
$lang->build->generatedBugs      = '產生的Bug';

$lang->build->finishStories = ' 本次共完成 %s 個需求';
$lang->build->resolvedBugs  = ' 本次共解決 %s 個Bug';
$lang->build->createdBugs   = ' 本次共產生 %s 個Bug';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' 軟件原始碼庫，如Subversion、Git庫地址';
$lang->build->placeholder->filePath = ' 該版本軟件包下載存儲地址';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, 由 <strong>$actor</strong> 創建版本 <strong>$objectID</strong>。' . "\n";
