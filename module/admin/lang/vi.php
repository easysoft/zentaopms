<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  admin
 * @version  $Id: vi.php 4460 2013-02-26 02:28:02Z quocnho@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->admin->common        = 'Quản trị';
$lang->admin->index         = 'Trang quản trị';
$lang->admin->checkDB       = 'Kiểm tra CSDL';
$lang->admin->sso           = 'Zdoo';
$lang->admin->ssoAction     = 'Liên kết Zdoo';
$lang->admin->safeIndex     = 'Bảo mật';
$lang->admin->checkWeak     = 'Kiểm tra độ yếu mật khẩu';
$lang->admin->certifyMobile = 'Xác thực di động của bạn';
$lang->admin->certifyEmail  = 'Xác thực email của bạn';
$lang->admin->ztCompany     = 'Xác thực doanh nghiệp của bạn';
$lang->admin->captcha       = 'Mã xác thực';
$lang->admin->getCaptcha    = 'Gửi Mã xác thực';

$lang->admin->api     = 'API';
$lang->admin->log     = 'Nhật ký';
$lang->admin->setting = 'Thiết lập';
$lang->admin->days    = 'Ngày hợp lệ';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Phiên bản hiện tại là %s. ';
$lang->admin->info->links   = ' Bạn có thể ghé thăm liên kết sau';
$lang->admin->info->account = 'Tài khoản ZenTao của bạn là %s.';
$lang->admin->info->log     = 'Nhật ký vượt quá ngày hợp lệ sẽ bị xóa và bạn phải chay Cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Chú ý: Bạn chưa đăng ký trên trang web chính thức của ZenTao (www.zentao.pm). %s sau đó nhận Bản tin và nâng cấp mới nhất của ZenTao.";
$lang->admin->notice->ignore   = "Bỏ qua";
$lang->admin->notice->int      = "『%s』 nên là số nguyên dương.";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Kết nối tài khoản';
$lang->admin->register->caption    = 'Đăng ký cộng đồng ZenTao';
$lang->admin->register->click      = 'Đăng ký';
$lang->admin->register->lblAccount = '>= 3 chữ và số';
$lang->admin->register->lblPasswd  = '>= 6 chữ và số';
$lang->admin->register->submit     = 'Gửi';
$lang->admin->register->bind       = "Kết nối với tài khoản đang tồn tại";
$lang->admin->register->success    = "Bạn đã đăng ký!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Liên kết tài khoản';
$lang->admin->bind->success = "Tài khoản đã được kết nối!";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Chính sách bảo mật';
$lang->admin->safe->set        = 'Thiết lập mật khẩu';
$lang->admin->safe->password   = 'Độ mạnh mật khẩu';
$lang->admin->safe->weak       = 'Mật khẩu yếu thường dùng';
$lang->admin->safe->reason     = 'Loại';
$lang->admin->safe->checkWeak  = 'Quét mật khẩu yếu';
$lang->admin->safe->changeWeak = 'Chủ động thay đổi mật khẩu yếu';
$lang->admin->safe->modifyPasswordFirstLogin = 'Chủ động thay đổi mật khẩu sau lần đăng nhập đầu tiên';

$lang->admin->safe->modeList[0] = 'Yếu';
$lang->admin->safe->modeList[1] = 'Trung bình';
$lang->admin->safe->modeList[2] = 'Mạnh';

$lang->admin->safe->modeRuleList[1] = ' >= 6 chữ hoa, chữ thường và số';
$lang->admin->safe->modeRuleList[2] = ' >= 10 chữ hoa, chữ thường, số và ký tự đặc biệt.';

$lang->admin->safe->reasonList['weak']     = 'Mật khẩu yếu thường dùng';
$lang->admin->safe->reasonList['account']  = 'Giống tài khoản';
$lang->admin->safe->reasonList['mobile']   = 'Giống số di động';
$lang->admin->safe->reasonList['phone']    = 'Giống số điện thoại';
$lang->admin->safe->reasonList['birthday'] = 'Giống ngày sinh';

$lang->admin->safe->modifyPasswordList[1] = 'Có';
$lang->admin->safe->modifyPasswordList[0] = 'Không';

$lang->admin->safe->noticeMode   = 'Mật khẩu sẽ được kiểm tra khi người dùng đăng nhập hoặc người dùng thêm hoặc sửa.';
$lang->admin->safe->noticeStrong = '';
