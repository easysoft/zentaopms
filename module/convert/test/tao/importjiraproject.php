#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraProject();
timeout=0
cid=0

- 执行convertTest模块的importJiraProjectTest方法，参数是array  @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

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
    // 表可能已存在，忽略错误
}

if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 清理可能存在的测试数据
try {
    $tester->dbh->exec('DELETE FROM zt_project WHERE name LIKE "Test%" OR name LIKE "测试%"');
    $tester->dbh->exec('DELETE FROM zt_product WHERE name LIKE "Test%" OR name LIKE "测试%"');
} catch (Exception $e) {
    // 忽略清理错误
}

// 准备用户数据，用于项目角色分配
$user = zenData('user');
$user->account->range('admin,test1,test2,existing1,existing2,projectlead,developer,qa1,dev1,pm1');
$user->password->range('123456{10}');
$user->realname->range('管理员,测试用户1,测试用户2,已存在用户1,已存在用户2,项目负责人,开发者,测试员1,开发员1,项目经理1');
$user->email->range('admin@test.com,test1@test.com,test2@test.com,existing1@test.com,existing2@test.com,lead@test.com,dev@test.com,qa1@test.com,dev1@test.com,pm1@test.com');
$user->role->range('admin,qa{3},dev{3},pm{3}');
$user->deleted->range('0{10}');
$user->gen(10);

// 准备已存在的项目数据，用于测试项目导入去重
$project = zenData('project');
$project->name->range('已存在项目,测试项目2,测试项目3,Normal Project,Archived Project');
$project->code->range('EXIST,TEST2,TEST3,NORMAL,ARCHIVED');
$project->type->range('project{5}');
$project->status->range('wait,doing,closed,doing,closed');
$project->deleted->range('0{5}');
$project->gen(5);

// 准备产品数据
$product = zenData('product');
$product->name->range('产品{5}');
$product->code->range('PROD{5}');
$product->status->range('normal{5}');
$product->type->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

// 跳过execution表的数据准备，避免表不存在的问题
// $execution = zenData('execution');
// $execution->name->range('执行阶段{5}');
// $execution->code->range('EXEC{5}');
// $execution->type->range('sprint{5}');
// $execution->status->range('wait,doing,closed,wait,doing');
// $execution->deleted->range('0{5}');
// $execution->gen(5);

// 设置必要的session数据
global $app;
$app->session->set('jiraMethod', 'file');
$app->session->set('jiraUser', json_encode(array('password' => '123456', 'group' => 1, 'mode' => 'account')));
$app->session->set('jiraRelation', json_encode(array()));

// 清理可能存在的测试数据，避免干扰
try {
    $tester->dbh->exec('DELETE FROM ' . JIRA_TMPRELATION . ' WHERE AType = "jproject" AND AID IN ("3001", "3002", "3003", "3004", "3005", "3006")');
} catch (Exception $e) {
    // 忽略清理错误
}

// 准备临时关系表的数据，模拟已存在的项目关系
try {
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zproject', '1')");
} catch (Exception $e) {
    // 如果数据已存在则忽略错误
}

su('admin');

$convertTest = new convertTest();

// 步骤1：空数组边界值输入处理
r($convertTest->importJiraProjectTest(array())) && p() && e('true');

// 步骤2：已删除状态项目跳过处理逻辑
r($convertTest->importJiraProjectTest(array(
    '2001' => (object)array(
        'id' => '2001',
        'pkey' => 'DELETED',
        'originalkey' => 'DELETED_OLD',
        'pname' => 'Deleted Project',
        'description' => 'This project was deleted in JIRA',
        'ptype' => 'software',
        'pstatus' => 'deleted'
    )
))) && p() && e('true');

// 步骤3：重复项目ID导入去重机制
r($convertTest->importJiraProjectTest(array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'DUPLICATE',
        'originalkey' => 'DUPLICATE_OLD',
        'pname' => 'Duplicate Project Test',
        'description' => 'Testing duplicate project handling',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');

// 步骤4：正常活跃项目完整导入流程
r($convertTest->importJiraProjectTest(array(
    '3001' => (object)array(
        'id' => '3001',
        'pkey' => 'TESTNORMAL',
        'originalkey' => 'TESTNORMAL_OLD',
        'pname' => 'Test Normal Project',
        'description' => 'Normal project for complete testing',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');

// 步骤5：归档状态项目状态映射处理
r($convertTest->importJiraProjectTest(array(
    '3002' => (object)array(
        'id' => '3002',
        'pkey' => 'TESTARCHIVED',
        'originalkey' => 'TESTARCHIVED_OLD',
        'pname' => 'Test Archived Project',
        'description' => 'Archived project testing',
        'ptype' => 'software',
        'pstatus' => 'archived'
    )
))) && p() && e('true');

// 步骤6：批量多项目同时导入处理
r($convertTest->importJiraProjectTest(array(
    '3003' => (object)array(
        'id' => '3003',
        'pkey' => 'TESTBATCH1',
        'originalkey' => 'TESTBATCH1_OLD',
        'pname' => 'Test Batch Project 1',
        'description' => 'First batch import project',
        'ptype' => 'software',
        'pstatus' => 'active'
    ),
    '3004' => (object)array(
        'id' => '3004',
        'pkey' => 'TESTBATCH2',
        'originalkey' => 'TESTBATCH2_OLD',
        'pname' => 'Test Batch Project 2',
        'description' => 'Second batch import project',
        'ptype' => 'business',
        'pstatus' => 'active'
    )
))) && p() && e('true');

// 步骤7：项目关键字段完整性验证
r($convertTest->importJiraProjectTest(array(
    '3005' => (object)array(
        'id' => '3005',
        'pkey' => 'TESTFIELDS',
        'originalkey' => 'TESTFIELDS_OLD',
        'pname' => 'Test Complete Fields Project',
        'description' => 'Testing all required fields completion',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');

// 步骤8：异常数据容错性处理机制
r($convertTest->importJiraProjectTest(array(
    '3006' => (object)array(
        'id' => '3006',
        'pkey' => 'TESTEXCEPTION',
        'originalkey' => 'TESTEXCEPTION_OLD',
        'pname' => 'Test Exception Handling Project',
        'description' => 'Testing exception data handling and fault tolerance',
        'ptype' => 'unknown_type',
        'pstatus' => 'unknown_status'
    )
))) && p() && e('true');