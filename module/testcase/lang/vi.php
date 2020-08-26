<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  testcase
 * @version  $Id: vi.php 4966 2013-07-02 02:59:25Z wyd621@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->testcase->id               = 'ID';
$lang->testcase->product          = $lang->productCommon;
$lang->testcase->module           = 'Module';
$lang->testcase->lib              = "Thư viện tình huống";
$lang->testcase->branch           = "Branch/Platform";
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Câu chuyện';
$lang->testcase->storyVersion     = 'Phiên bản câu chuyện';
$lang->testcase->color            = 'Màu';
$lang->testcase->order            = 'Sắp xếp';
$lang->testcase->title            = 'Tiêu đề';
$lang->testcase->precondition     = 'Điều kiện bắt buộc';
$lang->testcase->pri              = 'Ưu tiên';
$lang->testcase->type             = 'Loại';
$lang->testcase->status           = 'Tình trạng';
$lang->testcase->subStatus        = 'Tình trạng con';
$lang->testcase->steps            = 'Các bước';
$lang->testcase->openedBy         = 'Người tạo';
$lang->testcase->openedDate       = 'Ngày tạo';
$lang->testcase->lastEditedBy     = 'Người sửa';
$lang->testcase->result           = 'Kết quả';
$lang->testcase->real             = 'Chi tiết';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Files';
$lang->testcase->linkCase         = 'Tình huống liên kết';
$lang->testcase->linkCases        = 'Liên kết tình huống';
$lang->testcase->unlinkCase       = 'Hủy liên kết tình huống';
$lang->testcase->stage            = 'Giai đoạn';
$lang->testcase->reviewedBy       = 'Người duyệt';
$lang->testcase->reviewedDate     = 'Ngày duyệt';
$lang->testcase->reviewResult     = 'Duyệt kết quả';
$lang->testcase->reviewedByAB     = 'Người duyệt';
$lang->testcase->reviewedDateAB   = 'Ngày duyệt';
$lang->testcase->reviewResultAB   = 'Kết quả';
$lang->testcase->forceNotReview   = 'Không có xét duyệt được yêu cầu';
$lang->testcase->lastEditedByAB   = 'Người sửa';
$lang->testcase->lastEditedDateAB = 'Ngày sửa';
$lang->testcase->lastEditedDate   = 'Ngày sửa';
$lang->testcase->version          = 'Phiên bản tình huống';
$lang->testcase->lastRunner       = 'Chạy bởi';
$lang->testcase->lastRunDate      = 'Ngày chạy cuối';
$lang->testcase->assignedTo       = 'Giao cho';
$lang->testcase->colorTag         = 'Màu';
$lang->testcase->lastRunResult    = 'Kết quả';
$lang->testcase->desc             = 'Các bước';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = 'Kỳ vọng';
$lang->testcase->allProduct       = "Tất cả {$lang->productCommon}";
$lang->testcase->fromBug          = 'Từ Bug';
$lang->testcase->toBug            = 'Tới Bug';
$lang->testcase->changed          = 'Đã thay đổi';
$lang->testcase->bugs             = 'Bug đã báo cáo';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = 'Kết quả';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = 'Các bước';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = 'Báo cáo Bug';
$lang->testcase->fromModule       = 'Nguồn Module';
$lang->testcase->fromCase         = 'Nguồn tình huống';
$lang->testcase->sync             = 'Đồng bộ tình huống';
$lang->testcase->ignore           = 'Bỏ qua';
$lang->testcase->fromTesttask     = 'Từ Yêu cầu Test';
$lang->testcase->fromCaselib      = 'Từ thư viện tình huống';
$lang->testcase->deleted          = 'Đã xóa';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID      = 'ID';
$lang->testcase->stepDesc    = 'Bước';
$lang->testcase->stepExpect  = 'Kỳ vọng';
$lang->testcase->stepVersion = 'Phiên bản';

$lang->testcase->common                  = 'Tình huống';
$lang->testcase->index                   = "Trang tình huống";
$lang->testcase->create                  = "Thêm tình huống";
$lang->testcase->batchCreate             = "Thêm hàng loạt";
$lang->testcase->delete                  = "Xóa";
$lang->testcase->deleteAction            = "Xóa tình huống";
$lang->testcase->view                    = "Chi tiết tình huống";
$lang->testcase->review                  = "Xét duyệt yêu cầu";
$lang->testcase->reviewAB                = "Xét duyệt";
$lang->testcase->batchReview             = "Duyệt hàng loạt";
$lang->testcase->edit                    = "Sửa tình huống";
$lang->testcase->batchEdit               = "Sửa hàng loạt ";
$lang->testcase->batchChangeModule       = "Thay đổi Module hàng loạt";
$lang->testcase->confirmLibcaseChange    = "Xác nhận thay đổi thư viện tình huống";
$lang->testcase->ignoreLibcaseChange     = "Bỏ qua thay đổi thư viện tình huống";
$lang->testcase->batchChangeBranch       = "Thay đổi chi nhánh hàng loạt";
$lang->testcase->groupByStories          = 'câu chuyện';
$lang->testcase->batchDelete             = "Xóa hàng loạt ";
$lang->testcase->batchConfirmStoryChange = "Xác nhận hàng loạt";
$lang->testcase->batchCaseTypeChange     = "Thay đổi Loại hàng loạt";
$lang->testcase->browse                  = "Danh sách tình huống";
$lang->testcase->groupCase               = "Xem theo Nhóm";
$lang->testcase->import                  = "Nhập";
$lang->testcase->importAction            = "Nhập tình huống";
$lang->testcase->fileImport              = "Nhập CSV";
$lang->testcase->importFromLib           = "Nhập từ thư viện";
$lang->testcase->showImport              = "Hiển thị Nhập khẩu";
$lang->testcase->exportTemplet           = "Xuất Mẫu";
$lang->testcase->export                  = "Xuất dữ liệu";
$lang->testcase->exportAction            = "Xuất tình huống";
$lang->testcase->reportChart             = 'Biểu đồ báo cáo';
$lang->testcase->reportAction            = 'Báo cáo tình huống';
$lang->testcase->confirmChange           = 'Xác nhận thay đổi tình huống';
$lang->testcase->confirmStoryChange      = 'Xác nhận thay đổi câu chuyện';
$lang->testcase->copy                    = 'Copy tình huống';
$lang->testcase->group                   = 'Nhóm';
$lang->testcase->groupName               = 'Tên nhóm';
$lang->testcase->step                    = 'Các bước';
$lang->testcase->stepChild               = 'Các bước con';
$lang->testcase->viewAll                 = 'Tất cả tình huống';

$lang->testcase->new = 'Mới';

$lang->testcase->num = 'Dòng tình huống: ';

$lang->testcase->deleteStep   = 'Xóa';
$lang->testcase->insertBefore = 'Trước khi nhập';
$lang->testcase->insertAfter  = 'Sau khi nhập';

$lang->testcase->assignToMe   = 'Giao cho bạn';
$lang->testcase->openedByMe   = 'Tạo bởi bạn';
$lang->testcase->allCases     = 'Tất cả';
$lang->testcase->allTestcases = 'Tất cả tình huống';
$lang->testcase->needConfirm  = 'Câu chuyện đã thay đổi';
$lang->testcase->bySearch     = 'Tìm kiếm';
$lang->testcase->unexecuted   = 'Chờ xử lý';

$lang->testcase->lblStory       = 'Câu chuyện liên kết';
$lang->testcase->lblLastEdited  = 'Người sửa';
$lang->testcase->lblTypeValue   = 'Giá trị Loại';
$lang->testcase->lblStageValue  = 'Giá trị giai đoạn';
$lang->testcase->lblStatusValue = 'Giá trị trạng thái';

$lang->testcase->legendBasicInfo   = 'Thông tin cơ bản';
$lang->testcase->legendAttatch     = 'Files';
$lang->testcase->legendLinkBugs    = 'Bugs';
$lang->testcase->legendOpenAndEdit = 'Tạo/Sửa';
$lang->testcase->legendComment     = 'Nhận xét';

$lang->testcase->summary            = "Tổng <strong>%s</strong> tình huống, và <strong>%s</strong> tình huống đang chạy.";
$lang->testcase->confirmDelete      = 'Bạn có muốn xóa tình huống này?';
$lang->testcase->confirmBatchDelete = 'Bạn có muốn xóa tình huống hàng loạt?';
$lang->testcase->ditto              = 'Như trên';
$lang->testcase->dittoNotice        = 'Tình huống này không liên kết tới sản phẩm bởi vì nó là cuối cùng!';

$lang->testcase->reviewList[0] = 'KHÔNG';
$lang->testcase->reviewList[1] = 'CÓ';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Tính năng';
$lang->testcase->typeList['performance'] = 'Hiệu suất';
$lang->testcase->typeList['config']      = 'Cấu hình';
$lang->testcase->typeList['install']     = 'Cài đặt';
$lang->testcase->typeList['security']    = 'Bảo mật';
$lang->testcase->typeList['interface']   = 'Giao diện';
$lang->testcase->typeList['unit']        = 'Unit';
$lang->testcase->typeList['other']       = 'Khác';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Kiểm tra đơn vị';
$lang->testcase->stageList['feature']    = 'Kiểm tra chức năng';
$lang->testcase->stageList['intergrate'] = 'Kiểm tra tích hợp';
$lang->testcase->stageList['system']     = 'Kiểm tra hệ thống';
$lang->testcase->stageList['smoke']      = 'Smoking Testing';
$lang->testcase->stageList['bvt']        = 'Kiểm tra BVT';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = 'Đạt';
$lang->testcase->reviewResultList['clarify'] = 'Đã làm rõ';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = 'Đang đợi';
$lang->testcase->statusList['normal']      = 'Bình thường';
$lang->testcase->statusList['blocked']     = 'Bị khóa';
$lang->testcase->statusList['investigate'] = 'Studying';

$lang->testcase->resultList['n/a']     = 'Bỏ qua';
$lang->testcase->resultList['pass']    = 'Đạt';
$lang->testcase->resultList['fail']    = 'Thất bại';
$lang->testcase->resultList['blocked'] = 'Bị khóa';

$lang->testcase->buttonToList = 'Trở lại';

$lang->testcase->errorEncode      = 'Không có dữ liệu. Vui lòng chọn giải mã đúng và tải lên lại!';
$lang->testcase->noFunction       = 'Iconv và mb_convert_encoding không được tìm thấy. Bạn không thể chuyển dữ liệu này thành mã hóa bạn muốn!';
$lang->testcase->noRequire        = "Dòng %s có “%s ” là trường bắt buộc và nó nên để trống.";
$lang->testcase->noLibrary        = "Không có thư viện tồn tại. Vui lòng tạo một trước.";
$lang->testcase->mustChooseResult = 'Kết quả xét duyệt là bắt buộc.';
$lang->testcase->noModule         = '<div>Chưa có Module.</div><div>Quản lý ngay.</div>';
$lang->testcase->noCase           = 'Không có tình huống nào';

$lang->testcase->searchStories = 'Nhập nội dung cần tìm cho câu chuyện';
$lang->testcase->selectLib     = 'Chọn thư viện';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, nhập bởi <strong>$actor</strong> từ <strong>$extra</strong>.');
$lang->testcase->action->reviewed = array('main' => '$date, ghi nhận bởi <strong>$actor</strong> và kết quả xét duyệt này là <strong>$extra</strong>.', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Đang đợi';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['suite']       = 'Suite';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
