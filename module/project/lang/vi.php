<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  project
 * @version  $Id: vi.php 5094 2013-07-10 08:46:15Z quocnho@gmail.com $
 * @link  http://www.zentao.net
 */
/* Fields. */
$lang->project->common        = $lang->projectCommon;
$lang->project->allProjects   = 'Tất cả ' . $lang->projectCommon;
$lang->project->id            = 'ID '.$lang->projectCommon;
$lang->project->type          = 'Loại';
$lang->project->name          = "Tên {$lang->projectCommon}";
$lang->project->code          = 'Mã';
$lang->project->statge        = 'Giai đoạn';
$lang->project->pri           = 'Ưu tiên';
$lang->project->openedBy      = 'Mở bởi';
$lang->project->openedDate    = 'Ngày mở';
$lang->project->closedBy      = 'Người đóng';
$lang->project->closedDate    = 'Ngày đóng';
$lang->project->canceledBy    = 'Hủy bởi';
$lang->project->canceledDate  = 'Ngày hủy';
$lang->project->begin         = 'Bắt đầu';
$lang->project->end           = 'Kết thúc';
$lang->project->dateRange     = 'Thời gian';
$lang->project->to            = 'Tới';
$lang->project->days          = 'Ngày khả dụng';
$lang->project->day           = ' ngày';
$lang->project->workHour      = 'giờ';
$lang->project->totalHours    = 'Giờ khả dụng';
$lang->project->totalDays     = 'Ngày khả dụng';
$lang->project->status        = 'Tình trạng';
$lang->project->subStatus     = 'Tình trạng con';
$lang->project->desc          = 'Mô tả';
$lang->project->owner         = 'Sở hữu';
$lang->project->PO            = "Sở hữu {$lang->projectCommon}";
$lang->project->PM            = "Quản lý {$lang->projectCommon}";
$lang->project->QD            = 'Quản lý QA';
$lang->project->RD            = 'Quản lý phát hành';
$lang->project->qa            = 'QA';
$lang->project->release       = 'Phát hành';
$lang->project->acl           = 'Quyền truy cập';
$lang->project->teamname      = 'Tên đội nhóm';
$lang->project->order         = "Đánh giá {$lang->projectCommon}";
$lang->project->orderAB       = "Đánh giá";
$lang->project->products      = "Liên kết {$lang->productCommon}";
$lang->project->whitelist     = 'Danh sách trắng';
$lang->project->totalEstimate = 'Dự tính';
$lang->project->totalConsumed = 'Đã làm';
$lang->project->totalLeft     = 'Còn';
$lang->project->progress      = ' Tiến độ';
$lang->project->hours         = 'Dự tính: %s, Đã làm: %s, Còn: %s.';
$lang->project->viewBug       = 'Bugs';
$lang->project->noProduct     = "Không có {$lang->productCommon} nào.";
$lang->project->createStory   = "Tạo câu chuyện";
$lang->project->all           = "Tất cả {$lang->projectCommon}";
$lang->project->undone        = 'Chưa kết thúc ';
$lang->project->unclosed      = 'Chưa đóng';
$lang->project->typeDesc      = 'Không có câu chuyện, bug, bản dựng, test, hoặc biểu đồ burndown được phép trong OPS';
$lang->project->mine          = 'Của bạn: ';
$lang->project->other         = 'Khác:';
$lang->project->deleted       = 'Đã xóa';
$lang->project->delayed       = 'Tạm ngưng';
$lang->project->product       = $lang->project->products;
$lang->project->readjustTime  = "Điều chỉnh {$lang->projectCommon} Thời gian";
$lang->project->readjustTask  = 'Điều chỉnh nhiệm vụ Thời gian';
$lang->project->effort        = 'Chấm công';
$lang->project->relatedMember = 'Đội nhóm';
$lang->project->watermark     = 'Đã xuất bởi ZenTao';
$lang->project->viewByUser    = 'Theo người dùng';

$lang->project->start    = 'Bắt đầu';
$lang->project->activate = 'Kích hoạt';
$lang->project->putoff   = 'Tạm ngưng';
$lang->project->suspend  = 'Đình chỉ';
$lang->project->close    = 'Đóng';
$lang->project->export   = 'Xuất';

$lang->project->typeList['sprint']    = 'Sprint';
$lang->project->typeList['waterfall'] = 'Waterfall';
$lang->project->typeList['ops']       = 'OPS';

$lang->project->endList[7]   = '1 tuần';
$lang->project->endList[14]  = '2 tuần';
$lang->project->endList[31]  = '1 tháng';
$lang->project->endList[62]  = '2 tháng';
$lang->project->endList[93]  = '3 tháng';
$lang->project->endList[186] = '6 tháng';
$lang->project->endList[365] = '1 năm';

$lang->team = new stdclass();
$lang->team->account    = 'Người dùng';
$lang->team->role       = 'Vai trò';
$lang->team->join       = 'Đã tham gia';
$lang->team->hours      = 'Giờ/ngày';
$lang->team->days       = 'Ngày';
$lang->team->totalHours = 'Tổng số giờ';

$lang->team->limited = 'Người dùng hạn chế';
$lang->team->limitedList['yes'] = 'Có';
$lang->team->limitedList['no']  = 'Không';

$lang->project->basicInfo = 'Thông tin cơ bản';
$lang->project->otherInfo = 'Thông tin khác';

/* Field value list. */
$lang->project->statusList['wait']      = 'Đang đợi';
$lang->project->statusList['doing']     = 'Đang làm';
$lang->project->statusList['suspended'] = 'Đã đình chỉ';
$lang->project->statusList['closed']    = 'Đã đóng';

$lang->project->aclList['open']    = "Mặc định (Người dùng có thể vào {$lang->projectCommon} có thể truy cập nó.)";
$lang->project->aclList['private'] = 'Riêng tư (Chỉ có  thành viên nhóm.)';
$lang->project->aclList['custom']  = 'Tùy biến (Thành viên nhóm và người dùng dánh sách trắng có thể truy cập nó.)';

/* Method list. */
$lang->project->index             = "{$lang->projectCommon} Home";
$lang->project->task              = 'Danh sách nhiệm vụ';
$lang->project->groupTask         = 'Xem kiểu nhóm';
$lang->project->story             = 'Danh sách câu chuyện';
$lang->project->bug               = 'Danh sách Bug';
$lang->project->dynamic           = 'Lịch sử';
$lang->project->latestDynamic     = 'Lịch sử';
$lang->project->build             = 'Danh sách bản dựng';
$lang->project->testtask          = 'Kiểm thử';
$lang->project->burn              = 'Burndown';
$lang->project->computeBurn       = 'Cập nhật';
$lang->project->burnData          = 'Dữ liệu Burndown';
$lang->project->fixFirst          = 'Sửa ngày đầu dự toán';
$lang->project->team              = 'Thành viên';
$lang->project->doc               = 'Tài liệu';
$lang->project->doclib            = 'Thư viện tài liệu';
$lang->project->manageProducts    = $lang->productCommon.' liên kết';
$lang->project->linkStory         = 'Liên kết câu chuyện';
$lang->project->linkStoryByPlan   = 'Liên kết câu chuyện theo kế hoạch';
$lang->project->linkPlan          = 'Kế hoạch liên kết';
$lang->project->unlinkStoryTasks  = 'Hủy liên kết';
$lang->project->linkedProducts    = "{$lang->productCommon} liên kết";
$lang->project->unlinkedProducts  = "{$lang->productCommon} đã hủy liên kết";
$lang->project->view              = "Chi tiết {$lang->projectCommon}";
$lang->project->startAction       = "Bắt đầu {$lang->projectCommon}";
$lang->project->activateAction    = "Kích hoạt {$lang->projectCommon}";
$lang->project->delayAction       = "Tạm ngưng {$lang->projectCommon}";
$lang->project->suspendAction     = "Đình chỉ {$lang->projectCommon}";
$lang->project->closeAction       = "Đóng {$lang->projectCommon}";
$lang->project->testtaskAction    = "Yêu cầu {$lang->projectCommon}";
$lang->project->teamAction        = "Thành viên {$lang->projectCommon}";
$lang->project->kanbanAction      = "Kanban {$lang->projectCommon}";
$lang->project->printKanbanAction = "In Kanban";
$lang->project->treeAction        = "Xem cây {$lang->projectCommon}";
$lang->project->exportAction      = "Xuất {$lang->projectCommon}";
$lang->project->computeBurnAction = "Tính Burndown";
$lang->project->create            = "Tạo {$lang->projectCommon}";
$lang->project->copy              = "Sao chép {$lang->projectCommon}";
$lang->project->delete            = "Xóa {$lang->projectCommon}";
$lang->project->browse            = "{$lang->projectCommon} List";
$lang->project->edit              = "Sửa {$lang->projectCommon}";
$lang->project->batchEdit         = "Sửa hàng loạt";
$lang->project->manageMembers     = 'Quản lý đội nhóm';
$lang->project->unlinkMember      = 'Gỡ bỏ thành viên';
$lang->project->unlinkStory       = 'Hủy liên kết câu chuyện';
$lang->project->unlinkStoryAB     = 'Hủy liên kết';
$lang->project->batchUnlinkStory  = 'Hủy liên kết câu chuyện hàng loạt';
$lang->project->importTask        = 'Chuyển thành nhiệm vụ';
$lang->project->importPlanStories = 'Liên kết câu chuyện theo kế hoạch';
$lang->project->importBug         = 'Nhập Bug';
$lang->project->updateOrder       = "Đánh giá {$lang->projectCommon}";
$lang->project->tree              = 'Cây';
$lang->project->treeTask          = 'Chỉ hiện nhiệm vụ';
$lang->project->treeStory         = 'Chỉ hiện câu chuyện';
$lang->project->treeOnlyTask      = 'Chỉ hiện nhiệm vụ';
$lang->project->treeOnlyStory     = 'Chỉ hiện câu chuyện';
$lang->project->storyKanban       = 'Kanban câu chuyện';
$lang->project->storySort         = 'Đánh giá câu chuyện';
$lang->project->importPlanStory   = $lang->projectCommon . ' được tạo!\nBạn có muốn nhập các câu chuyện mà đã liên kết đến kế hoạch này?';
$lang->project->iteration         = 'Lặp lại';
$lang->project->iterationInfo     = '%s lần lặp lại';
$lang->project->viewAll           = 'Xem tất cả';

/* Group browsing. */
$lang->project->allTasks     = 'Tất cả';
$lang->project->assignedToMe = 'Giao cho bạn';
$lang->project->myInvolved   = 'Liên đới';

$lang->project->statusSelects['']             = 'Thêm';
$lang->project->statusSelects['wait']         = 'Đang đợi';
$lang->project->statusSelects['doing']        = 'Đang làm';
$lang->project->statusSelects['undone']       = 'Chưa kết thúc';
$lang->project->statusSelects['finishedbyme'] = 'Kết thúc bởi bạn';
$lang->project->statusSelects['done']         = 'Hoàn thành';
$lang->project->statusSelects['closed']       = 'Đã đóng';
$lang->project->statusSelects['cancel']       = 'Đã hủy';

$lang->project->groups['']           = 'Xem theo nhóm';
$lang->project->groups['story']      = 'câu chuyện';
$lang->project->groups['status']     = 'tình trạng';
$lang->project->groups['pri']        = 'ưu tiên';
$lang->project->groups['assignedTo'] = 'Người được giao';
$lang->project->groups['finishedBy'] = 'Người kết thúc';
$lang->project->groups['closedBy']   = 'người đóng';
$lang->project->groups['type']       = 'loại';

$lang->project->groupFilter['story']['all']         = 'Tất cả';
$lang->project->groupFilter['story']['linked']      = 'Nhiệm vụ liên kết tới câu chuyện';
$lang->project->groupFilter['pri']['all']           = 'Tất cả';
$lang->project->groupFilter['pri']['noset']         = 'Chưa thiết lập';
$lang->project->groupFilter['assignedTo']['undone'] = 'Chưa kết thúc';
$lang->project->groupFilter['assignedTo']['all']    = 'Tất cả';

$lang->project->byQuery = 'Tìm kiếm';

/* Query condition list. */
$lang->project->allProject      = "Tất cả {$lang->projectCommon}";
$lang->project->aboveAllProduct = "Tất cả {$lang->productCommon} trên";
$lang->project->aboveAllProject = "Tất cả {$lang->projectCommon} trên";

/* Page prompt. */
$lang->project->linkStoryByPlanTips = "Hành động này sẽ liên kết tất cả câu chuyện trong kế hoạch này tới {$lang->projectCommon} này.";
$lang->project->selectProject       = "Chọn {$lang->projectCommon}";
$lang->project->beginAndEnd         = 'Thời gian';
$lang->project->begin               = 'Bắt đầu';
$lang->project->end                 = 'Kết thúc';
$lang->project->lblStats            = 'Chấm công';
$lang->project->stats               = 'Khả dụng: <strong>%s</strong>(giờ). Dự tính: <strong>%s</strong>(giờ). Đã làm: <strong>%s</strong>(giờ). Còn: <strong>%s</strong>(giờ).';
$lang->project->taskSummary         = "Tổng nhiệm vụ:<strong>%s</strong>. Đang đợi: <strong>%s</strong>. Đang làm: <strong>%s</strong>.  &nbsp;&nbsp;&nbsp;  Dự tính: <strong>%s</strong>(giờ). Đã làm: <strong>%s</strong>(giờ). Còn: <strong>%s</strong>(giờ).";
$lang->project->pageSummary         = "Tổng nhiệm vụ: <strong>%total%</strong>. Đang đợi: <strong>%wait%</strong>. Đang làm: <strong>%doing%</strong>. Dự tính: <strong>%estimate%</strong>(giờ). Đã làm: <strong>%consumed%</strong>(giờ). Còn: <strong>%left%</strong>(giờ).";
$lang->project->checkedSummary      = "Đã chọn: <strong>%total%</strong>. Đang đợi: <strong>%wait%</strong>. Đang làm: <strong>%doing%</strong>. Dự tính: <strong>%estimate%</strong>(giờ). Đã làm: <strong>%consumed%</strong>(giờ). Còn: <strong>%left%</strong>(giờ).";
$lang->project->memberHoursAB       = "%s có <strong>%s</ strong> giờ.";
$lang->project->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Giờ khả dụng </div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Nhiệm vụ</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Đang làm</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Đang đợi</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Dự tính</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Đã làm</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Còn lại</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB      = "<div>Nhiêm vụ <strong>%s ：</strong><span class='text-muted'>Đang đợi</span> %s &nbsp; <span class='text-muted'>Đang làm</span> %s</div><div>Dự tính <strong>%s ：</strong><span class='text-muted'>Đã làm</span> %s &nbsp; <span class='text-muted'>Còn lại</span> %s</div>";
$lang->project->wbs                 = "Tạo nhiệm vụ";
$lang->project->batchWBS            = "Tạo nhiệm vụ hàng loạt";
$lang->project->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Làm thế nào để cập nhật biểu đồ Burndown?' class='btn btn-link'>Giúp<i class='icon icon-help'></i></a>";
$lang->project->whyNoStories        = "Không có câu chuyện có thể liên kết. Vui lòng kiểm tra có câu chuyện trong {$lang->projectCommon} cái liên kết tới {$lang->productCommon} và chắc chắn nó đã được duyệt.";
$lang->project->productStories      = "Câu chuyện liên kết tới {$lang->projectCommon} là tập hợp con của câu chuyện liên kết tới {$lang->productCommon}. Các câu chuyện chỉ có thể liên kết sau khi chúng đã được duyệt. <a href='%s'> Liên kết câu chuyện</a> now.";
$lang->project->haveDraft           = "%s câu chuyện đang nháp, bởi vậy chúng không thể liên kết.";
$lang->project->doneProjects        = 'Kết thúc';
$lang->project->selectDept          = 'Chọn phòng/ban';
$lang->project->selectDeptTitle     = 'Chọn người dùng';
$lang->project->copyTeam            = 'Copy đội nhóm';
$lang->project->copyFromTeam        = "Sao chép từ {$lang->projectCommon} Đội nhóm: <strong>%s</strong>";
$lang->project->noMatched           = "Không có $lang->projectCommon bao gồm '%s' có thể được tìm thấy.";
$lang->project->copyTitle           = "Chọn một {$lang->projectCommon} để sao chép.";
$lang->project->copyTeamTitle       = "Chọn một {$lang->projectCommon} đội nhóm để sao chép.";
$lang->project->copyNoProject       = "Không có {$lang->projectCommon} có thể sao chép.";
$lang->project->copyFromProject     = "Sao chép từ {$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy          = 'Hủy sao chép';
$lang->project->byPeriod            = 'Theo thời gian';
$lang->project->byUser              = 'Theo người dùng';
$lang->project->noProject           = "Không có {$lang->projectCommon} nào";
$lang->project->noMembers           = 'Không có thành viên đội nhóm nào';

/* Interactive prompts. */
$lang->project->confirmDelete         = "Bạn có muốn xóa {$lang->projectCommon}[%s] này?";
$lang->project->confirmUnlinkMember   = "Bạn có muốn hủy liên kết người dùng này từ {$lang->projectCommon}?";
$lang->project->confirmUnlinkStory    = "Bạn có muốn hủy liên kết câu chuyện này từ {$lang->projectCommon}?";
$lang->project->errorNoLinkedProducts = "Không có {$lang->productCommon} liên kết tới {$lang->projectCommon}. Bạn sẽ được chuyển tới trang  {$lang->productCommon} để liên kết.";
$lang->project->errorSameProducts     = "{$lang->projectCommon} không thể liên kết tới {$lang->productCommon} giống nhau 2 lần.";
$lang->project->accessDenied          = "Truy cập của bạn tới {$lang->projectCommon} bị từ chối!";
$lang->project->tips                  = 'Ghi chú';
$lang->project->afterInfo             = "{$lang->projectCommon} được tạo. Tiếp theo bạn có thể ";
$lang->project->setTeam               = 'Thiết lập đội nhóm';
$lang->project->linkStory             = 'Liên kết câu chuyện';
$lang->project->createTask            = 'Tạo nhiệm vụ';
$lang->project->goback                = "Trở lại";
$lang->project->noweekend             = 'Không gồm cuối tuần';
$lang->project->withweekend           = 'Bao gồm cuối tuần';
$lang->project->interval              = 'Intervals ';
$lang->project->fixFirstWithLeft      = 'Cập nhật cả giờ còn lại';

$lang->project->action = new stdclass();
$lang->project->action->opened  = '$date, được tạo bởi <strong>$actor</strong>  $extra.' . "\n";
$lang->project->action->managed = '$date, quản lý bởi <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->edited  = '$date, edited by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->extra   = "{$lang->productCommon} liên kết này là %s.";

/* Statistics. */
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = " Burndown Chart";
$lang->project->charts->burn->graph->xAxisName    = "Ngày";
$lang->project->charts->burn->graph->yAxisName    = "Giờ";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
$lang->project->charts->burn->graph->reference    = 'KH';
$lang->project->charts->burn->graph->actuality    = 'Thực tế';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = "Viết tắt của tên {$lang->projectCommon}";
$lang->project->placeholder->totalLeft = "Giờ dự kiến trong ngày đầu của {$lang->projectCommon}.";

$lang->project->selectGroup       = new stdclass();
$lang->project->selectGroup->done = '(Hoàn thành)';

$lang->project->orderList['order_asc']  = "Đánh giá câu chuyện tăng dần";
$lang->project->orderList['order_desc'] = "Đánh giá câu chuyện giảm dần";
$lang->project->orderList['pri_asc']    = "Ưu tiên câu chuyện tăng dần";
$lang->project->orderList['pri_desc']   = "Ưu tiên câu chuyện giảm dần";
$lang->project->orderList['stage_asc']  = "Giai đoạn câu chuyện tăng dần";
$lang->project->orderList['stage_desc'] = "Giai đoạn câu chuyện giảm dần";

$lang->project->kanban        = "Kanban";
$lang->project->kanbanSetting = "Thiết lập";
$lang->project->resetKanban   = "Thiết lập lại";
$lang->project->printKanban   = "In";
$lang->project->bugList       = "Bugs";

$lang->project->kanbanHideCols   = 'Cột Đã đóng & đã hủy';
$lang->project->kanbanShowOption = 'Mở ra';
$lang->project->kanbanColsColor  = 'Tùy biến màu cột';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = 'Bạn có muốn thiết lập lại Kanban?';
$lang->kanbanSetting->optionList['0'] = 'Ẩn';
$lang->kanbanSetting->optionList['1'] = 'Hiện';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'In Kanban';
$lang->printKanban->content = 'Nội dung';
$lang->printKanban->print   = 'Print';

$lang->printKanban->taskStatus = 'Tình trạng';

$lang->printKanban->typeList['all']       = 'Tất cả';
$lang->printKanban->typeList['increment'] = 'Tăng dần';

$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved']   = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed']      = 'Tạm ngưng';
$lang->project->featureBar['task']['needconfirm']  = 'Đã thay đổi';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all']       = $lang->project->all;
$lang->project->featureBar['all']['undone']    = $lang->project->undone;
$lang->project->featureBar['all']['wait']      = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing']     = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed']    = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all']   = 'Mở tất cả';
$lang->project->treeLevel['root']  = 'Co lại tất cả';
$lang->project->treeLevel['task']  = 'Câu chuyện & nhiệm vụ';
$lang->project->treeLevel['story'] = 'Chỉ câu chuyện';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
