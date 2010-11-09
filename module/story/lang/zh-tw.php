<?php
/**
 * The story module zh-tw file of ZenTaoMS.
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
 * @package     story
 * @version     $Id: zh-tw.php 1068 2010-09-11 07:11:57Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->story->browse    = "需求列表";
$lang->story->create    = "新增需求";
$lang->story->change    = "變更";
$lang->story->changed   = '需求變更';
$lang->story->review    = '評審';
$lang->story->edit      = "編輯需求";
$lang->story->close     = '關閉';
$lang->story->activate  = '激活';
$lang->story->delete    = "刪除需求";
$lang->story->view      = "需求詳情";
$lang->story->tasks     = "相關任務";
$lang->story->taskCount = '任務數';
$lang->story->bugs      = "Bug";
$lang->story->linkStory = '關聯需求';

$lang->story->common         = '需求';
$lang->story->id             = '編號';
$lang->story->product        = '所屬產品';
$lang->story->module         = '所屬模組';
$lang->story->release        = '發佈計劃';
$lang->story->bug            = '相關bug';
$lang->story->title          = '需求名稱';
$lang->story->spec           = '需求描述';
$lang->story->type           = '需求類型 ';
$lang->story->pri            = '優先順序';
$lang->story->estimate       = '預計工時';
$lang->story->estimateAB     = '預計';
$lang->story->status         = '當前狀態';
$lang->story->stage          = '所處階段';
$lang->story->stageAB        = '階段';
$lang->story->mailto         = '抄送給';
$lang->story->openedBy       = '由誰創建';
$lang->story->openedDate     = '創建日期';
$lang->story->assignedTo     = '指派給';
$lang->story->assignedDate   = '指派日期';
$lang->story->lastEditedBy   = '最後修改';
$lang->story->lastEditedDate = '最後修改日期';
$lang->story->lastEdited     = '最後修改';
$lang->story->closedBy       = '由誰關閉';
$lang->story->closedDate     = '關閉日期';
$lang->story->closedReason   = '關閉原因';
$lang->story->rejectedReason = '拒絶原因';
$lang->story->reviewedBy     = '由誰評審';
$lang->story->reviewedDate   = '評審時間';
$lang->story->version        = '版本號';
$lang->story->project        = '所屬項目';
$lang->story->plan           = '所屬計劃';
$lang->story->planAB         = '計劃';
$lang->story->comment        = '備註';
$lang->story->linkStories    = '相關需求';
$lang->story->childStories   = '細分需求';
$lang->story->duplicateStory = '重複需求';
$lang->story->reviewResult   = '評審結果';
$lang->story->preVersion     = '之前版本';
$lang->story->keywords       = '關鍵詞';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = '草稿';
$lang->story->statusList['active']    = '激活';
$lang->story->statusList['closed']    = '已關閉';
$lang->story->statusList['changed']   = '已變更';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未開始';
$lang->story->stageList['planned']    = '已計劃';
$lang->story->stageList['projected']  = '已立項';
$lang->story->stageList['developing'] = '研發中';
$lang->story->stageList['developed']  = '研發完畢';
$lang->story->stageList['testing']    = '測試中';
$lang->story->stageList['tested']     = '測試完畢';
$lang->story->stageList['verified']   = '已驗收';
$lang->story->stageList['released']   = '已發佈';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '已完成';
$lang->story->reasonList['subdivided'] = '已細分';
$lang->story->reasonList['duplicate']  = '重複';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
$lang->story->reasonList['bydesign']   = '設計如此';
//$lang->story->reasonList['isbug']      = '是個Bug';

$lang->story->reviewResultList['']       = '';
$lang->story->reviewResultList['pass']   = '確認通過';
$lang->story->reviewResultList['revert'] = '撤銷變更';
$lang->story->reviewResultList['clarify']= '有待明確';
$lang->story->reviewResultList['reject'] = '拒絶';

$lang->story->priList[]   = '';
$lang->story->priList[3]  = '3';
$lang->story->priList[1]  = '1';
$lang->story->priList[2]  = '2';
$lang->story->priList[4]  = '4';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = '需求的一生';
$lang->story->legendRelated        = '相關信息';
$lang->story->legendMailto         = '抄送給';
$lang->story->legendAttatch        = '附件';
$lang->story->legendProjectAndTask = '項目任務';
$lang->story->legendLinkStories    = '相關需求';
$lang->story->legendChildStories   = '細分需求';
$lang->story->legendSpec           = '需求描述';
$lang->story->legendHistory        = '歷史記錄';
$lang->story->legendVersion        = '歷史版本';
$lang->story->legendMisc           = '其他相關';

$lang->story->lblChange            = '變更需求';
$lang->story->lblReview            = '評審需求';
$lang->story->lblActivate          = '激活需求';
$lang->story->lblClose             = '關閉需求';

$lang->story->affectedProjects     = '影響的項目';
$lang->story->affectedBugs         = '影響的Bug';
$lang->story->affectedCases        = '影響的用例';

$lang->story->specTemplate          = "建議參考的模板：作為一名<<i class='red'>某種類型的用戶</i>>，我希望<<i class='red'>達成某些目的</i>>，這樣可以<<i class='red'>開發的價值</i>>。";
$lang->story->needNotReview         = '不需要評審';
$lang->story->confirmDelete         = "您確認刪除該需求嗎?";
$lang->story->errorFormat           = '需求數據有誤';
$lang->story->errorEmptyTitle       = '標題不能為空';
$lang->story->mustChooseResult      = '必須選擇評審結果';
$lang->story->mustChoosePreVersion  = '必須選擇回溯的版本';
$lang->story->ajaxGetProjectStories = '介面:獲取項目需求列表';
$lang->story->ajaxGetProductStories = '介面:獲取產品需求列表';

$lang->story->action->reviewed            = array('main' => '$date, 由 <strong>$actor</strong> 記錄評審結果，結果為 <strong>$extra</strong>。', 'extra' => $lang->story->reviewResultList);
$lang->story->action->closed              = array('main' => '$date, 由 <strong>$actor</strong> 關閉，原因為 <strong>$extra</strong>。', 'extra' => $lang->story->reasonList);
$lang->story->action->linked2plan         = array('main' => '$date, 由 <strong>$actor</strong> 關聯到計劃 <strong>$extra</strong>。'); 
$lang->story->action->unlinkedfromplan    = array('main' => '$date, 由 <strong>$actor</strong> 從計劃 <strong>$extra</strong> 移除。'); 
$lang->story->action->linked2project      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到項目 <strong>$extra</strong>。'); 
$lang->story->action->unlinkedfromproject = array('main' => '$date, 由 <strong>$actor</strong> 從項目 <strong>$extra</strong> 移除。'); 
