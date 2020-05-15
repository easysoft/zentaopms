<?php
$lang->custom->common     = 'Tùy biến';
$lang->custom->index      = 'Trang chủ';
$lang->custom->set        = 'Tùy biến';
$lang->custom->restore    = 'Thiết lập lại';
$lang->custom->key        = 'Khóa';
$lang->custom->value      = 'Giá trị';
$lang->custom->flow       = 'Mô hình';
$lang->custom->working    = 'Chế độ';
$lang->custom->select     = 'Chọn mô hình';
$lang->custom->branch     = 'Multi-Branch';
$lang->custom->owner      = 'Sở hữu';
$lang->custom->module     = 'Module';
$lang->custom->section    = 'Section';
$lang->custom->lang       = 'Ngôn ngữ';
$lang->custom->setPublic  = 'Thiết lập Public';
$lang->custom->required   = 'Trường bắt buộc';
$lang->custom->score      = 'Điểm';
$lang->custom->timezone   = 'Timezone';
$lang->custom->scoreReset = 'Thiết lập lại điểm';
$lang->custom->scoreTitle = 'Tính năng điểm';

$lang->custom->object['story']    = 'Câu chuyện';
$lang->custom->object['task']     = 'Nhiệm vụ';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = 'Tình huống';
$lang->custom->object['testtask'] = 'Bản dựng';
$lang->custom->object['todo']     = 'Việc làm';
$lang->custom->object['user']     = 'Người dùng';
$lang->custom->object['block']    = 'Block';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = 'Ưu tiên';
$lang->custom->story->fields['sourceList']       = 'Nguồn';
$lang->custom->story->fields['reasonList']       = 'Lý do đóng';
$lang->custom->story->fields['stageList']        = 'Giai đoạn';
$lang->custom->story->fields['statusList']       = 'Tình trạng';
$lang->custom->story->fields['reviewResultList'] = 'Duyệt kết quả';
$lang->custom->story->fields['review']           = 'Duyệt nhu cầu';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = 'Ưu tiên';
$lang->custom->task->fields['typeList']   = 'Loại';
$lang->custom->task->fields['reasonList'] = 'Lý do đóng';
$lang->custom->task->fields['statusList'] = 'Tình trạng';
$lang->custom->task->fields['hours']      = 'Chấm công';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = 'Ưu tiên';
$lang->custom->bug->fields['severityList']   = 'Mức độ';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Loại';
$lang->custom->bug->fields['resolutionList'] = 'Giải pháp';
$lang->custom->bug->fields['statusList']     = 'Tình trạng';
$lang->custom->bug->fields['longlife']       = 'Ngày định trệ';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = 'Ưu tiên';
$lang->custom->testcase->fields['typeList']   = 'Loại';
$lang->custom->testcase->fields['stageList']  = 'Giai đoạn';
$lang->custom->testcase->fields['resultList'] = 'Kết quả';
$lang->custom->testcase->fields['statusList'] = 'Tình trạng';
$lang->custom->testcase->fields['review']     = 'Duyệt nhu cầu';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = 'Ưu tiên';
$lang->custom->testtask->fields['statusList'] = 'Tình trạng';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Ưu tiên';
$lang->custom->todo->fields['typeList']   = 'Loại';
$lang->custom->todo->fields['statusList'] = 'Tình trạng';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = 'Vai trò';
$lang->custom->user->fields['statusList']   = 'Tình trạng';
$lang->custom->user->fields['contactField'] = 'Liên hệ có sẵn';
$lang->custom->user->fields['deleted']      = 'Người dùng đã xóa';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = 'Đã đóng Block';

$lang->custom->currentLang = 'Ngôn ngữ hiện tại';
$lang->custom->allLang     = 'Tất cả ngôn ngữ';

$lang->custom->confirmRestore = 'Bạn có muốn thiết lập lại?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice   = 'Control whether the above fields are displayed on the user-related page. Leave it blank to display all.';
$lang->custom->notice->canNotAdd         = 'Mục này không được tính, bởi vậy tùy biến không thể kích hoạt';
$lang->custom->notice->forceReview       = '%s review là bắt buộc for committers selected.';
$lang->custom->notice->forceNotReview    = "%s review không là required for committers selected.";
$lang->custom->notice->longlife          = 'Xác định số ngày tối đa cho phép đình trệ Bugs.';
$lang->custom->notice->invalidNumberKey  = 'Khóa này nên =< 255.';
$lang->custom->notice->invalidStringKey  = 'Khóa này nên lowercase letters, numbers or underlines.';
$lang->custom->notice->cannotSetTimezone = 'date_default_timezone_set does not exist or is disabled. Timezone cannot be set.';
$lang->custom->notice->noClosedBlock     = 'Bạn không có blocks mà đã đóng vĩnh viễn.';
$lang->custom->notice->required          = 'Trường được chọn là bắt buộc.';
$lang->custom->notice->conceptResult     = 'According to your preference, <b> %s-%s </b> is set for you. Sử dụng <b>%s</b> + <b> %s</b>.';
$lang->custom->notice->conceptPath       = 'Vào Quản trị -> Tùy biến -> Mô hình để thiết lập nó.';

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ đã có trang Sản phẩm. Bạn có muốn tới trang Sản phẩm?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ has Project Home. Bạn có muốn go to Project Home?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ has QA Homepage. Bạn có muốn go to QA Homepage?";

$lang->custom->notice->invalidStrlen['ten']        = 'Khóa này nên <= 10 ký tự.';
$lang->custom->notice->invalidStrlen['twenty']     = 'Khóa này nên <= 20 ký tự.';
$lang->custom->notice->invalidStrlen['thirty']     = 'Khóa này nên <= 30 ký tự.';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'Khóa này nên <= 225 ký tự.';

$lang->custom->storyReview    = 'Xét duyệt';
$lang->custom->forceReview    = 'Duyệt Required';
$lang->custom->forceNotReview = 'Không có xét duyệt được yêu cầu';
$lang->custom->reviewList[1]  = 'On';
$lang->custom->reviewList[0]  = 'Off';

$lang->custom->deletedList[1] = 'Hiện';
$lang->custom->deletedList[0] = 'Ẩn';

$lang->custom->workingHours   = 'Giờ/ngày';
$lang->custom->weekend        = 'Cuối tuần';
$lang->custom->weekendList[2] = '2 ngày nghỉ';
$lang->custom->weekendList[1] = '1 ngày nghỉ';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'Sản phẩm - Dự án';
$lang->custom->productProject->relation['0_1'] = 'Sản phẩm - lặp lại';
$lang->custom->productProject->relation['1_1'] = 'Dự án - lặp lại';
$lang->custom->productProject->relation['0_2'] = 'Sản phẩm - Sprint';
$lang->custom->productProject->relation['1_2'] = 'Dự án - Sprint';

$lang->custom->productProject->notice = 'Chọn một mô hình phù hợp với doanh nghiệp của bạn.';

$lang->custom->workingList['full']      = 'Quản lý vòng đời ứng dụng';
$lang->custom->workingList['onlyTest']  = 'Quản lý kiểm thử';
$lang->custom->workingList['onlyStory'] = 'Quản lý câu chuyện';
$lang->custom->workingList['onlyTask']  = 'Quản lý nhiệm vụ';

$lang->custom->menuTip  = 'Click để hiện/ẩn menu. Kéo thả để chuyển vị trí hiển thị.';
$lang->custom->saveFail = 'Lưu thất bại!';
$lang->custom->page     = '';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Kế hoạch';
$lang->custom->moduleName['project']     = $lang->projectCommon;

$lang->custom->conceptQuestions['overview']         = "1. Sự kết hợp quản lý nào phù hợp với công ty của bạn?";
$lang->custom->conceptQuestions['story']            = "2. Bạn có sử dụng mô hình điều kiện hay câu chuyện người dùng trong doanh nghiệp của bạn?";
$lang->custom->conceptQuestions['requirementpoint'] = "3. Do you use hours or function points to make estimations in your company?";
$lang->custom->conceptQuestions['storypoint']       = "3. Bạn có dùng giờ hoặc điểm để tạo dự kiến trong doanh nghiệp của bạn ?";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = 'Điều kiện';
$lang->custom->conceptOptions->story['1'] = 'Câu chuyện';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Giờ';
$lang->custom->conceptOptions->hourPoint['1'] = 'Điểm';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Point';
