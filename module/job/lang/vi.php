<?php
$lang->job->common        = 'Job';
$lang->job->browse        = 'Browse Job';
$lang->job->create        = 'Tạo Job';
$lang->job->edit          = 'Sửa Job';
$lang->job->exec          = 'Xử lý Job';
$lang->job->view          = 'Execute Details';
$lang->job->delete        = 'Xóa Job';
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
$lang->job->buildType   = 'Loại bản dựng';
$lang->job->jkJob       = 'Jenkins nhiệm vụ';
$lang->job->frame       = 'Frame';
$lang->job->triggerType = 'Trigger';
$lang->job->atDay       = 'Ngày tùy chọn';
$lang->job->atTime      = 'Vào lúc';
$lang->job->lastStatus  = 'Tình trạng cuối';
$lang->job->lastExec    = 'Xử lý cuối';
$lang->job->comment     = 'Từ khóa phù hợp';

$lang->job->lblBasic = 'Basic Info';

$lang->job->example    = 'ví dụ: ';
$lang->job->commitEx   = "Used to match the keywords used to create a compile. Multiple keywords are separated by ','";
$lang->job->cronSample = 'ví dụ:  0 0 2 * * 2-6/1 means 2:00 a.m. every weekday.';
$lang->job->sendExec   = 'Gửi execute request success.';

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
