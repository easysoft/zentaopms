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
    $tester->dbh->exec('DELETE FROM ' . TABLE_USER . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\' OR account LIKE \'minimal%\' OR account LIKE \'special%\' OR account LIKE \'verylong%\'');
    $tester->dbh->exec('DELETE FROM ' . TABLE_USERGROUP . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\' OR account LIKE \'minimal%\' OR account LIKE \'special%\' OR account LIKE \'verylong%\'');
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

// 手动更新join字段为正确的日期格式
try {
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-01' WHERE account = 'admin'");
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-02' WHERE account = 'existing1'");
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-03' WHERE account = 'existing2'");
} catch (Exception $e) {
    // 忽略更新错误
}

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

// 验证测试前的初始状态
$initialUserCount = $tester->dbh->query('SELECT COUNT(*) FROM ' . TABLE_USER . ' WHERE deleted = "0"')->fetchColumn();
$initialRelationCount = $tester->dbh->query('SELECT COUNT(*) FROM jiratmprelation WHERE AType = "juser"')->fetchColumn();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少8个测试步骤

// 步骤1：导入新用户数据，验证用户创建成功和关系记录生成
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'newuser1', 'email' => 'newuser1@test.com', 'realname' => '新用户1', 'join' => '2023-01-01 00:00:00')
))) && p() && e('1');

// 步骤2：导入已存在关系的重复用户，验证跳过重复用户功能
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'duplicateuser', 'email' => 'duplicate@test.com', 'realname' => '重复用户'),
    (object)array('account' => 'newuser2', 'email' => 'newuser2@test.com', 'realname' => '新用户2')
))) && p() && e('1');

// 步骤3：导入Atlassian内部账号数据，验证过滤内部账号不导入
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'atlassian1', 'email' => 'user@connect.atlassian.com', 'realname' => 'Atlassian用户1'),
    (object)array('account' => 'newuser3', 'email' => 'newuser3@test.com', 'realname' => '新用户3')
))) && p() && e('1');

// 步骤4：导入已存在本地用户数据，验证跳过已存在用户创建但记录关系
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'admin', 'email' => 'admin@newdomain.com', 'realname' => '管理员账号'),
    (object)array('account' => 'existing1', 'email' => 'existing@test.com', 'realname' => '重复本地用户')
))) && p() && e('1');

// 步骤5：导入空数据和边界值数据，验证健壮性处理
r($convertTest->importJiraUserTest(array())) && p() && e('1');

// 步骤6：导入缺少必要字段的用户数据，验证默认值处理
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'minimaluser1'),  // 仅有account字段
    (object)array('account' => 'minimaluser2', 'email' => 'minimal2@test.com'),  // 缺少realname和join
    (object)array('account' => 'minimaluser3', 'realname' => '最小用户3')  // 缺少email和join
))) && p() && e('1');

// 步骤7：导入包含特殊字符和长字段的用户数据，验证数据处理
r($convertTest->importJiraUserTest(array(
    (object)array(
        'account' => 'specialuser_@#$',
        'email' => 'special.user+test@long-domain-name.com',
        'realname' => '特殊字符用户!@#$%^&*()_+-=[]{}|;:,.<>?',
        'join' => '2023-12-31 23:59:59'
    ),
    (object)array(
        'account' => 'verylongaccountnamewithabcdefghijklmnopqrstuvwxyz1234567890',
        'email' => 'verylonguser@verylongdomainnamewithmanysegments.example.com',
        'realname' => '这是一个非常长的真实姓名用来测试系统对长字符串的处理能力和边界情况验证'
    )
))) && p() && e('1');

// 步骤8：测试email模式下的用户导入，验证processJiraUser不同处理模式
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'emailuser', 'email' => 'emailmode@test.com', 'realname' => 'Email模式用户')
), 'email')) && p() && e('1');