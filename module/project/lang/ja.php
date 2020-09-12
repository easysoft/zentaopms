<?php
/**
 * The project module ja file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->project->common = $lang->projectCommon . 'ビュー';
$lang->project->allProjects = '全' . $lang->projectCommon;
$lang->project->id = $lang->projectCommon . '编号';
$lang->project->type = 'タイプ';
$lang->project->name = $lang->projectCommon . '名';
$lang->project->code = 'コード';
$lang->project->statge = '阶段';
$lang->project->pri = '优先级';
$lang->project->openedBy = '由谁创建';
$lang->project->openedDate = '创建日期';
$lang->project->closedBy = '由谁关闭';
$lang->project->closedDate = '关闭日期';
$lang->project->canceledBy = '由谁取消';
$lang->project->canceledDate = '取消日期';
$lang->project->begin = '開始日';
$lang->project->end = '終了日';
$lang->project->dateRange = '開始日・終了日';
$lang->project->to = '～';
$lang->project->days = '作業日数';
$lang->project->day = '日間';
$lang->project->workHour = 'h';
$lang->project->totalHours = '作業時間';
$lang->project->totalDays = '作業日数';
$lang->project->status = 'ステータス';
$lang->project->subStatus = '子状态';
$lang->project->desc = $lang->projectCommon . '説明';
$lang->project->owner = '担当';
$lang->project->PO = $lang->productCommon . '担当';
$lang->project->PM = '担当';
$lang->project->QD = 'テスト担当';
$lang->project->RD = 'リリース担当';
$lang->project->qa = 'テスト';
$lang->project->release = 'リリース';
$lang->project->acl = 'アクセス制御';
$lang->project->teamname = 'チーム名';
$lang->project->order = $lang->projectCommon . 'ソート';
$lang->project->orderAB = 'ソート';
$lang->project->products = '関連' . $lang->productCommon;
$lang->project->whitelist = 'ホワイトリストグループ';
$lang->project->totalEstimate = '計画時間';
$lang->project->totalConsumed = '実績時間';
$lang->project->totalLeft = '残時間';
$lang->project->progress = '進捗';
$lang->project->hours = '計画時間 %s 実績時間 %s 残時間 %s';
$lang->project->viewBug = 'バグ表示';
$lang->project->noProduct = "無{$lang->productCommon}{$lang->projectCommon}";
$lang->project->createStory = $lang->storyCommon . '追加';
$lang->project->all = '全て';
$lang->project->undone = '未完了';
$lang->project->unclosed = 'クローズ待';
$lang->project->typeDesc = "運用保守{$lang->projectCommon}に{$lang->storyCommon}、バグ、バージョン、テスト機能を載せていません。バーンダウンチャートの使用も禁止されます。";
$lang->project->mine = '担当：';
$lang->project->other = 'その他：';
$lang->project->deleted = '削除';
$lang->project->delayed = '延期';
$lang->project->product = '$ lang-> project-> products';
$lang->project->readjustTime = "{$lang->projectCommon}開始、終了時間調整";
$lang->project->readjustTask = 'タスク開始、終了時間順延';
$lang->project->effort = '日報';
$lang->project->relatedMember = '関係者';
$lang->project->watermark = '禅道からエクスポート';
$lang->project->viewByUser = 'ユーザ毎に表示';

$lang->project->start = 'スタート';
$lang->project->activate = 'アクティブ';
$lang->project->putoff = '延期';
$lang->project->suspend = 'サスペンド';
$lang->project->close = 'クローズ';
$lang->project->export = 'エクスポート';

$lang->project->typeList['sprint'] = "短期間$lang->projectCommon";
$lang->project->typeList['waterfall'] = "長期間$lang->projectCommon";
$lang->project->typeList['ops'] = "運用保守$lang->projectCommon";

$lang->project->endList[7] = '1 週間';
$lang->project->endList[14] = '2 週間';
$lang->project->endList[31] = '1 か月';
$lang->project->endList[62] = '2 か月';
$lang->project->endList[93] = '3 か月';
$lang->project->endList[186] = '半年';
$lang->project->endList[365] = '1 年';

$lang->team = new stdclass();
$lang->team->account = 'ユーザ';
$lang->team->role = '役割';
$lang->team->join = '登録日';
$lang->team->hours = '一日作業時間';
$lang->team->days = '利用可能な勤務日';
$lang->team->totalHours = '総計';

$lang->team->limited = '制限付きユーザ';
$lang->team->limitedList['yes'] = 'はい';
$lang->team->limitedList['no'] = 'いいえ';

$lang->project->basicInfo = '基本情報';
$lang->project->otherInfo = 'その他情報';

/* 字段取值列表。*/
$lang->project->statusList['wait'] = '待機中';
$lang->project->statusList['doing'] = '進行中';
$lang->project->statusList['suspended'] = 'サスペンド';
$lang->project->statusList['closed'] = 'クローズ';

$lang->project->aclList['open'] = "デフォルト設定（{$lang->projectCommon}ビュー権限を持つメンバーのみ）";
$lang->project->aclList['private'] = "プライベート{$lang->projectCommon}（{$lang->projectCommon}メンバーのみ）";
$lang->project->aclList['custom'] = 'カスタムホワイトリスト（チームメンバーおよびホワイトリストメンバーのみ）';

/* 方法列表。*/
$lang->project->index = "{$lang->projectCommon}";
$lang->project->task = 'タスクリスト';
$lang->project->groupTask = 'タスクグループ閲覧';
$lang->project->story = $lang->storyCommon . 'リスト';
$lang->project->bug = 'バグリスト';
$lang->project->dynamic = '履歴';
$lang->project->latestDynamic = '最新履歴';
$lang->project->build = '全バージョン';
$lang->project->testtask = 'テストタスク';
$lang->project->burn = 'バーンダウン';
$lang->project->computeBurn = 'バーンダウンチャート更新';
$lang->project->burnData = 'バーンダウンチャートデータ';
$lang->project->fixFirst = '計画時間変更';
$lang->project->team = 'チームメンバー';
$lang->project->doc = '資料リスト';
$lang->project->doclib = '資料ライブラリ';
$lang->project->manageProducts = '関連' . $lang->productCommon;
$lang->project->linkStory = '紐付け';
$lang->project->linkStoryByPlan = 'プラン毎に紐付け';
$lang->project->linkPlan = 'プラン紐付け';
$lang->project->unlinkStoryTasks = '紐付けないタスク';
$lang->project->linkedProducts = '紐付け済';
$lang->project->unlinkedProducts = '紐付け待ち';
$lang->project->view = "{$lang->projectCommon}概略";
$lang->project->startAction = "{$lang->projectCommon}スタート";
$lang->project->activateAction = "{$lang->projectCommon}プラン";
$lang->project->delayAction = "{$lang->projectCommon}延期";
$lang->project->suspendAction = "{$lang->projectCommon}サスペンド";
$lang->project->closeAction = "{$lang->projectCommon}クローズ";
$lang->project->testtaskAction = "{$lang->projectCommon}テストタスク";
$lang->project->teamAction = "{$lang->projectCommon}チーム";
$lang->project->kanbanAction = "{$lang->projectCommon}看板";
$lang->project->printKanbanAction = '看板プリント';
$lang->project->treeAction = "{$lang->projectCommon}樹形図";
$lang->project->exportAction = "{$lang->projectCommon}エクスポート";
$lang->project->computeBurnAction = 'バーンダウンチャート計算';
$lang->project->create = '新規';
$lang->project->copy = 'コピー';
$lang->project->delete = "{$lang->projectCommon}削除";
$lang->project->browse = "{$lang->projectCommon}閲覧";
$lang->project->edit = "{$lang->projectCommon}編集";
$lang->project->batchEdit = '一括編集';
$lang->project->manageMembers = 'チーム管理';
$lang->project->unlinkMember = 'メンバー除去';
$lang->project->unlinkStory = $lang->storyCommon . '除去';
$lang->project->unlinkStoryAB = $lang->storyCommon . '除去';
$lang->project->batchUnlinkStory = $lang->storyCommon . '一括除去';
$lang->project->importTask = 'タスクインポート';
$lang->project->importPlanStories = "プラン毎に{$lang->storyCommon}と紐付け";
$lang->project->importBug = 'バグインポート';
$lang->project->updateOrder = "{$lang->projectCommon}ソート";
$lang->project->tree = '樹形図';
$lang->project->treeTask = 'タスクのみ表示';
$lang->project->treeStory = $lang->storyCommon . 'のみ表示';
$lang->project->treeOnlyTask = '樹形図でタスクのみ表示';
$lang->project->treeOnlyStory = "樹形図で{$lang->storyCommon}のみ表示";
$lang->project->storyKanban = $lang->storyCommon . '看板';
$lang->project->storySort = $lang->storyCommon . 'ソート';
$lang->project->importPlanStory = '今' . $lang->projectCommon . 'の新規に成功しました！nプランと紐付けた' . $lang->storyCommon . 'をインポートしますか？';
$lang->project->iteration = 'バージョンイテレート';
$lang->project->iterationInfo = 'イテレート%s回';
$lang->project->viewAll = '全て表示';

/* 分组浏览。*/
$lang->project->allTasks = '全て';
$lang->project->assignedToMe = '担当タスク';
$lang->project->myInvolved = '参加';

$lang->project->statusSelects[''] = 'その他';
$lang->project->statusSelects['wait'] = 'スタート待';
$lang->project->statusSelects['doing'] = '作業中';
$lang->project->statusSelects['undone'] = '未完成';
$lang->project->statusSelects['finishedbyme'] = '自分完了';
$lang->project->statusSelects['done'] = '完了';
$lang->project->statusSelects['closed'] = 'クローズ';
$lang->project->statusSelects['cancel'] = 'キャンセル';

$lang->project->groups[''] = 'グループ表示';
$lang->project->groups['story'] = $lang->storyCommon . 'グルーピング';
$lang->project->groups['status'] = 'ステータスグルーピング';
$lang->project->groups['pri'] = '優先度グルーピング';
$lang->project->groups['assignedTo'] = '担当グルーピング';
$lang->project->groups['finishedBy'] = '完了者グルーピング';
$lang->project->groups['closedBy'] = 'クローズ者グルーピング';
$lang->project->groups['type'] = 'タイプグルーピング';

$lang->project->groupFilter['story']['all'] = '全て';
$lang->project->groupFilter['story']['linked'] = $lang->storyCommon . 'と紐付けたタスク';
$lang->project->groupFilter['pri']['all'] = '全て';
$lang->project->groupFilter['pri']['noset'] = '未設定';
$lang->project->groupFilter['assignedTo']['undone'] = '未完了';
$lang->project->groupFilter['assignedTo']['all'] = '全て';

$lang->project->byQuery = '検索';

/* 查询条件列表。*/
$lang->project->allProject = "全{$lang->projectCommon}";
$lang->project->aboveAllProduct = "上記全ての{$lang->productCommon}";
$lang->project->aboveAllProject = "上記全ての{$lang->projectCommon}";

/* 页面提示。*/
$lang->project->linkStoryByPlanTips = "この操作により、選択したプランの全ての{$lang->storyCommon}が当該{$lang->projectCommon}と紐付けます。";
$lang->project->selectProject = "{$lang->projectCommon}を選択してください";
$lang->project->beginAndEnd = '開始、終了時間';
$lang->project->begin = '開始日';
$lang->project->end = '終了日';
$lang->project->lblStats = '時間統計';
$lang->project->stats = '利用可能時間 <strong>%s</strong> 、総計画時間 <strong>%s</strong> 、実績時間<strong>%s</strong> 、残時間<strong>%s</strong>';
$lang->project->taskSummary = '共に <strong>%s</strong> 個タスク、待機中 <strong>%s</strong>、進行中 <strong>%s</strong>、総計画時間 <strong>%s</strong> 、実績時間 <strong>%s</strong> 、残時間<strong>%s</strong> があります。';
$lang->project->pageSummary = '本页共 <strong>%total%</strong> 个任务，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>，总预计 <strong>%estimate%</strong> 工时，已消耗 <strong>%consumed%</strong> 工时，剩余 <strong>%left%</strong> 工时。';
$lang->project->checkedSummary = '<strong>%total%</strong> 個タスク、スタート待ち <strong>%wait%</strong>、作業中 <strong>%doing%</strong>、総計画時間 <strong>%estimate%</strong> 、実績時間 <strong>%consumed%</strong> 、残時間<strong>%left%</strong> を選択しました。';
$lang->project->memberHoursAB = '<div>%s <strong>%s</strong> 時間があります</div>';
$lang->project->memberHours = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s利用可能時間</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">総タスク</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">進行中</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">スタート待</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">総予定</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">実績</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">残り</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB = "<div>総タスク <strong>%s : </strong><span class='text-muted'>スタート待</span> %s &nbsp; <span class='text-muted'>進行中</span> %s</div><div>総予定 <strong>%s : </strong><span class='text-muted'>実績</span> %s &nbsp; <span class='text-muted'>残り</span> %s</div>";
$lang->project->wbs = 'タスク振分';
$lang->project->batchWBS = '一括振分';
$lang->project->howToUpdateBurn = "<a href='https://www.zentao.jp/book/zentaomanual/free-open-source-project-management-software-updateburndowncharts-98.html' target='_blank' title='バーンダウンチャートを更新する方法?' class='btn btn-link'>ヘルプ <i class='icon icon-help'></i></a>";
$lang->project->whyNoStories = "紐付けられる{$lang->storyCommon}がありません。{$lang->projectCommon}と紐付けた{$lang->productCommon}に{$lang->storyCommon}があるかどうか、承認通過したかどうかを確認してください。";
$lang->project->productStories = "{$lang->projectCommon}紐付けの{$lang->storyCommon}は{$lang->productCommon}{$lang->storyCommon}のサブセット、それに承認通過のみ紐付けられます。<a href='%s'>{$lang->storyCommon}と紐付けてください</a>。";
$lang->project->haveDraft = "％s項目の下書き{$lang->storyCommon}が当該{$lang->projectCommon}と紐付けることはできません";
$lang->project->doneProjects = '終了';
$lang->project->selectDept = '部門選択';
$lang->project->selectDeptTitle = '部門メンバーを選択';
$lang->project->copyTeam = 'チームコピー';
$lang->project->copyFromTeam = "{$lang->projectCommon}チームをコピー： <strong>%s</strong>";
$lang->project->noMatched = "'%s'を含む$lang->projectCommonが見つかりませんでした";
$lang->project->copyTitle = "一つ{$lang->projectCommon}を選択してください";
$lang->project->copyTeamTitle = "一つ{$lang->projectCommon}チームを選択してください";
$lang->project->copyNoProject = "作業{$lang->projectCommon}がありませんのでコピーできません";
$lang->project->copyFromProject = "コピー：{$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy = 'コピーキャンセル';
$lang->project->byPeriod = '時間帯毎に';
$lang->project->byUser = 'ユーザ毎に';
$lang->project->noProject = "{$lang->projectCommon}がありません。";
$lang->project->noMembers = 'メンバーがいません。';

/* 交互提示。*/
$lang->project->confirmDelete = "{$lang->projectCommon}[%s]を削除してもよろしいですか？";
$lang->project->confirmUnlinkMember = "当該{$lang->projectCommon}からこのユーザを除去してもよろしいですか？";
$lang->project->confirmUnlinkStory = "当該{$lang->projectCommon}からこの{$lang->storyCommon}を除去してもよろしいですか？";
$lang->project->errorNoLinkedProducts = "当該{$lang->projectCommon}は紐付けた{$lang->productCommon}がありませんので、システムは{$lang->productCommon}紐付けページに移動します。";
$lang->project->errorSameProducts = "{$lang->projectCommon}は複数の同一{$lang->productCommon}と紐付けることはできません。";
$lang->project->accessDenied = "当該{$lang->projectCommon}にアクセスできません！";
$lang->project->tips = 'ヒント';
$lang->project->afterInfo = "{$lang->projectCommon}追加成功、以下の操作が実行できます：";
$lang->project->setTeam = 'チーム設定';
$lang->project->linkStory = '紐付け';
$lang->project->createTask = 'タスク作成';
$lang->project->goback = 'タスクリストに戻る';
$lang->project->noweekend = '週末除去';
$lang->project->withweekend = '週末表示';
$lang->project->interval = '間隔';
$lang->project->fixFirstWithLeft = '残時間更新';

$lang->project->action = new stdclass();
$lang->project->action->opened = '$date、 <strong>$actor</strong> より作成しました。$extra' . "\n";
$lang->project->action->managed = '$date、 <strong>$actor</strong> より保守しました。$extra' . "\n";
$lang->project->action->edited = '$date, 由 <strong>$actor</strong> 编辑。$extra' . "\n";
$lang->project->action->extra = '関連プロダクトは %s。';

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption = 'バーンダウンチャート';
$lang->project->charts->burn->graph->xAxisName = '日付';
$lang->project->charts->burn->graph->yAxisName = 'HOUR';
$lang->project->charts->burn->graph->baseFontSize = '12';
$lang->project->charts->burn->graph->formatNumber = '0';
$lang->project->charts->burn->graph->animation = '0';
$lang->project->charts->burn->graph->rotateNames = '1';
$lang->project->charts->burn->graph->showValues = '0';
$lang->project->charts->burn->graph->reference = '参考';
$lang->project->charts->burn->graph->actuality = '実績';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code = 'チーム内部略称';
$lang->project->placeholder->totalLeft = "{$lang->projectCommon}計画時間";

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(終了)';

$lang->project->orderList['order_asc'] = $lang->storyCommon . 'ソート昇順';
$lang->project->orderList['order_desc'] = $lang->storyCommon . 'ソート降順';
$lang->project->orderList['pri_asc'] = $lang->storyCommon . '優先度昇順';
$lang->project->orderList['pri_desc'] = $lang->storyCommon . '優先度降順';
$lang->project->orderList['stage_asc'] = $lang->storyCommon . '階段昇順';
$lang->project->orderList['stage_desc'] = $lang->storyCommon . '階段降順';

$lang->project->kanban = '看板';
$lang->project->kanbanSetting = '設定';
$lang->project->resetKanban = 'デフォルトに戻す';
$lang->project->printKanban = 'プリント';
$lang->project->bugList = 'バグリスト';

$lang->project->kanbanHideCols = 'クローズ、キャンセル済列を表示';
$lang->project->kanbanShowOption = '折りたたむ情報を表示';
$lang->project->kanbanColsColor = '列色を選択';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset = '看板のデフォルト設定に戻りますか？';
$lang->kanbanSetting->optionList['0'] = '非表示';
$lang->kanbanSetting->optionList['1'] = '表示';

$lang->printKanban = new stdclass();
$lang->printKanban->common = '看板プリント';
$lang->printKanban->content = '内容';
$lang->printKanban->print = 'プリント';

$lang->printKanban->taskStatus = 'ステータス';

$lang->printKanban->typeList['all'] = '全て';
$lang->printKanban->typeList['increment'] = '増量';

$lang->project->featureBar['task']['all'] = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed'] = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved'] = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed'] = '延期';
$lang->project->featureBar['task']['needconfirm'] = $lang->storyCommon . '変更';
$lang->project->featureBar['task']['status'] = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all'] = $lang->project->all;
$lang->project->featureBar['all']['undone'] = $lang->project->undone;
$lang->project->featureBar['all']['wait'] = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing'] = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed'] = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all'] = '全て展開';
$lang->project->treeLevel['root'] = '全て折りたたみ';
$lang->project->treeLevel['task'] = '全て表示';
$lang->project->treeLevel['story'] = $lang->storyCommon . 'のみ表示';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
