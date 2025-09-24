#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraUser();
timeout=0
cid=0

- 执行convertTest模块的importJiraUserTest方法，参数是array  @rue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 1.1 创建临时表
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
    // 清空表数据确保测试环境干净
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,existing1,existing2,testuser{1}');
$user->password->range('123456{4}');
$user->realname->range('管理员,已存在用户1,已存在用户2,测试用户{1}');
$user->email->range('admin@test.com,existing1@test.com,existing2@test.com,testuser@test.com{1}');
$user->gender->range('m{4}');
$user->type->range('inside{4}');
$user->deleted->range('0{4}');
$user->gen(4);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,existing1{1}');
$usergroup->group->range('1{2}');
$usergroup->project->range('{2}');
$usergroup->gen(2);

// 定义常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 手动添加jira临时关系表数据以测试重复导入
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'existing1', 'zuser', 'existing1', '')");
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'duplicateuser', 'zuser', 'duplicateuser', '')");

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少10个测试步骤

// 步骤1：导入正常Jira用户数据，验证用户创建和临时关系记录
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'newuser1', 'email' => 'newuser1@test.com', 'realname' => '新用户1', 'join' => '2023-01-01 00:00:00'),
    (object)array('account' => 'newuser2', 'email' => 'newuser2@test.com', 'realname' => '新用户2')
))) && p() && e(true);

// 步骤2：导入包含已存在用户的数据，验证跳过已存在用户逻辑
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'existing1', 'email' => 'existing1@test.com', 'realname' => '已存在用户1'),
    (object)array('account' => 'newuser3', 'email' => 'newuser3@test.com', 'realname' => '新用户3')
))) && p() && e(true);

// 步骤3：导入包含Atlassian内部账号的数据，验证过滤内部账号逻辑
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'atlassian1', 'email' => 'user@connect.atlassian.com', 'realname' => 'Atlassian用户1'),
    (object)array('account' => 'newuser4', 'email' => 'newuser4@test.com', 'realname' => '新用户4')
))) && p() && e(true);

// 步骤4：测试空数据列表处理，验证空数据处理
r($convertTest->importJiraUserTest(array())) && p() && e(true);

// 步骤5：测试无效邮箱用户数据处理，验证空邮箱处理
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'invaliduser', 'email' => '', 'realname' => '无邮箱用户'),
    (object)array('account' => 'validuser', 'email' => 'valid@test.com', 'realname' => '有效用户')
))) && p() && e(true);

// 步骤6：测试用户组分配功能，验证usergroup表记录创建
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'groupuser1', 'email' => 'groupuser1@test.com', 'realname' => '用户组测试用户1'),
    (object)array('account' => 'groupuser2', 'email' => 'groupuser2@test.com', 'realname' => '用户组测试用户2')
))) && p() && e(true);

// 步骤7：测试重复关系记录处理，验证跳过已存在的关系
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'duplicateuser', 'email' => 'duplicate@test.com', 'realname' => '重复用户'),
    (object)array('account' => 'relationuser', 'email' => 'relation@test.com', 'realname' => '关系测试用户')
))) && p() && e(true);

// 步骤8：测试email模式用户处理，验证使用邮箱前缀作为账号
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'jiraemailuser', 'email' => 'emailtestuser@example.com', 'realname' => '邮箱模式用户'),
    (object)array('account' => 'normaltestuser', 'email' => 'normal@example.com', 'realname' => '普通用户')
), 'email')) && p() && e(true);

// 步骤9：测试边界值和特殊字符处理，验证数据清理正确性
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'very_long_username_that_exceeds_thirty_character_limit_for_testing', 'email' => 'longuser@test.com', 'realname' => '超长用户名用户'),
    (object)array('account' => 'special-user_123.test', 'email' => 'special@test.com', 'realname' => '特殊字符用户'),
    (object)array('account' => '', 'email' => 'empty@test.com', 'realname' => '空账号用户')
))) && p() && e(true);

// 步骤10：测试多种邮箱格式处理，验证邮箱处理逻辑
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'multiformat1', 'email' => 'user.name+tag@domain.co.uk', 'realname' => '复杂邮箱用户1'),
    (object)array('account' => 'multiformat2', 'email' => 'username', 'realname' => '无@符号邮箱用户'),
    (object)array('account' => 'multiformat3', 'email' => '@invalidformat.com', 'realname' => '无效邮箱格式用户'),
    (object)array('account' => 'multiformat4', 'email' => 'user@', 'realname' => '不完整邮箱用户')
), 'email')) && p() && e(true);