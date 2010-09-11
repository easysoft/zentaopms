<?php
/**
 * The bug module Japanese file of ZenTaoMS.
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
 * @package     bug
 * @version     $Id: en.php 1033 2010-08-07 02:12:20Z wwccss $
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common         = 'バグ';
$lang->bug->id             = 'IDは';
$lang->bug->product        = '製品';
$lang->bug->module         = 'モジュール';
$lang->bug->path           = 'パス';
$lang->bug->project        = 'プロジェクト';
$lang->bug->story          = 'ストーリー';
$lang->bug->storyVersion   = '小説版';
$lang->bug->task           = 'タスク';
$lang->bug->title          = 'タイトル';
$lang->bug->severity       = '重大度';
$lang->bug->severityAB     = 'のS';
$lang->bug->pri            = '優先順位';
$lang->bug->type           = 'タイプ';
$lang->bug->os             = 'OSの';
$lang->bug->hardware       = 'ハードウェア';
$lang->bug->browser        = 'ブラウザ';
$lang->bug->machine        = 'マシン';
$lang->bug->found          = 'どのように発見';
$lang->bug->steps          = '手順';
$lang->bug->status         = 'ステータス';
$lang->bug->mailto         = 'MAILTOが';
$lang->bug->openedBy       = 'でオープン';
$lang->bug->openedByAB     = 'オープン';
$lang->bug->openedDate     = 'オープン日';
$lang->bug->openedBuild    = 'オープンビルド';
$lang->bug->assignedTo     = '割り当て';
$lang->bug->assignedDate   = '割り当て日';
$lang->bug->resolvedBy     = '解決で';
$lang->bug->resolution     = '解像度';
$lang->bug->resolutionAB   = '解像度';
$lang->bug->resolvedBuild  = '解決ビルド';
$lang->bug->resolvedDate   = '解決日';
$lang->bug->closedBy       = 'で休館';
$lang->bug->closedDate     = 'クローズド日';
$lang->bug->duplicateBug   = '重複';
$lang->bug->lastEditedBy   = '編集で最後に';
$lang->bug->lastEditedDate = '最終編集日';
$lang->bug->linkBug        = '関連した';
$lang->bug->case           = 'ケース';
$lang->bug->files          = 'ファイル';
$lang->bug->keywords       = 'キーワード';
$lang->bug->lastEditedByAB   = '編集';
$lang->bug->lastEditedDateAB = '編集日';

/* 方法列表。*/
$lang->bug->index          = 'インデックス';
$lang->bug->create         = '作成バグ';
$lang->bug->edit           = '[編集]バグ';
$lang->bug->browse         = 'ブラウズバグ';
$lang->bug->view           = 'バグ情報';
$lang->bug->resolve        = '解決バグ';
$lang->bug->close          = '閉じるバグ';
$lang->bug->activate       = 'アクティブバグ';
$lang->bug->reportChart    = 'レポート';
$lang->bug->delete         = '削除バグ';
$lang->bug->saveTemplate   = '保存テンプレートを';
$lang->bug->deleteTemplate = '削除テンプレートを';
$lang->bug->customFields   = 'カスタムフィールド';
$lang->bug->restoreDefault = 'デフォルト';
$lang->bug->ajaxGetUserBugs    = 'APIは：私のバグ';
$lang->bug->ajaxGetModuleOwner = 'APIは：モジュールのデフォルトの所有者を取得する';
$lang->bug->confirmStoryChange = '確認ストーリーの変更';

/* 查询条件列表。*/
$lang->bug->selectProduct  = '製品を選択';
$lang->bug->byModule       = 'モジュールで';
$lang->bug->assignToMe     = '私に割り当てられた';
$lang->bug->openedByMe     = '私のオープン';
$lang->bug->resolvedByMe   = '私の解決';
$lang->bug->assignToNull   = '空の割り当て';
$lang->bug->longLifeBugs   = '長寿命';
$lang->bug->postponedBugs  = '延期';
$lang->bug->allBugs        = 'すべてのバグ';
$lang->bug->moduleBugs     = 'モジュールで';
$lang->bug->byQuery        = '検索';
$lang->bug->needConfirm    = 'ストーリーが変更されました';
$lang->bug->allProduct     = 'すべての製品';

/* 页面标签。*/
$lang->bug->lblProductAndModule         = '製品＆モジュール';
$lang->bug->lblProjectAndTask           = 'プロジェクト＆タスク';
$lang->bug->lblStory                    = 'ストーリー';
$lang->bug->lblTypeAndSeverity          = 'タイプと重大度';
$lang->bug->lblSystemBrowserAndHardware = 'OSの＆ブラウザ';
$lang->bug->lblAssignedTo               = 'に割り当て';
$lang->bug->lblMailto                   = 'MAILTOが';
$lang->bug->lblLastEdited               = '最後の編集';
$lang->bug->lblResolved                 = '解決';
$lang->bug->lblAllFields                = 'すべてのフィールド';
$lang->bug->lblCustomFields             = 'カスタムフィールド';

/* legend列表。*/
$lang->bug->legendBasicInfo   = '基本的な情報をもっと見る';
$lang->bug->legendMailto      = 'MAILTOが';
$lang->bug->legendAttatch     = 'ファイル';
$lang->bug->legendLinkBugs    = '関連バグ';
$lang->bug->legendPrjStoryTask= 'プロジェクトストーリー＆タスク';
$lang->bug->legendCases       = '関連ケース';
$lang->bug->legendSteps       = '手順';
$lang->bug->legendAction      = 'アクション';
$lang->bug->legendHistory     = '歴史';
$lang->bug->legendComment     = 'コメント';
$lang->bug->legendLife        = '';
$lang->bug->legendMisc        = 'その他';

/* 功能按钮。*/
$lang->bug->buttonCopy     = 'コピー';
$lang->bug->buttonEdit     = '[編集]';
$lang->bug->buttonActivate = 'アクティブ';
$lang->bug->buttonResolve  = '解決';
$lang->bug->buttonClose    = 'クローズ';
$lang->bug->buttonToList   = 'バック';

/* 交互提示。*/
$lang->bug->confirmChangeProduct = '変更製品は、タスクやストーリーを、プロジェクトを変更する場合はよろしいですか？';
$lang->bug->confirmDelete        = 'あなたはこのバグを？削除しますか';
$lang->bug->setTemplateTitle     = '入力してくださいタイトルテンプレート：';

/* 模板。*/
$lang->bug->tplStep        = "[手順]\n\n";
$lang->bug->tplResult      = "[結果]\n\n";
$lang->bug->tplExpect      = "[期待]\n\n";

/* 各个字段取值列表。*/
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[3] = '3';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']        = '';
$lang->bug->osList['all']     = 'すべて';
$lang->bug->osList['windows'] = 'Windowsの';
$lang->bug->osList['winxp']   = 'Windows XPの';
$lang->bug->osList['win7']    = 'Windows 7の';
$lang->bug->osList['vista']   = 'Windows Vistaの';
$lang->bug->osList['win2000'] = 'Windows 2000の';
$lang->bug->osList['winnt']   = 'Windows NTの';
$lang->bug->osList['win98']   = 'Windows 98の';
$lang->bug->osList['linux']   = 'リナックス';
$lang->bug->osList['freebsd'] = 'FreeBSDの';
$lang->bug->osList['unix']    = 'Unixの';
$lang->bug->osList['others']  = '他人';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = 'すべて';
$lang->bug->browserList['ie']       = 'IEの';
$lang->bug->browserList['ie6']      = 'IE6の';
$lang->bug->browserList['ie7']      = 'IE7の';
$lang->bug->browserList['ie8']      = 'IE8の';
$lang->bug->browserList['firefox']  = 'Firefoxの';
$lang->bug->browserList['firefox2'] = 'Firefox2';
$lang->bug->browserList['firefx3']  = 'Firefox3の';
$lang->bug->browserList['opera']    = 'オペラ';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['safari']   = 'サファリ';
$lang->bug->browserList['chrome']   = 'クロム';
$lang->bug->browserList['other']    = '他人';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'コードエラー';
$lang->bug->typeList['interface']    = 'インターフェイス';
$lang->bug->typeList['designchange'] = 'デザインの変更';
$lang->bug->typeList['newfeature']   = '新機能';
$lang->bug->typeList['designdefect'] = 'デザイン欠陥';
$lang->bug->typeList['config']       = '構成';
$lang->bug->typeList['install']      = 'インストール';
$lang->bug->typeList['security']     = 'セキュリティー';
$lang->bug->typeList['performance']  = 'パフォーマンス';
$lang->bug->typeList['standard']     = 'スタンダード';
$lang->bug->typeList['automation']   = '自動化';
$lang->bug->typeList['trackthings']  = 'トラッキング';
$lang->bug->typeList['Others']       = '他人';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'アクティブ';
$lang->bug->statusList['resolved'] = '解決';
$lang->bug->statusList['closed']   = 'クローズド';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'デザインで';
$lang->bug->resolutionList['duplicate']  = '重複';
$lang->bug->resolutionList['external']   = '外部';
$lang->bug->resolutionList['fixed']      = '固定';
$lang->bug->resolutionList['notrepro']   = 'まだ再現';
$lang->bug->resolutionList['postponed']  = '延期';
$lang->bug->resolutionList['willnotfix'] = "ウィル解決しない";

/* 统计报表。*/
$lang->bug->report->common        = 'レポート';
$lang->bug->report->select        = '選択';
$lang->bug->report->create        = '作成';
$lang->bug->report->selectAll     = 'すべて';
$lang->bug->report->selectReverse = '逆';

$lang->bug->report->charts['bugsPerProject']     = 'プロジェクトのバグ';
$lang->bug->report->charts['bugsPerModule']      = 'モジュールのバグ';
$lang->bug->report->charts['openedBugsPerDay']   = '1日あたりのオープンバグ';
$lang->bug->report->charts['resolvedBugsPerDay'] = '1日あたりの解決済みのバグ';
$lang->bug->report->charts['closedBugsPerDay']   = '1日あたりのクローズドバグ';
$lang->bug->report->charts['openedBugsPerUser']  = 'ユーザーごとのオープンバグ';
$lang->bug->report->charts['resolvedBugsPerUser']= 'ユーザーごとの解決済みのバグ';
$lang->bug->report->charts['closedBugsPerUser']  = 'ユーザーごとのクローズドバグ';
$lang->bug->report->charts['bugsPerSeverity']    = '重大度';
$lang->bug->report->charts['bugsPerResolution']  = '解像度';
$lang->bug->report->charts['bugsPerStatus']      = 'ステータス';
$lang->bug->report->charts['bugsPerType']        = 'タイプ';
//$lang->bug->report->charts['bugLiveDays']        = 'バグ处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'バグ处理步骤统计';

$lang->bug->report->options->swf                     = 'pie2d';
$lang->bug->report->options->width                   = 'オート';
$lang->bug->report->options->height                  = 300;
$lang->bug->report->options->graph->baseFontSize     = 12;
$lang->bug->report->options->graph->showNames        = 1;
$lang->bug->report->options->graph->formatNumber     = 1;
$lang->bug->report->options->graph->decimalPrecision = 0;
$lang->bug->report->options->graph->animation        = 0;
$lang->bug->report->options->graph->rotateNames      = 0;
$lang->bug->report->options->graph->yAxisName        = 'COUNTは';
$lang->bug->report->options->graph->pieRadius        = 100; // 饼图直径。
$lang->bug->report->options->graph->showColumnShadow = 0;   // 是否显示柱状图阴影。

$lang->bug->report->bugsPerProject->graph->xAxisName     = 'プロジェクト';
$lang->bug->report->bugsPerModule->graph->xAxisName      = 'モジュール';

$lang->bug->report->openedBugsPerDay->swf                = 'column2d';
$lang->bug->report->openedBugsPerDay->height             = 400;
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = '日付';
$lang->bug->report->openedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->resolvedBugsPerDay->swf              = 'column2d';
$lang->bug->report->resolvedBugsPerDay->height           = 400;
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日付';
$lang->bug->report->resolvedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->closedBugsPerDay->swf                = 'column2d';
$lang->bug->report->closedBugsPerDay->height             = 400;
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = '日付';
$lang->bug->report->closedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = 'ユーザー';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= 'ユーザー';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = 'ユーザー';

$lang->bug->report->bugsPerSeverity->graph->xAxisName    = '重大度';
$lang->bug->report->bugsPerResolution->graph->xAxisName  = '解像度';
$lang->bug->report->bugsPerStatus->graph->xAxisName      = 'ステータス';
$lang->bug->report->bugsPerType->graph->xAxisName        = 'タイプ';
$lang->bug->report->bugLiveDays->graph->xAxisName        = 'ライブ日';
$lang->bug->report->bugHistories->graph->xAxisName       = '履歴';

/* 操作记录。*/
$lang->bug->action->resolved = array('main' => '$date, Resolved by <strong>$actor</strong>, resolution is <strong>$extra</strong>.', 'extra' => $lang->bug->resolutionList);
