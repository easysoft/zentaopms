<?php
$lang->testreport->common       = 'Báo cáo Test';
$lang->testreport->browse       = 'Báo cáo Test';
$lang->testreport->create       = 'Tạo báo cáo';
$lang->testreport->edit         = 'Sửa báo cáo';
$lang->testreport->delete       = 'Xóa báo cáo';
$lang->testreport->export       = 'Xuất';
$lang->testreport->exportAction = 'Xuất báo cáo';
$lang->testreport->view         = 'Chi tiết báo cáo';
$lang->testreport->recreate     = 'Tạo lại';

$lang->testreport->title       = 'Tiêu đề';
$lang->testreport->product     = $lang->productCommon;
$lang->testreport->bugTitle    = 'Bug';
$lang->testreport->storyTitle  = 'Câu chuyện';
$lang->testreport->project     = 'Dự án';
$lang->testreport->testtask    = 'Test bản dựng';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Bắt đầu & Kết thúc';
$lang->testreport->owner       = 'Sở hữu';
$lang->testreport->members     = 'Người dùng';
$lang->testreport->begin       = 'Bắt đầu';
$lang->testreport->end         = 'Kết thúc';
$lang->testreport->stories     = 'Câu chuyện đã Test';
$lang->testreport->bugs        = 'Bug đã Test';
$lang->testreport->builds      = 'Thông tin bản dựng';
$lang->testreport->goal        = 'Mục tiêu dự án';
$lang->testreport->cases       = 'Tình huống';
$lang->testreport->bugInfo     = 'Phân chia Bug';
$lang->testreport->report      = 'Tóm tắt';
$lang->testreport->legacyBugs  = 'Bug còn lại';
$lang->testreport->createdBy   = 'Người tạo';
$lang->testreport->createdDate = 'Ngày tạo';
$lang->testreport->objectID    = 'Đối tượng';
$lang->testreport->objectType  = 'Loại đối tượng';
$lang->testreport->profile     = 'Hồ sơ';
$lang->testreport->value       = 'Giá trị';
$lang->testreport->none        = 'None';
$lang->testreport->all         = 'Tất cả báo cáo';
$lang->testreport->deleted     = 'Đã xóa';
$lang->testreport->selectTask  = 'Tạo báo cáo theo yêu cầu';

$lang->testreport->legendBasic       = 'Thông tin cơ bản';
$lang->testreport->legendStoryAndBug = 'Phạm vi Test';
$lang->testreport->legendBuild       = 'Số vòng Test';
$lang->testreport->legendCase        = 'Tình huống liên kết';
$lang->testreport->legendLegacyBugs  = 'Bug còn lại';
$lang->testreport->legendReport      = 'Báo cáo';
$lang->testreport->legendComment     = 'Tóm tắt';
$lang->testreport->legendMore        = 'Thêm';

$lang->testreport->bugSeverityGroups   = 'Phân chia mức độ Bug';
$lang->testreport->bugTypeGroups       = 'Phân chia loại Bug';
$lang->testreport->bugStatusGroups     = 'Phân chia tình trạng Bug';
$lang->testreport->bugOpenedByGroups   = 'Phân chia người báo cáo Bug';
$lang->testreport->bugResolvedByGroups = 'Phân chia người giải quyết Bug';
$lang->testreport->bugResolutionGroups = 'Phân chia giải pháp Bug';
$lang->testreport->bugModuleGroups     = 'Phân chia Module Bug';
$lang->testreport->legacyBugs          = 'Bug còn lại';
$lang->testreport->bugConfirmedRate    = 'Đánh giá Bug đã xác nhận (Nghị quyết đã ban hành hoặc tạm hoãn / tình trạng được giải quyết hoặc đóng)';
$lang->testreport->bugCreateByCaseRate = 'Đánh giá Bug được báo cáo trong Tình huống (Bug được báo cáo trong tình huống /Thêm mới Bugs)';

$lang->testreport->caseSummary    = 'Tổng <strong>%s</strong> tình huống. <strong>%s</strong> tình huống đang chạy. <strong>%s</strong> kết quả được tạo. <strong>%s</strong> cases thất bại';
$lang->testreport->buildSummary   = 'Đã test <strong>%s</strong> bản dựng.';
$lang->testreport->confirmDelete  = 'Bạn có muốn xóa báo cáo này?';
$lang->testreport->moreNotice     = 'Tính năng bổ sung có thể được mở rộng với tham chiếu đến sổ tay mở rộng ZenTao hoặc bạn có thể liên hệ với chúng tôi tại renee@easysoft.ltd để tùy chỉnh.';
$lang->testreport->exportNotice   = "Xuất bởi <a href='https://www.zentao.net' target='_blank' style='color:grey'>ZenTao</a>";
$lang->testreport->noReport       = "Không có báo cáo được tạo. Vui lòng kiểm tra sau.";
$lang->testreport->foundBugTip    = "Lỗi Bug được tìm thấy trong giai đoạn xây dựng này và bản dựng bị ảnh hưởng là trong giai đoạn thử nghiệm này.";
$lang->testreport->legacyBugTip   = "Bug kích hoạt, hoặc Bug không được giải quyết trong giai đoạn thử nghiệm.";
$lang->testreport->fromCaseBugTip = "Lỗi được tìm thấy từ việc chạy các trường hợp trong giai đoạn thử nghiệm.";
$lang->testreport->errorTrunk     = "Bạn không thể tạo một báo cáo thử nghiệm cho trunk. Vui lòng sửa đổi bản dựng liên kết!";
$lang->testreport->noTestTask     = "Không có yêu cầu thử nghiệm {$lang->productCommon} này, so no reports can be generated. Vui lòng go to {$lang->productCommon} which has test requests and then generate the report.";
$lang->testreport->noObjectID     = "Không có yêu cầu test hoặc {$lang->projectCommon} được chọn, bởi vậy không có báo cáo có thể được tạo.";
$lang->testreport->moreProduct    = "Báo cáo Test chỉ có thể được tạo cho cùng {$lang->productCommon}.";
$lang->testreport->hiddenCase     = "Hide %s use cases";

$lang->testreport->bugSummary = <<<EOD
Total <strong>%s</strong> Bugs reported <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs remained unresolved <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs được tìm thấy từ tình huống đang chạy<a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='Nghị quyết được giải quyết hoặc tạm ngưng / tình trạng được giải quyết hoặc đóng'><i class='icon-help'></i></a>: <strong>%s</strong>，Bugs-reported-from-cases rate<a data-toggle='tooltip' class='text-warning' title='Bugs đã tạo từ Tình huống/Bugs'><i class='icon-help'></i></a>: <strong>%s</strong>
EOD;
