#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTask();
timeout=0
cid=0

- 步骤1：正常创建任务 @1
- 步骤2：创建包含时间估算的任务 @0
- 步骤3：创建包含截止日期和指派人的任务 @1
- 步骤4：创建包含resolution字段的任务 @1
- 步骤5：创建最小化字段的任务 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 创建测试所需的临时表
global $tester;
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

try {
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
}

if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 准备项目数据
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->code->range('PRJ001,PRJ002,PRJ003,PRJ004,PRJ005');
$project->status->range('doing{5}');
$project->type->range('project{5}');
$project->deleted->range('0{5}');
$project->gen(5);

// 准备执行数据
$execution = zenData('project');
$execution->id->range('11-15');
$execution->name->range('执行11,执行12,执行13,执行14,执行15');
$execution->code->range('EXE011,EXE012,EXE013,EXE014,EXE015');
$execution->status->range('doing{5}');
$execution->type->range('sprint{5}');
$execution->parent->range('1-5');
$execution->project->range('1-5');
$execution->deleted->range('0{5}');
$execution->gen(5, false);

// 准备用户数据
$user = zenData('user');
$user->account->range('admin,user1,user2,testuser');
$user->realname->range('管理员,用户1,用户2,测试用户');
$user->deleted->range('0{4}');
$user->gen(4);

zenData('action')->gen(0);

su('admin');

$convertTest = new convertTaoTest();

// 准备测试数据1：正常创建任务，包含完整必需字段
$data1 = new stdclass();
$data1->id = 1001;
$data1->summary = '测试任务1';
$data1->issuetype = 'Task';
$data1->issuestatus = 'Open';
$data1->priority = 2;
$data1->description = '这是测试任务的描述';
$data1->creator = 'admin';
$data1->created = '2023-01-01 10:00:00';
$data1->assignee = 'user1';
$data1->resolution = '';
$relations1 = array();

// 准备测试数据2：包含时间估算的任务
$data2 = new stdclass();
$data2->id = 1002;
$data2->summary = '测试任务2';
$data2->issuetype = 'Task';
$data2->issuestatus = 'Open';
$data2->priority = 3;
$data2->timeoriginalestimate = 7200; // 2小时=7200秒
$data2->timeestimate = 3600; // 1小时=3600秒
$data2->timespent = 1800; // 0.5小时=1800秒
$data2->description = '带时间估算的任务';
$data2->creator = 'admin';
$data2->created = '2023-01-02 10:00:00';
$data2->assignee = 'user1';
$data2->resolution = '';
$relations2 = array();

// 准备测试数据3：包含截止日期和指派人的任务
$data3 = new stdclass();
$data3->id = 1003;
$data3->summary = '测试任务3';
$data3->issuetype = 'Task';
$data3->issuestatus = 'Open';
$data3->priority = 1;
$data3->description = '带截止日期的任务';
$data3->creator = 'admin';
$data3->created = '2023-01-03 10:00:00';
$data3->assignee = 'user2';
$data3->duedate = '2023-12-31 23:59:59';
$data3->resolution = '';
$relations3 = array();

// 准备测试数据4：包含resolution字段的任务
$data4 = new stdclass();
$data4->id = 1004;
$data4->summary = '测试任务4';
$data4->issuetype = 'Task';
$data4->issuestatus = 'Closed';
$data4->priority = 3;
$data4->description = '已关闭的任务';
$data4->creator = 'admin';
$data4->created = '2023-01-04 10:00:00';
$data4->assignee = 'user1';
$data4->resolution = 'done';
$relations4 = array('zentaoReasonTask' => array('done' => 'done', 'cancel' => 'cancel'));

// 准备测试数据5：最小化字段的任务
$data5 = new stdclass();
$data5->id = 1005;
$data5->summary = '测试任务5';
$data5->issuetype = 'Task';
$data5->issuestatus = 'Open';
$data5->priority = 0; // 测试默认优先级
$data5->creator = '';
$data5->created = '';
$data5->assignee = '';
$data5->resolution = '';
$relations5 = array();

r($convertTest->createTaskTest(1, 11, $data1, $relations1)) && p() && e('1'); // 步骤1：正常创建任务
r($convertTest->createTaskTest(2, 12, $data2, $relations2)) && p() && e('0'); // 步骤2：创建包含时间估算的任务
r($convertTest->createTaskTest(3, 13, $data3, $relations3)) && p() && e('1'); // 步骤3：创建包含截止日期和指派人的任务
r($convertTest->createTaskTest(4, 14, $data4, $relations4)) && p() && e('1'); // 步骤4：创建包含resolution字段的任务
r($convertTest->createTaskTest(5, 15, $data5, $relations5)) && p() && e('1'); // 步骤5：创建最小化字段的任务
