<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  product
 * @version  $Id: vi.php 5091 2013-07-10 06:06:46Z quocnho@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->product->index         = 'Trang '.$lang->productCommon;
$lang->product->browse        = 'Danh sách câu chuyện';
$lang->product->dynamic       = 'Lịch sử';
$lang->product->view          = "Chi tiết {$lang->productCommon}";
$lang->product->edit          = "Sửa {$lang->productCommon}";
$lang->product->batchEdit     = 'Sửa hàng loạt';
$lang->product->create        = "Tạo {$lang->productCommon}";
$lang->product->delete        = "Xóa {$lang->productCommon}";
$lang->product->deleted       = 'Đã xóa';
$lang->product->close         = "Đóng";
$lang->product->closeAction   = "Đóng {$lang->productCommon}";
$lang->product->select        = "Chọn {$lang->productCommon}";
$lang->product->mine          = 'Của bạn';
$lang->product->other         = 'Khác';
$lang->product->closed        = 'Đã đóng';
$lang->product->updateOrder   = 'Sắp xếp';
$lang->product->orderAction   = "Đánh giá {$lang->productCommon}";
$lang->product->all           = "{$lang->productCommon} List";
$lang->product->manageLine    = "Manage {$lang->productCommon} Line";
$lang->product->newLine       = "Create {$lang->productCommon} Line";
$lang->product->export        = 'Xuất';
$lang->product->exportAction  = "Xuất {$lang->productCommon}";
$lang->product->dashboard     = "Dashboard";
$lang->product->changeProgram = "{$lang->productCommon} confirmation of the scope of influence of adjustment of the program set";

$lang->product->basicInfo = 'Thông tin cơ bản';
$lang->product->otherInfo = 'Thông tin khác';

$lang->product->plans       = 'Kế hoạch';
$lang->product->releases    = 'Phát hành';
$lang->product->docs        = 'Tài liệu';
$lang->product->bugs        = 'Bug liên kết';
$lang->product->projects    = "Linked Project";
$lang->product->executions  = "{$lang->execution->common} liên kết";
$lang->product->cases       = 'Tình huống';
$lang->product->builds      = 'Bản dựng';
$lang->product->roadmap     = "Lộ trình {$lang->productCommon}";
$lang->product->doc         = "Tài liệu {$lang->productCommon}";
$lang->product->project     = 'Danh sách '.$lang->executionCommon;
$lang->product->moreProduct = "More Product";
$lang->product->projectInfo = "Projects đã liên kết tới {$lang->productCommon} này được liệt kê bên dưới.";

$lang->product->currentExecution      = "Hiện Execution";
$lang->product->activeStories         = 'Kích hoạt [S]';
$lang->product->activeStoriesTitle    = 'Kích hoạt câu chuyện';
$lang->product->changedStories        = 'Đã thay đổi [S]';
$lang->product->changedStoriesTitle   = 'Đã thay đổi câu chuyện';
$lang->product->draftStories          = 'Nháp [S]';
$lang->product->draftStoriesTitle     = 'Câu chuyện nháp';
$lang->product->closedStories         = 'Đã đóng [S]';
$lang->product->closedStoriesTitle    = 'Câu chuyện đã đóng';
$lang->product->storyCompleteRate     = "{$lang->SRCommon} Completion rate";
$lang->product->activeRequirements    = "Active {$lang->URCommon}";
$lang->product->changedRequirements   = "Changed {$lang->URCommon}";
$lang->product->draftRequirements     = "Draft {$lang->URCommon}";
$lang->product->closedRequirements    = "Closed {$lang->URCommon}";
$lang->product->requireCompleteRate   = "{$lang->URCommon} Completion rate";
$lang->product->unResolvedBugs        = 'Kích hoạt [B]';
$lang->product->unResolvedBugsTitle   = 'Kích hoạt Bugs';
$lang->product->assignToNullBugs      = 'Chưa giao [B]';
$lang->product->assignToNullBugsTitle = 'Chưa giao Bugs';
$lang->product->closedBugs            = 'Closed Bug';
$lang->product->bugFixedRate          = 'Bug Repair rate';

$lang->product->confirmDelete        = " Bạn có muốn xóa {$lang->productCommon} này?";
$lang->product->errorNoProduct       = "Không có {$lang->productCommon} được tạo!";
$lang->product->accessDenied         = "Bạn không có quyền truy cập tới  {$lang->productCommon} này.";
$lang->product->programChangeTip     = "The projects linked with this {$lang->productCommon}: %s will be transferred to the modified program set together.";
$lang->product->notChangeProgramTip  = "The {$lang->SRCommon} of {$lang->productCommon} has been linked to the following projects, please cancel the link before proceeding";
$lang->product->confirmChangeProgram = "The projects linked with this {$lang->productCommon}: %s is also linked with other products, whether to transfer projects to the modified program set.";
$lang->product->changeProgramError   = "The {$lang->SRCommon} of this {$lang->productCommon} has been linked to the project, please unlink it before proceeding";

$lang->product->id             = 'ID';
$lang->product->program        = "Program";
$lang->product->name           = "Tên {$lang->productCommon}";
$lang->product->code           = 'Mã';
$lang->product->shadow         = "Shadow {$lang->productCommon}";
$lang->product->line           = "{$lang->productCommon} Line";
$lang->product->lineName       = "{$lang->productCommon} Line Name";
$lang->product->order          = 'Đánh giá';
$lang->product->type           = 'Loại';
$lang->product->typeAB         = 'Loại AB';
$lang->product->status         = 'Tình trạng';
$lang->product->subStatus      = 'Tình trạng con';
$lang->product->desc           = 'Mô tả';
$lang->product->manager        = 'Quản lý';
$lang->product->PO             = "Sở hữu {$lang->productCommon}";
$lang->product->QD             = 'Quản lý QA';
$lang->product->RD             = 'Quản lý phát hành';
$lang->product->acl            = 'Quyền truy cập';
$lang->product->whitelist      = 'Danh sách trắng';
$lang->product->addWhitelist   = 'Add Whitelist';
$lang->product->unbindWhitelist = 'Remove Whitelist';
$lang->product->branch         = '%s';
$lang->product->qa             = 'QA';
$lang->product->release        = 'Phát hành';
$lang->product->allRelease     = 'Tất cả Phát hành';
$lang->product->maintain       = 'Bảo trì';
$lang->product->latestDynamic  = 'Lịch sử';
$lang->product->plan           = 'Kế hoạch';
$lang->product->iteration      = 'Lặp lại';
$lang->product->iterationInfo  = '%s lặp lại';
$lang->product->iterationView  = 'Chi tiết';
$lang->product->createdBy      = 'Người tạo';
$lang->product->createdDate    = 'Ngày tạo';

$lang->product->searchStory    = 'Tìm kiếm';
$lang->product->assignedToMe   = 'Giao cho bạn';
$lang->product->openedByMe     = 'Tạo bởi bạn';
$lang->product->reviewedByMe   = 'Duyệt bởi bạn';
$lang->product->closedByMe     = 'Đóng bởi bạn';
$lang->product->draftStory     = 'Nháp';
$lang->product->activeStory    = 'Đã kích hoạt';
$lang->product->changingStory  = 'Biến';
$lang->product->reviewingStory = 'Đang xem xét';
$lang->product->willClose      = 'Đã đóng';
$lang->product->closedStory    = 'Đã đóng';
$lang->product->unclosed       = 'Mở';
$lang->product->unplan         = 'Chưa kế hoạch';
$lang->product->viewByUser     = 'Theo người dùng';

$lang->product->allStory             = 'Tất cả ';
$lang->product->allProduct           = 'Tất cả';
$lang->product->allProductsOfProject = 'Tất cả ' . $lang->productCommon.' liên kết';

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Bình thường';
$lang->product->typeList['branch']   = 'Multi-Branch';
$lang->product->typeList['platform'] = 'Multi-Platform';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = ' (đối với ngữ cảnh đã tùy biến. ví dụ:  Đội nhóm thuê ngoài)';
$lang->product->typeTips['platform'] = ' (đối với ứng dụng nhiều Flatform, ví dụ:  IOS, Android, PC, etc.)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Nhánh';
$lang->product->branchName['platform'] = 'Platform';

$lang->product->statusList['normal'] = 'Bình thường';
$lang->product->statusList['closed'] = 'Đã đóng';

$lang->product->aclList['private'] = "Riêng tư {$lang->productCommon} ({$lang->executionCommon} chỉ có thành viên nhóm)";
$lang->product->aclList['open']    = "Mặc định (Người dùng với quyền {$lang->productCommon} có thể truy cập nó.)";
//$lang->product->aclList['custom']  = 'Tùy biến (Thành viên đội nhóm và thành viên danh sách trắng có thể truy cập nó.)';

$lang->product->acls['private'] = "Riêng tư {$lang->productCommon}";
$lang->product->acls['open']    = 'Mặc định';

$lang->product->aclTips['open']    = "Người dùng với quyền {$lang->productCommon} có thể truy cập nó.";
$lang->product->aclTips['private'] = "{$lang->executionCommon} chỉ có thành viên nhóm";

$lang->product->storySummary   = "Tổng <strong>%s</strong> câu chuyện trên trang này. Dự tính: <strong>%s</strong> ({$lang->hourCommon}), và phạm vi tình huống: <strong>%s</strong>.";
$lang->product->checkedSummary = "<strong>%total%</strong> stories selected, Esitmates: <strong>%estimate%</strong> ({$lang->hourCommon}), và phạm vi tình huống: <strong>%rate%</strong>.";
$lang->product->noModule       = '<div>Chưa có Module. </div><div>Quản lý ngay</div>';
$lang->product->noProduct      = "Không có {$lang->productCommon} nào.";
$lang->product->noMatched      = '"%s" không thể tìm thấy ' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['all']      = $lang->product->allProduct;
$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];

$lang->product->moreSelects['closedbyme']     = $lang->product->closedByMe;
$lang->product->moreSelects['activestory']    = $lang->product->activeStory;
$lang->product->moreSelects['changingstory']  = $lang->product->changingStory;
$lang->product->moreSelects['reviewingstory'] = $lang->product->reviewingStory;
$lang->product->moreSelects['willclose']      = $lang->product->willClose;
$lang->product->moreSelects['closedstory']    = $lang->product->closedStory;
