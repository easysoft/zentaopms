<?php
$lang->job->common        = 'Job';
$lang->job->browse        = 'Browse Pipeline';
$lang->job->create        = 'Tạo Pipeline';
$lang->job->edit          = 'Sửa Pipeline';
$lang->job->exec          = 'Xử lý Pipeline';
$lang->job->view          = 'Execute Details';
$lang->job->delete        = 'Xóa Pipeline';
$lang->job->confirmDelete = 'Bạn có muốn xóa job này?';
$lang->job->dirChange     = 'Thư mục đã thay đổi';
$lang->job->buildTag      = 'Tag bản dựng';

$lang->job->id          = 'ID';
$lang->job->name        = 'Tên';
$lang->job->repo        = 'Repo';
$lang->job->product     = $lang->productCommon;
$lang->job->svnDir      = 'SVN Tag Watch Path';
$lang->job->jenkins     = 'Jenkins';
$lang->job->jkHost      = 'Jenkins Server';
$lang->job->jkJob       = 'Jenkins nhiệm vụ';
$lang->job->server      = 'Server';
$lang->job->pipeline    = 'Pipeline';
$lang->job->buildType   = 'Loại bản dựng';
$lang->job->frame       = 'Frame';
$lang->job->triggerType = 'Trigger';
$lang->job->atDay       = 'Ngày tùy chọn';
$lang->job->atTime      = 'Vào lúc';
$lang->job->lastStatus  = 'Tình trạng cuối';
$lang->job->lastExec    = 'Xử lý cuối';
$lang->job->comment     = 'Từ khóa phù hợp';
$lang->job->customParam = 'Tham số tự chọn';
$lang->job->paramName   = 'Tên';
$lang->job->paramValue  = 'giá';
$lang->job->custom      = 'Custom';

$lang->job->lblBasic = 'Basic Info';

$lang->job->example     = 'ví dụ: ';
$lang->job->commitEx    = "Used to match the keywords used to create a compile. Multiple keywords are separated by ','";
$lang->job->cronSample  = 'ví dụ:  0 0 2 * * 2-6/1 means 2:00 a.m. every weekday.';
$lang->job->sendExec    = 'Gửi execute request success.';
$lang->job->inputName   = 'Hãy nhập tên tham số.';
$lang->job->invalidName = 'Tên tham số phải là các chữ cái, số điện, hay ô gạch.';

$lang->job->buildTypeList['build']          = 'Chỉ bản dựng';
$lang->job->buildTypeList['buildAndDeploy'] = 'Bản dựng và triển khai';
$lang->job->buildTypeList['buildAndTest']   = 'Bản dựng và Test';

$lang->job->triggerTypeList['tag']      = 'Tag';
$lang->job->triggerTypeList['commit']   = 'Code Commit';
$lang->job->triggerTypeList['schedule'] = 'Lịch trình';

$lang->job->frameList['']        = '';
$lang->job->frameList['junit']   = 'JUnit';
$lang->job->frameList['testng']  = 'TestNG';
$lang->job->frameList['phpunit'] = 'PHPUnit';
$lang->job->frameList['pytest']  = 'Pytest';
$lang->job->frameList['jtest']   = 'JTest';
$lang->job->frameList['cppunit'] = 'CppUnit';
$lang->job->frameList['gtest']   = 'GTest';
$lang->job->frameList['qtest']   = 'QTest';

$lang->job->paramValueList['']                 = '';
$lang->job->paramValueList['$zentao_version']  = 'Current version';
$lang->job->paramValueList['$zentao_account']  = 'Current user';
$lang->job->paramValueList['$zentao_product']  = 'Current product ID';
$lang->job->paramValueList['$zentao_repopath'] = 'Current version library path';
