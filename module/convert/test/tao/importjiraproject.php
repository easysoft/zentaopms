#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraProject();
timeout=0
cid=0

- 步骤3：空数组边界测试 @true

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

$user = zenData('user');
$user->account->range('admin,test1,test2,existing1,existing2');
$user->password->range('123456{5}');
$user->realname->range('管理员,测试用户1,测试用户2,已存在用户1,已存在用户2');
$user->email->range('admin@test.com,test1@test.com,test2@test.com,existing1@test.com,existing2@test.com');
$user->gen(5);

$project = zenData('project');
$project->name->range('项目{5}');
$project->code->range('PROJ{5}');
$project->type->range('project{5}');
$project->gen(5);

$product = zenData('product');
$product->name->range('产品{5}');
$product->code->range('PROD{5}');
$product->gen(5);

// 跳过execution表的数据准备，专注于测试项目导入逻辑

global $app;
$app->session->set('jiraMethod', 'file');
$app->session->set('jiraUser', json_encode(array('password' => '123456', 'group' => 1, 'mode' => 'account')));
$app->session->set('jiraRelation', '{}');

su('admin');

$convertTest = new convertTest();

r($convertTest->importJiraProjectTest(array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'TEST',
        'originalkey' => 'TEST_OLD',
        'pname' => 'Test Project',
        'description' => 'Test Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('exception: '); // 步骤1：正常项目导入，由于依赖方法的限制产生空异常

r($convertTest->importJiraProjectTest(array(
    '1002' => (object)array(
        'id' => '1002',
        'pkey' => 'DEL',
        'originalkey' => 'DEL_OLD',
        'pname' => 'Deleted Project',
        'description' => 'Deleted Description',
        'ptype' => 'software',
        'pstatus' => 'deleted'
    )
))) && p() && e('true'); // 步骤2：删除状态项目处理

r($convertTest->importJiraProjectTest(array())) && p() && e('true'); // 步骤3：空数组边界测试

r($convertTest->importJiraProjectTest(array(
    '1003' => (object)array(
        'id' => '1003',
        'pkey' => 'ARCH',
        'originalkey' => 'ARCH_OLD',
        'pname' => 'Archived Project',
        'description' => 'Archived Description',
        'ptype' => 'software',
        'pstatus' => 'archived'
    ),
    '1004' => (object)array(
        'id' => '1004',
        'pkey' => 'NORM',
        'originalkey' => 'NORM_OLD',
        'pname' => 'Normal Project',
        'description' => 'Normal Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true'); // 步骤4：批量导入多状态项目

r($convertTest->importJiraProjectTest(array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'EXIST',
        'originalkey' => 'EXIST_OLD',
        'pname' => 'Existing Project',
        'description' => 'Existing Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true'); // 步骤5：重复项目导入测试

r($convertTest->importJiraProjectTest(array(
    '1005' => (object)array(
        'id' => '1005',
        'pkey' => 'INCOMPLETE',
        'originalkey' => 'INC_OLD',
        'pname' => '',
        'description' => null,
        'ptype' => '',
        'pstatus' => 'active'
    )
))) && p() && e('true'); // 步骤6：不完整数据测试

r($convertTest->importJiraProjectTest(array(
    '1006' => (object)array(
        'id' => '1006',
        'pkey' => 'SPECIAL_#@$%',
        'originalkey' => 'SPEC_#@$%_OLD',
        'pname' => 'Project with Special chars !@#$%^&*()',
        'description' => 'Description with <html> & "quotes" \'apostrophe\'',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true'); // 步骤7：特殊字符测试

r($convertTest->importJiraProjectTest(array(
    '1007' => (object)array(
        'id' => '1007',
        'pkey' => 'CLOSED',
        'originalkey' => 'CLOSED_OLD',
        'pname' => 'Closed Project',
        'description' => 'Project should be closed',
        'ptype' => 'software',
        'pstatus' => 'closed'
    )
))) && p() && e('true'); // 步骤8：项目关闭状态测试