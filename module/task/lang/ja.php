<?php
/**
 * The task module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->task->index = '一覧';
$lang->task->create = '新規';
$lang->task->batchCreate = '一括新規';
$lang->task->batchCreateChildren = '子タスク一括新規';
$lang->task->batchEdit = '一括編集';
$lang->task->batchChangeModule = 'モジュール一括更新';
$lang->task->batchClose = '一括クローズ';
$lang->task->batchCancel = '一括キャンセル';
$lang->task->edit = '編集';
$lang->task->delete = '削除';
$lang->task->deleteAction = '削除';
$lang->task->deleted = '削除';
$lang->task->delayed = '延期タスク';
$lang->task->view = '表示';
$lang->task->logEfforts = '時間レコード';
$lang->task->record = '時間';
$lang->task->start = '開始';
$lang->task->startAction = '開始';
$lang->task->restart = '継続';
$lang->task->restartAction = '継続';
$lang->task->finishAction = '完了';
$lang->task->finish = '完了';
$lang->task->pause = '一時停止';
$lang->task->pauseAction = '一時停止';
$lang->task->close = 'クローズ';
$lang->task->closeAction = 'クローズ';
$lang->task->cancel = 'キャンセル';
$lang->task->cancelAction = 'キャンセル';
$lang->task->activateAction = 'アクティブ';
$lang->task->activate = 'アクティブ';
$lang->task->export = 'データエクスポート';
$lang->task->exportAction = 'エクスポート';
$lang->task->reportChart = 'レポート統計';
$lang->task->fromBug = 'バグ';
$lang->task->case = '関連ケース';
$lang->task->confirmStoryChange = $lang->storyCommon . '変更確認';
$lang->task->storyChange = $lang->storyCommon . '変更';
$lang->task->progress = '進捗';
$lang->task->progressAB = '%';
$lang->task->progressTips = '実績時間/(実績+残時間)';
$lang->task->copy = 'タスクコピー';
$lang->task->waitTask = 'スタート待タスク';
$lang->task->allModule = '所有模块';

$lang->task->common = 'タスク';
$lang->task->id = '番号';
$lang->task->project = $lang->projectCommon;
$lang->task->module = 'モジュール';
$lang->task->moduleAB = 'モジュール';
$lang->task->story = $lang->storyCommon;
$lang->task->storyAB = $lang->storyCommon;
$lang->task->storySpec = $lang->storyCommon . '説明';
$lang->task->storyVerify = '検収基準';
$lang->task->storyVersion = "{$lang->storyCommon}版本";
$lang->task->color = '标题颜色';
$lang->task->name = 'タスク名';
$lang->task->type = 'タイプ';
$lang->task->pri = '優先度';
$lang->task->mailto = 'CC';
$lang->task->estimate = '計画時間';
$lang->task->estimateAB = '計画時間';
$lang->task->left = '計画残時間';
$lang->task->leftAB = '残工数';
$lang->task->consumed = '総実績時間';
$lang->task->currentConsumed = '今回実績時間';
$lang->task->myConsumed = 'マイ実績時間';
$lang->task->consumedAB = '実績';
$lang->task->hour = '/h';
$lang->task->consumedThisTime = '時間';
$lang->task->leftThisTime = '残時間';
$lang->task->datePlan = '日程';
$lang->task->estStarted = '開始日';
$lang->task->realStarted = '実際開始日';
$lang->task->date = '日付';
$lang->task->deadline = '終了日';
$lang->task->deadlineAB = '締切';
$lang->task->status = 'ステータス';
$lang->task->subStatus = '子状态';
$lang->task->desc = '説明';
$lang->task->assign = '担当';
$lang->task->assignAction = '担当追加';
$lang->task->assignTo = $lang->task->assign;
$lang->task->batchAssignTo = '担当一括追加';
$lang->task->assignedTo = '担当者';
$lang->task->assignedToAB = '担当者';
$lang->task->assignedDate = '担当決定日';
$lang->task->openedBy = '作成者';
$lang->task->openedDate = '作成日';
$lang->task->openedDateAB = '新規';
$lang->task->finishedBy = '完了者';
$lang->task->finishedByAB = '完了者';
$lang->task->finishedDate = '実際終了日';
$lang->task->finishedDateAB = '終了日';
$lang->task->finishedList = '完了者リスト';
$lang->task->canceledBy = 'キャンセル';
$lang->task->canceledDate = 'キャンセル時間';
$lang->task->closedBy = 'クローズ';
$lang->task->closedDate = 'クローズ時間';
$lang->task->closedReason = 'クローズ原因';
$lang->task->lastEditedBy = '最終更新';
$lang->task->lastEditedDate = '最終更新日';
$lang->task->lastEdited = '最終編集';
$lang->task->recordEstimate = '時間';
$lang->task->editEstimate = '時間編集';
$lang->task->deleteEstimate = '時間削除';
$lang->task->colorTag = '色タグ';
$lang->task->files = '添付';
$lang->task->hasConsumed = '前実績時間';
$lang->task->multiple = 'マルチタスク';
$lang->task->multipleAB = 'マルチ';
$lang->task->team = 'チーム';
$lang->task->transfer = '引き継ぐ';
$lang->task->transferTo = '引き継ぐ';
$lang->task->children = '子タスク';
$lang->task->childrenAB = '子';
$lang->task->parent = '親タスク';
$lang->task->parentAB = '親';
$lang->task->lblPri = '優先度';
$lang->task->lblHour = '(h)';
$lang->task->lblTestStory = $lang->storyCommon . 'テスト';

$lang->task->ditto = '同上';
$lang->task->dittoNotice = '当該タスクと先のタスクは違いプロジェクトに属しています！';
$lang->task->selectTestStory = "テスト{$lang->storyCommon}選択";
$lang->task->selectAllUser = '全て';
$lang->task->noStory = $lang->storyCommon . 'がありません';
$lang->task->noAssigned = '担当未定';
$lang->task->noFinished = '未完了';
$lang->task->noClosed = 'クローズ待';
$lang->task->yesterdayFinished = '昨日完了タスク数';
$lang->task->allTasks = '全タスク';

$lang->task->statusList[''] = '';
$lang->task->statusList['wait'] = 'スタート待';
$lang->task->statusList['doing'] = '進行中';
$lang->task->statusList['done'] = '完了';
$lang->task->statusList['pause'] = '一時停止';
$lang->task->statusList['cancel'] = 'キャンセル';
$lang->task->statusList['closed'] = 'クローズ';

$lang->task->typeList[''] = '';
$lang->task->typeList['design'] = 'デザイン';
$lang->task->typeList['devel'] = '開発';
$lang->task->typeList['test'] = 'テスト';
$lang->task->typeList['study'] = '研究';
$lang->task->typeList['discuss'] = '討論';
$lang->task->typeList['ui'] = 'I/F';
$lang->task->typeList['affair'] = '事務';
$lang->task->typeList['misc'] = '...';

$lang->task->priList[0] = '';
$lang->task->priList[1] = '1';
$lang->task->priList[2] = '2';
$lang->task->priList[3] = '3';
$lang->task->priList[4] = '4';

$lang->task->reasonList[''] = '';
$lang->task->reasonList['done'] = '完了';
$lang->task->reasonList['cancel'] = 'キャンセル';

$lang->task->afterChoices['continueAdding'] = "継続的に当該{$lang->storyCommon}にタスクを追加";
$lang->task->afterChoices['toTaskList'] = 'タスクリストに戻る';
$lang->task->afterChoices['toStoryList'] = $lang->storyCommon . 'リストに戻る';

$lang->task->legendBasic = '基本情報';
$lang->task->legendEffort = '時間情報';
$lang->task->legendLife = '履歴';
$lang->task->legendDesc = '説明';

$lang->task->confirmDelete = '当該タスクを削除してもよろしいですか？';
$lang->task->confirmDeleteEstimate = '当該レコードを削除してもよろしいですか？';
$lang->task->copyStoryTitle = '同じ' . $lang->storyCommon;
$lang->task->afterSubmit = '追加後';
$lang->task->successSaved = '追加成功，';
$lang->task->delayWarning = "<strong class='text-danger'> 延期%s日 </strong>";
$lang->task->remindBug = '当該タスクはバグから転換したものであり、バグ:%sを更新しますか ?';
$lang->task->confirmChangeProject = "{$lang->projectCommon}を更新すれば、相応な所属モジュール、関連{$lang->storyCommon}と担当者が変わりますので、更新してもよろしいですか？";
$lang->task->confirmFinish = '”計画残時間”は0になりましたので、タスクステータスを”完了”に更新しますか？';
$lang->task->confirmRecord = '"残時間"は0になりましたので、タスクが”完了”に更新してもよろしいですか？';
$lang->task->confirmTransfer = '”現在残時間”は0になりましたので、タスクは引き継いでもよろしいですか？';
$lang->task->noticeTaskStart       = '"总计消耗"和"预计剩余"不能同时为0';
$lang->task->noticeLinkStory = "紐付けられる{$lang->storyCommon}がありません、現在のプロジェクトに%s、%sしてください。";
$lang->task->noticeSaveRecord = '保存していない時間レコードがあります、先に保存してください。';
$lang->task->commentActions = '%s. %s、<strong>%s</strong> が備考を追加しました。';
$lang->task->deniedNotice = '現在のタスクは%sのみ%sができます。';
$lang->task->noTask = 'タスクがありません。';
$lang->task->createDenied = '当該プロジェクトにタスクを追加することができません';
$lang->task->cannotDeleteParent = '不能删除父任务。';

$lang->task->error = new stdclass();
$lang->task->error->totalNumber = '"总计消耗"必须为数字';
$lang->task->error->consumedNumber = '”今回実績時間”が数字でなければなりません';
$lang->task->error->estimateNumber = '「計画時間」は数字（小数含む）で入力してください。';
$lang->task->error->leftNumber = '"预计剩余"必须为数字';
$lang->task->error->recordMinus = '工时不能为负数';
$lang->task->error->consumedSmall = '”実績”が前回の実績より大きくなければなりません';
$lang->task->error->consumedThisTime = '”時間”を入力してください';
$lang->task->error->left = '”残時間”を入力してください';
$lang->task->error->work = '”備考”が%d文字を超えることができません';
$lang->task->error->skipClose = 'タスク：%s は”完了”或いは”キャンセル済”のステータスではありませんので、クローズしてもよろしいですか？';
$lang->task->error->consumed = 'タスク：%s時間は0より小さくすることができません。当該タスク時間の変更を見落とします';
$lang->task->error->assignedTo = '今のステータスでマルチタスクはチーム以外のメンバーに任せることができません';
$lang->task->error->consumedEmpty = '"今回実績時間"を入力してください';
$lang->task->error->deadlineSmall = '「終了日」には、「開始日」より後の日付を指定してください。';
$lang->task->error->alreadyStarted = '此任务已被启动，不能重复启动！';

/* Report. */
$lang->task->report = new stdclass();
$lang->task->report->common = 'レポート';
$lang->task->report->select = 'タイプを選択してください';
$lang->task->report->create = 'レポート生成';
$lang->task->report->value = 'タスク数';

$lang->task->report->charts['tasksPerProject'] = $lang->projectCommon . 'タスク数で統計';
$lang->task->report->charts['tasksPerModule'] = 'モジュールタスク数で統計';
$lang->task->report->charts['tasksPerAssignedTo'] = '担当者別で統計';
$lang->task->report->charts['tasksPerType'] = 'タスクタイプ別で統計';
$lang->task->report->charts['tasksPerPri'] = '優先度別で統計';
$lang->task->report->charts['tasksPerStatus'] = 'タスクステータス別で統計';
$lang->task->report->charts['tasksPerDeadline'] = '締め切り別で統計';
$lang->task->report->charts['tasksPerEstimate'] = '計画時間別で統計';
$lang->task->report->charts['tasksPerLeft'] = '残時間時間別で統計';
$lang->task->report->charts['tasksPerConsumed'] = '実績時間別で統計';
$lang->task->report->charts['tasksPerFinishedBy'] = '完了者別で統計';
$lang->task->report->charts['tasksPerClosedReason'] = 'クローズ原因別で統計';
$lang->task->report->charts['finishedTasksPerDay'] = '毎日完了別で統計';

$lang->task->report->options = new stdclass();
$lang->task->report->options->graph = new stdclass();
$lang->task->report->options->type = 'pie';
$lang->task->report->options->width = '500';
$lang->task->report->options->height = '140';

$lang->task->report->tasksPerProject = new stdclass();
$lang->task->report->tasksPerModule = new stdclass();
$lang->task->report->tasksPerAssignedTo = new stdclass();
$lang->task->report->tasksPerType = new stdclass();
$lang->task->report->tasksPerPri = new stdclass();
$lang->task->report->tasksPerStatus = new stdclass();
$lang->task->report->tasksPerDeadline = new stdclass();
$lang->task->report->tasksPerEstimate = new stdclass();
$lang->task->report->tasksPerLeft = new stdclass();
$lang->task->report->tasksPerConsumed = new stdclass();
$lang->task->report->tasksPerFinishedBy = new stdclass();
$lang->task->report->tasksPerClosedReason = new stdclass();
$lang->task->report->finishedTasksPerDay = new stdclass();

$lang->task->report->tasksPerProject->item = $lang->projectCommon;
$lang->task->report->tasksPerModule->item = 'モジュール';
$lang->task->report->tasksPerAssignedTo->item = 'ユーザ';
$lang->task->report->tasksPerType->item = 'タイプ';
$lang->task->report->tasksPerPri->item = '優先度';
$lang->task->report->tasksPerStatus->item = 'ステータス';
$lang->task->report->tasksPerDeadline->item = '日付';
$lang->task->report->tasksPerEstimate->item = '計画';
$lang->task->report->tasksPerLeft->item = '残時間';
$lang->task->report->tasksPerConsumed->item = '実績時間';
$lang->task->report->tasksPerFinishedBy->item = 'ユーザ';
$lang->task->report->tasksPerClosedReason->item = '原因';
$lang->task->report->finishedTasksPerDay->item = '日付';

$lang->task->report->tasksPerProject->graph = new stdclass();
$lang->task->report->tasksPerModule->graph = new stdclass();
$lang->task->report->tasksPerAssignedTo->graph = new stdclass();
$lang->task->report->tasksPerType->graph = new stdclass();
$lang->task->report->tasksPerPri->graph = new stdclass();
$lang->task->report->tasksPerStatus->graph = new stdclass();
$lang->task->report->tasksPerDeadline->graph = new stdclass();
$lang->task->report->tasksPerEstimate->graph = new stdclass();
$lang->task->report->tasksPerLeft->graph = new stdclass();
$lang->task->report->tasksPerConsumed->graph = new stdclass();
$lang->task->report->tasksPerFinishedBy->graph = new stdclass();
$lang->task->report->tasksPerClosedReason->graph = new stdclass();
$lang->task->report->finishedTasksPerDay->graph = new stdclass();

$lang->task->report->tasksPerProject->graph->xAxisName = $lang->projectCommon;
$lang->task->report->tasksPerModule->graph->xAxisName = 'モジュール';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName = 'ユーザ';
$lang->task->report->tasksPerType->graph->xAxisName = 'タイプ';
$lang->task->report->tasksPerPri->graph->xAxisName = '優先度';
$lang->task->report->tasksPerStatus->graph->xAxisName = 'ステータス';
$lang->task->report->tasksPerDeadline->graph->xAxisName = '日付';
$lang->task->report->tasksPerEstimate->graph->xAxisName = '時間';
$lang->task->report->tasksPerLeft->graph->xAxisName = '時間';
$lang->task->report->tasksPerConsumed->graph->xAxisName = '時間';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName = 'ユーザ';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'クローズ原因';

$lang->task->report->finishedTasksPerDay->type = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName = '日付';

$lang->taskestimate = new stdclass();
$lang->taskestimate->consumed = '工时';
