<?php
/**
 * The pivot module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: zh-tw.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->pivot->index     = '統計首頁';
$lang->pivot->list      = '透视表';
$lang->pivot->preview   = '查看透视表';
$lang->pivot->item      = '條目';
$lang->pivot->value     = '值';
$lang->pivot->percent   = '百分比';
$lang->pivot->undefined = '未設定';
$lang->pivot->query     = '查詢';
$lang->pivot->project   = '項目';
$lang->pivot->PO        = 'PO';

$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'F6BD0F';
$lang->pivot->colors[] = '8BBA00';
$lang->pivot->colors[] = 'FF8E46';
$lang->pivot->colors[] = '008E8E';
$lang->pivot->colors[] = 'D64646';
$lang->pivot->colors[] = '8E468E';
$lang->pivot->colors[] = '588526';
$lang->pivot->colors[] = 'B3AA00';
$lang->pivot->colors[] = '008ED6';
$lang->pivot->colors[] = '9D080D';
$lang->pivot->colors[] = 'A186BE';

$lang->pivot->assign['noassign'] = '未指派';
$lang->pivot->assign['assign']   = '已指派';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common}偏差報表";
$lang->pivot->productSummary   = $lang->productCommon . '彙總表';
$lang->pivot->bugCreate        = 'Bug創建表';
$lang->pivot->bugAssign        = '未解决Bug指派表';
$lang->pivot->workload         = '員工負載表';
$lang->pivot->workloadAB       = '工作負載';
$lang->pivot->bugOpenedDate    = 'Bug創建時間';
$lang->pivot->beginAndEnd      = '起止時間';
$lang->pivot->begin            = '起始日期';
$lang->pivot->end              = '結束日期';
$lang->pivot->dept             = '部門';
$lang->pivot->deviationChart   = "{$lang->execution->common}偏差曲綫";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . '彙總表|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common}偏差報表|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bug創建表|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = '未解决Bug指派表|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = '員工負載表|pivot|workload';

$lang->pivot->id            = '編號';
$lang->pivot->execution     = $lang->execution->common;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = '姓名';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = '任務數';
$lang->pivot->estimate      = '總預計';
$lang->pivot->consumed      = '總消耗';
$lang->pivot->remain        = '剩餘工時';
$lang->pivot->deviation     = '偏差';
$lang->pivot->deviationRate = '偏差率';
$lang->pivot->total         = '總計';
$lang->pivot->to            = '至';
$lang->pivot->taskTotal     = "總任務數";
$lang->pivot->manhourTotal  = "總工時";
$lang->pivot->validRate     = "有效率";
$lang->pivot->validRateTips = "方案為已解決或延期/狀態為已解決或已關閉";
$lang->pivot->unplanned     = "未計劃";
$lang->pivot->workhour      = '每天工時';
$lang->pivot->diffDays      = '工作日天數';

$lang->pivot->typeList['default'] = '預設';
$lang->pivot->typeList['pie']     = '餅圖';
$lang->pivot->typeList['bar']     = '柱狀圖';
$lang->pivot->typeList['line']    = '折線圖';

$lang->pivot->conditions    = '篩選條件：';
$lang->pivot->closedProduct = '關閉' . $lang->productCommon;
$lang->pivot->overduePlan   = "過期計劃";

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug標題';
$lang->pivot->taskName     = '任務名稱';
$lang->pivot->todoName     = '待辦名稱';
$lang->pivot->testTaskName = '版本名稱';
$lang->pivot->deadline     = '截止日期';

$lang->pivot->deviationDesc = '按照已關閉執行統計偏差率（偏差率 = (總消耗 - 總預計) / 總預計），總預計為0時偏差率為n/a。';
$lang->pivot->workloadDesc  = '工作負載=用戶所有任務剩餘工時之和/選擇的時間天數*每天的工時。例如：起止時間設為1月1日~1月7日、工作日天數5天、每天工時8h，統計的是所有指派給該人員的未完成的任務，在5天內，每天8h的情況下的工作負載。';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview'] = array();
