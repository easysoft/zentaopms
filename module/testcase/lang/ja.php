<?php
/**
 * The testcase module Japanese file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->testcase->id             = 'IDは';
$lang->testcase->product        = '製品';
$lang->testcase->module         = 'モジュール';
$lang->testcase->story          = 'ストーリー';
$lang->testcase->storyVersion   = 'ストーリーバージョン';
$lang->testcase->title          = 'タイトル';
$lang->testcase->pri            = '優先順位';
$lang->testcase->type           = 'タイプ';
$lang->testcase->status         = 'ステータス';
$lang->testcase->steps          = '手順';
$lang->testcase->frequency      = '周波数';
$lang->testcase->order          = '注文';
$lang->testcase->openedBy       = 'によって開か';
$lang->testcase->openedDate     = 'オープン日';
$lang->testcase->lastEditedBy   = '編集によって最終更新';
$lang->testcase->lastEditedDate = '最終編集日';
$lang->testcase->version        = 'バージョン';
$lang->testcase->result         = '結果';
$lang->testcase->real           = 'レアル';
$lang->testcase->keywords       = 'キーワード';
$lang->testcase->files          = 'ファイル';
$lang->testcase->howRun         = 'どのように実行する';
$lang->testcase->scriptedBy     = 'どのように実行する';
$lang->testcase->scriptedDate   = 'スクリプト日';
$lang->testcase->scriptedStatus = 'スクリプトステータス';
$lang->testcase->scriptedLocation = 'スクリプトの場所';
$lang->testcase->linkCase         = '関連例';
$lang->testcase->stage            = 'ステージ';
$lang->testcase->lastEditedByAB   = '編集によって最終更新';
$lang->testcase->lastEditedDateAB = '最終編集日';
$lang->testcase->allProduct       = 'All product';
$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID     = 'IDは';
$lang->testcase->stepDesc   = 'ステップ';
$lang->testcase->stepExpect = '期待';

$lang->testcase->common         = 'ケース';
$lang->testcase->index          = "インデックス";
$lang->testcase->create         = "作成";
$lang->testcase->delete         = "削除";
$lang->testcase->view           = "情報";
$lang->testcase->edit           = "[編集]";
$lang->testcase->delete         = "削除";
$lang->testcase->browse         = "ブラウズ";
$lang->testcase->confirmStoryChange = '確認の物語の変化';

$lang->testcase->deleteStep     = 'ｘ';
$lang->testcase->insertBefore   = '＋↑';
$lang->testcase->insertAfter    = '＋↓';

$lang->testcase->selectProduct  = '製品を選択';
$lang->testcase->byModule       = 'モジュールで';
$lang->testcase->assignToMe     = '私に割り当てられた';
$lang->testcase->openedByMe     = '私がオープン';
$lang->testcase->allCases       = '全てのケース';
$lang->testcase->needConfirm    = 'ストーリーが変更されました';
$lang->testcase->moduleCases    = 'モジュールで';
$lang->testcase->bySearch       = '検索で';

$lang->testcase->lblProductAndModule         = '製品＆モジュール';
$lang->testcase->lblTypeAndPri               = 'タイプと優先順位';
$lang->testcase->lblSystemBrowserAndHardware = 'OSの＆ブラウザ';
$lang->testcase->lblAssignAndMail            = '割り当て＆削除記録';
$lang->testcase->lblStory                    = 'ストーリー';
$lang->testcase->lblLastEdited               = '最後の編集';

$lang->testcase->legendRelated     = '関連情報をもっと見る';
$lang->testcase->legendBasicInfo   = '基本的な情報をもっと見る';
$lang->testcase->legendMailto      = 'MAILTOが';
$lang->testcase->legendAttatch     = 'ファイル';
$lang->testcase->legendLinkBugs    = 'バグ';
$lang->testcase->legendOpenAndEdit = 'オープン＆編集';
$lang->testcase->legendStoryAndTask= 'ストーリー';
$lang->testcase->legendCases       = '関連例';
$lang->testcase->legendSteps       = '手順';
$lang->testcase->legendAction      = 'アクション';
$lang->testcase->legendHistory     = '歴史';
$lang->testcase->legendComment     = 'コメント';
$lang->testcase->legendProduct     = '製品＆モジュール';
$lang->testcase->legendVersion     = 'バージョン';

$lang->testcase->confirmDelete     = 'この場合はを削除してよろしいですか？';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = '機能';
$lang->testcase->typeList['performance'] = 'パフォーマンス';
$lang->testcase->typeList['config']      = '構成';
$lang->testcase->typeList['install']     = 'インストール';
$lang->testcase->typeList['security']    = 'セキュリティー';
$lang->testcase->typeList['other']       = 'その他';

$lang->testcase->stageList['']            = '';
$lang->testcase->stageList['unittest']    = 'ユニットテスト';
$lang->testcase->stageList['feature']     = '機能テスト';
$lang->testcase->stageList['intergrate']  = '統合テスト';
$lang->testcase->stageList['system']      = 'システムテスト';
$lang->testcase->stageList['smoke']       = '禁煙のテスト';
$lang->testcase->stageList['bvt']         = 'BVTテスト';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = 'ノーマル';
$lang->testcase->statusList['blocked']     = 'ブロック';
$lang->testcase->statusList['investigate'] = '調査';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = 'パス';
$lang->testcase->resultList['fail']    = '失敗';
$lang->testcase->resultList['blocked'] = 'ブロック';

$lang->testcase->buttonEdit     = '[編集]';
$lang->testcase->buttonToList   = 'バック';
