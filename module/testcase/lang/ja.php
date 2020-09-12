<?php
/**
 * The testcase module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->testcase->id = '番号';
$lang->testcase->product = "{$lang->productCommon}";
$lang->testcase->module = 'モジュール';
$lang->testcase->lib = 'ライブラリ';
$lang->testcase->branch = 'ブランチ/プラットフォーム';
$lang->testcase->moduleAB = 'モジュール';
$lang->testcase->story = '関連' . $lang->storyCommon;
$lang->testcase->storyVersion = "{$lang->storyCommon}版本";
$lang->testcase->color = '标题颜色';
$lang->testcase->order = '排序';
$lang->testcase->title = 'ケース名';
$lang->testcase->precondition = '前提条件';
$lang->testcase->pri = '優先度';
$lang->testcase->type = 'タイプ';
$lang->testcase->status = 'ステータス';
$lang->testcase->subStatus = '子状态';
$lang->testcase->steps = 'ステップ';
$lang->testcase->openedBy = '作成者';
$lang->testcase->openedDate = '作成日';
$lang->testcase->lastEditedBy = '最終更新者';
$lang->testcase->result = 'テスト結果';
$lang->testcase->real = '実際状況';
$lang->testcase->keywords = 'キーワード';
$lang->testcase->files = '添付';
$lang->testcase->linkCase = '関連ケース';
$lang->testcase->linkCases = 'ケース紐付け';
$lang->testcase->unlinkCase = '関連ケース除去';
$lang->testcase->stage = 'フェーズ';
$lang->testcase->reviewedBy = '承認者';
$lang->testcase->reviewedDate = '承認時間';
$lang->testcase->reviewResult = '承認結果';
$lang->testcase->reviewedByAB = '承認者';
$lang->testcase->reviewedDateAB = '日付';
$lang->testcase->reviewResultAB = '結果';
$lang->testcase->forceNotReview = '承認不要';
$lang->testcase->lastEditedByAB = '更新者';
$lang->testcase->lastEditedDateAB = '更新日';
$lang->testcase->lastEditedDate = '更新日';
$lang->testcase->version = 'ケースバージョン';
$lang->testcase->lastRunner = '実行者';
$lang->testcase->lastRunDate = '実行時間';
$lang->testcase->assignedTo = '担当者';
$lang->testcase->colorTag = '色タグ';
$lang->testcase->lastRunResult = '結果';
$lang->testcase->desc = 'ステップ';
$lang->testcase->xml = 'XML';
$lang->testcase->expect = '想定結果';
$lang->testcase->allProduct = "全ての{$lang->productCommon}";
$lang->testcase->fromBug = 'バグより';
$lang->testcase->toBug = 'バグ生成';
$lang->testcase->changed = '元ケース更新';
$lang->testcase->bugs = '発生バグ数';
$lang->testcase->bugsAB = 'B';
$lang->testcase->results = '結果数';
$lang->testcase->resultsAB = 'R';
$lang->testcase->stepNumber = 'ケースステップ数';
$lang->testcase->stepNumberAB = 'S';
$lang->testcase->createBug = 'バグ変更';
$lang->testcase->fromModule = 'モジュールより';
$lang->testcase->fromCase = 'ケースより';
$lang->testcase->sync = 'シンクロ';
$lang->testcase->ignore = '見落とす';
$lang->testcase->fromTesttask = 'テストタスクより';
$lang->testcase->fromCaselib = 'ケースライブラリより';
$lang->testcase->deleted = '是否删除';
$lang->case = $lang->testcase;

$lang->testcase->stepID = '番号';
$lang->testcase->stepDesc = 'ステップ';
$lang->testcase->stepExpect = '想定結果';
$lang->testcase->stepVersion = 'バージョン';

$lang->testcase->common = 'ケース';
$lang->testcase->index = 'ケース管理';
$lang->testcase->create = '新規';
$lang->testcase->batchCreate = '一括新規';
$lang->testcase->delete = '削除';
$lang->testcase->deleteAction = 'ケース削除';
$lang->testcase->view = 'ケース詳細';
$lang->testcase->review = '承認';
$lang->testcase->reviewAB = '承認';
$lang->testcase->batchReview = '一括承認';
$lang->testcase->edit = 'ケース編集';
$lang->testcase->batchEdit = '一括編集';
$lang->testcase->batchChangeModule = 'モジュール一括更新';
$lang->testcase->confirmLibcaseChange = 'ケースライブラリの更新をシンクロ';
$lang->testcase->ignoreLibcaseChange = 'ケースライブラリの更新を見落とす';
$lang->testcase->batchChangeBranch = 'ブランチ一括更新';
$lang->testcase->groupByStories = $lang->storyCommon . 'グルーピング';
$lang->testcase->batchDelete = '一括削除';
$lang->testcase->batchConfirmStoryChange = '変更一括確認';
$lang->testcase->batchCaseTypeChange = 'タイプ一括更新';
$lang->testcase->browse = 'ケースリスト';
$lang->testcase->groupCase = 'ケースグループ閲覧';
$lang->testcase->import = 'インポート';
$lang->testcase->importAction = 'ケースインポート';
$lang->testcase->fileImport = '导入CSV';
$lang->testcase->importFromLib = 'ケースライブラリからインポート';
$lang->testcase->showImport = 'インポート内容表示';
$lang->testcase->exportTemplet = 'テンプレートエクスポート';
$lang->testcase->export = 'データエクスポート';
$lang->testcase->exportAction = 'ケースエクスポート';
$lang->testcase->reportChart = 'レポート統計';
$lang->testcase->reportAction = 'ケースレポート統計';
$lang->testcase->confirmChange = 'ケース変動確認';
$lang->testcase->confirmStoryChange = $lang->storyCommon . '変動確認';
$lang->testcase->copy = 'ケースコピー';
$lang->testcase->group = 'グループ';
$lang->testcase->groupName = 'グループ名';
$lang->testcase->step = 'ステップ';
$lang->testcase->stepChild = '子ステップ';
$lang->testcase->viewAll = '全て表示';

$lang->testcase->new = '新規';

$lang->testcase->num = 'ケースレコード数：';

$lang->testcase->deleteStep = '削除';
$lang->testcase->insertBefore = '前に追加';
$lang->testcase->insertAfter = '後で追加';

$lang->testcase->assignToMe = '担当';
$lang->testcase->openedByMe = '新規';
$lang->testcase->allCases = '全て';
$lang->testcase->allTestcases = '全ケース';
$lang->testcase->needConfirm = $lang->storyCommon . '変更';
$lang->testcase->bySearch = '検索';
$lang->testcase->unexecuted = '実行待';

$lang->testcase->lblStory = '関連' . $lang->storyCommon;
$lang->testcase->lblLastEdited = '最終編集';
$lang->testcase->lblTypeValue = 'タイプオプションリスト';
$lang->testcase->lblStageValue = 'フェーズオプションリスト';
$lang->testcase->lblStatusValue = 'ステータスオプションリスト';

$lang->testcase->legendBasicInfo = '基本情報';
$lang->testcase->legendAttatch = '添付';
$lang->testcase->legendLinkBugs = '関連バグ';
$lang->testcase->legendOpenAndEdit = '編集・新規';
$lang->testcase->legendComment = '備考';

$lang->testcase->summary = '共に <strong>%s</strong> 個のケースがあり、<strong>%s</strong>個のケースが実行されました。';
$lang->testcase->confirmDelete = '当該テストケースを削除してもよろしいですか？';
$lang->testcase->confirmBatchDelete = 'これらテストケースを一括削除してもよろしいですか？';
$lang->testcase->ditto = '同上';
$lang->testcase->dittoNotice = '当該ケースは前のケースと同じプロダクトに属していません！';

$lang->testcase->reviewList[0] = 'いいえ';
$lang->testcase->reviewList[1] = 'はい';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = '3';
$lang->testcase->priList[1] = '1';
$lang->testcase->priList[2] = '2';
$lang->testcase->priList[4] = '4';

/* Define the types. */
$lang->testcase->typeList[''] = '';
$lang->testcase->typeList['feature'] = '機能テスト';
$lang->testcase->typeList['performance'] = '性能テスト';
$lang->testcase->typeList['config'] = '配置';
$lang->testcase->typeList['install'] = 'インストール配置';
$lang->testcase->typeList['security'] = 'セキュリティ';
$lang->testcase->typeList['interface'] = 'インタフェーステスト';
$lang->testcase->typeList['unit'] = '单元测试';
$lang->testcase->typeList['other'] = 'その他';

$lang->testcase->stageList[''] = '';
$lang->testcase->stageList['unittest'] = '単体テストフェーズ';
$lang->testcase->stageList['feature'] = '機能テストフェーズ';
$lang->testcase->stageList['intergrate'] = '結合テストフェーズ';
$lang->testcase->stageList['system'] = 'システムテストフェーズ';
$lang->testcase->stageList['smoke'] = '負荷テストフェーズ';
$lang->testcase->stageList['bvt'] = '受入テストフェーズ';

$lang->testcase->reviewResultList[''] = '';
$lang->testcase->reviewResultList['pass'] = '通過';
$lang->testcase->reviewResultList['clarify'] = '継続的にチューニング';

$lang->testcase->statusList[''] = '';
$lang->testcase->statusList['wait'] = '承認待ち';
$lang->testcase->statusList['normal'] = '正常';
$lang->testcase->statusList['blocked'] = 'ブロック';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a'] = '見落とす';
$lang->testcase->resultList['pass'] = '通過';
$lang->testcase->resultList['fail'] = '失敗';
$lang->testcase->resultList['blocked'] = 'ブロック';

$lang->testcase->buttonToList = '戻る';

$lang->testcase->errorEncode = 'データがありません。正しいコードを選択して再アップロードしてください！';
$lang->testcase->noFunction = 'iconvとmb_convert_encodingのトランスコード方法は存在しませんから、データが所望のコードに変換できません！';
$lang->testcase->noRequire = '%s行の“%s”は必須フィールドでありますので、入力してください';
$lang->testcase->noLibrary = 'パブリックライブラリがありませんので、先に作成してください！';
$lang->testcase->mustChooseResult = '承認結果を選択してください';
$lang->testcase->noModule = '<div>モジュール情報がありません</div><div>テストモジュールを更新してください</div>';
$lang->testcase->noCase = 'ケースがありません。';

$lang->testcase->searchStories = "入力による{$lang->storyCommon}検索";
$lang->testcase->selectLib = 'ライブラリを選択してください';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib = array('main' => '$date、 <strong>$actor</strong> よりケースライブラリ<strong>$extra</strong>からインポートしました。');
$lang->testcase->action->reviewed = array('main' => '$date、 <strong>$actor</strong> より承認結果を記録し、結果は <strong>$extra</strong>。', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all'] = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait'] = '承認待ち';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group'] = 'グループ表示';
$lang->testcase->featureBar['browse']['suite'] = 'スイート';
$lang->testcase->featureBar['browse']['zerocase'] = 'ケースを含まない' . $lang->storyCommon;
$lang->testcase->featureBar['groupcase'] = $lang->testcase->featureBar['browse'];
