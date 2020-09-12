<?php
/**
 * The report module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin wangguannan wuhongjie
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->report->common = 'ビュー';
$lang->report->index = 'ホーム';
$lang->report->list = 'レポート';
$lang->report->item = 'アイテム';
$lang->report->value = '値';
$lang->report->percent = '％';
$lang->report->undefined = '未設定';
$lang->report->query = 'クエリ';
$lang->report->annual     = '年度总结';

$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';
$lang->report->colors[] = 'A186BE';

$lang->report->assign['noassign'] = 'アサイン待ち';
$lang->report->assign['assign'] = 'アサイン済';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . '偏差レポート';
$lang->report->productSummary = $lang->productCommon . 'サマリーレポート';
$lang->report->bugCreate = 'バグ作成表';
$lang->report->bugAssign = 'バグアサイン表';
$lang->report->workload = '社員ワークロード表';
$lang->report->workloadAB = 'ワークロード';
$lang->report->bugOpenedDate = 'バグ作成時間';
$lang->report->beginAndEnd = '開始、終了時間';
$lang->report->dept = '部門';
$lang->report->deviationChart = $lang->projectCommon . '偏差曲線';

$lang->reportList->project->lists[10] = $lang->projectCommon . '偏差レポート|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . 'サマリーレポート|report|productsummary';
$lang->reportList->test->lists[10] = 'バグ作成表|report|bugcreate';
$lang->reportList->test->lists[13] = 'バグアサイン表|report|bugassign';
$lang->reportList->staff->lists[10] = '社員ワークロード表|report|workload';

$lang->report->id = '番号';
$lang->report->project = $lang->projectCommon;
$lang->report->product = $lang->productCommon;
$lang->report->user = '名前';
$lang->report->bugTotal = 'バグ';
$lang->report->task = 'タスク数';
$lang->report->estimate = '見積';
$lang->report->consumed = '実績';
$lang->report->remain = '残時間';
$lang->report->deviation = '偏差';
$lang->report->deviationRate = '偏差率';
$lang->report->total = '合計';
$lang->report->to = '～';
$lang->report->taskTotal = 'タスク数';
$lang->report->manhourTotal = '時間';
$lang->report->validRate = '有効比率';
$lang->report->validRateTips = '案は解決済または延期/ステータスは解決済またはクローズ済';
$lang->report->unplanned = '未計画';
$lang->report->workday = '1 日時間';
$lang->report->diffDays = '平日日数';

$lang->report->typeList['default'] = 'デフォルト';
$lang->report->typeList['pie'] = '円グラフ';
$lang->report->typeList['bar'] = '縦棒グラフ';
$lang->report->typeList['line'] = '折れ線グラフ';

$lang->report->conditions = 'フィルタ条件：';
$lang->report->closedProduct = 'クローズ' . $lang->productCommon;
$lang->report->overduePlan = '期限切れプラン';

/* daily reminder. */
$lang->report->idAB = 'ID';
$lang->report->bugTitle = 'バグ名';
$lang->report->taskName = 'タスク名';
$lang->report->todoName = 'ToDo名';
$lang->report->testTaskName = 'バージョン名';
$lang->report->deadline = '締切日';

$lang->report->mailTitle = new stdclass();
$lang->report->mailTitle->begin = '注意：そちらは';
$lang->report->mailTitle->bug = 'バグ(%s),';
$lang->report->mailTitle->task = 'タスク(%s),';
$lang->report->mailTitle->todo = 'ToDo(%s),';
$lang->report->mailTitle->testTask = 'テストバージョン(%s),';

$lang->report->proVersion = '<a href="https://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">もっと多くのもの、プロ版へ！</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s年工作内容统计一览表 —— %s";
$lang->report->annualData->baseInfo         = "基本数据信息";
$lang->report->annualData->logins           = "累计登录次数";
$lang->report->annualData->actions          = "累计动态数";
$lang->report->annualData->efforts          = "累计日志数";
$lang->report->annualData->consumed         = "累计工时数";
$lang->report->annualData->foundBugs        = "累计创建Bug数";
$lang->report->annualData->createdCases     = "累计创建用例数";
$lang->report->annualData->involvedProducts = "累计参与{$lang->productCommon}数";
$lang->report->annualData->createdPlans     = "累计创建计划数";
$lang->report->annualData->createdStories   = "累计创建{$lang->storyCommon}数";

$lang->report->annualData->productOverview = "{$lang->productCommon}创建{$lang->storyCommon}数及占比";
$lang->report->annualData->qaOverview      = "{$lang->productCommon}创建Bug数及占比";
$lang->report->annualData->projectOverview = "参与{$lang->projectCommon}概览";
$lang->report->annualData->doneProject     = "已完成的{$lang->projectCommon}";
$lang->report->annualData->doingProject    = "正在进行的{$lang->projectCommon}";
$lang->report->annualData->suspendProject  = "已挂起的{$lang->projectCommon}";

$lang->report->annualData->projectName   = "{$lang->projectCommon}名称";
$lang->report->annualData->finishedStory = "完成{$lang->storyCommon}数";
$lang->report->annualData->finishedTask  = '完成任务数';
$lang->report->annualData->foundBug      = '创建Bug数';
$lang->report->annualData->resolvedBug   = '解决Bug数';
$lang->report->annualData->productName   = "{$lang->productCommon}名称";
$lang->report->annualData->planCount     = '计划数';
$lang->report->annualData->storyCount    = "{$lang->storyCommon}数";

$lang->report->annualData->qaData           = "累计创建Bug数和创建用例数";
$lang->report->annualData->totalCreatedBug  = '累计创建Bug数';
$lang->report->annualData->totalCreatedCase = '累计创建用例数';

$lang->report->annualData->devData           = "完成任务数和解决Bug数";
$lang->report->annualData->totalFinishedTask = '完成任务数';
$lang->report->annualData->totalResolvedBug  = '解决Bug数';
$lang->report->annualData->totalConsumed     = '累计工时';

$lang->report->annualData->poData          = "所创建{$lang->storyCommon}数和对应的优先级及状态";
$lang->report->annualData->totalStoryPri   = "创建{$lang->storyCommon}优先级分布";
$lang->report->annualData->totalStoryStage = "创建{$lang->storyCommon}阶段分布";

$lang->report->annualData->qaStatistics  = "月创建Bug数和创建用例数";
$lang->report->annualData->poStatistics  = "月创建{$lang->storyCommon}数";
$lang->report->annualData->devStatistics = "月完成任务数及累计工时和解决Bug数";

$lang->report->annualData->unit = "个";
