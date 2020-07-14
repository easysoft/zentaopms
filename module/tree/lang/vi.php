<?php
/**
 * The tree module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  tree
 * @version  $Id: vi.php 5045 2020-04-06 07:04:40Z quocnho@gmail.com$
 * @link  http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common             = 'Module';
$lang->tree->edit               = 'Sửa Module';
$lang->tree->delete             = 'Xóa Module';
$lang->tree->browse             = 'Quản lý module chung';
$lang->tree->browseTask         = 'Quản lý Module nhiệm vụ';
$lang->tree->manage             = 'Quản lý Module';
$lang->tree->fix                = 'Sửa Module';
$lang->tree->manageProduct      = "Quản lý module {$lang->productCommon}";
$lang->tree->manageProject      = "Quản lý module {$lang->projectCommon}";
$lang->tree->manageLine         = "Quản lý dòng {$lang->productCommon}";
$lang->tree->manageBug          = 'Quản lý Bug';
$lang->tree->manageCase         = 'Quản lý tình huống';
$lang->tree->manageCaseLib      = 'Quản lý thư viện';
$lang->tree->manageCustomDoc    = 'Quản lý thư viện tài liệu';
$lang->tree->updateOrder        = 'Đánh giá Module';
$lang->tree->manageChild        = 'Quản lý module con';
$lang->tree->manageStoryChild   = 'Quản lý module con';
$lang->tree->manageLineChild    = "Quản lý dòng {$lang->productCommon}";
$lang->tree->manageBugChild     = 'Quản lý Bugs con';
$lang->tree->manageCaseChild    = 'Quản lý tình huống con';
$lang->tree->manageCaselibChild = 'Quản lý thư viện con';
$lang->tree->manageTaskChild    = "Quản lý module {$lang->projectCommon} con";
$lang->tree->syncFromProduct    = "Sao chép từ {$lang->productCommon} khác";
$lang->tree->dragAndSort        = "Drag để sắp xếp";
$lang->tree->sort               = "Thứ tự";
$lang->tree->addChild           = "Thêm Module con";
$lang->tree->confirmDelete      = 'Bạn có muốn xóa module này và module con của nó?';
$lang->tree->confirmDeleteLine  = "Bạn có muốn xóa dòng {$lang->productCommon} này không?";
$lang->tree->confirmRoot        = "Mọi sự thay đổi tới {$lang->productCommon} này sẽ thay đổi the câu chuyện, bugs, tình huống của  {$lang->productCommon} nó sở hữu, cũng như liên kết của {$lang->projectCommon} và {$lang->productCommon}, điều này nguy hiểm. Bạn có muốn thay đổi nó?";
$lang->tree->confirmRoot4Doc    = "Mọi sự thay đổi tới thư viện này sẽ thay đổi thư viện tài liệu nó sở hữu, điều này nguy hiểm. Bạn có muốn thay đổi nó?";
$lang->tree->successSave        = 'Đã lưu.';
$lang->tree->successFixed       = 'Đã sửa';
$lang->tree->repeatName         = 'Tên "%s" đã tồn tại!';
$lang->tree->shouldNotBlank     = 'Module name should not be blank!';

$lang->tree->module     = 'Module';
$lang->tree->name       = 'Tên';
$lang->tree->line       = "Dòng {$lang->productCommon}";
$lang->tree->cate       = 'Danh mục';
$lang->tree->root       = 'Root';
$lang->tree->branch     = 'Platform/Branch';
$lang->tree->path       = 'Đường dẫn';
$lang->tree->type       = 'Loại';
$lang->tree->parent     = 'Module mẹ';
$lang->tree->parentCate = 'Danh mục mẹ';
$lang->tree->child      = 'Con';
$lang->tree->lineChild  = "Dòng {$lang->productCommon} con";
$lang->tree->owner      = 'Sở hữu';
$lang->tree->order      = 'Sắp xếp';
$lang->tree->short      = 'V.tắt';
$lang->tree->all        = 'Tất cả Modules';
$lang->tree->projectDoc = "Tài liệu {$lang->projectCommon}";
$lang->tree->product    = $lang->productCommon;
