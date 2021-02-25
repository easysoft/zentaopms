<?php
$lang->my->common = '';

/* Method List。*/
$lang->my->index           = 'Trang chủ';
$lang->my->todo            = 'Việc của bạn';
$lang->my->calendar        = 'Lịch trình';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribute';
$lang->my->task            = 'Nhiệm vụ';
$lang->my->bug             = 'Bugs của bạn';
$lang->my->testTask        = 'Bản dựng của bạn';
$lang->my->testCase        = 'Tình huống của bạn';
$lang->my->story           = 'Câu chuyện của bạn';
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My Projects";
$lang->my->execution       = "My {$lang->execution->common}s";
$lang->my->issue           = 'My Issues';
$lang->my->risk            = 'My Risks';
$lang->my->profile         = 'Hồ sơ của bạn';
$lang->my->dynamic         = 'Lịch sử của bạn';
$lang->my->team            = 'My Team';
$lang->my->editProfile     = 'Sửa';
$lang->my->changePassword  = 'Sửa mật khẩu';
$lang->my->preference      = 'Preference';
$lang->my->unbind          = 'Gỡ kết nối từ Zdoo';
$lang->my->manageContacts  = 'Quản lý Liên hệ';
$lang->my->deleteContacts  = 'Xóa Liên hệ';
$lang->my->shareContacts   = 'Công khai';
$lang->my->limited         = 'Hành động bị giới hạn (Người dùng chỉ có thể sửa những gì liên quan chúng.)';
$lang->my->storyConcept    = 'Story Concept';
$lang->my->score           = 'Điểm của bạn';
$lang->my->scoreRule       = 'Quy định điểm';
$lang->my->noTodo          = 'Chưa có việc nào.';
$lang->my->noData          = 'No %s yet. ';
$lang->my->storyChanged    = "Story Changed";
$lang->my->hours           = "Giờ/ngày";
$lang->my->uploadAvatar    = 'Upload Avatar';
$lang->my->requirement     = "My {$lang->URCommon}";
$lang->my->testtask        = 'My Test Task';
$lang->my->testcase        = 'My Case';

$lang->my->myExecutions = "My Stage/Sprint/Iteration";
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = 'Project';
$lang->my->executions   = $lang->execution->common;

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = 'Undone';
$lang->my->executionMenu->done   = 'Done';

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

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = 'Doing';
$lang->my->projectMenu->wait       = 'Waiting';
$lang->my->projectMenu->suspended  = 'Suspended';
$lang->my->projectMenu->closed     = 'Closed';
$lang->my->projectMenu->openedbyme = 'CreatedByMe';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Lịch sử';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->executionCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Tạo {$lang->executionCommon}";
$lang->my->home->createProduct = "Tạo {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Trợ giúp</a>";
$lang->my->home->noProductsTip = "Không có {$lang->productCommon} được tìm thấy.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Thông tin cơ bản';
$lang->my->form->lblContact = 'Thông tin liên hệ';
$lang->my->form->lblAccount = 'Thông tin tài khoản';

$lang->my->programLink = 'Program Default Page';
$lang->my->productLink = 'Product Default Page'; 
$lang->my->projectLink = 'Project Default Page';

$lang->my->programLinkList = array();
//$lang->my->programLinkList['program-home']     = 'The default access to the program home page, you can understand the company’s overall strategic planning status';
$lang->my->programLinkList['program-pgmbrowse']  = 'By default, you go to the program list, where you can view all of the programs';
//$lang->my->programLinkList['program-pgmindex'] = 'By default, you go to the most recent program dashboard to see the current program overview';
$lang->my->programLinkList['program-pgmproject'] = 'By default, you go to the list of items in the most recent program, and you can view all items under the current program';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = 'The default access to the product home page, you can understand the company’s overall product status';
$lang->my->productLinkList['product-all']       = 'By default, you go to the product list, where you can view all the products';
$lang->my->productLinkList['product-dashboard'] = 'By default, go to the latest product dashboard to see the current product overview';
$lang->my->productLinkList['product-browse']    = 'By default, go to the list of requirements for the most recent product and see the requirements under the current product';

global $config;
$lang->my->projectLinkList = array();
//$lang->my->projectLinkList['program-home']    = 'The default access to the project home page, you can understand the overall project status of the company';
$lang->my->projectLinkList['program-prjbrowse'] = 'By default, you go to the project list, where you can view all the projects';
if($config->systemMode == 'new') $lang->my->projectLinkList['program-index']     = 'By default, go to the most recent project dashboard to see the current project overview';
$lang->my->projectLinkList['project-task']      = 'By default, you go to the task list for the most recent project iteration to see the task information for the current iteration';
