<?php
/**
 * The pivot module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  pivot
 * @version  $Id: vi.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->pivot->index     = 'Trang báo cáo';
$lang->pivot->list      = 'Báo cáo';
$lang->pivot->preview   = 'View Pivot Table';
$lang->pivot->item      = 'Hạng mục';
$lang->pivot->value     = 'Giá trị';
$lang->pivot->percent   = '%';
$lang->pivot->undefined = 'Chưa định nghĩa';
$lang->pivot->query     = 'Truy vấn';
$lang->pivot->project   = 'Project';
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

$lang->pivot->assign['noassign'] = 'Chưa giao';
$lang->pivot->assign['assign']   = 'Đã giao';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = 'Độ lệch thực hiện';
$lang->pivot->productSummary   = 'Tóm tắt '.$lang->productCommon;
$lang->pivot->bugCreate        = 'Báo cáo Bug';
$lang->pivot->bugAssign        = 'Bàn giao Bug';
$lang->pivot->workload         = 'Lượng công việc đội nhóm';
$lang->pivot->workloadAB       = 'Lượng công việc';
$lang->pivot->bugOpenedDate    = 'Bug được báo cáo từ';
$lang->pivot->beginAndEnd      = ' từ';
$lang->pivot->begin            = 'Begin';
$lang->pivot->end              = 'End';
$lang->pivot->dept             = 'Phòng/Ban';
$lang->pivot->deviationChart   = 'Biểu đồ chênh lệcthực hiệnh';

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = 'Tóm tắt '.$lang->productCommon . '|pivot|productsummary';
$lang->pivotList->project->lists[10] = 'Chênh lệchthực hiện|pivotprojectdeviation';
$lang->pivotList->test->lists[10]    = 'Báo cáo Bug|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bàn giao Bug|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Lượng công việc đội nhóm|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->executionCommon;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'Người dùng';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = 'Nhiệm vụ';
$lang->pivot->estimate      = 'Dự tính';
$lang->pivot->consumed      = 'Đã làm';
$lang->pivot->remain        = 'Còn';
$lang->pivot->deviation     = 'Chênh lệch';
$lang->pivot->deviationRate = 'Tỷ lệ lệch';
$lang->pivot->total         = 'Tổng';
$lang->pivot->to            = 'to';
$lang->pivot->taskTotal     = "Tổng nhiệm vụ";
$lang->pivot->manhourTotal  = "Tổng số giờ";
$lang->pivot->validRate     = "Tỷ lệ hợp lý";
$lang->pivot->validRateTips = "Giải pháp được Giải quyết/Hoãn lại hoặc Giải quyết/Đã đóng.";
$lang->pivot->unplanned     = 'Chưa kế hoạch';
$lang->pivot->workday       = 'Giờ/ngày';
$lang->pivot->diffDays      = 'ngày';

$lang->pivot->typeList['default'] = 'Mặc định';
$lang->pivot->typeList['pie']     = 'Pie';
$lang->pivot->typeList['bar']     = 'Bar';
$lang->pivot->typeList['line']    = 'Line';

$lang->pivot->conditions    = 'Lọc theo:';
$lang->pivot->closedProduct = $lang->productCommon . ' đã đóng';
$lang->pivot->overduePlan   = 'Kế hoạch quá hạn';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Tên Bug';
$lang->pivot->taskName     = 'Tên nhiệm vụ';
$lang->pivot->todoName     = 'Tên việc';
$lang->pivot->testTaskName = 'Tên yêu cầu';
$lang->pivot->deadline     = 'Hạn chót';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day.
For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview']['product'] = $lang->product->common;
$lang->pivot->featureBar['preview']['project'] = $lang->project->common;
$lang->pivot->featureBar['preview']['test']    = $lang->qa->common;
$lang->pivot->featureBar['preview']['staff']   = $lang->system->common;
