<?php
global $config;

$lang->custom->common               = 'Tùy biến';
$lang->custom->index                = 'Trang chủ';
$lang->custom->set                  = 'Tùy biến';
$lang->custom->restore              = 'Thiết lập lại';
$lang->custom->key                  = 'Khóa';
$lang->custom->value                = 'Giá trị';
$lang->custom->flow                 = 'Mô hình';
$lang->custom->working              = 'Chế độ';
$lang->custom->select               = 'Chọn mô hình';
$lang->custom->branch               = 'Multi-Branch';
$lang->custom->owner                = 'Sở hữu';
$lang->custom->module               = 'Module';
$lang->custom->section              = 'Section';
$lang->custom->lang                 = 'Ngôn ngữ';
$lang->custom->setPublic            = 'Thiết lập Public';
$lang->custom->required             = 'Trường bắt buộc';
$lang->custom->score                = 'Điểm';
$lang->custom->timezone             = 'Timezone';
$lang->custom->scoreReset           = 'Thiết lập lại điểm';
$lang->custom->scoreTitle           = 'Tính năng điểm';
$lang->custom->product              = $lang->productCommon;
$lang->custom->convertFactor        = 'Convert factor';
$lang->custom->region               = 'Interval';
$lang->custom->tips                 = 'Tips';
$lang->custom->setTips              = 'Set Tips';
$lang->custom->isRange              = 'Is Target Control';
$lang->custom->concept              = "Concept";
$lang->custom->URStory              = "User requirements";
$lang->custom->SRStory              = "Software requirements";
$lang->custom->epic                 = "Epic";
$lang->custom->default              = "Default";
$lang->custom->mode                 = "Mode";
$lang->custom->scrumStory           = "Story";
$lang->custom->waterfallCommon      = "Waterfall";
$lang->custom->buildin              = "Buildin";
$lang->custom->editStoryConcept     = "Edit Story Concept";
$lang->custom->setStoryConcept      = "Set Story Concept";
$lang->custom->setDefaultConcept    = "Set Default Concept";
$lang->custom->browseStoryConcept   = "List of story concepts";
$lang->custom->deleteStoryConcept   = "Delete story Concept";
$lang->custom->URConcept            = "UR Concept";
$lang->custom->SRConcept            = "SR Concept";
$lang->custom->switch               = "Switch";
$lang->custom->oneUnit              = "One {$lang->hourCommon}";
$lang->custom->convertRelationTitle = "Please set the conversion factor of {$lang->hourCommon} to %s first";

if($config->systemMode == 'new') $lang->custom->execution = 'Execution';
if($config->systemMode == 'classic' || !$config->systemMode) $lang->custom->execution = $lang->executionCommon;

$lang->custom->unitList['efficiency'] = 'Working Hours/';
$lang->custom->unitList['manhour']    = 'Man-hour/';
$lang->custom->unitList['cost']       = 'Yuan/Hour';
$lang->custom->unitList['hours']      = 'Hours';
$lang->custom->unitList['days']       = 'Days';
$lang->custom->unitList['loc']        = 'KLOC';

$lang->custom->tipProgressList['SPI'] = 'Schedule Performance Index(SPI)';
$lang->custom->tipProgressList['SV']  = 'Schedule Variance(SV%)';

$lang->custom->tipCostList['CPI'] = 'Cost Performed Index(CPI)';
$lang->custom->tipCostList['CV']  = 'Cost Variance(CV%)';

$lang->custom->tipRangeList[0]  = 'No';
$lang->custom->tipRangeList[1]  = 'Yes';

$lang->custom->regionMustNumber    = 'The interval must be a number!';
$lang->custom->tipNotEmpty         = 'The prompt can not be empty!';
$lang->custom->currencyNotEmpty    = 'You have to select one currency at least.';
$lang->custom->defaultNotEmpty     = 'The default currency can not bu empty';
$lang->custom->convertRelationTips = "After {$lang->hourCommon} is converted to %s, historical data will be uniformly converted to %s";
$lang->custom->saveTips            = 'After clicking save, the current %s will be used as the default estimation unit';

$lang->custom->numberError = 'The interval must be greater than zero!';

$lang->custom->closedExecution = 'Closed ' . $lang->executionCommon;
$lang->custom->closedProduct   = 'Closed ' . $lang->productCommon;

if($config->systemMode == 'new') $lang->custom->object['project']   = 'Project';
$lang->custom->object['product']   = $lang->productCommon;
$lang->custom->object['execution'] = $lang->custom->execution;
$lang->custom->object['story']     = 'Câu chuyện';
$lang->custom->object['task']      = 'Nhiệm vụ';
$lang->custom->object['bug']       = 'Bug';
$lang->custom->object['testcase']  = 'Tình huống';
$lang->custom->object['testtask']  = 'Bản dựng';
$lang->custom->object['todo']      = 'Việc làm';
$lang->custom->object['user']      = 'Người dùng';
$lang->custom->object['block']     = 'Block';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = 'Currency Setting';
$lang->custom->project->defaultCurrency    = 'Default Currency';
$lang->custom->project->fields['unitList'] = 'Unit List';

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

$lang->custom->system = array('required', 'flow', 'score');

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = 'Đã đóng Block';

$lang->custom->currentLang = 'Ngôn ngữ hiện tại';
$lang->custom->allLang     = 'Tất cả ngôn ngữ';

$lang->custom->confirmRestore = 'Bạn có muốn thiết lập lại?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = 'Control whether the above fields are displayed on the user-related page. Leave it blank to display all.';
$lang->custom->notice->canNotAdd           = 'Mục này không được tính, bởi vậy tùy biến không thể kích hoạt';
$lang->custom->notice->forceReview         = '%s review là bắt buộc for committers selected.';
$lang->custom->notice->forceNotReview      = "%s review không là required for committers selected.";
$lang->custom->notice->longlife            = 'Xác định số ngày tối đa cho phép đình trệ Bugs.';
$lang->custom->notice->invalidNumberKey    = 'Khóa này nên =< 255.';
$lang->custom->notice->invalidStringKey    = 'Khóa này nên lowercase letters, numbers or underlines.';
$lang->custom->notice->cannotSetTimezone   = 'date_default_timezone_set does not exist or is disabled. Timezone cannot be set.';
$lang->custom->notice->noClosedBlock       = 'Bạn không có blocks mà đã đóng vĩnh viễn.';
$lang->custom->notice->required            = 'Trường được chọn là bắt buộc.';
$lang->custom->notice->conceptResult       = 'According to your preference, <b> %s-%s </b> is set for you. Sử dụng <b>%s</b> + <b> %s</b>.';
$lang->custom->notice->conceptPath         = 'Vào Quản trị -> Tùy biến -> Mô hình để thiết lập nó.';
$lang->custom->notice->readOnlyOfProduct   = 'If Change Forbidden, any change on stories, bugs, cases, efforts, releases and plans of the closed product is also forbidden.';
$lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts and stories of the closed {$lang->executionCommon} is also forbidden.";
$lang->custom->notice->URSREmpty           = 'Custom requirement name can not be empty!';
$lang->custom->notice->confirmDelete       = 'Are you sure you want to delete it?';

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

global $config;
if($config->systemMode == 'classic')
{
    $lang->custom->sprintConceptList[0] = 'Product - Project';
    $lang->custom->sprintConceptList[1] = 'Product - Iteration';
    $lang->custom->sprintConceptList[2] = 'Product - Sprint';
}
else
{
    $lang->custom->sprintConceptList[0] = 'Program - Product - Iteration';
    $lang->custom->sprintConceptList[1] = 'Program - Product - Sprint';
}

$lang->custom->workingList['full']      = 'Quản lý vòng đời ứng dụng';

$lang->custom->menuTip  = 'Click để hiện/ẩn menu. Kéo thả để chuyển vị trí hiển thị.';
$lang->custom->saveFail = 'Lưu thất bại!';
$lang->custom->page     = '';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->CRProduct[1] = 'Change Allowed';
$lang->custom->CRProduct[0] = 'Change Forbidden';

$lang->custom->CRExecution[1] = 'Change Allowed';
$lang->custom->CRExecution[0] = 'Change Forbidden';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Kế hoạch';
$lang->custom->moduleName['execution']   = $lang->custom->execution;

$lang->custom->conceptQuestions['overview'] = "1. Sự kết hợp quản lý nào phù hợp với công ty của bạn?";
$lang->custom->conceptQuestions['URAndSR']  = "2. Do you want to use the concept of {$lang->URCommon} and {$lang->SRCommon} in ZenTao?";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = 'Điều kiện';
$lang->custom->conceptOptions->story['1'] = 'Câu chuyện';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = 'Yes';
$lang->custom->conceptOptions->URAndSR['0'] = 'No';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'giờ';
$lang->custom->conceptOptions->hourPoint['1'] = 'Điểm';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Point';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = 'Set concept';
