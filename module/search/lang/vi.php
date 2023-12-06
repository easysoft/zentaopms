<?php
/**
 * The search module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  search
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  https://www.zentao.net
 */
$lang->search->common        = 'Tìm kiếm';
$lang->search->id            = 'ID';
$lang->search->editedDate    = 'Edited Date';
$lang->search->key           = 'Key';
$lang->search->value         = 'Value';
$lang->search->reset         = 'Thiết lập lại';
$lang->search->saveQuery     = 'Lưu truy vấn';
$lang->search->myQuery       = 'My truy vấn';
$lang->search->group1        = 'Nhóm 1';
$lang->search->group2        = 'Nhóm 2';
$lang->search->buildForm     = 'Form tìm kiếm';
$lang->search->buildQuery    = 'Xử lý truy vấn';
$lang->search->savedQuery    = 'Truy vấn đã lưu';
$lang->search->deleteQuery   = 'Xóa truy vấn';
$lang->search->setQueryTitle = 'Nhập một tiêu đề. Tìm kiếm sau đó truy vấn này được lưu.';
$lang->search->select        = 'Lọc theo Câu chuyện/Nhiệm vụ';
$lang->search->me            = 'Của bạn';
$lang->search->noQuery       = 'Không có truy vấn được lưu nào!';
$lang->search->onMenuBar     = 'Hiện trong Menu';
$lang->search->custom        = 'Tùy biến';
$lang->search->setCommon     = 'Set as public query criteria';
$lang->search->saveCondition = 'Save search options';
$lang->search->setCondName   = 'Please enter a save condition name';

$lang->search->account  = 'Tài khoản';
$lang->search->module   = 'Module';
$lang->search->title    = 'Tiêu đề';
$lang->search->form     = 'Trường Form';
$lang->search->sql      = 'Điều kiện SQL';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = 'Bao gồm';
$lang->search->operators['between']    = 'Giữa';
$lang->search->operators['notinclude'] = 'Không gồm';
$lang->search->operators['belong']     = 'Sở hữu';

$lang->search->andor['and'] = 'Và';
$lang->search->andor['or']  = 'Hoặc';

$lang->search->null = 'Null';

$lang->userquery        = new stdclass();
$lang->userquery->title = 'Title';

$lang->searchObjects['todo']      = 'Việc làm';
$lang->searchObjects['effort']    = 'Chấm công';
$lang->searchObjects['testsuite'] = 'Test Suite';

$lang->search->objectType = 'Loại đối tượng';
$lang->search->objectID   = 'ID đối tượng';
$lang->search->content    = 'Nội dung';
$lang->search->addedDate  = 'Ngày thêm';

$lang->search->index      = 'Full Text Search';
$lang->search->buildIndex = 'Rebuild Index';
$lang->search->preview    = 'Preview';

$lang->search->inputWords        = 'Please input search words';
$lang->search->result            = 'Kết quả tìm kiếm';
$lang->search->resultCount       = '<strong>%s</strong> items';
$lang->search->buildSuccessfully = 'Tìm kiếm khởi tạo chỉ mục.';
$lang->search->executeInfo       = '%s kết quả tìm kiếm trong %s giây';
$lang->search->buildResult       = "Create index %s and created <strong class='%scount'>%s</strong> records.";
$lang->search->queryTips         = "Separate ids with comma";

$lang->search->modules['all']         = 'Tất cả';
$lang->search->modules['task']        = 'Nhiệm vụ';
$lang->search->modules['bug']         = 'Bug';
$lang->search->modules['case']        = 'Tình huống';
$lang->search->modules['doc']         = 'Tài liệu';
$lang->search->modules['todo']        = 'Việc làm';
$lang->search->modules['build']       = 'Bản dựng';
$lang->search->modules['effort']      = 'Chấm công';
$lang->search->modules['caselib']     = 'CaseLib';
$lang->search->modules['product']     = $lang->productCommon;
$lang->search->modules['release']     = 'Phát hành';
$lang->search->modules['testtask']    = 'Yêu cầu Test';
$lang->search->modules['testsuite']   = 'Test Suite';
$lang->search->modules['testreport']  = 'Báo cáo Test';
$lang->search->modules['productplan'] = 'Kế hoạch';
$lang->search->modules['program']     = 'Program';
$lang->search->modules['project']     = 'Project';
$lang->search->modules['execution']   = $lang->executionCommon;
$lang->search->modules['story']       = 'Story';
$lang->search->modules['requirement'] = $lang->URCommon;

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['stage']            = 'stage';
$lang->search->objectTypeList['sprint']           = $lang->executionCommon;
$lang->search->objectTypeList['kanban']           = 'kanban';
$lang->search->objectTypeList['commonIssue']      = 'Issue';
$lang->search->objectTypeList['stakeholderIssue'] = 'Stakeholder Issue';
