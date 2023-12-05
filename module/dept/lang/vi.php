<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  dept
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->dept->common      = 'Phòng/Ban';
$lang->dept->manageChild = "Phòng/Ban con";
$lang->dept->edit        = "Sửa Department";
$lang->dept->delete      = "Xóa Department";
$lang->dept->parent      = "Parent Dept";
$lang->dept->manager     = "Manager";
$lang->dept->name        = "Department Name";
$lang->dept->browse      = "Quản lý phòng/ban";
$lang->dept->manage      = "Quản lý phòng/ban";
$lang->dept->updateOrder = "Đánh giá Department";
$lang->dept->add         = "Thêm Department";
$lang->dept->grade       = "Department Grade";
$lang->dept->order       = "Department Order";
$lang->dept->dragAndSort = "Drag để sắp xếp";

$lang->dept->confirmDelete = " Bạn có muốn xóa this department?";
$lang->dept->successSave   = " Saved!";
$lang->dept->repeatDepart  = " Có một tên bộ phận trùng lặp, bạn có chắc chắn thêm nó không?";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'This Department has child departments. You cannot xóa it!';
$lang->dept->error->hasUsers = 'This Department has users. You cannot xóa it!';
