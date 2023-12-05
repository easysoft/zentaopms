<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  convert
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->convert->common  = 'Imported';
$lang->convert->index   = 'Trang chủ';

$lang->convert->start   = 'Bắt đầu';
$lang->convert->desc = <<<EOT
<p>Welcome to the System Conversion Wizard, this program will assist you to convert data to ZenTao.</p>
<strong>There are risks in the conversion, so it is strongly recommended that you back up your databse and relavant files before conversion, and make sure that no one is using either system.</strong>
EOT;

$lang->convert->setConfig      = 'Nguồn cấu hình';
$lang->convert->setBugfree     = 'Bugfree cấu hình';
$lang->convert->setRedmine     = 'Redmine cấu hình';
$lang->convert->checkBugFree   = 'Kiểm tra Bugfree';
$lang->convert->checkRedmine   = 'Kiểm tra Redmine';
$lang->convert->convertRedmine = 'Convert Redmine';
$lang->convert->convertBugFree = 'Convert BugFree';

$lang->convert->selectSource     = 'Chọn hệ thống nguồn và phiên bản của nó';
$lang->convert->mustSelectSource = "Bạn phải chọn một hệ thống nguồn.";

$lang->convert->direction             = "{$lang->executionCommon} converted to";
$lang->convert->questionTypeOfRedmine = 'Type in Redmine';
$lang->convert->aimTypeOfZentao       = 'Chuyển thành Type in ZenTao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Nhiệm vụ';
$lang->convert->directionList['story'] = 'Câu chuyện';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Thiết lập';
$lang->convert->checkConfig = 'Kiểm tra Thiết lập';

$lang->convert->ok    = '<span class="text-success"><i class="icon-check-sign"></i> OK </span>';
$lang->convert->fail  = '<span class="text-danger"><i class="icon-remove-sign"></i> Failed</span>';

$lang->convert->dbHost      = 'Database Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Tên người dùng CSDL';
$lang->convert->dbPassword  = 'Mật khẩu CSDL';
$lang->convert->dbName      = 'Tên CSDL %s';
$lang->convert->dbCharset   = '%s Dữ liệu mã hóa';
$lang->convert->dbPrefix    = '%s Tiền tố bảng';
$lang->convert->installPath = '%s thư mục gốc cài đặt';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Bảng';
$lang->convert->checkPath  = 'Đường dẫn cài đặt';

$lang->convert->execute = 'Chuyển đổi';
$lang->convert->item    = 'Mục được chuyển';
$lang->convert->count   = 'STT';
$lang->convert->info    = 'Thông tin';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users      = 'Người dùng';
$lang->convert->bugfree->executions = $lang->executionCommon;
$lang->convert->bugfree->modules    = 'Module';
$lang->convert->bugfree->bugs       = 'Bug';
$lang->convert->bugfree->cases      = 'tình huống';
$lang->convert->bugfree->results    = 'Kết quả';
$lang->convert->bugfree->actions    = 'Lịch sử';
$lang->convert->bugfree->files      = 'Files';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'Người dùng';
$lang->convert->redmine->groups       = 'Nhóm';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->executions   = $lang->executionCommon;
$lang->convert->redmine->stories      = 'Câu chuyện';
$lang->convert->redmine->tasks        = 'Nhiệm vụ';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = 'Kế hoạch '.$lang->productCommon;
$lang->convert->redmine->teams        = 'Đội nhóm';
$lang->convert->redmine->releases     = 'Phát hành';
$lang->convert->redmine->builds       = 'Bản dựng';
$lang->convert->redmine->docLibs      = 'Doc Lib';
$lang->convert->redmine->docs         = 'Tài liệu';
$lang->convert->redmine->files        = 'Files';

$lang->convert->errorFileNotExits  = 'File %s không là found.';
$lang->convert->errorUserExists    = 'Người dùng %s đã tồn tại.';
$lang->convert->errorGroupExists   = 'Nhóm %s đã tồn tại.';
$lang->convert->errorBuildExists   = 'Bản dựng %s đã tồn tại.';
$lang->convert->errorReleaseExists = 'Phát hành %s đã tồn tại.';
$lang->convert->errorCopyFailed    = 'File %s copy thất bại';

$lang->convert->setParam = 'Thiết lập thông số';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Chuyển đổi vấn đề';
$lang->convert->statusType->bug   = 'Chuyển tình trạng (Tình trạng Bug)';
$lang->convert->statusType->story = 'Chuyển tình trạng (Tình trạng câu chuyện)';
$lang->convert->statusType->task  = 'Chuyển tình trạng (Tình trạng nhiệm vụ)';
$lang->convert->priType->bug      = 'Chuyển ưu tiên (Tình trạng Bug)';
$lang->convert->priType->story    = 'Chuyển ưu tiên (Tình trạng câu chuyện)';
$lang->convert->priType->task     = 'Chuyển ưu tiên (Tình trạng nhiệm vụ)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'ZenTao';
$lang->convert->issue->goto    = 'Chuyển thành';
