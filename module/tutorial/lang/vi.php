<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Hao Sun <sunhao@cnezsoft.com>
 * @package  ZenTaoPMS
 * @version  $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link  https://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = 'Hướng dẫn';
$lang->tutorial->desc             = 'Bạn có thể biết làm thể nào sử dụng ZenTao thông qua thực hiện các nhiệm vụ sau. Mất khoảng 10 phút, và bạn có thể thoát bất kỳ lúc nào..';
$lang->tutorial->start            = "Bắt đầu!";
$lang->tutorial->exit             = 'Thoát';
$lang->tutorial->congratulation   = 'Chúc mừng! Bạn đã hoàn thành tất cả nhiệm vụ.';
$lang->tutorial->restart          = 'Khởi động lại';
$lang->tutorial->currentTask      = 'Nhiệm vụ hiện tại';
$lang->tutorial->allTasks         = 'Tất cả nhiệm vụ';
$lang->tutorial->previous         = 'Trước';
$lang->tutorial->nextTask         = 'Tiếp';
$lang->tutorial->openTargetPage   = 'Mở <strong class="task-page-name">mục tiêu</strong>';
$lang->tutorial->atTargetPage     = 'Tại <strong class="task-page-name">mục tiêu</strong>';
$lang->tutorial->reloadTargetPage = 'Nạp lại';
$lang->tutorial->target           = 'Mục tiêu';
$lang->tutorial->targetPageTip    = 'Mở trang 【%s】 theo hướng dẫn này.';
$lang->tutorial->requiredTip      = '【%s】 là bắt buộc.';
$lang->tutorial->congratulateTask = 'Chúc mừng! Bạn đã hoàn thành /n 【<span class="task-name-current"></span>】!';
$lang->tutorial->serverErrorTip   = 'Lỗi!';
$lang->tutorial->ajaxSetError     = 'Nhiệm vụ hoàn thành phải được định nghĩa. Nếu bạn muốn thiết lập lại nhiệm vụ này, vui lòng thiết lập giá trị của nó là null.';
$lang->tutorial->novice           = "Đối với một khởi động nhanh, chúng ta hãy tham giá một vài phút Hướng dẫn.";
$lang->tutorial->dataNotSave      = "Dữ liệu được tạo trong Hướng dẫn này sẽ không được lưu lại!";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => 'Tạo một người dùng');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'browseUser', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-user-btn', 'targetPageName' => 'Thêm người dùng');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>Tạo một Người dùng: </p><ul><li data-target='nav'>Mở <span class='task-nav'>Doanh nghiệp <i class='icon icon-angle-right'></i> Thêm người dùng</span></li><li data-target='form'>Điền form thông tin người dùng</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => 'Tạo một ' . $lang->productCommon);
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' => $lang->productCommon);
$lang->tutorial->tasks['createProduct']['desc'] = "<p>Tạo một {$lang->productCommon}: </p><ul><li data-target='nav'> Mở <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i>Trang sản phẩm<i class='icon icon-angle-right'></i>Tạo sản phẩm</span></li><li data-target='form'>Điền form thông tin {$lang->productCommon};</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => 'Tạo một câu chuyện');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Tạo câu chuyện');
$lang->tutorial->tasks['createStory']['desc'] = "<p>Tạo một câu chuyện: </p><ul><li data-target='nav'>Mở <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i>Câu chuyện <i class='icon icon-angle-right'></i>Tạo câu chuyện</span></li><li data-target='form'>Điền form thông tin câu chuyện;</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => 'Tạo một ' . $lang->executionCommon);
$lang->tutorial->tasks['createProject']['nav']  = array('module' => 'execution', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => 'Tạo ' . $lang->executionCommon);
$lang->tutorial->tasks['createProject']['desc'] = "<p>Tạo một {$lang->executionCommon}: </p><ul><li data-target='nav'>Mở <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> Tạo {$lang->executionCommon} </span>;</li><li data-target='form'>Điền form thông tin {$lang->executionCommon}</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => "Quản lý thành viên {$lang->executionCommon}");
$lang->tutorial->tasks['manageTeam']['nav']  = array('module' => 'execution', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => 'Quản lý thành viên');
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>Quản lý thành viên {$lang->executionCommon}: </p><ul><li data-target='nav'>Mở <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> Đội nhóm <i class='icon icon-angle-right'></i> Quản lý thành viên nhóm</span> Page；</li><li data-target='form'>Chọn người dùng cho đội nhóm</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => 'Liên kết một câu chuyện');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'execution', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => 'Liên kết câu chuyện');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>Liên kết một câu chuyện tới {$lang->executionCommon}: </p><ul><li data-target='nav'>Mở <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> Câu chuyện <i class='icon icon-angle-right'></i>Liên kết câu chuyện</span></li><li data-target='form'>Chọn câu chuyện từ danh sách câu chuyện để liên kếtli><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => 'Chia nhỏ nhiệm vụ');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'execution', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Tạo nhiệm vụ');
$lang->tutorial->tasks['createTask']['desc'] = "<p>Chia nhỏ nhiệm vụ cho một câu chuyện: </p><ul><li data-target='nav'>Mở <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> Câu chuyện <i class='icon icon-angle-right'></i> Chia nhỏ;</span></li><li data-target='form'>Điền form thông tin nhiệm vụ;</li><li data-target='submit'>Lưu lại</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => 'Báo cáo Bug');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Báo cáo Bug');
$lang->tutorial->tasks['createBug']['desc'] = "<p>Báo cáo một Bug: </p><ul><li data-target='nav'>Mở <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Báo cáo Bug</span>；</li><li data-target='form'>Điền form thông tin Bug:</li><li data-target='submit'>Lưu lại</li></ul>";
