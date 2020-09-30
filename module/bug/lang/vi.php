<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  bug
 * @version  $Id: vi.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link  http://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'ID';
$lang->bug->product          = $lang->productCommon;
$lang->bug->branch           = 'Branch/Platform';
$lang->bug->productplan      = 'Kế hoạch';
$lang->bug->module           = 'Module';
$lang->bug->moduleAB         = 'Module';
$lang->bug->project          = $lang->projectCommon;
$lang->bug->story            = 'Câu chuyện';
$lang->bug->storyVersion     = 'Phiên bản câu chuyện';
$lang->bug->color            = 'Màu';
$lang->bug->task             = 'Nhiệm vụ';
$lang->bug->title            = 'Tiêu đề';
$lang->bug->severity         = 'Mức độ';
$lang->bug->severityAB       = 'S';
$lang->bug->pri              = 'Ưu tiên';
$lang->bug->type             = 'Loại';
$lang->bug->os               = 'OS';
$lang->bug->browser          = 'Browser';
$lang->bug->steps            = 'Các bước Repro';
$lang->bug->status           = 'Tình trạng';
$lang->bug->statusAB         = 'Tình trạng';
$lang->bug->subStatus        = 'Tình trạng con';
$lang->bug->activatedCount   = 'Số lần kích hoạt';
$lang->bug->activatedCountAB = 'Kích hoạt';
$lang->bug->activatedDate    = 'Ngày kích hoạt';
$lang->bug->confirmed        = 'Đã xác nhận';
$lang->bug->confirmedAB      = 'C';
$lang->bug->toTask           = 'Chuyển thành nhiệm vụ';
$lang->bug->toStory          = 'Chuyển thành câu chuyện';
$lang->bug->mailto           = 'Mail tới';
$lang->bug->openedBy         = 'Người báo cáo';
$lang->bug->openedByAB       = 'Người báo cáo';
$lang->bug->openedDate       = 'Ngày báo cáo';
$lang->bug->openedDateAB     = 'Đã báo cáo';
$lang->bug->openedBuild      = 'Mở bản dựng';
$lang->bug->assignedTo       = 'Giao cho';
$lang->bug->assignBug        = 'Giao cho';
$lang->bug->assignedToAB     = 'Giao cho';
$lang->bug->assignedDate     = 'Ngày giao';
$lang->bug->resolvedBy       = 'Người giải quyết';
$lang->bug->resolvedByAB     = 'Người giải quyết';
$lang->bug->resolution       = 'Giải pháp';
$lang->bug->resolutionAB     = 'Giải pháp';
$lang->bug->resolvedBuild    = 'Bản dựng';
$lang->bug->resolvedDate     = 'Ngày giải quyết';
$lang->bug->resolvedDateAB   = 'Ngày giải quyết';
$lang->bug->deadline         = 'Hạn chót';
$lang->bug->plan             = 'Kế hoạch';
$lang->bug->closedBy         = 'Người đóng';
$lang->bug->closedDate       = 'Ngày đóng';
$lang->bug->duplicateBug     = 'ID Bug đã nhân bản';
$lang->bug->lastEditedBy     = 'Người sửa';
$lang->bug->linkBug          = 'Bugs liên kết';
$lang->bug->linkBugs         = 'Liên kết Bug';
$lang->bug->unlinkBug        = 'Hủy liên kết';
$lang->bug->case             = 'Tình huống';
$lang->bug->caseVersion      = 'Phiên bản tình huống';
$lang->bug->testtask         = 'Yêu cầu';
$lang->bug->files            = 'Files';
$lang->bug->keywords         = 'Tags';
$lang->bug->lastEditedByAB   = 'Người sửa';
$lang->bug->lastEditedDateAB = 'Ngày sửa';
$lang->bug->lastEditedDate   = 'Ngày sửa';
$lang->bug->fromCase         = 'Từ tình huống';
$lang->bug->toCase           = 'Tới tình huống';
$lang->bug->colorTag         = 'Màu';

/* Method list. */
$lang->bug->index              = 'Trang Bug';
$lang->bug->create             = 'Báo cáo Bug';
$lang->bug->batchCreate        = 'Batch báo cáo';
$lang->bug->confirmBug         = 'Xác nhận';
$lang->bug->confirmAction      = 'Xác nhận Bug';
$lang->bug->batchConfirm       = 'Xác nhận hàng loạt';
$lang->bug->edit               = 'Sửa Bug';
$lang->bug->batchEdit          = 'Sửa hàng loạt';
$lang->bug->batchChangeModule  = 'Sửa Module hàng loạt';
$lang->bug->batchChangeBranch  = 'Sửa Chi nhánh hàng loạt';
$lang->bug->batchClose         = 'Đóng hàng loạt';
$lang->bug->assignTo           = 'Giao cho';
$lang->bug->assignAction       = 'Bàn giao Bug';
$lang->bug->batchAssignTo      = 'Bàn giao hàng loạt';
$lang->bug->browse             = 'Danh sách Bug';
$lang->bug->view               = 'Chi tiết Bug';
$lang->bug->resolve            = 'Giải quyết';
$lang->bug->resolveAction      = 'Giải quyết Bug';
$lang->bug->batchResolve       = 'Giải quyết hàng loạt';
$lang->bug->close              = 'Đóng';
$lang->bug->closeAction        = 'Đóng Bug';
$lang->bug->activate           = 'Kích hoạt';
$lang->bug->activateAction     = 'Kích hoạt Bug';
$lang->bug->batchActivate      = 'Kích hoạt hàng loạt';
$lang->bug->reportChart        = 'Báo cáo';
$lang->bug->reportAction       = 'Bug báo cáo';
$lang->bug->export             = 'Xuất dữ liệu';
$lang->bug->exportAction       = 'Xuất Bug';
$lang->bug->delete             = 'Xóa';
$lang->bug->deleteAction       = 'Xóa Bug';
$lang->bug->deleted            = 'Đã xóa';
$lang->bug->confirmStoryChange = 'Xác nhận thay đổi câu chuyện';
$lang->bug->copy               = 'Copy';
$lang->bug->search             = 'Tìm kiếm';

/* Query condition list. */
$lang->bug->assignToMe         = 'Giao cho bạn';
$lang->bug->openedByMe         = 'Báo cáo bởi bạn';
$lang->bug->resolvedByMe       = 'Giải quyêt bởi bạn';
$lang->bug->closedByMe         = 'Đóng bởi bạn';
$lang->bug->assignToNull       = 'Chưa giao';
$lang->bug->unResolved         = 'Kích hoạt';
$lang->bug->toClosed           = 'Đã đóng';
$lang->bug->unclosed           = 'Chưa đóng';
$lang->bug->unconfirmed        = 'Chưa xác nhận';
$lang->bug->longLifeBugs       = 'Bug mãn tính';
$lang->bug->postponedBugs      = 'Đã hoãn lại';
$lang->bug->overdueBugs        = 'Quá hạn';
$lang->bug->allBugs            = 'Tất cả Bugs';
$lang->bug->byQuery            = 'Tìm kiếm';
$lang->bug->needConfirm        = 'Câu chuyện đã thay đổi';
$lang->bug->allProduct         = 'All' . $lang->productCommon;
$lang->bug->my                 = 'Của bạn';
$lang->bug->yesterdayResolved  = 'Giải quyết hôm qua ';
$lang->bug->yesterdayConfirmed = 'Xác nhận hôm qua ';
$lang->bug->yesterdayClosed    = 'Đóng hôm qua ';

$lang->bug->assignToMeAB   = 'Giao cho bạn';
$lang->bug->openedByMeAB   = 'Báo cáo bởi bạn';
$lang->bug->resolvedByMeAB = 'Giải quyêt bởi bạn';

$lang->bug->ditto        = 'Như trên';
$lang->bug->dittoNotice  = 'Bug này chưa được liên kết tới cùng sản phẩm bởi bởi vì nó là cuối cùng!';
$lang->bug->noAssigned   = 'Chưa giao';
$lang->bug->noBug        = 'Không có bugs nào';
$lang->bug->noModule     = '<div>Chưa có Module.</div><div>Quản lý ngay</div>';
$lang->bug->delayWarning = " <strong class='text-danger'> Trễ %s ngày </strong>";

/* Page tags. */
$lang->bug->lblAssignedTo = 'Giao cho';
$lang->bug->lblMailto     = 'Mail tới';
$lang->bug->lblLastEdited = 'Sửa cuối';
$lang->bug->lblResolved   = 'Người giải quyết';
$lang->bug->allUsers      = 'Nạp tất cả người dùng';
$lang->bug->allBuilds     = 'Tất cả bản dựng';
$lang->bug->createBuild   = 'Mới';

/* Legend list。*/
$lang->bug->legendBasicInfo             = 'Thông tin cơ bản';
$lang->bug->legendAttatch               = 'Files';
$lang->bug->legendPrjStoryTask          = $lang->projectCommon . '/Câu chuyện/Nhiệm vụ';
$lang->bug->lblTypeAndSeverity          = 'Loại/Mức độ';
$lang->bug->lblSystemBrowserAndHardware = 'Hệ thống/Trình duyệt';
$lang->bug->legendSteps                 = 'Các bước Repro';
$lang->bug->legendComment               = 'Ghi chú';
$lang->bug->legendLife                  = 'Toàn bộ';
$lang->bug->legendMisc                  = 'Khác';
$lang->bug->legendRelated               = 'Thông tin liên quan';

/* Button. */
$lang->bug->buttonConfirm = 'Xác nhận';

/* Interactive prompt. */
$lang->bug->summary               = "Tổng <strong>%s</strong> bugs, và <strong>%s</strong> kích hoạt.";
$lang->bug->confirmChangeProduct  = "Mọi sự thay đổi tới {$lang->productCommon} sẽ là nguyên nhân liên kết {$lang->projectCommon}, câu chuyện và nhiệm vụ thay đổi. Bạn có muốn làm điều này?";
$lang->bug->confirmDelete         = 'Bạn có muốn xóa bug này?';
$lang->bug->remindTask            = 'Bug này đã chuyển thành một nhiệm vụ. Bạn có muốn cập nhật tình trạng của nhiệm vụ(ID %s)?';
$lang->bug->skipClose             = 'Bug %s được kích hoạt. Bạn không thể đóng nó.';
$lang->bug->projectAccessDenied   = "Truy cập của bạn tới {$lang->projectCommon} mà Bug này sở hữu bị từ chối!";

/* Template. */
$lang->bug->tplStep   = "<p>[Các bước]</p><br/>";
$lang->bug->tplResult = "<p>[Kết quả]</p><br/>";
$lang->bug->tplExpect = "<p>[Kỳ vọng]</p><br/>";

/* Value list for each field. */
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']  = '';
$lang->bug->osList['all']     = 'Tất cả';
$lang->bug->osList['windows'] = 'Windows';
$lang->bug->osList['win10']   = 'Windows 10';
$lang->bug->osList['win8']    = 'Windows 8';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win2012'] = 'Windows 2012';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['android'] = 'Android';
$lang->bug->osList['ios']     = 'IOS';
$lang->bug->osList['wp8']     = 'WP8';
$lang->bug->osList['wp7']     = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['osx']     = 'OS X';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = 'Khác';

$lang->bug->browserList[''] = '';
$lang->bug->browserList['all']      = 'Tất cả';
$lang->bug->browserList['ie']       = 'IE series';
$lang->bug->browserList['ie11']     = 'IE11';
$lang->bug->browserList['ie10']     = 'IE10';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['chrome']   = 'Chrome';
$lang->bug->browserList['firefox']  = 'Firefox series';
$lang->bug->browserList['firefox4'] = 'Firefox4';
$lang->bug->browserList['firefox3'] = 'Firefox3';
$lang->bug->browserList['firefox2'] = 'Firefox2';
$lang->bug->browserList['opera']    = 'Opera series';
$lang->bug->browserList['oprea11']  = 'Opera11';
$lang->bug->browserList['oprea10']  = 'Opera10';
$lang->bug->browserList['opera9']   = 'Opera9';
$lang->bug->browserList['safari']   = 'Safari';
$lang->bug->browserList['maxthon']  = 'Maxthon';
$lang->bug->browserList['uc']       = 'UC';
$lang->bug->browserList['other']    = 'Khác';

$lang->bug->typeList[''] = '';
$lang->bug->typeList['codeerror']    = 'Lỗi mã nguồn';
$lang->bug->typeList['config']       = 'Lỗi cấu hình';
$lang->bug->typeList['install']      = 'Lỗi cài đặt';
$lang->bug->typeList['security']     = 'Lỗi bảo mật';
$lang->bug->typeList['performance']  = 'Lỗi hiệu suất';
$lang->bug->typeList['standard']     = 'Lỗi tiêu chuẩn';
$lang->bug->typeList['automation']   = 'Lỗi Script';
$lang->bug->typeList['designdefect'] = 'Lỗi thiết kế';
$lang->bug->typeList['others']       = 'Lỗi khác';

$lang->bug->statusList[''] = '';
$lang->bug->statusList['active']   = 'Kích hoạt';
$lang->bug->statusList['resolved'] = 'Đã giải quyết';
$lang->bug->statusList['closed']   = 'Đã đóng';

$lang->bug->confirmedList[1] = 'Có';
$lang->bug->confirmedList[0] = 'Không';

$lang->bug->resolutionList[''] = '';
$lang->bug->resolutionList['bydesign']   = 'Như thiết kế';
$lang->bug->resolutionList['duplicate']  = 'Đã nhân bản';
$lang->bug->resolutionList['external']   = 'External';
$lang->bug->resolutionList['fixed']      = 'Đã giải quyết';
$lang->bug->resolutionList['notrepro']   = 'Không thể sản xuất';
$lang->bug->resolutionList['postponed']  = 'Đã hoãn lại';
$lang->bug->resolutionList['willnotfix'] = "Không thể sửa chữa";
$lang->bug->resolutionList['tostory']    = 'Chuyển thành câu chuyện';

/* Statistical statement. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Báo cáo';
$lang->bug->report->select = 'Chọn loại báo cáo';
$lang->bug->report->create = 'Tạo báo cáo';

$lang->bug->report->charts['bugsPerProject']        = 'Bugs '.$lang->projectCommon;
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs mỗi bản dựng';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs mỗi Module';
$lang->bug->report->charts['openedBugsPerDay']      = 'Bugs đã báo cáo mỗi ngày';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Bugs đã giải quyết mỗi ngày';
$lang->bug->report->charts['closedBugsPerDay']      = 'Đã đóng Bugs mỗi ngày';
$lang->bug->report->charts['openedBugsPerUser']     = 'Bugs đã báo cáo mỗi người dùng';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Bugs đã giải quyết mỗi người dùng';
$lang->bug->report->charts['closedBugsPerUser']     = 'Đã đóng Bugs mỗi người dùng';
$lang->bug->report->charts['bugsPerSeverity']       = 'Mức độ Bug';
$lang->bug->report->charts['bugsPerResolution']     = 'Nghị quyết Bug';
$lang->bug->report->charts['bugsPerStatus']         = 'Tình trạng Bug';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Số lần kích hoạt Bug';
$lang->bug->report->charts['bugsPerPri']            = 'Bug ưu tiên';
$lang->bug->report->charts['bugsPerType']           = 'Loại Bug';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bàn giao Bug';
//$lang->bug->report->charts['bugLiveDays']         = 'Bug Cột thời gian báo cáo';
//$lang->bug->report->charts['bugHistories']        = 'Bug Cột bước báo cáo';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerProject        = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerProject->graph        = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerProject->graph->xAxisName  = $lang->projectCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName    = 'Bản dựng';
$lang->bug->report->bugsPerModule->graph->xAxisName   = 'Module';

$lang->bug->report->openedBugsPerDay->type              = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName  = 'Ngày';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Ngày';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = 'Ngày';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = 'Người dùng';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'Người dùng';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = 'Người dùng';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Ưu tiên';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Giải pháp';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Tình trạng';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Thời gian kích hoạt';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Ưu tiên';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Loại';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'Giao cho';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Cột thời gian';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Cột bước';

/* Operating record. */
$lang->bug->action = new stdclass();
$lang->bug->action->resolved            = array('main' => '$date, được giải quyết bởi <strong>$actor</strong> và giải pháp là <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
$lang->bug->action->tostory             = array('main' => '$date, được chuyển bởi <strong>$actor</strong> thành <strong>Story</strong> với ID <strong>$extra</strong>.');
$lang->bug->action->totask              = array('main' => '$date, nhập bởi <strong>$actor</strong> như <strong>Task</strong> with ID <strong>$extra</strong>.');
$lang->bug->action->linked2plan         = array('main' => '$date, liên kết bởi <strong>$actor</strong> cho kế hoạch <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromplan    = array('main' => '$date, được xóa bởi <strong>$actor</strong> từ kế hoạch <strong>$extra</strong>.');
$lang->bug->action->linked2build        = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Bản dựng <strong>$extra</strong>.');
$lang->bug->action->unlinkedfrombuild   = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ bản dựng <strong>$extra</strong>.');
$lang->bug->action->linked2release      = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Phát hành <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrelease = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ Phát hành <strong>$extra</strong>.');
$lang->bug->action->linkrelatedbug      = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Bug <strong>$extra</strong>.');
$lang->bug->action->unlinkrelatedbug    = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ Bug <strong>$extra</strong>.');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = 'Chọn bản dựng';
$lang->bug->placeholder->newBuildName = 'Tên bản dựng mới';

$lang->bug->featureBar['browse']['all']          = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;


$lang->bug->featureBar['browse']['more']   = $lang->more;

$lang->bug->moreSelects['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['needconfirm']   = $lang->bug->needConfirm;
