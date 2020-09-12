<?php
/**
 * The bug module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common = 'バグ';
$lang->bug->id = 'バグ番号';
$lang->bug->product = $lang->productCommon;
$lang->bug->branch = 'ブランチ/プラットフォーム';
$lang->bug->productplan = 'プラン';
$lang->bug->module = 'モジュール';
$lang->bug->moduleAB = 'モジュール';
$lang->bug->project = $lang->projectCommon;
$lang->bug->story = '関連' . $lang->storyCommon;
$lang->bug->storyVersion = "{$lang->storyCommon}版本";
$lang->bug->color = '标题颜色';
$lang->bug->task = '関連タスク';
$lang->bug->title = 'バグ名';
$lang->bug->severity = '重大度レベル';
$lang->bug->severityAB = 'レベル';
$lang->bug->pri = '優先度';
$lang->bug->type = 'タイプ';
$lang->bug->os = 'OS';
$lang->bug->browser = 'ブラウザ';
$lang->bug->steps = 'ステップ再現';
$lang->bug->status = 'ステータス';
$lang->bug->statusAB = 'ステータス';
$lang->bug->subStatus = '子状态';
$lang->bug->activatedCount = 'アクティブ回数';
$lang->bug->activatedCountAB = 'アクティブ回数';
$lang->bug->activatedDate = 'アクティブ日';
$lang->bug->confirmed = '確認';
$lang->bug->confirmedAB = '確認';
$lang->bug->toTask = 'タスクへ';
$lang->bug->toStory = $lang->storyCommon . 'へ';
$lang->bug->mailto = 'CC';
$lang->bug->openedBy = '作成者';
$lang->bug->openedByAB = '作成者';
$lang->bug->openedDate = '作成日';
$lang->bug->openedDateAB = '作成日';
$lang->bug->openedBuild = '影響バージョン';
$lang->bug->assignedTo = '担当者';
$lang->bug->assignBug = '担当者';
$lang->bug->assignedToAB = '担当者';
$lang->bug->assignedDate = '担当決定日';
$lang->bug->resolvedBy = '作業者';
$lang->bug->resolvedByAB = '処理者';
$lang->bug->resolution = '処理策';
$lang->bug->resolutionAB = '施策';
$lang->bug->resolvedBuild = '処理済バージョン';
$lang->bug->resolvedDate = '処理日';
$lang->bug->resolvedDateAB = '処理日';
$lang->bug->deadline = '締め切り';
$lang->bug->plan = 'プラン';
$lang->bug->closedBy = 'クローズ';
$lang->bug->closedDate = 'クローズ日';
$lang->bug->duplicateBug = '重複ID';
$lang->bug->lastEditedBy = '最終更新者';
$lang->bug->linkBug = '関連バグ';
$lang->bug->linkBugs = 'バグ紐付け';
$lang->bug->unlinkBug = '関連バグ除去';
$lang->bug->case = '関連ケース';
$lang->bug->caseVersion = '用例版本';
$lang->bug->testtask = '测试单';
$lang->bug->files = '添付';
$lang->bug->keywords = 'キーワード';
$lang->bug->lastEditedByAB = '更新者';
$lang->bug->lastEditedDateAB = '更新日';
$lang->bug->lastEditedDate = '更新日';
$lang->bug->fromCase = 'ケースより';
$lang->bug->toCase = 'ケース生成';
$lang->bug->colorTag = '色タグ';

/* 方法列表。*/
$lang->bug->index = 'トップページ';
$lang->bug->create = '新規';
$lang->bug->batchCreate = '一括新規';
$lang->bug->confirmBug = '確認';
$lang->bug->confirmAction = 'バグ確認';
$lang->bug->batchConfirm = '一括確認';
$lang->bug->edit = 'バグ編集';
$lang->bug->batchEdit = '一括編集';
$lang->bug->batchChangeModule = 'モジュール一括更新';
$lang->bug->batchChangeBranch = 'ブランチ一括更新';
$lang->bug->batchClose = '一括クローズ';
$lang->bug->assignTo = '担当者';
$lang->bug->assignAction = 'バグ担当者追加';
$lang->bug->batchAssignTo = '担当者一括追加';
$lang->bug->browse = 'バグリスト';
$lang->bug->view = 'バグ詳細';
$lang->bug->resolve = '処理';
$lang->bug->resolveAction = 'バグ処理';
$lang->bug->batchResolve = '一括処理';
$lang->bug->close = 'クローズ';
$lang->bug->closeAction = 'バグクローズ';
$lang->bug->activate = 'アクティブ';
$lang->bug->activateAction = 'バグアクティブ';
$lang->bug->batchActivate = '一括アクティブ';
$lang->bug->reportChart = 'レポート統計';
$lang->bug->reportAction = 'バグレポート統計';
$lang->bug->export = 'データエクスポート';
$lang->bug->exportAction = 'バグエクスポート';
$lang->bug->delete = '削除';
$lang->bug->deleteAction = 'バグ削除';
$lang->bug->deleted = '削除';
$lang->bug->confirmStoryChange = $lang->storyCommon . '変更確認';
$lang->bug->copy = 'バグコピー';
$lang->bug->search = '検索';

/* 查询条件列表。*/
$lang->bug->assignToMe = '担当バグ';
$lang->bug->openedByMe = '作成';
$lang->bug->resolvedByMe = '処理';
$lang->bug->closedByMe = 'クローズ';
$lang->bug->assignToNull = '担当未定';
$lang->bug->unResolved = '処理待ち';
$lang->bug->toClosed = 'クローズ待ち';
$lang->bug->unclosed = 'クローズ待ち';
$lang->bug->unconfirmed = '確認待ち';
$lang->bug->longLifeBugs = '長時間処理待ち';
$lang->bug->postponedBugs = '延期';
$lang->bug->overdueBugs = '期限切れバグ';
$lang->bug->allBugs = '全て';
$lang->bug->byQuery = '検索';
$lang->bug->needConfirm = $lang->storyCommon . '変更';
$lang->bug->allProduct = '全ての' . $lang->productCommon;
$lang->bug->my = '自分';
$lang->bug->yesterdayResolved = '昨日処理済バグ数';
$lang->bug->yesterdayConfirmed = '昨日確認';
$lang->bug->yesterdayClosed = '昨日クローズ';

$lang->bug->assignToMeAB = '担当バグ';
$lang->bug->openedByMeAB = '作成';
$lang->bug->resolvedByMeAB = '処理者';

$lang->bug->ditto = '同上';
$lang->bug->dittoNotice = '当該バグは前のバグと同じプロダクトに属していません！';
$lang->bug->noAssigned = '担当者未定';
$lang->bug->noBug = 'バグがありません。';
$lang->bug->noModule = '<div>モジュール情報がありません</div><div>テストモジュールを更新してください</div>';
$lang->bug->delayWarning = "<strong class='text-danger'> 延期%s日 </strong>";

/* 页面标签。*/
$lang->bug->lblAssignedTo = '担当';
$lang->bug->lblMailto = 'CC';
$lang->bug->lblLastEdited = '最終更新';
$lang->bug->lblResolved = '処理者';
$lang->bug->allUsers = '全ユーザをロード';
$lang->bug->allBuilds = '全て';
$lang->bug->createBuild = '新規';

/* legend列表。*/
$lang->bug->legendBasicInfo = '基本情報';
$lang->bug->legendAttatch = '添付';
$lang->bug->legendPrjStoryTask = $lang->projectCommon . '/' . $lang->storyCommon . '/タスク';
$lang->bug->lblTypeAndSeverity = 'タイプ/重大度レベル';
$lang->bug->lblSystemBrowserAndHardware = 'システム/ブラウザ';
$lang->bug->legendSteps = 'ステップ再現';
$lang->bug->legendComment = '備考';
$lang->bug->legendLife = 'バグの履歴';
$lang->bug->legendMisc = 'その他の関連';
$lang->bug->legendRelated = 'その他の情報';

/* 功能按钮。*/
$lang->bug->buttonConfirm = '確認';

/* 交互提示。*/
$lang->bug->summary = '共に <strong>%s</strong> 個バグ、処理待ち <strong>%s</strong>。';
$lang->bug->confirmChangeProduct = "{$lang->productCommon}を変更したら相応な{$lang->projectCommon}、{$lang->storyCommon}とタスクが変わりますので、変更してもよろしいですか？";
$lang->bug->confirmDelete = '当該バグを削除してもよろしいですか？';
$lang->bug->remindTask = '当該バグはタスクに転換しました。タスク(番号:%s)ステータスを更新しますか？';
$lang->bug->skipClose = 'バグ %s のステータスは処理済ではありませんので、クローズできません。';
$lang->bug->projectAccessDenied = "您无权访问该Bug所属的{$lang->projectCommon}！";

/* 模板。*/
$lang->bug->tplStep = '<p>[ステップ]</p><br/>';
$lang->bug->tplResult = '<p>[結果]</p><br/>';
$lang->bug->tplExpect = '<p>[想定結果]</p><br/>';

/* 各个字段取值列表。*/
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList[''] = '';
$lang->bug->osList['all'] = '全て';
$lang->bug->osList['windows'] = 'Windows';
$lang->bug->osList['win10'] = 'Windows 10';
$lang->bug->osList['win8'] = 'Windows 8';
$lang->bug->osList['win7'] = 'Windows 7';
$lang->bug->osList['vista'] = 'Windows Vista';
$lang->bug->osList['winxp'] = 'Windows XP';
$lang->bug->osList['win2012'] = 'Windows 2012';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['android'] = 'Android';
$lang->bug->osList['ios'] = 'IOS';
$lang->bug->osList['wp8'] = 'WP8';
$lang->bug->osList['wp7'] = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux'] = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['osx'] = 'OS X';
$lang->bug->osList['unix'] = 'Unix';
$lang->bug->osList['others'] = 'その他';

$lang->bug->browserList[''] = '';
$lang->bug->browserList['all'] = '全て';
$lang->bug->browserList['ie'] = 'IEシリーズ';
$lang->bug->browserList['ie11'] = 'IE11';
$lang->bug->browserList['ie10'] = 'IE10';
$lang->bug->browserList['ie9'] = 'IE9';
$lang->bug->browserList['ie8'] = 'IE8';
$lang->bug->browserList['ie7'] = 'IE7';
$lang->bug->browserList['ie6'] = 'IE6';
$lang->bug->browserList['chrome'] = 'chrome';
$lang->bug->browserList['firefox'] = 'firefoxシリーズ';
$lang->bug->browserList['firefox4'] = 'firefox4';
$lang->bug->browserList['firefox3'] = 'firefox3';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['opera'] = 'operaシリーズ';
$lang->bug->browserList['oprea11'] = 'opera11';
$lang->bug->browserList['oprea10'] = 'opera10';
$lang->bug->browserList['opera9'] = 'opera9';
$lang->bug->browserList['safari'] = 'safari';
$lang->bug->browserList['maxthon'] = 'maxthon';
$lang->bug->browserList['uc'] = 'UC';
$lang->bug->browserList['other'] = 'その他';

$lang->bug->typeList[''] = '';
$lang->bug->typeList['codeerror'] = 'コードエラー';
$lang->bug->typeList['config'] = '配置';
$lang->bug->typeList['install'] = 'インストール配置';
$lang->bug->typeList['security'] = 'セキュリティ';
$lang->bug->typeList['performance'] = '性能';
$lang->bug->typeList['standard'] = '規格';
$lang->bug->typeList['automation'] = 'オートメーション';
$lang->bug->typeList['designdefect'] = '設計上の欠陥';
$lang->bug->typeList['others'] = 'その他';

$lang->bug->statusList[''] = '';
$lang->bug->statusList['active'] = 'アクティブ';
$lang->bug->statusList['resolved'] = '処理済';
$lang->bug->statusList['closed'] = 'クローズ';

$lang->bug->confirmedList[1] = 'はい';
$lang->bug->confirmedList[0] = 'いいえ';

$lang->bug->resolutionList[''] = '';
$lang->bug->resolutionList['bydesign'] = '仕様通り';
$lang->bug->resolutionList['duplicate'] = '重複バグ';
$lang->bug->resolutionList['external'] = '外部原因';
$lang->bug->resolutionList['fixed'] = '処理済';
$lang->bug->resolutionList['notrepro'] = '再現不能';
$lang->bug->resolutionList['postponed'] = '延期処理';
$lang->bug->resolutionList['willnotfix'] = '処理しない';
$lang->bug->resolutionList['tostory'] = $lang->storyCommon . 'へ';

/* 统计报表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'レポート';
$lang->bug->report->select = 'タイプを選択してください';
$lang->bug->report->create = 'レポート生成';

$lang->bug->report->charts['bugsPerProject'] = $lang->projectCommon . 'バグ数';
$lang->bug->report->charts['bugsPerBuild'] = 'バージョンバグ数';
$lang->bug->report->charts['bugsPerModule'] = 'モジュールバグ数';
$lang->bug->report->charts['openedBugsPerDay'] = '1 日新規バグ数';
$lang->bug->report->charts['resolvedBugsPerDay'] = '1 日処理バグ数';
$lang->bug->report->charts['closedBugsPerDay'] = '1 日クローズバグ数';
$lang->bug->report->charts['openedBugsPerUser'] = '人ごとに提出バグ数';
$lang->bug->report->charts['resolvedBugsPerUser'] = '人ごとに処理バグ数';
$lang->bug->report->charts['closedBugsPerUser'] = '人ごとにクローズバグ数';
$lang->bug->report->charts['bugsPerSeverity'] = 'バグ重大度レベル別で統計';
$lang->bug->report->charts['bugsPerResolution'] = 'バグ処理策別で統計';
$lang->bug->report->charts['bugsPerStatus'] = 'バグステータスで統計';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'バグアクティブ回数で統計';
$lang->bug->report->charts['bugsPerPri'] = 'バグ優先度別で統計';
$lang->bug->report->charts['bugsPerType'] = 'バグのタイプ別で統計';
$lang->bug->report->charts['bugsPerAssignedTo'] = '担当者別で統計';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph = new stdclass();
$lang->bug->report->options->type = 'pie';
$lang->bug->report->options->width = '500';
$lang->bug->report->options->height = '140';

$lang->bug->report->bugsPerProject = new stdclass();
$lang->bug->report->bugsPerBuild = new stdclass();
$lang->bug->report->bugsPerModule = new stdclass();
$lang->bug->report->openedBugsPerDay = new stdclass();
$lang->bug->report->resolvedBugsPerDay = new stdclass();
$lang->bug->report->closedBugsPerDay = new stdclass();
$lang->bug->report->openedBugsPerUser = new stdclass();
$lang->bug->report->resolvedBugsPerUser = new stdclass();
$lang->bug->report->closedBugsPerUser = new stdclass();
$lang->bug->report->bugsPerSeverity = new stdclass();
$lang->bug->report->bugsPerResolution = new stdclass();
$lang->bug->report->bugsPerStatus = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType = new stdclass();
$lang->bug->report->bugsPerPri = new stdclass();
$lang->bug->report->bugsPerAssignedTo = new stdclass();
$lang->bug->report->bugLiveDays = new stdclass();
$lang->bug->report->bugHistories = new stdclass();

$lang->bug->report->bugsPerProject->graph = new stdclass();
$lang->bug->report->bugsPerBuild->graph = new stdclass();
$lang->bug->report->bugsPerModule->graph = new stdclass();
$lang->bug->report->openedBugsPerDay->graph = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph = new stdclass();
$lang->bug->report->closedBugsPerDay->graph = new stdclass();
$lang->bug->report->openedBugsPerUser->graph = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph = new stdclass();
$lang->bug->report->closedBugsPerUser->graph = new stdclass();
$lang->bug->report->bugsPerSeverity->graph = new stdclass();
$lang->bug->report->bugsPerResolution->graph = new stdclass();
$lang->bug->report->bugsPerStatus->graph = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph = new stdclass();
$lang->bug->report->bugsPerPri->graph = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph = new stdclass();
$lang->bug->report->bugLiveDays->graph = new stdclass();
$lang->bug->report->bugHistories->graph = new stdclass();

$lang->bug->report->bugsPerProject->graph->xAxisName = $lang->projectCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName = 'バージョン';
$lang->bug->report->bugsPerModule->graph->xAxisName = 'モジュール';

$lang->bug->report->openedBugsPerDay->type = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = '日付';

$lang->bug->report->resolvedBugsPerDay->type = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日付';

$lang->bug->report->closedBugsPerDay->type = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = '日付';

$lang->bug->report->openedBugsPerUser->graph->xAxisName = 'ユーザ';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'ユーザ';
$lang->bug->report->closedBugsPerUser->graph->xAxisName = 'ユーザ';

$lang->bug->report->bugsPerSeverity->graph->xAxisName = '重大度レベル';
$lang->bug->report->bugsPerResolution->graph->xAxisName = '処理策';
$lang->bug->report->bugsPerStatus->graph->xAxisName = 'ステータス';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'アクティブ回数';
$lang->bug->report->bugsPerPri->graph->xAxisName = '優先度';
$lang->bug->report->bugsPerType->graph->xAxisName = 'タイプ';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName = '担当者';
$lang->bug->report->bugLiveDays->graph->xAxisName = '処理時間';
$lang->bug->report->bugHistories->graph->xAxisName = '処理ステップ';

/* 操作记录。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved = array('main' => '$date、 <strong>$actor</strong> より処理しました。対応済み <strong>$extra</strong> $appendLink。', 'extra' => 'resolutionList');
$lang->bug->action->tostory = array('main' => '$date、 <strong>$actor</strong> より<strong>' . $lang->storyCommon . '</strong>に変更しました。番号は <strong>$extra</strong>。');
$lang->bug->action->totask = array('main' => '$date、 <strong>$actor</strong> より<strong>タスク</strong>に変更しました。番号は <strong>$extra</strong>。');
$lang->bug->action->linked2plan = array('main' => '$date、 <strong>$actor</strong> よりプラン <strong>$extra</strong> と紐付けました。');
$lang->bug->action->unlinkedfromplan = array('main' => '$date、 <strong>$actor</strong> よりプラン <strong>$extra</strong> から除去しました 。');
$lang->bug->action->linked2build = array('main' => '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> と紐付けました。');
$lang->bug->action->unlinkedfrombuild = array('main' => '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong> から除去しました。');
$lang->bug->action->linked2release = array('main' => '$date、 <strong>$actor</strong> よりリリース <strong>$extra</strong> と紐付けました。');
$lang->bug->action->unlinkedfromrelease = array('main' => '$date、 <strong>$actor</strong> よりリリース <strong>$extra</strong> から除去しました。');
$lang->bug->action->linkrelatedbug = array('main' => '$date、 <strong>$actor</strong> よりバグ <strong>$extra</strong> と紐付けました。');
$lang->bug->action->unlinkrelatedbug = array('main' => '$date、 <strong>$actor</strong> より関連バグ <strong>$extra</strong> を除去しました。');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = '関連バージョンを選択...';
$lang->bug->placeholder->newBuildName = '新しいバージョン名';

$lang->bug->featureBar['browse']['all'] = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed'] = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme'] = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome'] = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['toclosed'] = $lang->bug->toClosed;
$lang->bug->featureBar['browse']['unresolved'] = $lang->bug->unResolved;
$lang->bug->featureBar['browse']['more'] = $lang->more;



$lang->bug->moreSelects['unconfirmed'] = $lang->bug->unconfirmed;
$lang->bug->moreSelects['assigntonull'] = $lang->bug->assignToNull;
$lang->bug->moreSelects['longlifebugs'] = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['overduebugs'] = $lang->bug->overdueBugs;
$lang->bug->moreSelects['needconfirm'] = $lang->bug->needConfirm;
