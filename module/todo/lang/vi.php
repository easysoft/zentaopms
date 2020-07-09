<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  todo
 * @version  $Id: vi.php 4676 2020-04-06 06:08:23Z quocnho@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->todo->common       = 'Việc làm';
$lang->todo->index        = "Home";
$lang->todo->create       = "Thêm việc";
$lang->todo->createCycle  = "Thêm việc lặp lại";
$lang->todo->assignTo     = "Giao cho";
$lang->todo->assignedDate = "Ngày giao";
$lang->todo->assignAction = "Giao việc";
$lang->todo->start        = "Start Todo";
$lang->todo->activate     = "Kích hoạt việc";
$lang->todo->batchCreate  = "Thêm hàng loạt";
$lang->todo->edit         = "Sửa việc";
$lang->todo->close        = "Đóng việc";
$lang->todo->batchClose   = "Đóng hàng loạt";
$lang->todo->batchEdit    = "Sửa việc làm hàng loạt";
$lang->todo->view         = "Chi tiết việc làm";
$lang->todo->finish       = "Hoàn thành việc";
$lang->todo->batchFinish  = "Kết thúc hàng loạt";
$lang->todo->export       = "Xuất việc";
$lang->todo->delete       = "Xóa việc";
$lang->todo->import2Today = "Nhập thành hôm nay";
$lang->todo->import       = "Nhập";
$lang->todo->legendBasic  = "Thông tin cơ bản";
$lang->todo->cycle        = "Lặp lại";
$lang->todo->cycleConfig  = "Cấu hình lặp lại";

$lang->todo->reasonList['story'] = "Chuyển thành câu chuyện";
$lang->todo->reasonList['task']  = "Chuyển thành nhiệm vụ";
$lang->todo->reasonList['bug']   = "Chuyển thành Bug";
$lang->todo->reasonList['done']  = "Hoàn thành";

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Sở hữu';
$lang->todo->date         = 'Ngày';
$lang->todo->begin        = 'Bắt đầu';
$lang->todo->end          = 'Kết thúc';
$lang->todo->beginAB      = 'Bắt đầu';
$lang->todo->endAB        = 'Kết thúc';
$lang->todo->beginAndEnd  = 'Thời gian';
$lang->todo->idvalue      = 'Liên kết ID';
$lang->todo->type         = 'Loại';
$lang->todo->pri          = 'Ưu tiên';
$lang->todo->name         = 'Tiêu đề';
$lang->todo->status       = 'Tình trạng';
$lang->todo->desc         = 'Mô tả';
$lang->todo->private      = 'Riêng tư';
$lang->todo->cycleDay     = 'Ngày';
$lang->todo->cycleWeek    = 'Tuần';
$lang->todo->cycleMonth   = 'Tháng';
$lang->todo->assignedTo   = 'Giao cho';
$lang->todo->assignedBy   = 'Người giao';
$lang->todo->finishedBy   = 'Người kết thúc';
$lang->todo->finishedDate = 'Ngày kết thúc';
$lang->todo->closedBy     = 'Người đóng';
$lang->todo->closedDate   = 'Ngày đóng';
$lang->todo->deadline     = 'Quá hạn';

$lang->todo->every      = 'Mọi';
$lang->todo->beforeDays = "<span class='input-group-addon'>Tự động tạo việc làm </span>%s<span class='input-group-addon'> ngày trước </span>";
$lang->todo->dayNames   = array(1 => 'Thứ hai', 2 => 'Thứ ba', 3 => 'Thứ tư', 4 => 'Thứ năm', 5 => 'Thứ sáu', 6 => 'Thứ bảy', 0 => 'Chủ nhật');

$lang->todo->confirmBug   = 'Việc này được liên kết tới Bug #%s. Bạn có muốn sửa nó?';
$lang->todo->confirmTask  = 'Việc này được liên kết tới Nhiệm vụ #%s，Bạn có muốn sửa nó?';
$lang->todo->confirmStory = 'Việc này được liên kết tới Câu chuyện #%s，Bạn có muốn sửa nó?';

$lang->todo->statusList['wait']       = 'Đang đợi';
$lang->todo->statusList['doing']      = 'Đang làm';
$lang->todo->statusList['done']       = 'Hoàn thành';
$lang->todo->statusList['closed']     = 'Đã đóng';
//$lang->todo->statusList['cancel']   = 'Đã hủy';
//$lang->todo->statusList['postpone'] = 'Tạm ngưng';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = 'Bình thường';
$lang->todo->priList[1] = 'Khẩn cấp';
$lang->todo->priList[2] = 'Quan trọng';
$lang->todo->priList[4] = 'Thấp';

$lang->todo->typeList['custom'] = 'Tùy biến';
$lang->todo->typeList['cycle']  = 'Lặp lại';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = 'Nhiệm vụ';
$lang->todo->typeList['story']  = 'Câu chuyện';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "Bạn có muốn xóa việc này?";
$lang->todo->thisIsPrivate  = 'Đây là một việc riêng tư';
$lang->todo->lblDisableDate = 'TBD';
$lang->todo->lblBeforeDays  = "Tạo một việc %s ngày trước";
$lang->todo->lblClickCreate = "Click để thêm việc";
$lang->todo->noTodo         = 'Không có việc của loại này.';
$lang->todo->noAssignedTo   = "Người được giao không nên trống.";
$lang->todo->unfinishedTodo = 'The todos of ID %s are not finished, and can not close.';

$lang->todo->periods['all']      = 'Tất cả việc';
$lang->todo->periods['thisYear'] = 'Năm nay';
$lang->todo->periods['future']   = 'TBD';
$lang->todo->periods['before']   = 'Chưa kết thúc';
$lang->todo->periods['cycle']    = 'Lặp lại';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, là $extra bởi <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, được đánh dấu bởi <strong>$actor</strong> theo <strong>$extra</strong>.', 'extra' => 'statusList');
