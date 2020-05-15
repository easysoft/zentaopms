<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  upgrade
 * @version  $Id: vi.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->upgrade->common          = 'Cập nhật';
$lang->upgrade->result          = 'Kết quả';
$lang->upgrade->fail            = 'Thất bại';
$lang->upgrade->success         = "<p><i class='icon icon-check-circle'></i></p><p>Chúc mừng!</p><p> ZenTao của bạn đã được cập nhật.</p>";
$lang->upgrade->tohome          = 'Visit ZenTao';
$lang->upgrade->license         = 'ZenTao is under Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning        = 'Cảnh báo';
$lang->upgrade->checkExtension  = 'Kiểm tra Extensions';
$lang->upgrade->consistency     = 'Kiểm tra tính nhất quán';
$lang->upgrade->warnningContent = <<<EOT
<p>Vui lòng sao lưu cơ sở dữ liệu của bạn trước khi cập nhật ZenTao!</p>
<pre>
1. Sử dụng phpMyAdmin để sao lưu.
2. Sử dụng mysqlCommand để sao lưu.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span> 
   Thay đổi văn bản màu đỏ thành tên người dùng và cơ sở dữ liệu tương ứng.
   ví dụ:  mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = 'Mở dòng lệnh và xử lý <strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = 'Xử lý dòng lệnh: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Please complete the following actions</h4>
           <ul style="line-height:1.5;font-size:13px;">
           <li>%s</li>
           <li>Or xóa "<strong style="color:#ed980f">%s</strong>" and create <strong style="color:#ed980f">ok.txt</strong> and leave it blank.</li>
           </ul>
           <p><strong style="color:red">I have read and done as instructed above. <a href="upgrade.php">Continue upgrading.</a></strong></p>';
$lang->upgrade->selectVersion = 'Phiên bản';
$lang->upgrade->continue      = 'Tiếp tục';
$lang->upgrade->noteVersion   = "Chọn the compatible version, or it might cause data loss.";
$lang->upgrade->fromVersion   = 'Từ';
$lang->upgrade->toVersion     = 'Tới';
$lang->upgrade->confirm       = 'Xác nhận SQL';
$lang->upgrade->sureExecute   = 'Xử lý';
$lang->upgrade->forbiddenExt  = 'The extension is incompatible with the version. It has been deactivated:';
$lang->upgrade->updateFile    = 'File information has to be updated.';
$lang->upgrade->noticeSQL     = 'Your database is inconsistent with the standard and it failed to fix it. Vui lòng run the following SQL and refresh.';
$lang->upgrade->afterDeleted  = 'File không là deleted. Vui lòng refresh after you xóa it.';

include dirname(__FILE__) . '/version.php';
