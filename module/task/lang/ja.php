<?php
/**
 * The task module Japanese file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentaoms.com
 */
$lang->task->index     = "インデックス";
$lang->task->create    = "作成";
$lang->task->import    = "インポート元に戻す";
$lang->task->edit      = "更新";
$lang->task->delete    = "削除";
$lang->task->view      = "情報";
$lang->task->logEfforts= "取り組み";
$lang->task->start     = "スタート";
$lang->task->complete  = "完全な";
$lang->task->close     = "クローズ";
$lang->task->cancel    = "キャンセル";
$lang->task->activate  = "アクティブ";
$lang->task->confirmStoryChange = "確認の物語の変化";

$lang->task->common       = 'タスク';
$lang->task->id           = 'IDは';
$lang->task->project      = 'プロジェクト';
$lang->task->story        = 'ストーリー';
$lang->task->storyVersion = 'バージョン';
$lang->task->name         = '名';
$lang->task->type         = 'タイプ';
$lang->task->pri          = 'ぷり';
$lang->task->owner        = '所有者';
$lang->task->mailto       = 'MAILTOが';
$lang->task->estimate     = '見積もり';
$lang->task->estimateAB   = '見積もり';
$lang->task->left         = '左';
$lang->task->leftAB       = '左';
$lang->task->consumed     = '消費';
$lang->task->consumedAB   = '消費';
$lang->task->deadline     = '締め切り';
$lang->task->deadlineAB   = '締め切り';
$lang->task->status       = 'ステータス';
$lang->task->desc         = '降順';
$lang->task->statusCustom = 'ステータス注文';

$lang->task->statusList['wait']    = 'ステータス注文';
$lang->task->statusList['doing']   = '行う';
$lang->task->statusList['done']    = '完了';
$lang->task->statusList['cancel']  = 'キャンセル';

$lang->task->typeList[''] = '';
$lang->task->typeList['design'] = 'デザイン';
$lang->task->typeList['devel']  = 'develを';
$lang->task->typeList['test']   = 'テスト';
$lang->task->typeList['study']  = '研究';
$lang->task->typeList['discuss']= '論議';
$lang->task->typeList['ui']     = 'のUI';
$lang->task->typeList['affair'] = '不倫';
$lang->task->typeList['misc']   = 'その他';

$lang->task->priList[0] = '';
$lang->task->priList[3]  = '3';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[4]  = '4';

$lang->task->afterChoices['continueAdding'] = '続きは、この物語のためのタスクを追加する';
$lang->task->afterChoices['toTastList']     = 'するには、タスクリスト';
$lang->task->afterChoices['toStoryList']    = 'にストーリーリスト';

$lang->task->buttonEdit       = '[編集]';
$lang->task->buttonClose      = 'クローズ';
$lang->task->buttonCancel     = 'キャンセル';
$lang->task->buttonActivate   = 'アクティブ';
$lang->task->buttonLogEfforts = '取り組み';
$lang->task->buttonDelete     = '削除';
$lang->task->buttonBackToList = 'バック';
$lang->task->buttonStart      = 'スタート';
$lang->task->buttonDone       = '完了';

$lang->task->legendBasic  = '基本的な情報をもっと見る';
$lang->task->legendEffort = '努力';
$lang->task->legendDesc   = '降順';
$lang->task->legendAction = 'アクション';

$lang->task->ajaxGetUserTasks    = "APIは：私のタスク";
$lang->task->ajaxGetProjectTasks = "APIは：プロジェクトのタスク";
$lang->task->confirmDelete       = "あなたは、このタスクを削除しますか？";
$lang->task->copyStoryTitle      = "ストーリーと同じ";
$lang->task->afterSubmit         = "作成後";
$lang->task->successSaved        = "成功を保存";
$lang->task->delayWarning        = " <strong class='delayed f-14px'> Postponed %s days </strong>";
