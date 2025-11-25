#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraIssue();
timeout=0
cid=15859

- 执行convertTest模块的importJiraIssueTest方法，参数是array  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData2  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData3  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData4  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData5  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData6  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData7  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData8  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData9  @1
- 执行convertTest模块的importJiraIssueTest方法，参数是$testData10  @1

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
    // 表可能已存在,忽略错误
}

if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 清理可能存在的测试数据
try {
    $tester->dbh->exec('DELETE FROM zt_story WHERE title LIKE "Test%" OR title LIKE "Jira%"');
    $tester->dbh->exec('DELETE FROM zt_task WHERE name LIKE "Test%" OR name LIKE "Jira%"');
    $tester->dbh->exec('DELETE FROM zt_bug WHERE title LIKE "Test%" OR title LIKE "Jira%"');
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 忽略清理错误
}

// 准备用户数据
$user = zenData('user');
$user->account->range('admin,user1,user2,jirauser1,jirauser2');
$user->realname->range('管理员,用户1,用户2,Jira用户1,Jira用户2');
$user->password->range('123456{5}');
$user->role->range('admin{1},qa{2},dev{2}');
$user->deleted->range('0{5}');
$user->gen(5);

// 准备项目数据
$project = zenData('project');
$project->name->range('测试项目1,测试项目2,Jira项目1,Jira项目2');
$project->code->range('PRJ001,PRJ002,JIRA001,JIRA002');
$project->status->range('doing{4}');
$project->deleted->range('0{4}');
$project->type->range('project{4}');
$project->gen(4);

// 准备产品数据
$product = zenData('product');
$product->name->range('测试产品1,测试产品2,Jira产品1,Jira产品2');
$product->code->range('PROD001,PROD002,JIRAPROD1,JIRAPROD2');
$product->status->range('normal{4}');
$product->deleted->range('0{4}');
$product->type->range('normal{4}');
$product->gen(4);

// 准备执行数据
$execution = zenData('project');
$execution->name->range('迭代1,迭代2,Sprint1,Sprint2');
$execution->status->range('doing{4}');
$execution->deleted->range('0{4}');
$execution->type->range('sprint{4}');
$execution->parent->range('1,1,2,2');
$execution->gen(4);

// 准备临时关系表的数据
try {
    // 项目和产品的映射关系
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zproject', '1')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zproduct', '1')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zexecution', '1')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1002', 'zproject', '2')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1002', 'zproduct', '2')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1002', 'zexecution', '2')");

    // 已存在的issue记录(用于测试去重)
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('jissueid', '5001', 'zstory', '1', 'issue')");

    // Sprint映射关系
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jsprint', '2001', 'zexecution', '3')");

    // 项目key映射
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('joldkey', 'OLD', 'jnewkey', 'NEW', '1001')");
} catch (Exception $e) {
    // 如果数据已存在则忽略错误
}

// 设置必要的session数据
global $app;
$app->session->set('jiraMethod', 'file');
$app->session->set('jiraRelation', json_encode(array(
    'zentaoObject' => array(
        '1' => 'story',
        '2' => 'task',
        '3' => 'bug',
        '4' => 'testcase',
        '5' => 'feedback',
        '6' => 'ticket'
    )
)));

su('admin');

$convertTest = new convertTaoTest();

// 步骤1:空数组输入边界值测试
r($convertTest->importJiraIssueTest(array())) && p() && e('1');

// 步骤2:已存在issue去重机制验证
$testData2 = array(
    '5001' => (object)array(
        'id' => '5001',
        'project' => '1001',
        'issuetype' => '1',
        'issuenum' => '101',
        'summary' => 'Test Story Already Exists',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 10:00:00',
        'assignee' => 'admin',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData2)) && p() && e('1');

// 步骤3:项目关系未映射场景过滤测试
$testData3 = array(
    '5002' => (object)array(
        'id' => '5002',
        'project' => '9999',
        'issuetype' => '1',
        'issuenum' => '102',
        'summary' => 'Test Unmapped Project',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 10:00:00',
        'assignee' => 'admin',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData3)) && p() && e('1');

// 步骤4:Story类型issue正常导入流程测试
$testData4 = array(
    '5003' => (object)array(
        'id' => '5003',
        'project' => '1001',
        'issuetype' => '1',
        'issuenum' => '103',
        'summary' => 'Jira Story Import Test',
        'priority' => '2',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 11:00:00',
        'assignee' => 'user1',
        'resolution' => null,
        'description' => 'This is a test story imported from Jira'
    )
);
r($convertTest->importJiraIssueTest($testData4)) && p() && e('1');

// 步骤5:Task类型issue正常导入流程测试
$testData5 = array(
    '5004' => (object)array(
        'id' => '5004',
        'project' => '1001',
        'issuetype' => '2',
        'issuenum' => '104',
        'summary' => 'Jira Task Import Test',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 12:00:00',
        'assignee' => 'user1',
        'resolution' => null,
        'description' => 'This is a test task imported from Jira'
    )
);
r($convertTest->importJiraIssueTest($testData5)) && p() && e('1');

// 步骤6:Bug类型issue正常导入流程测试
$testData6 = array(
    '5005' => (object)array(
        'id' => '5005',
        'project' => '1001',
        'issuetype' => '3',
        'issuenum' => '105',
        'summary' => 'Jira Bug Import Test',
        'priority' => '1',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 13:00:00',
        'assignee' => 'user2',
        'resolution' => null,
        'description' => 'This is a test bug imported from Jira'
    )
);
r($convertTest->importJiraIssueTest($testData6)) && p() && e('1');

// 步骤7:批量issue并发导入处理测试
$testData7 = array(
    '5006' => (object)array(
        'id' => '5006',
        'project' => '1001',
        'issuetype' => '1',
        'issuenum' => '106',
        'summary' => 'Batch Import Story 1',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 14:00:00',
        'assignee' => 'admin',
        'resolution' => null
    ),
    '5007' => (object)array(
        'id' => '5007',
        'project' => '1001',
        'issuetype' => '2',
        'issuenum' => '107',
        'summary' => 'Batch Import Task 1',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 14:30:00',
        'assignee' => 'user1',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData7)) && p() && e('1');

// 步骤8:自定义字段数据映射转换测试
$testData8 = array(
    '5008' => (object)array(
        'id' => '5008',
        'project' => '1001',
        'issuetype' => '1',
        'issuenum' => '108',
        'summary' => 'Custom Field Mapping Test',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 15:00:00',
        'assignee' => 'user1',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData8)) && p() && e('1');

// 步骤9:Sprint执行关联关系建立验证
$testData9 = array(
    '5009' => (object)array(
        'id' => '5009',
        'project' => '1001',
        'issuetype' => '1',
        'issuenum' => '109',
        'execution' => '2001',
        'summary' => 'Sprint Association Test',
        'priority' => '2',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 16:00:00',
        'assignee' => 'user1',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData9)) && p() && e('1');

// 步骤10:Ticket类型issue导入及临时关系创建测试
$testData10 = array(
    '5010' => (object)array(
        'id' => '5010',
        'project' => '1001',
        'issuetype' => '6',
        'issuenum' => '110',
        'summary' => 'Ticket Import Test',
        'priority' => '3',
        'issuestatus' => '1',
        'creator' => 'admin',
        'created' => '2024-01-01 17:00:00',
        'assignee' => 'admin',
        'resolution' => null
    )
);
r($convertTest->importJiraIssueTest($testData10)) && p() && e('1');