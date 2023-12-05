<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  install
 * @version  $Id: vi.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link  http://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common = 'Cài đặt';
$lang->install->next   = 'Tiếp';
$lang->install->pre    = 'Trước';
$lang->install->reload = 'Refresh';
$lang->install->error  = 'Lỗi ';

$lang->install->officeDomain = 'https://www.zentao.pm';

$lang->install->start            = 'Bắt đầu';
$lang->install->keepInstalling   = 'Tiếp tục cài đặt với phiên bản này';
$lang->install->seeLatestRelease = 'Xem the latest version';
$lang->install->welcome          = 'Thanks for choosing ZenTao!';
$lang->install->license          = 'ZenTao is under Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->devopsDesc       = 'The underlying foundation of the DevOps platform is built upon cloud-native technologies such as Docker and Kubernetes (K8s). It incorporates an integrated application marketplace, allowing seamless installation of essential tools like code repositories, pipelines, and artifact libraries.';
$lang->install->desc = <<<EOT
ZenTao ALM is an open source software released under <a href='http://zpl.pub/page/zplv12.html' target='_blank'>Z Public License</a>. It integrates with Product Management, Project Management, Test Management, Document Management, CI Management, etc. ZenTao is a perfect choice for managing software development projects.

ZenTao ALM is built on PHP + MySQL + zentaoPHP which is an independent framework developed by ZenTao Software. Third-party developers/organizations can develop extensions or customize ZenTao accordingly.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM is developed by <strong><a href='https://en.easysoft.ltd' target='_blank' class='text-danger'>ZenTao Software</a></strong>.
Official Website: <a href='https://www.zentao.pm' target='_blank'>https://www.zentao.pm</a>
Technical Support: <a href='https://www.zentao.pm/forum/' target='_blank'>https://www.zentao.pm/forum/</a>
LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>ZenTao Software</a>
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>ZenTao Software</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

You are installing <strong class='text-danger'>ZenTao %s</strong>.
EOT;

$lang->install->newReleased = "<strong class='text-danger'>Thông báo</strong>: Official Website has the latest version<strong class='text-danger'>%s</strong>, released on %s.";
$lang->install->or          = 'Or';
$lang->install->checking    = 'Kiểm tra hệ thống';
$lang->install->ok          = 'Passed(√)';
$lang->install->fail        = 'Failed(×)';
$lang->install->loaded      = 'Loaded';
$lang->install->unloaded    = 'Not loaded';
$lang->install->exists      = 'Tìm thấy ';
$lang->install->notExists   = 'Không tìm thấy ';
$lang->install->writable    = 'Có thể ghi ';
$lang->install->notWritable = 'Không thể ghi ';
$lang->install->phpINI      = 'Tập tin PHP ini';
$lang->install->checkItem   = 'Hạng mục';
$lang->install->current     = 'Thiết lập hiện tại';
$lang->install->result      = 'Kết quả';
$lang->install->action      = 'Hành động';

$lang->install->phpVersion = 'Phiên bản PHP';
$lang->install->phpFail    = 'PHP Version should be 5.2.0+';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Sửa php.ini to load PDO extension.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Sửa php.ini to load PDO_MySQL extension.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Sửa php.ini to load JSON extension.';
$lang->install->openssl      = 'OpenSSL Extension';
$lang->install->opensslFail  = 'Sửa php.ini to load openssl extension.';
$lang->install->mbstring     = 'Mbstring Extension';
$lang->install->mbstringFail = 'Sửa php.ini to load mbstring extension.';
$lang->install->zlib         = 'Zlib Extension';
$lang->install->zlibFail     = 'Sửa php.ini to load zlib extension.';
$lang->install->curl         = 'Curl Extension';
$lang->install->curlFail     = 'Sửa php.ini to load curl extension.';
$lang->install->filter       = 'Filter Extension';
$lang->install->filterFail   = 'Sửa the php.ini file to load filter extension.';
$lang->install->gd           = 'GD Extension';
$lang->install->gdFail       = 'Sửa the php.ini file to load gd extension.';
$lang->install->iconv        = 'Iconv Extension';
$lang->install->iconvFail    = 'Sửa the php.ini file to load iconv extension.';
$lang->install->tmpRoot      = 'Temp Directory';
$lang->install->dataRoot     = 'Thư mục tập tin tải lên';
$lang->install->session      = 'Đường dẫn lưu Session';
$lang->install->sessionFail  = 'Sửa the php.ini file to set session.save_path.';
$lang->install->mkdirWin     = '<p>%s directory has to be created.<br /> Run <code>mkdir %s</code> to create it.</p>';
$lang->install->chmodWin     = ' "%s" privilege has to be changed.';
$lang->install->mkdirLinux   = '<p>%s directory has to be created.<br /> Run <code>mkdir -p %s</code> to create it.</p>';
$lang->install->chmodLinux   = ' "%s" permison has to be changed.<br /> Run <code>chmod 777 -R %s</code> to change it.';

$lang->install->timezone       = 'Thiết lập Timezone';
$lang->install->defaultLang    = 'Ngôn ngữ mặc định';
$lang->install->dbDriver       = 'Database Driver';
$lang->install->dbHost         = 'Database Host';
$lang->install->dbHostNote     = 'If 127.0.0.1 không là accessible, try localhost.';
$lang->install->dbPort         = 'Host Port';
$lang->install->dbEncoding     = 'Database Charset';
$lang->install->dbUser         = 'Người dùng CSDL';
$lang->install->dbPassword     = 'Mật khẩu CSDL';
$lang->install->dbName         = 'Tên CSDL';
$lang->install->dbPrefix       = 'Tiền tố bảng';
$lang->install->clearDB        = 'Clean up existing data';
$lang->install->importDemoData = 'Nhập Demo dữ liệu';
$lang->install->working        = 'Operation chế độ';

$lang->install->dbDriverList = array();
$lang->install->dbDriverList['mysql'] = 'MySQL';
$lang->install->dbDriverList['dm']    = 'DM8';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Quản lý vòng đời ứng dụng';

$lang->install->errorConnectDB      = 'Connection to the database Failed. ';
$lang->install->errorDBName         = 'Database name should exclude “.” ';
$lang->install->errorCreateDB       = 'Tạo thất bại the database.';
$lang->install->errorTableExists    = 'The data table has existed. If ZenTao has been installed before, please return to the previous step and clear data, then continue the installation.';
$lang->install->errorCreateTable    = 'Tạo thất bại the table.';
$lang->install->errorEngineInnodb   = 'Your MySQL does not support InnoDB data table engine. Please modify it to MyISAM and try again.';
$lang->install->errorImportDemoData = 'Thất bại nhập dữ liệu demo.';

$lang->install->setConfig          = 'Tạo tập tin cấu hình';
$lang->install->key                = 'Hạng mục';
$lang->install->value              = 'Giá trị';
$lang->install->saveConfig         = 'Lưu tập tin cấu hình';
$lang->install->save2File          = '<div class="alert alert-warning">Copy the content in the text box above and save it to "<strong> %s </strong>". Bạn có thể change this configuration file later.</div>';
$lang->install->saved2File         = 'The configuration file has been saved to " <strong>%s</strong> ". Bạn có thể change this file later.';
$lang->install->errorNotSaveConfig = 'The configuration file không là saved.';

$lang->install->getPriv            = 'Thiết lập Admin';
$lang->install->company            = 'Tên doanh nghiệp';
$lang->install->account            = 'Tài khoản quản trị';
$lang->install->password           = 'Mật khẩu quản trị';
$lang->install->errorEmptyPassword = 'Mật khẩu không nên là blank.';

$lang->install->selectedMode     = 'Selection mode';
$lang->install->selectedModeTips = 'You can go to the Admin - Custom - Mode to set it later.';

$lang->install->groupList['ADMIN']['name']   = 'Quản trị';
$lang->install->groupList['ADMIN']['desc']   = 'Quản trị hệ thống';
$lang->install->groupList['DEV']['name']     = 'Dev';
$lang->install->groupList['DEV']['desc']     = 'Lập trình viên';
$lang->install->groupList['QA']['name']      = 'QA';
$lang->install->groupList['QA']['desc']      = 'Tester';
$lang->install->groupList['PM']['name']      = 'CH';
$lang->install->groupList['PM']['desc']      = 'Quản lý dự án';
$lang->install->groupList['PO']['name']      = 'PO';
$lang->install->groupList['PO']['desc']      = 'Sở hữu sản phẩm';
$lang->install->groupList['TD']['name']      = 'Dev Manager';
$lang->install->groupList['TD']['desc']      = 'Quản lý phát triển';
$lang->install->groupList['PD']['name']      = 'PD';
$lang->install->groupList['PD']['desc']      = 'Giám đốc sản phẩm';
$lang->install->groupList['QD']['name']      = 'QD';
$lang->install->groupList['QD']['desc']      = 'Giám đốc QA';
$lang->install->groupList['TOP']['name']     = 'Senior';
$lang->install->groupList['TOP']['desc']     = 'Quản lý cấp cao';
$lang->install->groupList['OTHERS']['name']  = 'Khác';
$lang->install->groupList['OTHERS']['desc']  = 'Người dùng khác';
$lang->install->groupList['LIMITED']['name'] = 'Người dùng hạn chế';
$lang->install->groupList['LIMITED']['desc'] = 'Người dùng chỉ có thể chỉnh sửa nội dung được liên kết tới của họ.';

$lang->install->cronList[''] = 'Monitor Cron';
$lang->install->cronList['moduleName=execution&methodName=computeBurn'] = 'Cập nhật biểu đồ Burndown';
$lang->install->cronList['moduleName=report&methodName=remind']         = 'Nhắc nhở nhiệm vụ hàng ngày';
$lang->install->cronList['moduleName=svn&methodName=run']               = 'Đồng bộ SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = 'Đồng bộ GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = 'Sao lưu dữ liệu';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = 'Đồng bộ gửi tin nhắn';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = 'Đồng bộ gửi webhook';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = 'Xóa nhật ký quá hạn';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = 'Tạo việc thường xuyên';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Tạo recurring Jenkins';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Đồng bộ Jenkins tình trạng';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Xử lý Jenkins';

$lang->install->groupList['IPDPRODUCTPLAN']['name'] = 'PRODUCT PLANING';
$lang->install->groupList['IPDDEMAND']['name']      = 'DEMAND ANALYSIS';
$lang->install->groupList['IPDPMT']['name']         = 'IPDPMT';
$lang->install->groupList['IPDADMIN']['name']       = 'IPDADMIN';

$lang->install->success  = "Đã được cài đặt!";
$lang->install->login    = 'Đăng nhập ZenTao';
$lang->install->register = 'Đăng ký cộng đồng ZenTao';

$lang->install->successLabel       = "<p>Bạn đã cài đặt ZenTao %s.</p>";
$lang->install->successNoticeLabel = "<p>Bạn đã cài đặt ZenTao %s.<strong class='text-danger'> Vui lòng xóa install.php</strong>.</p>";
$lang->install->joinZentao         = <<<EOT
<p>Chú ý: Để nhận tin tức mới nhất về ZenTao, vui lòng đăng ký trên Cộng đồng ZenTao(<a href='https://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo', 'ydisk', 'meshiot');

$lang->install->promotion = "Sản phẩm từ ZenTao Software:";

$lang->install->chanzhi  = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'https://www.zsite.net';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Article, Blog, Manual, Member, Shop, Forum, Feedback</li>
  <li>Customize page at will by Theme, Effect, Widget, CSS, JS and layout</li>
  <li>Support both desktop and mobile in one system</li>
  <li>Highly optimized for search engines</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'https://www.zdoo.co/';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>CRM: Customer Management and Order Tracking</li>
  <li>OA: Approve, Announce, Trip, Leave and more </li>
  <li>Project, Task and Document management </li>
  <li>Cash: Income, Expense, Transfer, Invest and Debt</li>
</ul>
EOD;














$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name = 'YDisk';
$lang->install->ydisk->logo = 'images/main/ydisk.ico';
$lang->install->ydisk->url  = 'http://www.ydisk.cn';
$lang->install->ydisk->desc = <<<EOD
<ul>
  <li>Self-Hosted: deploy on your own machine</li>
  <li>Unlimited Storage: depend on your hard drive size</li>
  <li>Fast Transmission: as fast as your bandwidth allows</li>
  <li>Secure: 12 permissions for any strict settings</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name = 'MeshIoT';
$lang->install->meshiot->logo = 'images/main/meshiot.ico';
$lang->install->meshiot->url  = 'https://www.meshiot.com';
$lang->install->meshiot->desc = <<<EOD
<ul>
  <li>Performance: one gateway can monitor 65,536 equipments</li>
  <li>Accessibility: unique radio communication protocol covers 2,500m radius</li>
  <li>Dimming System: 200+ sensors and monitors</li>
  <li>Battery Khả dụng: no changes required to any equipment on your site</li>
</ul>
EOD;

$lang->install->solution = new stdclass();
$lang->install->solution->skip        = 'Skip';
$lang->install->solution->skipInstall = 'Skip';
$lang->install->solution->log         = 'Log';
$lang->install->solution->title       = 'DevOps platform application settings';
$lang->install->solution->progress    = 'Installing of DevOps platform';
$lang->install->solution->desc        = 'Welcome to the DevOps platform. We will install the following applications simultaneously when you install the platform to help you get started quickly!';
$lang->install->solution->overMemory  = 'Insufficient memory prevents simultaneous installation. It is recommended to install applications manually after the platform is started.';
