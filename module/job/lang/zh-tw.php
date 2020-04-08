<?php
$lang->job->common        = '構建任務';
$lang->job->browse        = '瀏覽構建任務';
$lang->job->create        = '創建構建任務';
$lang->job->edit          = '編輯構建任務';
$lang->job->exec          = '執行構建';
$lang->job->view          = '執行詳情';
$lang->job->delete        = '刪除構建任務';
$lang->job->confirmDelete = '確認刪除該構建任務';
$lang->job->dirChange     = '目錄改動';
$lang->job->buildTag      = '打標籤';

$lang->job->id          = 'ID';
$lang->job->name        = '名稱';
$lang->job->repo        = '代碼庫';
$lang->job->product     = '關聯' . $lang->productCommon;
$lang->job->svnDir      = 'SVN監控路徑';
$lang->job->jenkins     = 'Jenkins';
$lang->job->jkHost      = 'Jenkins伺服器';
$lang->job->buildType   = '構建類型';
$lang->job->jkJob       = 'Jenkins任務';
$lang->job->frame       = '工具/框架';
$lang->job->triggerType = '觸發方式';
$lang->job->atDay       = '自定義日期';
$lang->job->atTime      = '執行時間';
$lang->job->lastStatus  = '最後執行狀態';
$lang->job->lastExec    = '最後執行時間';
$lang->job->comment     = '匹配關鍵字';

$lang->job->lblBasic = '基本信息';

$lang->job->example    = '舉例';
$lang->job->commitEx   = "用於匹配創建構建任務的關鍵字，多個關鍵字用','分割";
$lang->job->cronSample = '如 0 0 2 * * 2-6/1 表示每個工作日凌晨2點';
$lang->job->sendExec   = '發送執行請求成功！執行結果：%s';

$lang->job->buildTypeList['build']          = '僅構建';
$lang->job->buildTypeList['buildAndDeploy'] = '構建部署';
$lang->job->buildTypeList['buildAndTest']   = '構建測試';

$lang->job->triggerTypeList['tag']      = '打標籤';
$lang->job->triggerTypeList['commit']   = '提交註釋包含關鍵字';
$lang->job->triggerTypeList['schedule'] = '定時計劃';

$lang->job->frameList['']        = '';
$lang->job->frameList['junit']   = 'JUnit';
$lang->job->frameList['testng']  = 'TestNG';
$lang->job->frameList['phpunit'] = 'PHPUnit';
$lang->job->frameList['pytest']  = 'Pytest';
$lang->job->frameList['jtest']   = 'JTest';
$lang->job->frameList['cppunit'] = 'CppUnit';
$lang->job->frameList['gtest']   = 'GTest';
$lang->job->frameList['qtest']   = 'QTest';
