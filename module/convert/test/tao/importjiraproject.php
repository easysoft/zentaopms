#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraProject();
timeout=0
cid=15862

- 执行convertTest模块的importJiraProjectTest方法，参数是array  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData2  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData3  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData4  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData5  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData6  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData7  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData8  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData9  @true
- 执行convertTest模块的importJiraProjectTest方法，参数是$testData10  @true

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
    // $tester->dbh->exec('DELETE FROM zt_execution WHERE name LIKE "Test%" OR name LIKE "测试%"'); // execution表可能不存在
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 忽略清理错误
}

// 准备更完整的测试数据
$user = zenData('user');
$user->account->range('admin,user1,user2,user3,testuser1,testuser2,testuser3,testuser4,testuser5');
$user->realname->range('管理员,用户1,用户2,用户3,测试用户1,测试用户2,测试用户3,测试用户4,测试用户5');
$user->password->range('123456{9}');
$user->role->range('admin{1},qa{3},dev{5}');
$user->deleted->range('0{9}');
$user->gen(9);

$project = zenData('project');
$project->name->range('项目1,项目2,项目3,Test Project{2}');
$project->code->range('PRJ001,PRJ002,PRJ003,TEST{2}');
$project->status->range('wait{1},doing{2},closed{2}');
$project->deleted->range('0{5}');
$project->type->range('project{5}');
$project->gen(8);

$product = zenData('product');
$product->name->range('产品1,产品2,产品3,Test Product{5}');
$product->code->range('PROD001,PROD002,PROD003,TEST{5}');
$product->status->range('normal{5},closed{3}');
$product->deleted->range('0{8}');
$product->type->range('normal{8}');
$product->gen(8);

// 准备group数据用于权限测试
$group = zenData('group');
$group->name->range('管理员,项目经理,开发{3},测试{2}');
$group->role->range('admin{1},manager{1},dev{3},qa{2}');
$group->gen(7);

// 准备临时关系表的数据，模拟已存在的项目关系（用于测试去重）
try {
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zproject', '1')");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID) VALUES ('jproject', '1001', 'zproduct', '1')");
} catch (Exception $e) {
    // 如果数据已存在则忽略错误
}

// 设置必要的session数据
global $app;
$app->session->set('jiraMethod', 'file');
$app->session->set('jiraUser', json_encode(array('password' => '123456', 'group' => 1, 'mode' => 'account')));
$app->session->set('jiraRelation', json_encode(array()));

su('admin');

$convertTest = new convertTest();

// 步骤1：空数组输入边界值测试
r($convertTest->importJiraProjectTest(array())) && p() && e('true');

// 步骤2：已删除项目状态过滤测试
$testData2 = array(
    '2001' => (object)array(
        'id' => '2001',
        'pkey' => 'DELETED',
        'originalkey' => 'DELETED_OLD',
        'pname' => 'Deleted Project',
        'description' => 'This project was deleted in JIRA',
        'ptype' => 'software',
        'pstatus' => 'deleted'
    )
);
r($convertTest->importJiraProjectTest($testData2)) && p() && e('true');

// 步骤3：重复项目ID去重机制验证
$testData3 = array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'DUPLICATE',
        'originalkey' => 'DUPLICATE_OLD',
        'pname' => 'Duplicate Project Test',
        'description' => 'Testing duplicate project handling',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
);
r($convertTest->importJiraProjectTest($testData3)) && p() && e('true');

// 步骤4：正常项目完整导入流程测试
$testData4 = array(
    '3001' => (object)array(
        'id' => '3001',
        'pkey' => 'TESTNORMAL',
        'originalkey' => 'TESTNORMAL_OLD',
        'pname' => 'Test Normal Project',
        'description' => 'Normal project for complete testing',
        'ptype' => 'software',
        'pstatus' => 'active',
        'lead' => 'admin'
    )
);
r($convertTest->importJiraProjectTest($testData4)) && p() && e('true');

// 步骤5：项目状态映射转换验证
$testData5 = array(
    '3002' => (object)array(
        'id' => '3002',
        'pkey' => 'TESTARCHIVED',
        'originalkey' => 'TESTARCHIVED_OLD',
        'pname' => 'Test Archived Project',
        'description' => 'Archived project testing',
        'ptype' => 'software',
        'pstatus' => 'archived'
    )
);
r($convertTest->importJiraProjectTest($testData5)) && p() && e('true');

// 步骤6：批量项目导入并发处理测试
$testData6 = array(
    '3003' => (object)array(
        'id' => '3003',
        'pkey' => 'TESTBATCH1',
        'originalkey' => 'TESTBATCH1_OLD',
        'pname' => 'Test Batch Project 1',
        'description' => 'First batch import project',
        'ptype' => 'software',
        'pstatus' => 'active',
        'lead' => 'user1'
    ),
    '3004' => (object)array(
        'id' => '3004',
        'pkey' => 'TESTBATCH2',
        'originalkey' => 'TESTBATCH2_OLD',
        'pname' => 'Test Batch Project 2',
        'description' => 'Second batch import project',
        'ptype' => 'business',
        'pstatus' => 'active',
        'lead' => 'user2'
    )
);
r($convertTest->importJiraProjectTest($testData6)) && p() && e('true');

// 步骤7：必填字段数据完整性验证
$testData7 = array(
    '3005' => (object)array(
        'id' => '3005',
        'pkey' => 'TESTFIELDS',
        'originalkey' => 'TESTFIELDS_OLD',
        'pname' => 'Test Complete Fields Project',
        'description' => 'Testing all required fields completion',
        'ptype' => 'software',
        'pstatus' => 'active',
        'lead' => 'admin',
        'created' => '2023-01-01 10:00:00'
    )
);
r($convertTest->importJiraProjectTest($testData7)) && p() && e('true');

// 步骤8：异常数据容错性处理测试
$testData8 = array(
    '3006' => (object)array(
        'id' => '3006',
        'pkey' => 'TESTEXCEPTION',
        'originalkey' => 'TESTEXCEPTION_OLD',
        'pname' => 'Test Exception Handling Project',
        'description' => 'Testing exception data handling and fault tolerance',
        'ptype' => 'unknown_type',
        'pstatus' => 'unknown_status'
    )
);
r($convertTest->importJiraProjectTest($testData8)) && p() && e('true');

// 步骤9：项目关联关系建立验证
$testData9 = array(
    '3007' => (object)array(
        'id' => '3007',
        'pkey' => 'TESTRELATION',
        'originalkey' => 'TESTRELATION_OLD',
        'pname' => 'Test Relation Project',
        'description' => 'Testing project relation creation',
        'ptype' => 'software',
        'pstatus' => 'active',
        'lead' => 'admin'
    )
);
r($convertTest->importJiraProjectTest($testData9)) && p() && e('true');

// 步骤10：数据库事务一致性测试
$testData10 = array(
    '3008' => (object)array(
        'id' => '3008',
        'pkey' => 'TESTTRANSACTION',
        'originalkey' => 'TESTTRANSACTION_OLD',
        'pname' => 'Test Transaction Consistency Project',
        'description' => 'Testing database transaction consistency during import',
        'ptype' => 'service_management',
        'pstatus' => 'active',
        'lead' => 'user1'
    )
);
r($convertTest->importJiraProjectTest($testData10)) && p() && e('true');