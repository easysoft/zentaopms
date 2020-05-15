<?php
$lang->backup->common      = 'Sao lưu';
$lang->backup->index       = 'Trang sao lưu';
$lang->backup->history     = 'Lịch sử';
$lang->backup->delete      = 'Xóa sao lưu';
$lang->backup->backup      = 'Sao lưu';
$lang->backup->restore     = 'Khôi phục';
$lang->backup->change      = 'Sửa Expiration';
$lang->backup->changeAB    = 'Sửa';
$lang->backup->rmPHPHeader = 'Xóa PHP header';

$lang->backup->time     = 'Ngày';
$lang->backup->files    = 'Files';
$lang->backup->allCount = 'All Count';
$lang->backup->count    = 'Backup Count';
$lang->backup->size     = 'Size';
$lang->backup->status   = 'Status';

$lang->backup->statusList['success'] = 'Success';
$lang->backup->statusList['fail']    = 'Fail';

$lang->backup->setting    = 'Thiết lập';
$lang->backup->settingDir = 'Thư mục sao lưu';
$lang->backup->settingList['nofile'] = 'Không sao lưu tập tin hoặc mã nguồn.';
$lang->backup->settingList['nosafe'] = 'Không ngăn tải xuống tập tin PHP.';

$lang->backup->waitting       = '<span id="backupType"></span> đang diễn ra. Vui lòng đợi...';
$lang->backup->progressSQL    = '<p>Sao lưu SQL: %s được sao lưu.</p>';
$lang->backup->progressAttach = '<p>Sao lưu SQL đã hoàn thành.</p><p>Sao lưu đính kèm: %s được sao lưu.</p>';
$lang->backup->progressCode   = '<p>Sao lưu SQL đã hoàn thành.</p><p>Sao lưu đính kèm đã hoàn thành.</p><p>Sao lưu mã nguồn: %s được sao lưu.</p>';
$lang->backup->confirmDelete  = 'Bạn có muốn xóa sao lưu này?';
$lang->backup->confirmRestore = 'Bạn có muốn khôi phục sao lưu này?';
$lang->backup->holdDays       = 'Giữ ít nhất %s ngày sao lưu';
$lang->backup->copiedFail     = 'Copy failed files: ';
$lang->backup->restoreTip     = 'Chỉ tập tin và CSDL có thể được khôi phục bằng cách Click Khôi phục. Mã nguồn có thể khôi phục thủ công.';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Sao lưu hoàn thành!';
$lang->backup->success->restore = 'Khôi phục hoàn thành!';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir = 'Thư mục không tồn tại và không thể tạo';
$lang->backup->error->noWritable  = "<code>%s</code> không thể ghi! Vui lòng kiểm tra quyền, nếu không sao lưu sẽ không thể hoàn thành.";
$lang->backup->error->noDelete    = "%s không thể xóa. Vui lòng điều chỉnh quyền hoặc xóa nó thủ công.";
$lang->backup->error->restoreSQL  = "Lỗi khôi phục thư viện CSDL. Lỗi %s.";
$lang->backup->error->restoreFile = "Lỗi khôi phục tập tin. Lỗi %s.";
$lang->backup->error->backupFile  = "Lỗi sao lưu tập tin. Lỗi %s.";
$lang->backup->error->backupCode  = "Lỗi sao lưu mã nguồn. Lỗi %s.";
