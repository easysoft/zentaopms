<?php
/**
 * The testtask module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      zengqingyang wangguannan
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->testtask->index = 'バージョン';
$lang->testtask->create = 'テスト提出';
$lang->testtask->reportChart = 'レポート統計';
$lang->testtask->reportAction = 'ケースレポート統計';
$lang->testtask->delete = 'バージョン削除';
$lang->testtask->importUnitResult = "导入单元测试结果";
$lang->testtask->importunitresult = "导入单元测试"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "单元测试列表";
$lang->testtask->unitCases        = "单元测试用例";
$lang->testtask->view = '概略';
$lang->testtask->edit = 'テストタスク編集';
$lang->testtask->browse = 'バージョンリスト';
$lang->testtask->linkCase = 'ケース紐付け';
$lang->testtask->selectVersion = 'バージョン選択';
$lang->testtask->unlinkCase = '除去';
$lang->testtask->batchUnlinkCases = 'ケース一括除去';
$lang->testtask->batchAssign = '担当者一括追加';
$lang->testtask->runCase = '実行';
$lang->testtask->batchRun = '一括実行';
$lang->testtask->results = '結果';
$lang->testtask->resultsAction = 'ケース結果';
$lang->testtask->createBug = 'バグ提出';
$lang->testtask->assign = '担当者';
$lang->testtask->cases = 'ケース';
$lang->testtask->groupCase = 'ケースグループ閲覧';
$lang->testtask->pre = '前';
$lang->testtask->next = '次';
$lang->testtask->start = 'スタート';
$lang->testtask->startAction = 'スタートバージョン';
$lang->testtask->close = 'クローズ';
$lang->testtask->closeAction = 'クローズバージョン';
$lang->testtask->wait = 'テスト待ちバージョン';
$lang->testtask->block = 'ブロック';
$lang->testtask->blockAction = 'ブロックバージョン';
$lang->testtask->activate = 'アクティブ';
$lang->testtask->activateAction = 'アクティブバージョン';
$lang->testtask->testing = 'テスト中バージョン';
$lang->testtask->blocked = 'ブロックされたバージョン';
$lang->testtask->done = 'テスト済バージョン';
$lang->testtask->totalStatus = '全て';
$lang->testtask->all = "全て" . $lang->productCommon;
$lang->testtask->allTasks = '全てテスト';
$lang->testtask->collapseAll = '全て折りたたみ';
$lang->testtask->expandAll = '全て展開';

$lang->testtask->id = '番号';
$lang->testtask->common = 'テストタスク';
$lang->testtask->product = $lang->productCommon;
$lang->testtask->project = $lang->projectCommon;
$lang->testtask->build = 'バージョン';
$lang->testtask->owner = '担当者';
$lang->testtask->executor       = '执行人';
$lang->testtask->execTime       = '执行时间';
$lang->testtask->pri = '優先度';
$lang->testtask->name = 'テストタスク名';
$lang->testtask->begin = '開始日';
$lang->testtask->end = '終了日';
$lang->testtask->desc = '説明';
$lang->testtask->mailto = 'CC';
$lang->testtask->status = 'ステータス';
$lang->testtask->subStatus      = '子状态';
$lang->testtask->assignedTo = '担当者';
$lang->testtask->linkVersion = 'バージョン';
$lang->testtask->lastRunAccount = '実行者';
$lang->testtask->lastRunTime = '実行時間';
$lang->testtask->lastRunResult = '結果';
$lang->testtask->reportField = 'テスト総括';
$lang->testtask->files = '添付アップロード';
$lang->testtask->case = 'ケース';
$lang->testtask->version = 'バージョン';
$lang->testtask->caseResult = 'テスト結果';
$lang->testtask->stepResults = 'ステップ結果';
$lang->testtask->lastRunner = '最終実行者';
$lang->testtask->lastRunDate = '最終実行時間';
$lang->testtask->date = 'テスト時間';
$lang->testtask->deleted        = "已删除";
$lang->testtask->resultFile     = "结果文件";
$lang->testtask->caseCount      = '用例数';
$lang->testtask->passCount      = '成功';
$lang->testtask->failCount      = '失败';
$lang->testtask->summary        = '有%s个用例，失败%s个，耗时%s。';

$lang->testtask->beginAndEnd = '開始、終了時間';
$lang->testtask->to = '～';

$lang->testtask->legendDesc = 'バージョン説明';
$lang->testtask->legendReport = 'テスト総括';
$lang->testtask->legendBasicInfo = '基本情報';

$lang->testtask->statusList['wait'] = 'スタート待';
$lang->testtask->statusList['doing'] = '作業中';
$lang->testtask->statusList['done'] = '完了';
$lang->testtask->statusList['blocked'] = 'ブロック';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = '紐付け待ち';
$lang->testtask->linkByBuild = 'バージョンコピー';
$lang->testtask->linkByStory = $lang->storyCommon . 'によって紐付け';
$lang->testtask->linkByBug = 'バグによって紐付け';
$lang->testtask->linkBySuite = 'スイートによって紐付け';
$lang->testtask->passAll = '全て通過';
$lang->testtask->pass = '通過';
$lang->testtask->fail = '失敗';
$lang->testtask->showResult = '<span class="text-info">%s</span>回を実行しました　';
$lang->testtask->showFail = '<span class="text-danger">%s</span>回に失敗しました';

$lang->testtask->confirmDelete = '当該バージョンを削除してもよろしいですか？';
$lang->testtask->confirmUnlinkCase = '当該ケースを除去してもよろしいですか？';
$lang->testtask->noticeNoOther = '当該プロダクトは他のテストバージョンがありません';
$lang->testtask->noTesttask = 'テストバージョンがありません。';
$lang->testtask->checkLinked = 'テストタスクのプロダクトはプロジェクトと紐付けているかどうかことを確認してください';
$lang->testtask->noImportData      = '导入的XML没有解析出数据。';
$lang->testtask->unitXMLFormat     = '请选择Junit XML 格式的文件。';
$lang->testtask->titleOfAuto       = "%s 自动化测试";

$lang->testtask->assignedToMe = '自分担当';
$lang->testtask->allCases = '全てケース';

$lang->testtask->lblCases = 'ケースリスト';
$lang->testtask->lblUnlinkCase = 'ケース除去';
$lang->testtask->lblRunCase = 'ケース実行';
$lang->testtask->lblResults = '実行結果';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = '開始日';
$lang->testtask->placeholder->end = '終了日';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit = new stdclass();
$lang->testtask->mail->close = new stdclass();
$lang->testtask->mail->create->title = '%sバージョンを新規しました： #%s:%s';
$lang->testtask->mail->edit->title = '%sバージョンを編集しました： #%s:%s';
$lang->testtask->mail->close->title = '%sバージョンをクローズしました： #%s:%s';

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened = '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> を作成しました。';
$lang->testtask->action->testtaskstarted = '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> を起動しました。';
$lang->testtask->action->testtaskclosed = '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> を完了しました。';

$lang->testtask->unexecuted = '実行待';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'レポート';
$lang->testtask->report->select = 'レポートタイプを選択してください';
$lang->testtask->report->create = 'レポート生成';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'ケース結果別で統計';
$lang->testtask->report->charts['testTaskPerType'] = 'ケースタイプ別で統計';
$lang->testtask->report->charts['testTaskPerModule'] = 'ケースモジュール別で統計';
$lang->testtask->report->charts['testTaskPerRunner'] = 'ケース実行者別で統計';
$lang->testtask->report->charts['bugSeverityGroups'] = 'バグ重大度レベル別で分布';
$lang->testtask->report->charts['bugStatusGroups'] = 'バグステータス別で分布';
$lang->testtask->report->charts['bugOpenedByGroups'] = 'バグ作成者別で分布';
$lang->testtask->report->charts['bugResolvedByGroups'] = 'バグ処理者別で分布';
$lang->testtask->report->charts['bugResolutionGroups'] = 'バグ処理策別で分布';
$lang->testtask->report->charts['bugModuleGroups'] = 'バグモジュール別で分布';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph = new stdclass();
$lang->testtask->report->options->type = 'pie';
$lang->testtask->report->options->width = '500';
$lang->testtask->report->options->height = '140';

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;

$lang->testtask->unitTag['all']       = '所有';
$lang->testtask->unitTag['newest']    = '最近';
$lang->testtask->unitTag['thisWeek']  = '本周';
$lang->testtask->unitTag['lastWeek']  = '上周';
$lang->testtask->unitTag['thisMonth'] = '本月';
$lang->testtask->unitTag['lastMonth'] = '上月';
