<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  report
 * @version  $Id: vi.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->report->common     = 'Báo cáo';
$lang->report->index      = 'Trang báo cáo';
$lang->report->list       = 'Báo cáo';
$lang->report->item       = 'Hạng mục';
$lang->report->value      = 'Giá trị';
$lang->report->percent    = '%';
$lang->report->undefined  = 'Chưa định nghĩa';
$lang->report->query      = 'Truy vấn';
$lang->report->annual     = 'Tóm tắt theo năm';

$lang->report->colors[]   = 'AFD8F8';
$lang->report->colors[]   = 'F6BD0F';
$lang->report->colors[]   = '8BBA00';
$lang->report->colors[]   = 'FF8E46';
$lang->report->colors[]   = '008E8E';
$lang->report->colors[]   = 'D64646';
$lang->report->colors[]   = '8E468E';
$lang->report->colors[]   = '588526';
$lang->report->colors[]   = 'B3AA00';
$lang->report->colors[]   = '008ED6';
$lang->report->colors[]   = '9D080D';
$lang->report->colors[]   = 'A186BE';

$lang->report->assign['noassign'] = 'Chưa giao';
$lang->report->assign['assign']   = 'Đã giao';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = 'Độ lệch '.$lang->projectCommon;
$lang->report->productSummary   = 'Tóm tắt '.$lang->productCommon;
$lang->report->bugCreate        = 'Báo cáo Bug';
$lang->report->bugAssign        = 'Bàn giao Bug';
$lang->report->workload         = 'Lượng công việc đội nhóm';
$lang->report->workloadAB       = 'Lượng công việc';
$lang->report->bugOpenedDate    = 'Bug được báo cáo từ';
$lang->report->beginAndEnd      = ' từ';
$lang->report->dept             = 'Phòng/Ban';
$lang->report->deviationChart   = 'Biểu đồ chênh lệch '.$lang->projectCommon;

$lang->reportList->project->lists[10] = 'Chênh lệch '.$lang->projectCommon . '|report|projectdeviation';
$lang->reportList->product->lists[10] = 'Tóm tắt '.$lang->productCommon . '|report|productsummary';
$lang->reportList->test->lists[10]    = 'Báo cáo Bug|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bàn giao Bug|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Lượng công việc đội nhóm|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Người dùng';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = 'Nhiệm vụ';
$lang->report->estimate      = 'Dự tính';
$lang->report->consumed      = 'Đã làm';
$lang->report->remain        = 'Còn';
$lang->report->deviation     = 'Chênh lệch';
$lang->report->deviationRate = 'Tỷ lệ lệch';
$lang->report->total         = 'Tổng';
$lang->report->to            = 'to';
$lang->report->taskTotal     = "Tổng nhiệm vụ";
$lang->report->manhourTotal  = "Tổng số giờ";
$lang->report->validRate     = "Tỷ lệ hợp lý";
$lang->report->validRateTips = "Giải pháp được Giải quyết/Hoãn lại hoặc Giải quyết/Đã đóng.";
$lang->report->unplanned     = 'Chưa kế hoạch';
$lang->report->workday       = 'Giờ/ngày';
$lang->report->diffDays      = 'ngày';

$lang->report->typeList['default'] = 'Mặc định';
$lang->report->typeList['pie']     = 'Pie';
$lang->report->typeList['bar']     = 'Bar';
$lang->report->typeList['line']    = 'Line';

$lang->report->conditions    = 'Lọc theo:';
$lang->report->closedProduct = $lang->productCommon . ' đã đóng';
$lang->report->overduePlan   = 'Kế hoạch quá hạn';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Tên Bug';
$lang->report->taskName     = 'Tên nhiệm vụ';
$lang->report->todoName     = 'Tên việc';
$lang->report->testTaskName = 'Tên yêu cầu';
$lang->report->deadline     = 'Hạn chót';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Thông báo: Bạn có';
$lang->report->mailTitle->bug      = " %s) Bug,";
$lang->report->mailTitle->task     = " %s) nhiệm vụ,";
$lang->report->mailTitle->todo     = " (%s) việc,";
$lang->report->mailTitle->testTask = " (%s) yêu cầu,";

$lang->report->proVersion   = '<a href="https://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Hãy thử ZenTao Pro để biết thêm!</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Hãy thử ZenTao Pro để biết thêm!</a>';

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s Tóm tắt công việc - %s";
$lang->report->annualData->baseInfo         = "Thông tin cơ bản";
$lang->report->annualData->logins           = "Đăng nhập";
$lang->report->annualData->actions          = "Hành động";
$lang->report->annualData->efforts          = "Chấm công";
$lang->report->annualData->consumed         = "Giờ đã làm";
$lang->report->annualData->foundBugs        = "Bug được tạo";
$lang->report->annualData->createdCases     = "Tình huống được tạo";
$lang->report->annualData->involvedProducts = "{$lang->productCommon} liên quan";
$lang->report->annualData->createdPlans     = "Kế hoạch được tạo";
$lang->report->annualData->createdStories   = "{$lang->storyCommon} được tạo";

$lang->report->annualData->productOverview = "{$lang->productCommon} {$lang->storyCommon} số lượng và Phần trăm";
$lang->report->annualData->qaOverview      = "Bug {$lang->productCommon} số lượng và Phần trăm";
$lang->report->annualData->projectOverview = "Tổng quan {$lang->projectCommon}";
$lang->report->annualData->doneProject     = "{$lang->projectCommon} hoàn thành";
$lang->report->annualData->doingProject    = "{$lang->projectCommon} đang thực hiện";
$lang->report->annualData->suspendProject  = "{$lang->projectCommon} bị đình chỉ";

$lang->report->annualData->projectName   = "{$lang->projectCommon}";
$lang->report->annualData->finishedStory = "{$lang->storyCommon} kết thúc";
$lang->report->annualData->finishedTask  = 'Nhiệm vụ hoàn thành';
$lang->report->annualData->foundBug      = 'Bug được báo cáo';
$lang->report->annualData->resolvedBug   = 'Bug được giải quyết';
$lang->report->annualData->productName   = "{$lang->productCommon}";
$lang->report->annualData->planCount     = 'Kế hoạch';
$lang->report->annualData->storyCount    = "{$lang->storyCommon}";

$lang->report->annualData->qaData           = "Bug được tạo và Tình huống được tạo";
$lang->report->annualData->totalCreatedBug  = 'Bug được báo cáo';
$lang->report->annualData->totalCreatedCase = 'Tình huống được tạo';

$lang->report->annualData->devData           = "Nhiệm vụ hoàn thành và Bug được giải quyết";
$lang->report->annualData->totalFinishedTask = 'Nhiệm vụ hoàn thành';
$lang->report->annualData->totalResolvedBug  = 'Bug được giải quyết';
$lang->report->annualData->totalConsumed     = 'Đã làm';

$lang->report->annualData->poData          = "{$lang->storyCommon} được tạo, Ưu tiên và tình trạng";
$lang->report->annualData->totalStoryPri   = "{$lang->storyCommon} Ưu tiên";
$lang->report->annualData->totalStoryStage = "Giai đoạn {$lang->storyCommon}";

$lang->report->annualData->qaStatistics  = "Bug được tạo hàng tháng và Tình huống";
$lang->report->annualData->poStatistics  = "{$lang->storyCommon} được tạo hàng tháng";
$lang->report->annualData->devStatistics = "Nhiệm vụ hoàn thành háng tháng, giờ và Bug được giải quyết";

$lang->report->annualData->unit = " ";
