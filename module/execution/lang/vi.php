<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  project
 * @version  $Id: vi.php 5094 2013-07-10 08:46:15Z quocnho@gmail.com $
 * @link  http://www.zentao.net
 */
/* Fields. */
$lang->execution->allExecutions   = 'Tất cả ' . $lang->execution->common;
$lang->execution->allExecutionAB  = 'Execution List';
$lang->execution->id              = 'ID '.$lang->executionCommon;
$lang->execution->type            = 'Loại';
$lang->execution->name            = "Tên {$lang->executionCommon}";
$lang->execution->code            = 'Mã';
$lang->execution->projectName     = 'Project';
$lang->execution->execName        = 'Execution Name';
$lang->execution->execCode        = 'Execution Code';
$lang->execution->execType        = 'Execution Type';
$lang->execution->stage           = 'Giai đoạn';
$lang->execution->pri             = 'Ưu tiên';
$lang->execution->openedBy        = 'Mở bởi';
$lang->execution->openedDate      = 'Ngày mở';
$lang->execution->closedBy        = 'Người đóng';
$lang->execution->closedDate      = 'Ngày đóng';
$lang->execution->canceledBy      = 'Hủy bởi';
$lang->execution->canceledDate    = 'Ngày hủy';
$lang->execution->begin           = 'Bắt đầu';
$lang->execution->end             = 'Kết thúc';
$lang->execution->dateRange       = 'Thời gian';
$lang->execution->realBegan       = 'Khởi đầu thật';
$lang->execution->realEnd         = 'Kết thúc thật';
$lang->execution->to              = 'Tới';
$lang->execution->days            = 'Ngày khả dụng';
$lang->execution->day             = ' ngày';
$lang->execution->workHour        = 'giờ';
$lang->execution->workHourUnit    = 'H';
$lang->execution->totalHours      = 'Giờ khả dụng';
$lang->execution->totalDays       = 'Ngày khả dụng';
$lang->execution->status          = $lang->executionCommon . 'Status';
$lang->execution->execStatus      = 'Status';
$lang->execution->subStatus       = 'Tình trạng con';
$lang->execution->desc            = $lang->executionCommon . 'Description';
$lang->execution->execDesc        = 'Description';
$lang->execution->owner           = 'Sở hữu';
$lang->execution->PO              = "Sở hữu {$lang->executionCommon}";
$lang->execution->PM              = "Quản lý {$lang->executionCommon}";
$lang->execution->execPM          = "Quản lý Execution";
$lang->execution->QD              = 'Quản lý QA';
$lang->execution->RD              = 'Quản lý phát hành';
$lang->execution->release         = 'Phát hành';
$lang->execution->acl             = 'Quyền truy cập';
$lang->execution->teamName        = 'Tên đội nhóm';
$lang->execution->teamCount       = 'số người';
$lang->execution->order           = "Đánh giá {$lang->executionCommon}";
$lang->execution->orderAB         = "Đánh giá";
$lang->execution->products        = "Liên kết {$lang->productCommon}";
$lang->execution->whitelist       = 'Danh sách trắng';
$lang->execution->addWhitelist    = 'Add Whitelist';
$lang->execution->unbindWhitelist = 'Remove Whitelist';
$lang->execution->totalEstimate   = 'Dự tính';
$lang->execution->totalConsumed   = 'Đã làm';
$lang->execution->totalLeft       = 'Còn';
$lang->execution->progress        = ' Tiến độ';
$lang->execution->hours           = 'Dự tính: %s, Đã làm: %s, Còn: %s.';
$lang->execution->viewBug         = 'Bugs';
$lang->execution->noProduct       = "Không có {$lang->productCommon} nào.";
$lang->execution->createStory     = "Tạo câu chuyện";
$lang->execution->storyTitle      = "Story Name";
$lang->execution->all             = "Tất cả {$lang->executionCommon}";
$lang->execution->undone          = 'Chưa kết thúc ';
$lang->execution->unclosed        = 'Chưa đóng';
$lang->execution->typeDesc        = 'Không có câu chuyện, bug, bản dựng, test, hoặc biểu đồ burndown được phép trong OPS';
$lang->execution->mine            = 'Của bạn: ';
$lang->execution->involved        = 'Mine';
$lang->execution->other           = 'Khác';
$lang->execution->deleted         = 'Đã xóa';
$lang->execution->delayed         = 'Tạm ngưng';
$lang->execution->delayed         = 'Delayed';
$lang->execution->product         = $lang->execution->products;
$lang->execution->readjustTime    = "Điều chỉnh {$lang->executionCommon} Thời gian";
$lang->execution->readjustTask    = 'Điều chỉnh nhiệm vụ Thời gian';
$lang->execution->effort          = 'Chấm công';
$lang->execution->storyEstimate   = 'Story Estimate';
$lang->execution->newEstimate     = 'New Estimate';
$lang->execution->reestimate      = 'Reestimate';
$lang->execution->selectRound     = 'Select Round';
$lang->execution->average         = 'Average';
$lang->execution->relatedMember   = 'Đội nhóm';
$lang->execution->watermark       = 'Đã xuất bởi ZenTao';
$lang->execution->burnXUnit       = '(Date)';
$lang->execution->burnYUnit       = '(Hours)';
$lang->execution->waitTasks       = 'Waiting Tasks';
$lang->execution->viewByUser      = 'Theo người dùng';
$lang->execution->oneProduct      = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct   = "Stage not linked {$lang->productCommon}";
$lang->execution->recent          = 'Recent visits: ';
$lang->execution->copyNoExecution = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->noTeam          = 'No team members at the moment';
$lang->execution->or              = ' or ';
$lang->execution->selectProject   = 'Please select project';
$lang->execution->copyTeamTip     = "select Project/{$lang->execution->common} to copy its members";

$lang->execution->start    = 'Bắt đầu';
$lang->execution->activate = 'Kích hoạt';
$lang->execution->putoff   = 'Tạm ngưng';
$lang->execution->suspend  = 'Đình chỉ';
$lang->execution->close    = 'Đóng';
$lang->execution->export   = 'Xuất';

$lang->execution->endList[7]   = '1 tuần';
$lang->execution->endList[14]  = '2 tuần';
$lang->execution->endList[31]  = '1 tháng';
$lang->execution->endList[62]  = '2 tháng';
$lang->execution->endList[93]  = '3 tháng';
$lang->execution->endList[186] = '6 tháng';
$lang->execution->endList[365] = '1 năm';

$lang->execution->lifeTimeList['short'] = "Short-Term";
$lang->execution->lifeTimeList['long']  = "Long-Term";
$lang->execution->lifeTimeList['ops']   = "DevOps";

$lang->team = new stdclass();
$lang->team->account    = 'Người dùng';
$lang->team->role       = 'Vai trò';
$lang->team->roleAB     = 'Vai trò của tôi';
$lang->team->join       = 'Đã tham gia';
$lang->team->hours      = 'Giờ/ngày';
$lang->team->days       = 'Ngày';
$lang->team->totalHours = 'Tổng số giờ';

$lang->team->limited = 'Người dùng hạn chế';
$lang->team->limitedList['yes'] = 'Có';
$lang->team->limitedList['no']  = 'Không';

$lang->execution->basicInfo = 'Thông tin cơ bản';
$lang->execution->otherInfo = 'Thông tin khác';

/* Field value list. */
$lang->execution->statusList['wait']      = 'Đang đợi';
$lang->execution->statusList['doing']     = 'Đang làm';
$lang->execution->statusList['suspended'] = 'Đã đình chỉ';
$lang->execution->statusList['closed']    = 'Đã đóng';

global $config;
$lang->execution->aclList['private'] = 'Private (for team members and project stakeholders)';
$lang->execution->aclList['open']    = 'Inherited Execution ACL (for who can access the current project)';

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Method list. */
$lang->execution->index             = "{$lang->executionCommon} Home";
$lang->execution->task              = 'Danh sách nhiệm vụ';
$lang->execution->groupTask         = 'Xem kiểu nhóm';
$lang->execution->story             = 'Danh sách câu chuyện';
$lang->execution->qa                = 'QA';
$lang->execution->bug               = 'Danh sách Bug';
$lang->execution->testcase          = 'Testcase List';
$lang->execution->dynamic           = 'Lịch sử';
$lang->execution->latestDynamic     = 'Lịch sử';
$lang->execution->build             = 'Danh sách bản dựng';
$lang->execution->testtask          = 'Request';
$lang->execution->burn              = 'Burndown';
$lang->execution->computeBurn       = 'Cập nhật';
$lang->execution->burnData          = 'Dữ liệu Burndown';
$lang->execution->fixFirst          = 'Sửa ngày đầu dự toán';
$lang->execution->team              = 'Thành viên';
$lang->execution->doc               = 'Tài liệu';
$lang->execution->doclib            = 'Thư viện tài liệu';
$lang->execution->manageProducts    = $lang->productCommon.' liên kết';
$lang->execution->linkStory         = 'Liên kết câu chuyện';
$lang->execution->linkStoryByPlan   = 'Liên kết câu chuyện theo kế hoạch';
$lang->execution->linkPlan          = 'Kế hoạch liên kết';
$lang->execution->unlinkStoryTasks  = 'Hủy liên kết';
$lang->execution->linkedProducts    = "{$lang->productCommon} liên kết";
$lang->execution->unlinkedProducts  = "{$lang->productCommon} đã hủy liên kết";
$lang->execution->view              = "Chi tiết Execution";
$lang->execution->startAction       = "Bắt đầu Execution";
$lang->execution->activateAction    = "Kích hoạt Execution";
$lang->execution->delayAction       = "Tạm ngưng Execution";
$lang->execution->suspendAction     = "Đình chỉ Execution";
$lang->execution->closeAction       = "Đóng Execution";
$lang->execution->testtaskAction    = "Yêu cầu Execution";
$lang->execution->teamAction        = "Thành viên Execution";
$lang->execution->kanbanAction      = "Kanban Execution";
$lang->execution->printKanbanAction = "In Kanban";
$lang->execution->treeAction        = "Xem cây Execution";
$lang->execution->exportAction      = "Xuất Execution";
$lang->execution->computeBurnAction = "Tính Burndown";
$lang->execution->create            = "Tạo {$lang->executionCommon}";
$lang->execution->copy              = "Sao chép Execution";
$lang->execution->delete            = "Xóa Execution";
$lang->execution->copy              = "Copy {$lang->executionCommon}";
$lang->execution->delete            = "Delete {$lang->executionCommon}";
$lang->execution->deleteAB          = "Delete Execution";
$lang->execution->browse            = "{$lang->executionCommon} List";
$lang->execution->list              = "{$lang->executionCommon} List";
$lang->execution->edit              = "Sửa {$lang->executionCommon}";
$lang->execution->editAB            = "Edit Execution";
$lang->execution->batchEdit         = "Sửa hàng loạt";
$lang->execution->batchEditAB       = "Batch Edit";
$lang->execution->manageMembers     = 'Quản lý đội nhóm';
$lang->execution->unlinkMember      = 'Gỡ bỏ thành viên';
$lang->execution->unlinkStory       = 'Hủy liên kết câu chuyện';
$lang->execution->unlinkStoryAB     = 'Hủy liên kết';
$lang->execution->batchUnlinkStory  = 'Hủy liên kết câu chuyện hàng loạt';
$lang->execution->importTask        = 'Chuyển thành nhiệm vụ';
$lang->execution->importPlanStories = 'Liên kết câu chuyện theo kế hoạch';
$lang->execution->importBug         = 'Nhập Bug';
$lang->execution->tree              = 'Cây';
$lang->execution->treeTask          = 'Chỉ hiện nhiệm vụ';
$lang->execution->treeStory         = 'Chỉ hiện câu chuyện';
$lang->execution->treeViewTask      = 'Chỉ hiện nhiệm vụ';
$lang->execution->treeViewStory     = 'Chỉ hiện câu chuyện';
$lang->execution->storyKanban       = 'Kanban câu chuyện';
$lang->execution->storySort         = 'Đánh giá câu chuyện';
$lang->execution->importPlanStory   = $lang->executionCommon . ' được tạo!\nBạn có muốn nhập các câu chuyện mà đã liên kết đến kế hoạch này?';
$lang->execution->iteration         = 'Lặp lại';
$lang->execution->iterationInfo     = '%s lần lặp lại';
$lang->execution->viewAll           = 'Xem tất cả';

/* Group browsing. */
$lang->execution->allTasks     = 'Tất cả';
$lang->execution->assignedToMe = 'Giao cho bạn';
$lang->execution->myInvolved   = 'Liên đới';

$lang->execution->statusSelects['']             = 'Thêm';
$lang->execution->statusSelects['wait']         = 'Đang đợi';
$lang->execution->statusSelects['doing']        = 'Đang làm';
$lang->execution->statusSelects['undone']       = 'Chưa kết thúc';
$lang->execution->statusSelects['finishedbyme'] = 'Kết thúc bởi bạn';
$lang->execution->statusSelects['done']         = 'Hoàn thành';
$lang->execution->statusSelects['closed']       = 'Đã đóng';
$lang->execution->statusSelects['cancel']       = 'Đã hủy';

$lang->execution->groups['']           = 'Xem theo nhóm';
$lang->execution->groups['story']      = 'câu chuyện';
$lang->execution->groups['status']     = 'tình trạng';
$lang->execution->groups['pri']        = 'ưu tiên';
$lang->execution->groups['assignedTo'] = 'Người được giao';
$lang->execution->groups['finishedBy'] = 'Người kết thúc';
$lang->execution->groups['closedBy']   = 'người đóng';
$lang->execution->groups['type']       = 'loại';

$lang->execution->groupFilter['story']['all']         = 'Tất cả';
$lang->execution->groupFilter['story']['linked']      = 'Nhiệm vụ liên kết tới câu chuyện';
$lang->execution->groupFilter['pri']['all']           = 'Tất cả';
$lang->execution->groupFilter['pri']['noset']         = 'Chưa thiết lập';
$lang->execution->groupFilter['assignedTo']['undone'] = 'Chưa kết thúc';
$lang->execution->groupFilter['assignedTo']['all']    = 'Tất cả';

$lang->execution->byQuery = 'Tìm kiếm';

/* Query condition list. */
$lang->execution->allExecution      = "Tất cả {$lang->executionCommon}";
$lang->execution->aboveAllProduct = "Tất cả {$lang->productCommon} trên";
$lang->execution->aboveAllExecution = "Tất cả {$lang->executionCommon} trên";

/* Page prompt. */
$lang->execution->linkStoryByPlanTips = "Hành động này sẽ liên kết tất cả câu chuyện trong kế hoạch này tới {$lang->executionCommon} này.";
$lang->execution->selectExecution     = "Chọn {$lang->executionCommon}";
$lang->execution->beginAndEnd         = 'Thời gian';
$lang->execution->lblStats            = 'Chấm công';
$lang->execution->stats               = 'Khả dụng: <strong>%s</strong>(giờ). Dự tính: <strong>%s</strong>(giờ). Đã làm: <strong>%s</strong>(giờ). Còn: <strong>%s</strong>(giờ).';
$lang->execution->taskSummary         = "Tổng nhiệm vụ:<strong>%s</strong>. Đang đợi: <strong>%s</strong>. Đang làm: <strong>%s</strong>.  &nbsp;&nbsp;&nbsp;  Dự tính: <strong>%s</strong>(giờ). Đã làm: <strong>%s</strong>(giờ). Còn: <strong>%s</strong>(giờ).";
$lang->execution->pageSummary         = "Tổng nhiệm vụ: <strong>%total%</strong>. Đang đợi: <strong>%wait%</strong>. Đang làm: <strong>%doing%</strong>. Dự tính: <strong>%estimate%</strong>(giờ). Đã làm: <strong>%consumed%</strong>(giờ). Còn: <strong>%left%</strong>(giờ).";
$lang->execution->checkedSummary      = "Đã chọn: <strong>%total%</strong>. Đang đợi: <strong>%wait%</strong>. Đang làm: <strong>%doing%</strong>. Dự tính: <strong>%estimate%</strong>(giờ). Đã làm: <strong>%consumed%</strong>(giờ). Còn: <strong>%left%</strong>(giờ).";
$lang->execution->memberHoursAB       = "%s có <strong>%s</ strong> giờ.";
$lang->execution->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Giờ khả dụng </div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Nhiệm vụ</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Đang làm</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Đang đợi</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Dự tính</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Đã làm</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Còn lại</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB      = "<div>Nhiêm vụ <strong>%s ：</strong><span class='text-muted'>Đang đợi</span> %s &nbsp; <span class='text-muted'>Đang làm</span> %s</div><div>Dự tính <strong>%s ：</strong><span class='text-muted'>Đã làm</span> %s &nbsp; <span class='text-muted'>Còn lại</span> %s</div>";
$lang->execution->wbs                 = "Tạo nhiệm vụ";
$lang->execution->batchWBS            = "Tạo nhiệm vụ hàng loạt";
$lang->execution->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Làm thế nào để cập nhật biểu đồ Burndown?' class='btn btn-link'>Giúp<i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories        = "Không có câu chuyện có thể liên kết. Vui lòng kiểm tra có câu chuyện trong {$lang->executionCommon} cái liên kết tới {$lang->productCommon} và chắc chắn nó đã được duyệt.";
$lang->execution->projectNoStories    = "No story can be linked. Please check whether there is any story in project and make sure it has been reviewed.";
$lang->execution->productStories      = "Câu chuyện liên kết tới {$lang->executionCommon} là tập hợp con của câu chuyện liên kết tới {$lang->productCommon}. Các câu chuyện chỉ có thể liên kết sau khi chúng đã được duyệt. <a href='%s'> Liên kết câu chuyện</a> now.";
$lang->execution->haveDraft           = "%s câu chuyện đang nháp, bởi vậy chúng không thể liên kết.";
$lang->execution->doneExecutions      = 'Kết thúc';
$lang->execution->selectDept          = 'Chọn phòng/ban';
$lang->execution->selectDeptTitle     = 'Chọn người dùng';
$lang->execution->copyTeam            = 'Copy đội nhóm';
$lang->execution->copyFromTeam        = "Sao chép từ {$lang->executionCommon} Đội nhóm: <strong>%s</strong>";
$lang->execution->noMatched           = "Không có $lang->executionCommon bao gồm '%s' có thể được tìm thấy.";
$lang->execution->copyTitle           = "Chọn một {$lang->executionCommon} để sao chép.";
$lang->execution->copyTeamTitle       = "Chọn một {$lang->executionCommon} đội nhóm để sao chép.";
$lang->execution->copyNoExecution     = "Không có {$lang->executionCommon} có thể sao chép.";
$lang->execution->copyFromExecution   = "Sao chép từ {$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy          = 'Hủy sao chép';
$lang->execution->byPeriod            = 'Theo thời gian';
$lang->execution->byUser              = 'Theo người dùng';
$lang->execution->noExecution         = "Không có {$lang->executionCommon} nào";
$lang->execution->noExecutions        = "Không có {$lang->execution->common} nào";
$lang->execution->noMembers           = 'Không có thành viên đội nhóm nào';
$lang->execution->workloadTotal       = "The cumulative workload ratio should not exceed 100, and the total workload under the current product is: %s";
$lang->execution->linkPRJStoryTip     = "(Link {$lang->SRCommon} comes from {$lang->SRCommon} linked under the project)";
$lang->execution->linkAllStoryTip     = "({$lang->SRCommon} has never been linked under the project, and can be directly linked with {$lang->SRCommon} of the product linked with the sprint/stage)";

/* Interactive prompts. */
$lang->execution->confirmDelete               = "Bạn có muốn xóa {$lang->executionCommon}[%s] này?";
$lang->execution->confirmUnlinkMember         = "Bạn có muốn hủy liên kết người dùng này từ {$lang->executionCommon}?";
$lang->execution->confirmUnlinkStory          = "After {$lang->SRCommon} is removed, cased linked to {$lang->SRCommon} will be reomoved and tasks linked to {$lang->SRCommon} will be cancelled. Do you want to continue?";
$lang->execution->confirmUnlinkExecutionStory = "Do you want to unlink this Story from the project?";
$lang->execution->notAllowedUnlinkStory       = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the project. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts      = "The story of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts       = "Không có {$lang->productCommon} liên kết tới {$lang->executionCommon}. Bạn sẽ được chuyển tới trang  {$lang->productCommon} để liên kết.";
$lang->execution->errorSameProducts           = "{$lang->executionCommon} không thể liên kết tới {$lang->productCommon} giống nhau 2 lần.";
$lang->execution->errorBegin                  = "The start time of {$lang->executionCommon} cannot be less than the start time of the project %s.";
$lang->execution->errorEnd                    = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the project.";
$lang->execution->accessDenied                = "Truy cập của bạn tới {$lang->executionCommon} bị từ chối!";
$lang->execution->tips                        = 'Ghi chú';
$lang->execution->afterInfo                   = "{$lang->executionCommon} được tạo. Tiếp theo bạn có thể ";
$lang->execution->setTeam                     = 'Thiết lập đội nhóm';
$lang->execution->linkStory                   = 'Liên kết câu chuyện';
$lang->execution->createTask                  = 'Tạo nhiệm vụ';
$lang->execution->goback                      = "Trở lại";
$lang->execution->noweekend                   = 'Không gồm cuối tuần';
$lang->execution->withweekend                 = 'Bao gồm cuối tuần';
$lang->execution->interval                    = 'Intervals ';
$lang->execution->fixFirstWithLeft            = 'Cập nhật cả giờ còn lại';
$lang->execution->unfinishedExecution         = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask              = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug               = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty             = 'Project cannot be empty.';
$lang->execution->errorCommonBegin            = 'The start date of ' . $lang->executionCommon . ' should be ≥ the start date of project : %s.';
$lang->execution->errorCommonEnd              = 'The deadline of ' . $lang->executionCommon .  ' should be ≤ the deadline of project : %s.';

/* Statistics. */
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = " Burndown Chart";
$lang->execution->charts->burn->graph->xAxisName    = "Ngày";
$lang->execution->charts->burn->graph->yAxisName    = "Giờ";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = 'KH';
$lang->execution->charts->burn->graph->actuality    = 'Thực tế';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = "Viết tắt của tên {$lang->executionCommon}";
$lang->execution->placeholder->totalLeft = "Giờ dự kiến trong ngày đầu của {$lang->executionCommon}.";

$lang->execution->selectGroup       = new stdclass();
$lang->execution->selectGroup->done = '(Hoàn thành)';

$lang->execution->orderList['order_asc']  = "Đánh giá câu chuyện tăng dần";
$lang->execution->orderList['order_desc'] = "Đánh giá câu chuyện giảm dần";
$lang->execution->orderList['pri_asc']    = "Ưu tiên câu chuyện tăng dần";
$lang->execution->orderList['pri_desc']   = "Ưu tiên câu chuyện giảm dần";
$lang->execution->orderList['stage_asc']  = "Giai đoạn câu chuyện tăng dần";
$lang->execution->orderList['stage_desc'] = "Giai đoạn câu chuyện giảm dần";

$lang->execution->kanban        = "Kanban";
$lang->execution->kanbanSetting = "Thiết lập";
$lang->execution->resetKanban   = "Thiết lập lại";
$lang->execution->printKanban   = "In";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Cột Đã đóng & đã hủy';
$lang->execution->kanbanShowOption = 'Mở ra';
$lang->execution->kanbanColsColor  = 'Tùy biến màu cột';

$lang->execution->kanbanViewList['all']   = 'All';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}";
$lang->execution->kanbanViewList['bug']   = 'Bug';
$lang->execution->kanbanViewList['task']  = 'Task';

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

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = 'Stage';
$lang->execution->typeList['sprint'] = $lang->executionCommon;

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['delayed']      = 'Tạm ngưng';
$lang->execution->featureBar['task']['needconfirm']  = 'Đã thay đổi';
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = 'All';
$lang->execution->featureBar['bug']['unresolved'] = 'Active';

$lang->execution->featureBar['build']['all'] = 'Build List';

$lang->execution->myExecutions = 'Tôi tham gia';
$lang->execution->doingProject = 'Hiện trường';

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . ' ' . $lang->execution->common;

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Mở tất cả';
$lang->execution->treeLevel['root']  = 'Co lại tất cả';
$lang->execution->treeLevel['task']  = 'Câu chuyện & nhiệm vụ';
$lang->execution->treeLevel['story'] = 'Chỉ câu chuyện';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

$lang->execution->teamWords  = 'đội';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');
