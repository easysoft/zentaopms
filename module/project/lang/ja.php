<?php
/**
 * The project module Japanese file of ZenTaoMS.
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
 * @package     project
 * @version     $Id: en.php 1014 2010-08-03 05:48:50Z wwccss $
 * @link        http://www.zentaoms.com
 */
/* 字段列表.*/
$lang->project->common       = 'プロジェクト';
$lang->project->id           = 'IDは';
$lang->project->company      = '会社';
$lang->project->iscat        = 'カテゴリとは';
$lang->project->type         = 'タイプ';
$lang->project->parent       = '親';
$lang->project->name         = '名';
$lang->project->code         = 'コード';
$lang->project->begin        = '開始';
$lang->project->end          = '終了';
$lang->project->status       = 'ステータス';
$lang->project->statge       = 'ステージ';
$lang->project->pri          = '優先順位';
$lang->project->desc         = '降順';
$lang->project->goal         = '目標';
$lang->project->openedBy     = '';
$lang->project->openedDate   = 'オープン日';
$lang->project->closedBy     = 'で休館';
$lang->project->closedDate   = 'クローズド日';
$lang->project->canceledBy   = 'による欠航';
$lang->project->canceledDate = 'キャンセル日';
$lang->project->PO           = '製品の所有者';
$lang->project->PM           = 'プロジェクトマネージャー';
$lang->project->QM           = '品質保証マネージャー';
$lang->project->acl          = 'アクセス制限';
$lang->project->teamname     = 'チーム名';
$lang->project->products     = '製品';
$lang->project->childProjects= '子プロジェクト';
$lang->project->whitelist    = 'ホワイトリスト';

$lang->team->account     = 'アカウント';
$lang->team->role        = '役割';
$lang->team->joinDate    = '参加日';
$lang->team->workingHour = '作業/日';

/* 字段取值列表.*/
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = 'HP。待っ';
$lang->project->statusList['doing'] = '行う';
$lang->project->statusList['done']  = '完了';

$lang->project->aclList['open']    = 'デフォルトでは（プロジェクトのモジュールの権限を持つ）このプロジェクトを訪れることができます';
$lang->project->aclList['private'] = '（のみプライベートチームメンバー訪れることができます）';
$lang->project->aclList['custom']  = 'ホワイト（チームメンバー、誰がホワイトリストたちグループ訪れることができます）に属している';

/* 方法列表.*/
$lang->project->index          = "インデックス";
$lang->project->task           = 'タスク';
$lang->project->groupTask      = 'グループ別に表示タスク';
$lang->project->story          = 'ストーリー';
$lang->project->bug            = 'バグ';
$lang->project->build          = 'ビルド';
$lang->project->burn           = 'Burndownチャート';
$lang->project->computeBurn    = '更新burndown';
$lang->project->burnData       = 'Burndownデータ';
$lang->project->team           = 'チーム';
$lang->project->doc            = '';
$lang->project->manageProducts = 'リンク製品';
$lang->project->linkStory      = 'リンクの話';
$lang->project->view           = "情報";
$lang->project->create         = "追加";
$lang->project->delete         = "削除";
$lang->project->browse         = "ブラウズ";
$lang->project->edit           = "";
$lang->project->manageMembers  = '管理チームのメンバー';
$lang->project->unlinkMember   = 'メンバーの削除';
$lang->project->unlinkStory    = '削除の話';
$lang->project->importTask     = 'インポートタスクが元に戻す';
$lang->project->ajaxGetProducts= "APIは：プロジェクトの製品を購入する";

/* 分组浏览.*/
$lang->project->listTask            = 'リスト';
$lang->project->groupTaskByStory    = '物語では';
$lang->project->groupTaskByStatus   = '状態で';
$lang->project->groupTaskByPri      = '優先順位で';
$lang->project->groupTaskByOwner    = '所有者によって';
$lang->project->groupTaskByEstimate = '見積もりでは';
$lang->project->groupTaskByConsumed = 'によって消費される';
$lang->project->groupTaskByLeft     = '左に';
$lang->project->groupTaskByType     = '種類別';
$lang->project->groupTaskByDeadline = 'BYの期限';
$lang->project->listTaskNeedConfrim = 'ストーリーが変更されました';

/* 页面提示.*/
$lang->project->selectProject  = "選択してプロジェクト";
$lang->project->beginAndEnd    = '開始と終了';
$lang->project->lblStats       = '統計';
$lang->project->stats          = 'Total estimate is『%s』hours,<br />confused『%s』hours<br />left『%s』hours';
$lang->project->oneLineStats   = "Project『%s』, code is『%s』, products is『%s』,begin from『%s』to 『%s』,total estimate『%s』hours,consumed『%s』hours,left『%s』hours.";
$lang->project->storySummary   = "Total 『%s』stories, estimate『%s』hours.";
$lang->project->wbs            = "ワイドバンドシステム";
$lang->project->largeBurnChart = 'プロフィール大';

/* 交互提示.*/
$lang->project->confirmDelete         = 'Are you sure to delete project [%s]?';
$lang->project->confirmUnlinkMember   = 'あなたはこのプロジェクトからユーザーを削除しますか？';
$lang->project->confirmUnlinkStory    = 'あなたはこのプロジェクトから話を削除しますか？';
$lang->project->errorNoLinkedProducts = 'ないリンクの製品は、リンクのページに移動されます。';
$lang->project->accessDenied          = 'アクセスは、このプロジェクトに否定した。';

/* 统计.*/
$lang->project->charts->burn->graph->caption      = "Burndownチャート";
$lang->project->charts->burn->graph->xAxisName    = "日付";
$lang->project->charts->burn->graph->yAxisName    = "アワー";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
