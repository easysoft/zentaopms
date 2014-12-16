<?php
/**
 * The dept module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->dept->common      = '部門結構';
$lang->dept->add         = "添加";
$lang->dept->addChild    = "添加下級部門";
$lang->dept->manageChild = "下級部門";
$lang->dept->edit        = "編輯部門";
$lang->dept->delete      = "刪除部門";
$lang->dept->parent      = "上級部門";
$lang->dept->manager     = "負責人";
$lang->dept->name        = "部門名稱";
$lang->dept->browse      = "部門維護";
$lang->dept->manage      = "維護部門結構";
$lang->dept->updateOrder = "更新排序";
$lang->dept->users       = "成員列表";

$lang->dept->saveButton    = " 保存 (S) ";
$lang->dept->confirmDelete = " 您確定刪除該部門嗎？";
$lang->dept->successSave   = " 修改成功。";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = '該部門有子部門，不能刪除！';
$lang->dept->error->hasUsers = '該部門有職員，不能刪除！';
