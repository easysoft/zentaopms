<?php
/**
 * The action module Japanese file of ZenTaoMS.
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
 * @version     $Id: en.php 1000 2010-08-03 01:49:25Z yuren_@126.com $
 * @link        http://www.zentao.net
 */
$lang->action->common   = 'ログ';
$lang->action->trash    = 'ごみ';
$lang->action->undelete = '復元';

$lang->action->objectType = 'オブジェクト';
$lang->action->objectID   = 'IDは';
$lang->action->objectName = '名';
$lang->action->actor      = '俳優';
$lang->action->date       = '日付';

$lang->action->objectTypes['product']     = '製品';
$lang->action->objectTypes['story']       = '話';
$lang->action->objectTypes['productplan'] = 'プラン';
$lang->action->objectTypes['release']     = 'リリース';
$lang->action->objectTypes['project']     = 'プロジェクト';
$lang->action->objectTypes['task']        = 'TASKを';
$lang->action->objectTypes['build']       = 'ビルド';
$lang->action->objectTypes['bug']         = 'バグ';
$lang->action->objectTypes['case']        = 'ケース';
$lang->action->objectTypes['testtask']    = 'テストタスク';
$lang->action->objectTypes['user']        = 'ユーザー';
$lang->action->objectTypes['doc']         = 'ドキュメント';
$lang->action->objectTypes['doclib']      = 'DocLib';

/* 用来描述操作历史记录.*/
$lang->action->desc->common      = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra       = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened      = '$date, Opened by <strong>$actor</strong>.';
$lang->action->desc->created     = '$date, Created by <strong>$actor</strong>.';
$lang->action->desc->changed     = '$date, Changed by <strong>$actor</strong>.';
$lang->action->desc->edited      = '$date, Edited by <strong>$actor</strong>.';
$lang->action->desc->closed      = '$date, Closed by <strong>$actor</strong>.';
$lang->action->desc->deleted     = '$date, Deleted by <strong>$actor</strong>.';
$lang->action->desc->deletedfile = '$date, Deleted file by <strong>$actor</strong>, the file is <strong><i>$extra</i></strong>';
$lang->action->desc->erased      = '$date, Erased by <strong>$actor</strong>.';
$lang->action->desc->undeleted   = '$date, Restored by <strong>$actor</strong>.';
$lang->action->desc->commented   = '$date, Commented by <strong>$actor</strong>.';
$lang->action->desc->activated   = '$date, Activated by <strong>$actor</strong>.';
$lang->action->desc->moved       = '$date, Moved by <strong>$actor</strong>, previouse is "$extra"';
$lang->action->desc->confirmed   = '$date, Confirmed by <strong>$actor</strong>, version is<strong>#$extra</strong>';
$lang->action->desc->started     = '$date, Started by <strong>$actor</strong>.';
$lang->action->desc->canceled    = '$date, Canceled by <strong>$actor</strong>.';
$lang->action->desc->finished    = '$date, Finished by <strong>$actor</strong>.';
$lang->action->desc->diff1       = 'Changed <strong><i>%s</i></strong>, old is "%s", new is "%s".<br />';
$lang->action->desc->diff2       = 'Changed <strong><i>%s</i></strong>, the diff is：<blockquote>%s</blockquote>';

/* 用来显示动态信息.*/
$lang->action->label->created             = '作成';
$lang->action->label->opened              = 'オープン';
$lang->action->label->changed             = '変更';
$lang->action->label->edited              = '編集';
$lang->action->label->closed              = '閉じた';
$lang->action->label->deleted             = '削除された';
$lang->action->label->deletedfile         = 'ファイルを削除';
$lang->action->label->erased              = '削除された';
$lang->action->label->undeleted           = '復元する';
$lang->action->label->commented           = 'コメント';
$lang->action->label->activated           = '活性化';
$lang->action->label->resolved            = '解決';
$lang->action->label->reviewed            = '見直し';
$lang->action->label->moved               = '恥ずかしい';
$lang->action->label->confirmed           = '、確認';
$lang->action->label->linked2plan         = 'リンクを計画する';
$lang->action->label->unlinkedfromplan    = 'プランからリンク解除';
$lang->action->label->linked2project      = 'リンクがプロジェクトに';
$lang->action->label->unlinkedfromproject = 'プロジェクトからunlik';
$lang->action->label->marked              = '編集';
$lang->action->label->started             = '開始';
$lang->action->label->canceled            = 'キャンセル';
$lang->action->label->finished            = '終えた';
$lang->action->label->login               = 'ログイン';
$lang->action->label->logout              = "ログアウト";

/* 用来生成相应对象的链接.*/
$lang->action->label->product     = 'product|product|view|productID=%s';
$lang->action->label->productplan = 'plan|productplan|view|productID=%s';
$lang->action->label->release     = 'release|release|view|productID=%s';
$lang->action->label->story       = 'story|story|view|storyID=%s';
$lang->action->label->project     = 'project|project|view|projectID=%s';
$lang->action->label->task        = 'task|task|view|taskID=%s';
$lang->action->label->build       = 'build|build|view|buildID=%s';
$lang->action->label->bug         = 'bug|bug|view|bugID=%s';
$lang->action->label->case        = 'case|testcase|view|caseID=%s';
$lang->action->label->testtask    = 'test task|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = 'doc library|doc|browse|libID=%s';
$lang->action->label->doc         = 'doc|doc|view|docID=%s';
$lang->action->label->user        = 'ユーザー';

$lang->action->label->space     ='';
