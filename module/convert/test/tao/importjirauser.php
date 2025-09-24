#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraUser();
timeout=0
cid=0

- 执行convertTest模块的importJiraUserTest方法，参数是array  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 1.1 创建临时表并清理数据
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
    // 清空相关表数据确保测试环境干净
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
    $tester->dbh->exec('DELETE FROM ' . TABLE_USER . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\'');
    $tester->dbh->exec('DELETE FROM ' . TABLE_USERGROUP . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\'');
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,existing1,existing2');
$user->password->range('123456{3}');
$user->realname->range('管理员,已存在用户1,已存在用户2');
$user->email->range('admin@test.com,existing1@test.com,existing2@test.com');
$user->gender->range('m{3}');
$user->type->range('inside{3}');
$user->deleted->range('0{3}');
$user->gen(3);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,existing1');
$usergroup->group->range('1{2}');
$usergroup->project->range('{2}');
$usergroup->gen(2);

// 定义常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 预置已存在的关系数据以测试去重功能
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'existing1', 'zuser', 'existing1', '')");
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'duplicateuser', 'zuser', 'duplicateuser', '')");

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常导入新用户数据，验证用户创建成功和关系记录生成
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'testuser1', 'email' => 'testuser1@example.com', 'realname' => '测试用户1', 'join' => '2023-01-01 00:00:00')
))) && p() && e('1');

// 步骤2：导入已存在用户数据，验证跳过已存在用户逻辑
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'existing1', 'email' => 'existing1@test.com', 'realname' => '已存在用户1'),
    (object)array('account' => 'testuser2', 'email' => 'testuser2@example.com', 'realname' => '测试用户2')
))) && p() && e('1');

// 步骤3：导入Atlassian内部账号，验证过滤内部账号功能
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'atlassian1', 'email' => 'user@connect.atlassian.com', 'realname' => 'Atlassian用户1'),
    (object)array('account' => 'testuser3', 'email' => 'testuser3@example.com', 'realname' => '测试用户3')
))) && p() && e('1');

// 步骤4：导入空数据列表，验证空数据处理正确
r($convertTest->importJiraUserTest(array())) && p() && e('1');

// 步骤5：导入无邮箱用户数据，验证邮箱字段处理
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'testuser4', 'email' => '', 'realname' => '无邮箱用户'),
    (object)array('account' => 'testuser5', 'realname' => '缺失邮箱字段用户')
))) && p() && e('1');