<?php
$lang->my->common = '';

/* Method List。*/
$lang->my->index          = 'Trang chủ';
$lang->my->todo           = 'Việc của bạn';
$lang->my->calendar       = 'Lịch trình';
$lang->my->task           = 'Nhiệm vụ';
$lang->my->bug            = 'Bugs của bạn';
$lang->my->testTask       = 'Bản dựng của bạn';
$lang->my->testCase       = 'Tình huống của bạn';
$lang->my->story          = 'Câu chuyện của bạn';
$lang->my->myProject      = "{$lang->projectCommon} của bạn";
$lang->my->profile        = 'Hồ sơ của bạn';
$lang->my->dynamic        = 'Lịch sử của bạn';
$lang->my->editProfile    = 'Sửa';
$lang->my->changePassword = 'Sửa mật khẩu';
$lang->my->unbind         = 'Gỡ kết nối từ Zdoo';
$lang->my->manageContacts = 'Quản lý Liên hệ';
$lang->my->deleteContacts = 'Xóa Liên hệ';
$lang->my->shareContacts  = 'Công khai';
$lang->my->limited        = 'Hành động bị giới hạn (Người dùng chỉ có thể sửa những gì liên quan chúng.)';
$lang->my->score          = 'Điểm của bạn';
$lang->my->scoreRule      = 'Quy định điểm';
$lang->my->noTodo         = 'Chưa có việc nào.';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'Giao cho bạn';
$lang->my->taskMenu->openedByMe   = 'Tạo bởi bạn';
$lang->my->taskMenu->finishedByMe = 'Kết thúc bởi bạn';
$lang->my->taskMenu->closedByMe   = 'Đóng bởi bạn';
$lang->my->taskMenu->canceledByMe = 'Hủy bởi bạn';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Giao cho bạn';
$lang->my->storyMenu->openedByMe   = 'Tạo bởi bạn';
$lang->my->storyMenu->reviewedByMe = 'Duyệt bởi bạn';
$lang->my->storyMenu->closedByMe   = 'Đóng bởi bạn';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Lịch sử';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Tạo {$lang->projectCommon}";
$lang->my->home->createProduct = "Tạo {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Trợ giúp</a>";
$lang->my->home->noProductsTip = "Không có {$lang->productCommon} được tìm thấy.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Thông tin cơ bản';
$lang->my->form->lblContact = 'Thông tin liên hệ';
$lang->my->form->lblAccount = 'Thông tin tài khoản';
