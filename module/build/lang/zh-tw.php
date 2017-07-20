<?php
/**
 * The build module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common       = "版本";
$lang->build->create       = "創建版本";
$lang->build->edit         = "編輯版本";
$lang->build->linkStory    = "關聯需求";
$lang->build->linked2build = "关联關聯版本";
$lang->build->linkBug      = "關聯Bug";
$lang->build->delete       = "刪除版本";
$lang->build->deleted      = "已刪除";
$lang->build->view         = "版本詳情";
$lang->build->batchUnlink          = '批量移除';
$lang->build->batchUnlinkStory     = '批量移除需求';
$lang->build->batchUnlinkBug       = '批量移除Bug';

$lang->build->confirmDelete      = "您確認刪除該版本嗎？";
$lang->build->confirmUnlinkStory = "您確認移除該需求嗎？";
$lang->build->confirmUnlinkBug   = "您確認移除該Bug嗎？";

$lang->build->basicInfo = '基本信息';

$lang->build->id        = 'ID';
$lang->build->product   = $lang->productCommon;
$lang->build->project   = '所屬' . $lang->projectCommon;
$lang->build->name      = '名稱編號';
$lang->build->date      = '打包日期';
$lang->build->builder   = '構建者';
$lang->build->scmPath   = '原始碼地址';
$lang->build->filePath  = '下載地址';
$lang->build->desc      = '描述';
$lang->build->files     = '上傳發行包';
$lang->build->last      = '上個版本';
$lang->build->unlinkStory        = '移除需求';
$lang->build->unlinkBug          = '移除Bug';
$lang->build->stories            = '完成的需求';
$lang->build->bugs               = '解決的Bug';
$lang->build->generatedBugs      = '產生的Bug';
$lang->build->noProduct          = " <span style='color:red'>該{$lang->projectCommon}沒有關聯{$lang->productCommon}，無法創建版本，請先<a href='%s'>關聯{$lang->productCommon}</a></span>";

$lang->build->finishStories = ' 本次共完成 %s 個需求';
$lang->build->resolvedBugs  = ' 本次共解決 %s 個Bug';
$lang->build->createdBugs   = ' 本次共產生 %s 個Bug';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' 軟件原始碼庫，如Subversion、Git庫地址';
$lang->build->placeholder->filePath = ' 該版本軟件包下載存儲地址';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, 由 <strong>$actor</strong> 創建版本 <strong>$extra</strong>。' . "\n";
