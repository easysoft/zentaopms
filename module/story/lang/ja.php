<?php
/**
 * The story module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->story->create = '新規';
$lang->story->batchCreate = '一括新規';
$lang->story->change = '変更';
$lang->story->changeAction = $lang->storyCommon . '変更';
$lang->story->changed = $lang->storyCommon . '変更';
$lang->story->assignTo = '担当者';
$lang->story->assignAction = '担当者追加';
$lang->story->review = '承認';
$lang->story->reviewAction = $lang->storyCommon . '承認';
$lang->story->needReview = '承認必要';
$lang->story->batchReview = '一括承認';
$lang->story->edit = '編集';
$lang->story->batchEdit = '一括編集';
$lang->story->subdivide = '細分';
$lang->story->subdivideAction = $lang->storyCommon . '細分';
$lang->story->splitRequirent = '拆分';
$lang->story->close = 'クローズ';
$lang->story->closeAction = $lang->storyCommon . 'クローズ';
$lang->story->batchClose = '一括クローズ';
$lang->story->activate = 'アクティブ';
$lang->story->activateAction = $lang->storyCommon . 'アクティブ';
$lang->story->delete = '削除';
$lang->story->deleteAction = $lang->storyCommon . '削除';
$lang->story->view = $lang->storyCommon . '詳細';
$lang->story->setting = '設定';
$lang->story->tasks = '関連タスク';
$lang->story->bugs = '関連バグ';
$lang->story->cases = '関連ケース';
$lang->story->taskCount = 'タスク数';
$lang->story->bugCount = 'バグ数';
$lang->story->caseCount = 'ケース数';
$lang->story->taskCountAB = 'T';
$lang->story->bugCountAB = 'B';
$lang->story->caseCountAB = 'C';
$lang->story->linkStory = $lang->storyCommon . '紐付け';
$lang->story->unlinkStory = "関連{$lang->storyCommon}除去";
$lang->story->export = 'データエクスポート';
$lang->story->exportAction = $lang->storyCommon . 'エクスポート';
$lang->story->zeroCase = 'ケースを含まない' . $lang->storyCommon;
$lang->story->zeroTask = "タスクない{$lang->storyCommon}表示";
$lang->story->reportChart = 'レポート統計';
$lang->story->reportAction = 'レポート統計';
$lang->story->copyTitle = "同じ{$lang->storyCommon}名";
$lang->story->batchChangePlan = 'プラン一括更新';
$lang->story->batchChangeBranch = 'ブランチ一括更新';
$lang->story->batchChangeStage = 'フェーズ一括更新';
$lang->story->batchAssignTo = '担当者一括追加';
$lang->story->batchChangeModule = 'モジュール一括更新';
$lang->story->viewAll = '全て表示';
$lang->story->skipStory = '需求：%s 为父需求，将不会被关闭。';
$lang->story->closedStory = '需求：%s 已关闭，将不会被关闭。';

$lang->story->common = $lang->storyCommon;
$lang->story->id = '番号';
$lang->story->parent = '父需求';
$lang->story->product = "{$lang->productCommon}";
$lang->story->branch = 'ブランチ/プラットフォーム';
$lang->story->module = 'モジュール';
$lang->story->moduleAB = 'モジュール';
$lang->story->source = 'ソース';
$lang->story->sourceNote = 'ソース備考';
$lang->story->fromBug = 'バグより';
$lang->story->title = $lang->storyCommon . '名';
$lang->story->type = "{$lang->storyCommon}类型";
$lang->story->color = '标题颜色';
$lang->story->toBug = '转Bug';
$lang->story->spec = '説明';
$lang->story->assign = '担当者';
$lang->story->verify = '終了条件';
$lang->story->pri = '優先度';
$lang->story->estimate = '計画時間';
$lang->story->estimateAB = '時間';
$lang->story->hour = '/h';
$lang->story->status = 'ステータス';
$lang->story->subStatus = '子状态';
$lang->story->stage = 'フェーズ';
$lang->story->stageAB = 'フェーズ';
$lang->story->stagedBy = 'フェーズの設定者';
$lang->story->mailto = 'CC';
$lang->story->openedBy = '作成者';
$lang->story->openedDate = '作成日';
$lang->story->assignedTo = '担当者';
$lang->story->assignedDate = '担当者決定日';
$lang->story->lastEditedBy = '最終更新';
$lang->story->lastEditedDate = '最終更新日';
$lang->story->closedBy = 'クローズ';
$lang->story->closedDate = 'クローズ日';
$lang->story->closedReason = 'クローズ原因';
$lang->story->rejectedReason = '拒否原因';
$lang->story->reviewedBy = '承認者';
$lang->story->reviewedDate = '承認時間';
$lang->story->version = 'バージョン番号';
$lang->story->plan = 'プラン';
$lang->story->planAB = 'プラン';
$lang->story->comment = '備考';
$lang->story->children = "子{$lang->storyCommon}";
$lang->story->childrenAB = '子';
$lang->story->linkStories = '関連' . $lang->storyCommon;
$lang->story->childStories = '細分' . $lang->storyCommon;
$lang->story->duplicateStory = '重複' . $lang->storyCommon;
$lang->story->reviewResult = '承認結果';
$lang->story->preVersion = '前バージョン';
$lang->story->keywords = 'キーワード';
$lang->story->newStory = $lang->storyCommon . '追加';
$lang->story->colorTag = '色タグ';
$lang->story->files = '添付';
$lang->story->copy = $lang->storyCommon . 'コピー';
$lang->story->total = '全' . $lang->storyCommon;
$lang->story->allStories = '全' . $lang->storyCommon;
$lang->story->unclosed = 'クローズ待ち';
$lang->story->deleted = '削除';
$lang->story->released = "リリース済{$lang->storyCommon}数";

$lang->story->ditto = '同上';
$lang->story->dittoNotice = "当該{$lang->storyCommon}と前の{$lang->storyCommon}は同じプロダクトに属していません！";

$lang->story->needNotReviewList[0] = '需要评审';
$lang->story->needNotReviewList[1] = '不需要评审';

$lang->story->useList[0] = '利用しない';
$lang->story->useList[1] = '利用';

$lang->story->statusList[''] = '';
$lang->story->statusList['draft'] = '下書き';
$lang->story->statusList['active'] = 'アクティブ';
$lang->story->statusList['closed'] = 'クローズ';
$lang->story->statusList['changed'] = '変更';

$lang->story->stageList[''] = '';
$lang->story->stageList['wait'] = '待機中';
$lang->story->stageList['planned'] = '計画済';
$lang->story->stageList['projected'] = '立案済';
$lang->story->stageList['developing'] = '開発中';
$lang->story->stageList['developed'] = '開発完了';
$lang->story->stageList['testing'] = 'テスト中';
$lang->story->stageList['tested'] = 'テスト完了';
$lang->story->stageList['verified'] = '検収';
$lang->story->stageList['released'] = 'リリース済';
$lang->story->stageList['closed'] = 'クローズ';

$lang->story->reasonList[''] = '';
$lang->story->reasonList['done'] = '完了';
$lang->story->reasonList['subdivided'] = '細分';
$lang->story->reasonList['duplicate'] = '重複';
$lang->story->reasonList['postponed'] = '延期';
$lang->story->reasonList['willnotdo'] = 'しない';
$lang->story->reasonList['cancel'] = 'キャンセル';
$lang->story->reasonList['bydesign'] = '仕様通り';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList[''] = '';
$lang->story->reviewResultList['pass'] = '通過';
$lang->story->reviewResultList['revert'] = '変更取り消し';
$lang->story->reviewResultList['clarify'] = '不明瞭';
$lang->story->reviewResultList['reject'] = '拒否';

$lang->story->reviewList[0] = 'いいえ';
$lang->story->reviewList[1] = 'はい';

$lang->story->sourceList[''] = '';
$lang->story->sourceList['customer'] = '客先';
$lang->story->sourceList['user'] = 'ユーザ';
$lang->story->sourceList['po'] = $lang->productCommon . '担当';
$lang->story->sourceList['market'] = 'マーケット';
$lang->story->sourceList['service'] = 'カスタマーサービス';
$lang->story->sourceList['operation'] = '運営';
$lang->story->sourceList['support'] = '技術サポート';
$lang->story->sourceList['competitor'] = 'ライバル';
$lang->story->sourceList['partner'] = 'パートナー';
$lang->story->sourceList['dev'] = '開発メンバー';
$lang->story->sourceList['tester'] = 'テストメンバー';
$lang->story->sourceList['bug'] = 'バグ';
$lang->story->sourceList['forum'] = 'ブログ';
$lang->story->sourceList['other'] = '...';

$lang->story->priList[] = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->legendBasicInfo = '基本情報';
$lang->story->legendLifeTime = $lang->storyCommon . '履歴';
$lang->story->legendRelated = '関連情報';
$lang->story->legendMailto = 'CC';
$lang->story->legendAttatch = '添付';
$lang->story->legendProjectAndTask = $lang->projectCommon . 'タスク';
$lang->story->legendBugs = '関連バグ';
$lang->story->legendFromBug = 'バグより';
$lang->story->legendCases = '関連ケース';
$lang->story->legendLinkStories = '関連' . $lang->storyCommon;
$lang->story->legendChildStories = '細分' . $lang->storyCommon;
$lang->story->legendSpec = $lang->storyCommon . '説明';
$lang->story->legendVerify = '検収基準';
$lang->story->legendMisc = 'その他の関連';

$lang->story->lblChange = $lang->storyCommon . '変更';
$lang->story->lblReview = $lang->storyCommon . '承認';
$lang->story->lblActivate = $lang->storyCommon . 'アクティブ';
$lang->story->lblClose = $lang->storyCommon . 'クローズ';
$lang->story->lblTBC = 'タスクバグケース';

$lang->story->checkAffection = '影響範囲';
$lang->story->affectedProjects = '影響の' . $lang->projectCommon;
$lang->story->affectedBugs = '影響バグ';
$lang->story->affectedCases = '影響のケース';

$lang->story->specTemplate = 'お勧めの参考テンプレート：＜あるタイプのユーザ＞として、＜ある目的の達成＞を期待し、これで＜開発の価値＞になります。';
$lang->story->needNotReview = '承認不要';
$lang->story->successSaved = $lang->storyCommon . 'を新規追加しました';
$lang->story->confirmDelete = "当該{$lang->storyCommon}を削除してもよろしいですか。";
$lang->story->errorEmptyChildStory = "『細分{$lang->storyCommon}』を入力してください";
$lang->story->errorNotSubdivide = "状态不是激活，或者阶段不是未开始的{$lang->storyCommon}，或者是子需求，则不能细分。";
$lang->story->mustChooseResult = '承認結果を選んでください';
$lang->story->mustChoosePreVersion = '溯ろうバージョンを選んでください';
$lang->story->noStory = $lang->storyCommon . 'がありません';
$lang->story->ignoreChangeStage = $lang->storyCommon . ' %s のステータスは下書き、そのフェーズが更新していません。';
$lang->story->cannotDeleteParent = "不能删除父{$lang->storyCommon}";
$lang->story->moveChildrenTips = "修改父{$lang->storyCommon}的所属产品会将其下的子{$lang->storyCommon}也移动到所选产品下。";

$lang->story->form = new stdclass();
$lang->story->form->area = "当該{$lang->storyCommon}の所属範囲";
$lang->story->form->desc = "説明と基準、どの{$lang->storyCommon}？どうやって検収しますか？";
$lang->story->form->resource = '資源の割り当て、誰より完了？何時間かかりますか？';
$lang->story->form->file = "添付ファイル、当該{$lang->storyCommon}が関連ファイルがあった場合、ここにクリックしてアップロードしてください。";

$lang->story->action = new stdclass();
$lang->story->action->reviewed = array('main' => '$date、 <strong>$actor</strong> より承認結果を記録しました。結果は <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->story->action->closed = array('main' => '$date、 <strong>$actor</strong> よりクローズしました。原因は <strong>$extra</strong> $appendLink。', 'extra' => 'reasonList');
$lang->story->action->linked2plan = array('main' => '$date、 <strong>$actor</strong> よりプラン <strong>$extra</strong>と紐付けました。');
$lang->story->action->unlinkedfromplan = array('main' => '$date、 <strong>$actor</strong> よりプラン <strong>$extra</strong> から除去しました。');
$lang->story->action->linked2project = array('main' => '$date、 <strong>$actor</strong> より' . $lang->projectCommon . ' <strong>$extra</strong>と紐付けました。');
$lang->story->action->unlinkedfromproject = array('main' => '$date、 <strong>$actor</strong> より' . $lang->projectCommon . 'から <strong>$extra</strong> 除去しました。');
$lang->story->action->linked2build = array('main' => '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong>と紐付けました。');
$lang->story->action->unlinkedfrombuild = array('main' => '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> から除去しました。');
$lang->story->action->linked2release = array('main' => '$date、 <strong>$actor</strong> よりリリース <strong>$extra</strong>と紐付けました。');
$lang->story->action->unlinkedfromrelease = array('main' => '$date、 <strong>$actor</strong> よりリリース <strong>$extra</strong> から除去しました。');
$lang->story->action->linkrelatedstory = array('main' => '$date、 <strong>$actor</strong> より' . $lang->storyCommon . ' <strong>$extra</strong>と紐付けました。');
$lang->story->action->subdividestory = array('main' => '$date、 <strong>$actor</strong> より' . $lang->storyCommon . ' <strong>$extra</strong>に細分しました。');
$lang->story->action->unlinkrelatedstory = array('main' => '$date、 <strong>$actor</strong> より関連' . $lang->storyCommon . ' <strong>$extra</strong>を除去しました。');
$lang->story->action->unlinkchildstory = array('main' => '$date、 <strong>$actor</strong> より細分' . $lang->storyCommon . ' <strong>$extra</strong>を除去しました。');

/* 统计报表。*/
$lang->story->report = new stdclass();
$lang->story->report->common = 'レポート';
$lang->story->report->select = 'タイプを選んでください';
$lang->story->report->create = 'レポート生成';
$lang->story->report->value = $lang->storyCommon . '数';

$lang->story->report->charts['storysPerProduct'] = $lang->productCommon . $lang->storyCommon . '数';
$lang->story->report->charts['storysPerModule'] = "モジュール{$lang->storyCommon}数";
$lang->story->report->charts['storysPerSource'] = $lang->storyCommon . 'ソース別で統計';
$lang->story->report->charts['storysPerPlan'] = 'プラン別で統計';
$lang->story->report->charts['storysPerStatus'] = 'ステータス別で統計';
$lang->story->report->charts['storysPerStage'] = 'フェーズ別で統計';
$lang->story->report->charts['storysPerPri'] = '優先度別で統計';
$lang->story->report->charts['storysPerEstimate'] = '計画時間別で統計';
$lang->story->report->charts['storysPerOpenedBy'] = '作成者別で統計';
$lang->story->report->charts['storysPerAssignedTo'] = '担当者別で統計';
$lang->story->report->charts['storysPerClosedReason'] = 'クローズ原因別で統計';
$lang->story->report->charts['storysPerChange'] = '変更回数別で統計';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph = new stdclass();
$lang->story->report->options->type = 'pie';
$lang->story->report->options->width = '500';
$lang->story->report->options->height = '140';

$lang->story->report->storysPerProduct = new stdclass();
$lang->story->report->storysPerModule = new stdclass();
$lang->story->report->storysPerSource = new stdclass();
$lang->story->report->storysPerPlan = new stdclass();
$lang->story->report->storysPerStatus = new stdclass();
$lang->story->report->storysPerStage = new stdclass();
$lang->story->report->storysPerPri = new stdclass();
$lang->story->report->storysPerOpenedBy = new stdclass();
$lang->story->report->storysPerAssignedTo = new stdclass();
$lang->story->report->storysPerClosedReason = new stdclass();
$lang->story->report->storysPerEstimate = new stdclass();
$lang->story->report->storysPerChange = new stdclass();

$lang->story->report->storysPerProduct->item = $lang->productCommon;
$lang->story->report->storysPerModule->item = 'モジュール';
$lang->story->report->storysPerSource->item = 'ソース';
$lang->story->report->storysPerPlan->item = 'プラン';
$lang->story->report->storysPerStatus->item = 'ステータス';
$lang->story->report->storysPerStage->item = 'フェーズ';
$lang->story->report->storysPerPri->item = '優先度';
$lang->story->report->storysPerOpenedBy->item = '作成者';
$lang->story->report->storysPerAssignedTo->item = '担当者';
$lang->story->report->storysPerClosedReason->item = '原因';
$lang->story->report->storysPerEstimate->item = '計画時間';
$lang->story->report->storysPerChange->item = '変更回数';

$lang->story->report->storysPerProduct->graph = new stdclass();
$lang->story->report->storysPerModule->graph = new stdclass();
$lang->story->report->storysPerSource->graph = new stdclass();
$lang->story->report->storysPerPlan->graph = new stdclass();
$lang->story->report->storysPerStatus->graph = new stdclass();
$lang->story->report->storysPerStage->graph = new stdclass();
$lang->story->report->storysPerPri->graph = new stdclass();
$lang->story->report->storysPerOpenedBy->graph = new stdclass();
$lang->story->report->storysPerAssignedTo->graph = new stdclass();
$lang->story->report->storysPerClosedReason->graph = new stdclass();
$lang->story->report->storysPerEstimate->graph = new stdclass();
$lang->story->report->storysPerChange->graph = new stdclass();

$lang->story->report->storysPerProduct->graph->xAxisName = $lang->productCommon;
$lang->story->report->storysPerModule->graph->xAxisName = 'モジュール';
$lang->story->report->storysPerSource->graph->xAxisName = 'ソース';
$lang->story->report->storysPerPlan->graph->xAxisName = 'プラン';
$lang->story->report->storysPerStatus->graph->xAxisName = 'ステータス';
$lang->story->report->storysPerStage->graph->xAxisName = 'フェーズ';
$lang->story->report->storysPerPri->graph->xAxisName = '優先度';
$lang->story->report->storysPerOpenedBy->graph->xAxisName = '作成者';
$lang->story->report->storysPerAssignedTo->graph->xAxisName = '担当者';
$lang->story->report->storysPerClosedReason->graph->xAxisName = 'クローズ原因';
$lang->story->report->storysPerEstimate->graph->xAxisName = '予定時間';
$lang->story->report->storysPerChange->graph->xAxisName = '変更回数';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = '承認者選択...';

$lang->story->notice = new stdclass();
$lang->story->notice->closed = "ご選択した{$lang->storyCommon}が既にクローズされました！";
