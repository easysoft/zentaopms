<?php
$lang->mail->common        = 'Thiết lập Email';
$lang->mail->index         = 'Trang Email';
$lang->mail->detect        = 'Dò tìm';
$lang->mail->detectAction  = 'Dò tìm theo Email';
$lang->mail->edit          = 'Sửa Thiết lập';
$lang->mail->save          = 'Lưu lại';
$lang->mail->saveAction    = 'Lưu Thiết lập';
$lang->mail->test          = 'Email gửi Test';
$lang->mail->reset         = 'Thiết lập lại';
$lang->mail->resetAction   = 'Thiết lập lại cấu hình';
$lang->mail->resend        = 'Gửi lại';
$lang->mail->resendAction  = 'Gửi lại Email';
$lang->mail->browse        = 'Danh sách Email';
$lang->mail->delete        = 'Xóa Email';
$lang->mail->ztCloud       = 'ZenTao CloudMail';
$lang->mail->gmail         = 'Gmail';
$lang->mail->sendCloud     = 'Thông báo SendCloud';
$lang->mail->batchDelete   = 'Xóa hàng loạt';
$lang->mail->sendcloudUser = 'Đồng bộ liên hệ';
$lang->mail->agreeLicense  = 'Có';
$lang->mail->disagree      = 'Không';

$lang->mail->turnon      = 'Thông báo Email';
$lang->mail->async       = 'Đồng bộ gửi đi';
$lang->mail->fromAddress = 'Email người gửi';
$lang->mail->fromName    = 'Người gửi';
$lang->mail->domain      = 'ZenTao Domain';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'SMTP Validation';
$lang->mail->username    = 'SMTP tài khoản';
$lang->mail->password    = 'SMTP mật khẩu';
$lang->mail->secure      = 'Encryption';
$lang->mail->debug       = 'Debugging';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = 'Truy cập Key';
$lang->mail->secretKey   = 'Mã bí mật';
$lang->mail->license     = 'ZenTao CloudMail Notice';

$lang->mail->selectMTA = 'Chọn loại';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Đã đồng bộ';
$lang->mail->unsyncUser = 'Chưa đồng bộ';
$lang->mail->sync       = 'Đồng bộ';
$lang->mail->remove     = 'Gỡ bỏ';

$lang->mail->toList      = 'Người nhận';
$lang->mail->ccList      = 'Copy tới';
$lang->mail->subject     = 'Chủ đề';
$lang->mail->createdBy   = 'Người gửi';
$lang->mail->createdDate = 'Ngày tạo';
$lang->mail->sendTime    = 'Đã gửi';
$lang->mail->status      = 'Tình trạng';
$lang->mail->failReason  = 'Lý do';

$lang->mail->statusList['wait']   = 'Đợi';
$lang->mail->statusList['sended'] = 'Đã gửi';
$lang->mail->statusList['fail']   = 'Thất bại';

$lang->mail->turnonList[1] = 'On';
$lang->mail->turnonList[0] = 'Off';

$lang->mail->asyncList[1] = 'Có';
$lang->mail->asyncList[0] = 'Không';

$lang->mail->debugList[0] = 'Off';
$lang->mail->debugList[1] = 'Bình thường';
$lang->mail->debugList[2] = 'Cao';

$lang->mail->authList[1] = 'Có';
$lang->mail->authList[0] = 'Không';

$lang->mail->secureList['']    = 'Plain';
$lang->mail->secureList['ssl'] = 'Ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = 'Thêm';
$lang->mail->noticeResend   = 'Email này đã được gửi lại!';
$lang->mail->inputFromEmail = 'Email người gửi';
$lang->mail->nextStep       = 'Tiếp';
$lang->mail->successSaved   = 'Thiết lập Email đã được lưu.';
$lang->mail->setForUser     = 'Could not test mail configure because the users are without mail in system. Please set mail for user first.';
$lang->mail->testSubject    = 'Testing Email';
$lang->mail->testContent    = 'Thiết lập Email đã hoàn thành!';
$lang->mail->successSended  = 'Đã gửi!';
$lang->mail->confirmDelete  = 'Bạn có muốn xóa nó?';
$lang->mail->sendmailTips   = 'Ghi chú: Người gửi Email sẽ không nhận được email này.';
$lang->mail->needConfigure  = 'Thiết lập Email không được tìm thấy. Vui lòng thiết lập Email trước.';
$lang->mail->connectFail    = 'Kết nối tới ZenTao thất bại.';
$lang->mail->centifyFail    = 'Xác thực thất bại. Khóa bí mật có thể được thay đổi. Vui lòng cố gắng kết nối lại!';
$lang->mail->nofsocket      = 'các chức năng liên quan đến fsocket bị vô hiệu hóa. vì vậy Email không thể được gửi đi. Vui lòng sửa đổi allow_url_fopen trong php.ini để bật Onopenssl và khởi động lại Apache.';
$lang->mail->noOpenssl      = 'Vui lòng bật Onopenssl, và khởi động lại Apache.';
$lang->mail->disableSecure  = 'Không có openssl. Disable ssl and tls.';
$lang->mail->sendCloudFail  = 'Thất bại. Lý do:';
$lang->mail->sendCloudHelp  = <<<EOD



EOD;
$lang->mail->sendCloudSuccess = 'Hoàn thành';
$lang->mail->closeSendCloud   = 'Đóng SendCloud';
$lang->mail->addressWhiteList = 'Thêm nó vào danh sách trắng của máy chủ email của bạn để tránh bị chặn.';
$lang->mail->ztCloudNotice    = <<<EOD









EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'Một số Máy chủ Email yêu cầu mã xác thực, tham chiếu tới  nhà cung cấp dịch vụ Email của bạn';
