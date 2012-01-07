<?php
/**
 * The action module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-tw.php 2528 2012-01-04 00:33:25Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->action->common   = '系統日誌';
$lang->action->trash    = '資源回收筒';
$lang->action->undelete = '還原';

$lang->action->product    = '產品';
$lang->action->project    = '項目';
$lang->action->objectType = '對象類型';
$lang->action->objectID   = '對象ID';
$lang->action->objectName = '對象名稱';
$lang->action->actor      = '操作者';
$lang->action->action     = '動作';
$lang->action->actionID   = '記錄ID';
$lang->action->date       = '日期';
$lang->action->trashTips  = '提示：為了保證系統的完整性，禪道系統的刪除都是標記刪除。';

$lang->action->dynamic->today      = '今天';
$lang->action->dynamic->yesterday  = '昨天';
$lang->action->dynamic->twoDaysAgo = '前天';
$lang->action->dynamic->thisWeek   = '本週';
$lang->action->dynamic->lastWeek   = '上周';
$lang->action->dynamic->thisMonth  = '本月';
$lang->action->dynamic->lastMonth  = '上月';
$lang->action->dynamic->all        = '所有';
$lang->action->dynamic->search     = '搜索';

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
$lang->action->objectTypes['todo']        = 'TODO';

/* 用來描述操作歷史記錄。*/
$lang->action->desc->common       = '$date, <strong>$action</strong> by <strong>$actor</strong>' . "\n";
$lang->action->desc->extra        = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>' . "\n";
$lang->action->desc->opened       = '$date, 由 <strong>$actor</strong> 創建。' . "\n";
$lang->action->desc->created      = '$date, 由 <strong>$actor</strong> 創建。' . "\n";
$lang->action->desc->changed      = '$date, 由 <strong>$actor</strong> 變更。' . "\n";
$lang->action->desc->edited       = '$date, 由 <strong>$actor</strong> 編輯。' . "\n";
$lang->action->desc->assigned     = '$date, 由 <strong>$actor</strong> 指派給 <strong>$extra</strong>' . "\n";
$lang->action->desc->closed       = '$date, 由 <strong>$actor</strong> 關閉。' . "\n";
$lang->action->desc->deleted      = '$date, 由 <strong>$actor</strong> 刪除。' . "\n";
$lang->action->desc->deletedfile  = '$date, 由 <strong>$actor</strong> 刪除了附件：<strong><i>$extra</i></strong>' . "\n";
$lang->action->desc->editfile     = '$date, 由 <strong>$actor</strong> 編輯了附件：<strong><i>$extra</i></strong>' . "\n";
$lang->action->desc->erased       = '$date, 由 <strong>$actor</strong> 刪除。' . "\n";
$lang->action->desc->undeleted    = '$date, 由 <strong>$actor</strong> 還原。' . "\n";
$lang->action->desc->commented    = '$date, 由 <strong>$actor</strong> 添加備註。' . "\n";
$lang->action->desc->activated    = '$date, 由 <strong>$actor</strong> 激活。' . "\n";
$lang->action->desc->moved        = '$date, 由 <strong>$actor</strong> 移動，之前為 "$extra"' . "\n";
$lang->action->desc->confirmed    = '$date, 由 <strong>$actor</strong> 確認需求變動，最新版本為<strong>#$extra</strong>' . "\n";
$lang->action->desc->bugconfirmed = '$date, 由 <strong>$actor</strong> 確認Bug' . "\n";
$lang->action->desc->frombug      = '$date, 由 <strong>$actor</strong> Bug轉化而來，Bug編號為 <strong>$extra</strong>。';
$lang->action->desc->started      = '$date, 由 <strong>$actor</strong> 啟動。' . "\n";
$lang->action->desc->canceled     = '$date, 由 <strong>$actor</strong> 取消。' . "\n";
$lang->action->desc->svncommited  = '$date, 由 <strong>$actor</strong> 提交代碼，版本為<strong>#$extra</strong>' . "\n";
$lang->action->desc->finished     = '$date, 由 <strong>$actor</strong> 完成。' . "\n";
$lang->action->desc->diff1        = '修改了 <strong><i>%s</i></strong>，舊值為 "%s"，新值為 "%s"。<br />' . "\n";
$lang->action->desc->diff2        = '修改了 <strong><i>%s</i></strong>，區別為：' . "\n" . '<blockquote>%s</blockquote>' . "\n";
$lang->action->desc->diff3        = '將檔案名 %s 改為 %s ' . "\n";

/* 用來顯示動態信息。*/
$lang->action->label->created             = '創建了';
$lang->action->label->opened              = '創建了';
$lang->action->label->changed             = '變更了';
$lang->action->label->edited              = '編輯了';
$lang->action->label->assigned            = '指派了';
$lang->action->label->closed              = '關閉了';
$lang->action->label->deleted             = '刪除了';
$lang->action->label->deletedfile         = '刪除附件';
$lang->action->label->editfile            = '編輯附件';
$lang->action->label->erased              = '刪除了';
$lang->action->label->undeleted           = '還原了';
$lang->action->label->commented           = '評論了';
$lang->action->label->activated           = '激活了';
$lang->action->label->resolved            = '解決了';
$lang->action->label->reviewed            = '評審了';
$lang->action->label->moved               = '移動了';
$lang->action->label->confirmed           = '確認了需求，';
$lang->action->label->bugconfirmed        = '確認了';
$lang->action->label->tostory             = '轉需求';
$lang->action->label->frombug             = '轉需求';
$lang->action->label->totask              = '轉任務';
$lang->action->label->svncommited         = '提交代碼';
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
$lang->action->label->space       = '　';

/* Object type. */
$lang->action->search->objectTypeList['']            = '';    
$lang->action->search->objectTypeList['product']     = '產品';    
$lang->action->search->objectTypeList['project']     = '項目';    
$lang->action->search->objectTypeList['bug']         = 'Bug';
$lang->action->search->objectTypeList['case']        = '用例'; 
$lang->action->search->objectTypeList['story']       = '需求';  
$lang->action->search->objectTypeList['task']        = '任務'; 
$lang->action->search->objectTypeList['testtask']    = '測試任務';     
$lang->action->search->objectTypeList['user']        = '用戶'; 
$lang->action->search->objectTypeList['doc']         = '文檔';
$lang->action->search->objectTypeList['doclib']      = '文檔庫';   
$lang->action->search->objectTypeList['todo']        = 'TODO'; 
$lang->action->search->objectTypeList['build']       = 'Build';  
$lang->action->search->objectTypeList['release']     = '發佈';    
$lang->action->search->objectTypeList['productplan'] = '計劃';        

/* 用來在動態顯示中顯示動作 */
$lang->action->search->label['']                    = '';
$lang->action->search->label['created']             = $lang->action->label->created;            
$lang->action->search->label['opened']              = $lang->action->label->opened;             
$lang->action->search->label['changed']             = $lang->action->label->changed;            
$lang->action->search->label['edited']              = $lang->action->label->edited;             
$lang->action->search->label['assigned']            = $lang->action->label->assigned;           
$lang->action->search->label['closed']              = $lang->action->label->closed;             
$lang->action->search->label['deleted']             = $lang->action->label->deleted;            
$lang->action->search->label['deletedfile']         = $lang->action->label->deletedfile;        
$lang->action->search->label['editfile']            = $lang->action->label->editfile;           
$lang->action->search->label['erased']              = $lang->action->label->erased;             
$lang->action->search->label['undeleted']           = $lang->action->label->undeleted;          
$lang->action->search->label['commented']           = $lang->action->label->commented;          
$lang->action->search->label['activated']           = $lang->action->label->activated;          
$lang->action->search->label['resolved']            = $lang->action->label->resolved;           
$lang->action->search->label['reviewed']            = $lang->action->label->reviewed;           
$lang->action->search->label['moved']               = $lang->action->label->moved;              
$lang->action->search->label['confirmed']           = $lang->action->label->confirmed;   
$lang->action->search->label['bugconfirmed']        = $lang->action->label->bugconfirmed;       
$lang->action->search->label['tostory']             = $lang->action->label->tostory;            
$lang->action->search->label['frombug']             = $lang->action->label->frombug;            
$lang->action->search->label['totask']              = $lang->action->label->totask;             
$lang->action->search->label['svncommited']         = $lang->action->label->svncommited;        
$lang->action->search->label['linked2plan']         = $lang->action->label->linked2plan;        
$lang->action->search->label['unlinkedfromplan']    = $lang->action->label->unlinkedfromplan;   
$lang->action->search->label['linked2project']      = $lang->action->label->linked2project;     
$lang->action->search->label['unlinkedfromproject'] = $lang->action->label->unlinkedfromproject;
$lang->action->search->label['marked']              = $lang->action->label->marked;             
$lang->action->search->label['started']             = $lang->action->label->started;            
$lang->action->search->label['canceled']            = $lang->action->label->canceled;           
$lang->action->search->label['finished']            = $lang->action->label->finished;           
$lang->action->search->label['login']               = $lang->action->label->login;              
$lang->action->search->label['logout']              = $lang->action->label->logout;             
