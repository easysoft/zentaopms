<?php
/**
 * The action module zh-tw file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-tw.php 1111 2010-09-14 03:16:08Z yuren_@126.com $
 * @link        http://www.zentao.net
 */
$lang->action->common   = '系統日誌';
$lang->action->trash    = '資源回收筒';
$lang->action->undelete = '還原';

$lang->action->objectType = '對象類型';
$lang->action->objectID   = '對象ID';
$lang->action->objectName = '對象名稱';
$lang->action->actor      = '操作者';
$lang->action->date       = '日期';

$lang->action->objectTypes['product']     = '產品';
$lang->action->objectTypes['story']       = '需求';
$lang->action->objectTypes['productplan'] = '產品計劃';
$lang->action->objectTypes['release']     = '發佈';
$lang->action->objectTypes['project']     = '項目';
$lang->action->objectTypes['task']        = '任務';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = '用例';
$lang->action->objectTypes['testtask']    = '測試任務';
$lang->action->objectTypes['user']        = '用戶';
$lang->action->objectTypes['doc']         = '文檔';
$lang->action->objectTypes['doclib']      = '文檔庫';

/* 用來描述操作歷史記錄。*/
$lang->action->desc->common      = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra       = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened      = '$date, 由 <strong>$actor</strong> 創建。';
$lang->action->desc->created     = '$date, 由 <strong>$actor</strong> 創建。';
$lang->action->desc->changed     = '$date, 由 <strong>$actor</strong> 變更。';
$lang->action->desc->edited      = '$date, 由 <strong>$actor</strong> 編輯。';
$lang->action->desc->closed      = '$date, 由 <strong>$actor</strong> 關閉。';
$lang->action->desc->deleted     = '$date, 由 <strong>$actor</strong> 刪除。';
$lang->action->desc->deletedfile = '$date, 由 <strong>$actor</strong> 刪除了附件：<strong><i>$extra</i></strong>';
$lang->action->desc->erased      = '$date, 由 <strong>$actor</strong> 刪除。';
$lang->action->desc->undeleted   = '$date, 由 <strong>$actor</strong> 還原。';
$lang->action->desc->commented   = '$date, 由 <strong>$actor</strong> 發表評論。';
$lang->action->desc->activated   = '$date, 由 <strong>$actor</strong> 激活。';
$lang->action->desc->moved       = '$date, 由 <strong>$actor</strong> 移動，之前為 "$extra"';
$lang->action->desc->confirmed   = '$date, 由 <strong>$actor</strong> 確認需求變動，最新版本為<strong>#$extra</strong>';
$lang->action->desc->started     = '$date, 由 <strong>$actor</strong> 啟動。';
$lang->action->desc->canceled    = '$date, 由 <strong>$actor</strong> 取消。';
$lang->action->desc->finished    = '$date, 由 <strong>$actor</strong> 完成。';
$lang->action->desc->diff1       = '修改了 <strong><i>%s</i></strong>，舊值為 "%s"，新值為 "%s"。<br />';
$lang->action->desc->diff2       = '修改了 <strong><i>%s</i></strong>，區別為：<blockquote>%s</blockquote>';

/* 用來顯示動態信息。*/
$lang->action->label->created             = '創建了';
$lang->action->label->opened              = '創建了';
$lang->action->label->changed             = '變更了';
$lang->action->label->edited              = '編輯了';
$lang->action->label->closed              = '關閉了';
$lang->action->label->deleted             = '刪除了';
$lang->action->label->deletedfile         = '刪除附件';
$lang->action->label->erased              = '刪除了';
$lang->action->label->undeleted           = '還原了';
$lang->action->label->commented           = '評論了';
$lang->action->label->activated           = '激活了';
$lang->action->label->resolved            = '解決了';
$lang->action->label->reviewed            = '評審了';
$lang->action->label->moved               = '移動了';
$lang->action->label->confirmed           = '確認了需求，';
$lang->action->label->linked2plan         = '關聯計劃';
$lang->action->label->unlinkedfromplan    = '移除計劃';
$lang->action->label->linked2project      = '關聯項目';
$lang->action->label->unlinkedfromproject = '移除項目';
$lang->action->label->marked              = '編輯了';
$lang->action->label->started             = '開始了';
$lang->action->label->canceled            = '取消了';
$lang->action->label->finished            = '完成了';
$lang->action->label->login               = '登錄系統';
$lang->action->label->logout              = "退出登錄";

/* 用來生成相應對象的連結。*/
$lang->action->label->product     = '產品|product|view|productID=%s';
$lang->action->label->productplan = '計劃|productplan|view|productID=%s';
$lang->action->label->release     = '發佈|release|view|productID=%s';
$lang->action->label->story       = '需求|story|view|storyID=%s';
$lang->action->label->project     = '項目|project|view|projectID=%s';
$lang->action->label->task        = '任務|task|view|taskID=%s';
$lang->action->label->build       = 'Build|build|view|buildID=%s';
$lang->action->label->bug         = 'Bug|bug|view|bugID=%s';
$lang->action->label->case        = '用例|testcase|view|caseID=%s';
$lang->action->label->testtask    = '測試任務|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = '文檔庫|doc|browse|libID=%s';
$lang->action->label->doc         = '文檔|doc|view|docID=%s';
$lang->action->label->user        = '用戶';

$lang->action->label->space     = '　';
