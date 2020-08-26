<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  testtask
 * @version  $Id: vi.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->testtask->index            = "Trang yêu cầu";
$lang->testtask->create           = "Gửi yêu cầu";
$lang->testtask->reportChart      = 'Báo cáo';
$lang->testtask->reportAction     = 'Báo cáo tình huống';
$lang->testtask->delete           = "Xóa yêu cầu";
$lang->testtask->importUnitResult = "Import Unit Result";
$lang->testtask->importunitresult = "Import Unit Result"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "Unit Test List";
$lang->testtask->unitCases        = "Unit Test Cases";
$lang->testtask->view             = "Chi tiết yêu cầu";
$lang->testtask->edit             = "Sửa yêu cầu";
$lang->testtask->browse           = "Test yêu cầu";
$lang->testtask->linkCase         = "Liên kết tình huống";
$lang->testtask->selectVersion    = "Chọn phiên bản";
$lang->testtask->unlinkCase       = "Hủy liên kết";
$lang->testtask->batchUnlinkCases = "Hủy liên kết tình huống hàng loạt";
$lang->testtask->batchAssign      = "Bàn giao hàng loạt";
$lang->testtask->runCase          = "Chạy";
$lang->testtask->batchRun         = "Chạy hàng loạt";
$lang->testtask->results          = "Kết quả";
$lang->testtask->resultsAction    = "Kết quả tình huống";
$lang->testtask->createBug        = "Bug(+)";
$lang->testtask->assign           = 'Giao cho';
$lang->testtask->cases            = 'Danh sách tình huống';
$lang->testtask->groupCase        = "Xem theo Nhóm";
$lang->testtask->pre              = 'Trước';
$lang->testtask->next             = 'Tiếp';
$lang->testtask->start            = "Bắt đầu";
$lang->testtask->startAction      = "Bắt đầu yêu cầu";
$lang->testtask->close            = "Đóng";
$lang->testtask->closeAction      = "Đóng yêu cầu";
$lang->testtask->wait             = "Đang đợi";
$lang->testtask->block            = "Block";
$lang->testtask->blockAction      = "Block yêu cầu";
$lang->testtask->activate         = "Kích hoạt";
$lang->testtask->activateAction   = "Kích hoạt yêu cầu";
$lang->testtask->testing          = "Đang Test";
$lang->testtask->blocked          = "Blocked";
$lang->testtask->done             = "Đã test";
$lang->testtask->totalStatus      = "Tất cả";
$lang->testtask->all              = "Tất cả " . $lang->productCommon . "s";  
$lang->testtask->allTasks         = 'Tất cả yêu cầu';
$lang->testtask->collapseAll      = 'Co lại';
$lang->testtask->expandAll        = 'Mở ra';

$lang->testtask->id             = 'ID';
$lang->testtask->common         = 'Yêu cầu';
$lang->testtask->product        = $lang->productCommon;
$lang->testtask->project        = $lang->projectCommon;
$lang->testtask->build          = 'Bản dựng';
$lang->testtask->owner          = 'Sở hữu';
$lang->testtask->executor       = 'Executor';
$lang->testtask->execTime       = 'Exec Time';
$lang->testtask->pri            = 'Ưu tiên';
$lang->testtask->name           = 'Tên yêu cầu';
$lang->testtask->begin          = 'Bắt đầu';
$lang->testtask->end            = 'Kết thúc';
$lang->testtask->desc           = 'Mô tả';
$lang->testtask->mailto         = 'Mail tới';
$lang->testtask->status         = 'Tình trạng';
$lang->testtask->subStatus      = 'Tình trạng con';
$lang->testtask->assignedTo     = 'Đã giao';
$lang->testtask->linkVersion    = 'Bản dựng';
$lang->testtask->lastRunAccount = 'Chạy bởi';
$lang->testtask->lastRunTime    = 'Chạy gần nhất';
$lang->testtask->lastRunResult  = 'Kết quả';
$lang->testtask->reportField    = 'Báo cáo';
$lang->testtask->files          = 'Upload';
$lang->testtask->case           = 'Danh sách tình huống';
$lang->testtask->version        = 'Phiên bản';
$lang->testtask->caseResult     = 'Test kết quả';
$lang->testtask->stepResults    = 'Bước kết quả';
$lang->testtask->lastRunner     = 'Chạy bởi';
$lang->testtask->lastRunDate    = 'Chạy gần nhất';
$lang->testtask->date           = 'Tested on';;
$lang->testtask->deleted        = "Đã xóa";
$lang->testtask->resultFile     = "Result File";
$lang->testtask->caseCount      = 'Case Count';
$lang->testtask->passCount      = 'Pass';
$lang->testtask->failCount      = 'Fail';
$lang->testtask->summary        = '%s cases, %s failures, %s time.';

$lang->testtask->beginAndEnd = 'Thời gian';
$lang->testtask->to          = 'Tới';

$lang->testtask->legendDesc      = 'Mô tả';
$lang->testtask->legendReport    = 'Báo cáo';
$lang->testtask->legendBasicInfo = 'Thông tin cơ bản';

$lang->testtask->statusList['wait']    = 'Đang đợi';
$lang->testtask->statusList['doing']   = 'Đang làm';
$lang->testtask->statusList['done']    = 'Hoàn thành';
$lang->testtask->statusList['blocked'] = 'Bị khóa';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'Tình huống đã hủy liên kết';
$lang->testtask->linkByBuild   = 'Copy từ bản dựng';
$lang->testtask->linkByStory   = 'Liên kết bởi câu chuyện';
$lang->testtask->linkByBug     = 'Liên kết bởi Bug';
$lang->testtask->linkBySuite   = 'Liên kết bởi Suite';
$lang->testtask->passAll       = 'Duyệt tất cả';
$lang->testtask->pass          = 'Đạt';
$lang->testtask->fail          = 'Thất bại';
$lang->testtask->showResult    = 'Chạy <span class="text-info">%s</span> lần';
$lang->testtask->showFail      = 'Thất bại <span class="text-danger">%s</span> lần';

$lang->testtask->confirmDelete     = 'Bạn có muốn xóa bản dựng này?';
$lang->testtask->confirmUnlinkCase = 'Bạn có muốn hủy liên kết tình huống này?';
$lang->testtask->noticeNoOther     = 'Không có test builds cho sản phẩm này.';
$lang->testtask->noTesttask        = 'Không có yêu cầu. ';
$lang->testtask->checkLinked       = "Vui lòng kiểm tra sản phẩm đó mà yêu cầu test này là liên kết tới một dự án.";
$lang->testtask->noImportData      = 'The imported XML does not parse the data.';
$lang->testtask->unitXMLFormat     = 'Please select a file in JUnit XML format.';
$lang->testtask->titleOfAuto       = "%s automated testing";

$lang->testtask->assignedToMe = 'Giao cho bạn';
$lang->testtask->allCases     = 'Tất cả tình huống';

$lang->testtask->lblCases      = 'Tình huống';
$lang->testtask->lblUnlinkCase = 'Hủy liên kết tình huống';
$lang->testtask->lblRunCase    = 'Chạy tình huống';
$lang->testtask->lblResults    = 'Kết quả';

$lang->testtask->placeholder        = new stdclass();
$lang->testtask->placeholder->begin = 'Bắt đầu';
$lang->testtask->placeholder->end   = 'Kết thúc';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create        = new stdclass();
$lang->testtask->mail->edit          = new stdclass();
$lang->testtask->mail->close         = new stdclass();
$lang->testtask->mail->create->title = "%s được tạo yêu cầu test #%s:%s";
$lang->testtask->mail->edit->title   = "%s kết thúc yêu cầu test #%s:%s";
$lang->testtask->mail->close->title  = "%s đã đóng yêu cầu test #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date,  <strong>$actor</strong> yêu cầu Test được gửi<strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskstarted = '$date,  <strong>$actor</strong> yêu cầu Test được bắt đầu<strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskclosed  = '$date,  <strong>$actor</strong> yêu cầu Test được hoàn thành<strong>$extra</strong>.' . "\n";

$lang->testtask->unexecuted = 'Chờ xử lý';

/* Statistical statement. */
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'Báo cáo';
$lang->testtask->report->select = 'Chọn loại báo cáo';
$lang->testtask->report->create = 'Tạo báo cáo';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'Test Kết quả tình huống';
$lang->testtask->report->charts['testTaskPerType']      = 'Test Loại tình huống';
$lang->testtask->report->charts['testTaskPerModule']    = 'Test Case Module';
$lang->testtask->report->charts['testTaskPerRunner']    = 'Test Case RunBy';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Phân chia mức độ Bug';
$lang->testtask->report->charts['bugStatusGroups']      = 'Phân chia tình trạng Bug';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Phân chia người báo cáo Bug';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Phân chia người giải quyết Bug';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Phân chia giải pháp Bug';
$lang->testtask->report->charts['bugModuleGroups']      = 'Phân chia Module Bug';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;
