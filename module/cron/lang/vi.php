<?php
$lang->cron->common       = 'Cron';
$lang->cron->index        = 'Trang CRON';
$lang->cron->list         = 'Nhiệm vụ';
$lang->cron->create       = 'Thêm';
$lang->cron->createAction = 'Thêm Cron';
$lang->cron->edit         = 'Sửa Cron';
$lang->cron->delete       = 'Xóa Cron';
$lang->cron->toggle       = 'Kích hoạt/Hủy';
$lang->cron->turnon       = 'On/Off';
$lang->cron->openProcess  = 'Khởi động lại';
$lang->cron->restart      = 'Khởi động lại Cron';

$lang->cron->m        = 'Phút';
$lang->cron->h        = 'Giờ';
$lang->cron->dom      = 'Ngày';
$lang->cron->mon      = 'Tháng';
$lang->cron->dow      = 'Tuần';
$lang->cron->command  = 'Dòng lệnh';
$lang->cron->status   = 'Tình trạng';
$lang->cron->type     = 'Loại';
$lang->cron->remark   = 'Nhận xét';
$lang->cron->lastTime = 'Chạy gần nhất';

$lang->cron->turnonList['1'] = 'On';
$lang->cron->turnonList['0'] = 'Off';

$lang->cron->statusList['normal']  = 'Bình thường';
$lang->cron->statusList['running'] = 'Đang chạy';
$lang->cron->statusList['stop']    = 'Dừng';

$lang->cron->typeList['zentao'] = 'Tự gọi';
$lang->cron->typeList['system'] = 'Câu lệnh hệ thống';

$lang->cron->toggleList['start'] = 'Kích hoạt';
$lang->cron->toggleList['stop']  = 'Vô hiệu';

$lang->cron->confirmDelete = 'Bạn có muốn xóa CRON này?';
$lang->cron->confirmTurnon = 'Bạn có muốn turn off CRON này?';
$lang->cron->introduction  = <<<EOD
<p>Cron là để thực hiện các hành động theo lịch trình, chẳng hạn như cập nhật biểu đồ phát sinh, sao lưu, v.v.</p>
<p>Các tính năng của Cron cần được cải thiện, do đó nó bị tắt theo mặc định.</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>Bạn có muốn mở nó?<a href="%s" target='hiddenwin'><strong>Bật nhiệm vụ theo lịch trình<strong></a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m         = 'Dải: 0-59，"*" toàn bộ, "/" có nghĩa "mỗi", "-" nghĩa là giải. Ví dụ: */3: Mỗi 3 phút';
$lang->cron->notice->h         = 'Dải: 0-23';
$lang->cron->notice->dom       = 'Dải: 1-31';
$lang->cron->notice->mon       = 'Dải: 1-12';
$lang->cron->notice->dow       = 'Dải: 0-6';
$lang->cron->notice->help      = 'Chú ý: Nếu máy chủ được khởi động lại hoặc Cron không hoạt động, điều đó có nghĩa là Cron đã dừng. Bạn có thể khởi động lại nó bằng cách nhấp vào [Khởi động lại] hoặc làm mới trang này. Nếu thời gian thực hiện cuối cùng được thay đổi, điều đó có nghĩa là Cron đang chạy.';
$lang->cron->notice->errorRule = '"%s" không hợp lệ';
