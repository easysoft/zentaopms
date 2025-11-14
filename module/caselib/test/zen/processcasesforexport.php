#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processCasesForExport();
timeout=0
cid=15554

- 步骤1:测试空用例数组处理 @0
- 步骤2:测试单个用例的基本字段处理 @1
- 步骤3:测试用例类型字段被转换 @功能测试
- 步骤4:测试用例返回数量 @1
- 步骤5:测试用例日期字段格式化 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->lib->range('1{5},2{5}');
$caseTable->title->range('用例标题1,用例标题2,用例标题3,用例标题4,用例标题5,用例标题6,用例标题7,用例标题8,用例标题9,用例标题10');
$caseTable->module->range('101,102,103,104,105,106,107,108,109,110');
$caseTable->pri->range('1,2,3,4,1,2,3,4,1,2');
$caseTable->type->range('feature,performance,config,install,security,interface,feature,performance,config,install');
$caseTable->status->range('normal,blocked,investigate,normal,normal,blocked,normal,normal,investigate,normal');
$caseTable->stage->range('unittest,feature,integration,system,smoke,unittest,feature,integration,system,smoke');
$caseTable->openedBy->range('admin,user1,user2,user3,admin,user1,user2,user3,admin,user1');
$caseTable->lastEditedBy->range('admin,user1,user2,user3,admin,user1,user2,user3,admin,user1');
$caseTable->openedDate->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`,`2024-01-04 13:00:00`,`2024-01-05 14:00:00`,`2024-01-06 15:00:00`,`2024-01-07 16:00:00`,`2024-01-08 17:00:00`,`2024-01-09 18:00:00`,`0000-00-00 00:00:00`');
$caseTable->lastRunDate->range('`2024-02-01 10:00:00`,`2024-02-02 11:00:00`,`2024-02-03 12:00:00`,`2024-02-04 13:00:00`,`2024-02-05 14:00:00`,`2024-02-06 15:00:00`,`2024-02-07 16:00:00`,`2024-02-08 17:00:00`,`2024-02-09 18:00:00`,`0000-00-00 00:00:00`');
$caseTable->linkCase->range('0,2,0,4,0,6,0,8,0,0');
$caseTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('101-120');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10,模块11,模块12,模块13,模块14,模块15,模块16,模块17,模块18,模块19,模块20');
$moduleTable->type->range('caselib{20}');
$moduleTable->root->range('1{10},2{10}');
$moduleTable->gen(20);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->gen(10);

$stepTable = zenData('casestep');
$stepTable->id->range('1-15');
$stepTable->case->range('1{3},2{3},3{3},4{3},5{3}');
$stepTable->version->range('1{15}');
$stepTable->type->range('step{15}');
$stepTable->parent->range('0{15}');
$stepTable->desc->range('步骤1,步骤2,步骤3,步骤A,步骤B,步骤C,测试步骤1,测试步骤2,测试步骤3,操作1,操作2,操作3,执行1,执行2,执行3');
$stepTable->expect->range('预期1,预期2,预期3,预期A,预期B,预期C,测试预期1,测试预期2,测试预期3,结果1,结果2,结果3,期望1,期望2,期望3');
$stepTable->gen(15);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$caselibTest = new caselibTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($caselibTest->processCasesForExportTest(array(), 1, array('fileType' => 'csv'), 'count')) && p() && e('0'); // 步骤1:测试空用例数组处理
r($caselibTest->processCasesForExportTest(array(1 => (object)array('id' => 1, 'title' => '测试用例', 'pri' => 1, 'type' => 'feature', 'status' => 'normal', 'stage' => 'unittest', 'openedBy' => 'admin', 'lastEditedBy' => 'admin', 'module' => 101, 'openedDate' => '2024-01-01 10:00:00', 'lastRunDate' => '2024-02-01 10:00:00', 'linkCase' => '')), 1, array('fileType' => 'csv'), 'first_case_pri')) && p() && e('1'); // 步骤2:测试单个用例的基本字段处理
r($caselibTest->processCasesForExportTest(array(1 => (object)array('id' => 1, 'title' => '测试用例', 'pri' => 1, 'type' => 'feature', 'status' => 'normal', 'stage' => 'unittest', 'openedBy' => 'admin', 'lastEditedBy' => 'admin', 'module' => 101, 'openedDate' => '2024-01-01 10:00:00', 'lastRunDate' => '2024-02-01 10:00:00', 'linkCase' => '')), 1, array('fileType' => 'csv'), 'first_case_type')) && p() && e('功能测试'); // 步骤3:测试用例类型字段被转换
r($caselibTest->processCasesForExportTest(array(1 => (object)array('id' => 1, 'title' => '测试用例', 'pri' => 1, 'type' => 'feature', 'status' => 'normal', 'stage' => 'unittest', 'openedBy' => 'admin', 'lastEditedBy' => 'admin', 'module' => 101, 'openedDate' => '2024-01-01 10:00:00', 'lastRunDate' => '2024-02-01 10:00:00', 'linkCase' => '')), 1, array('fileType' => 'csv'), 'count')) && p() && e('1'); // 步骤4:测试用例返回数量
r($caselibTest->processCasesForExportTest(array(10 => (object)array('id' => 10, 'title' => '测试用例10', 'pri' => 2, 'type' => 'install', 'status' => 'normal', 'stage' => 'smoke', 'openedBy' => 'user1', 'lastEditedBy' => 'user1', 'module' => 110, 'openedDate' => '0000-00-00 00:00:00', 'lastRunDate' => '0000-00-00 00:00:00', 'linkCase' => '')), 2, array('fileType' => 'csv'), 'first_case_openedDate')) && p() && e('0'); // 步骤5:测试用例日期字段格式化