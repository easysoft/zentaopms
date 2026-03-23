#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraUser();
timeout=0
cid=15863

- 步骤1：正常情况 @1
- 步骤2：边界值 @1
- 步骤3：异常输入 @1
- 步骤4：权限验证 @1
- 步骤5：业务规则 @1
- 步骤6：数据完整性 @1
- 步骤7：边界条件 @1
- 步骤8：模式切换 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

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
    $tester->dbh->exec('DELETE FROM ' . TABLE_USER . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\' OR account LIKE \'minimal%\' OR account LIKE \'special%\' OR account LIKE \'verylong%\' OR account = \'emailmode\'');
    $tester->dbh->exec('DELETE FROM ' . TABLE_USERGROUP . ' WHERE account LIKE \'test%\' OR account LIKE \'jira%\' OR account LIKE \'new%\' OR account = \'emailuser\' OR account LIKE \'minimal%\' OR account LIKE \'special%\' OR account LIKE \'verylong%\' OR account = \'emailmode\'');
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,existing1,existing2,testuser');
$user->password->range('123456{4}');
$user->realname->range('管理员,已存在用户1,已存在用户2,测试用户');
$user->email->range('admin@test.com,existing1@test.com,existing2@test.com,testuser@test.com');
$user->gender->range('m{4}');
$user->type->range('inside{4}');
$user->deleted->range('0{4}');
$user->gen(4);

// 手动更新join字段为正确的日期格式
try {
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-01' WHERE account = 'admin'");
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-02' WHERE account = 'existing1'");
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-03' WHERE account = 'existing2'");
    $tester->dbh->exec("UPDATE " . TABLE_USER . " SET join = '2020-01-04' WHERE account = 'testuser'");
} catch (Exception $e) {
    // 忽略更新错误
}
$_SESSION['jiraUser'] = array('password' => '123456', 'mode' => 'email');

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,existing1,testuser');
$usergroup->group->range('1{3}');
$usergroup->project->range('{3}');
$usergroup->gen(3);

// 定义常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 预置已存在的关系数据以测试去重功能
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'existing1', 'zuser', 'existing1', '')");
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('juser', 'duplicateuser', 'zuser', 'duplicateuser', '')");

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少8个测试步骤

$dataList = [(object)['account' => 'newuser1', 'email'   => 'newuser1@test.com', 'realname' => '新用户1', 'join' => '2023-01-01 00:00:00']];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤1：正常情况

$dataList = [
    (object)['account' => 'duplicateuser', 'email'   => 'duplicate@test.com', 'realname' => '重复用户'],
    (object)['account' => 'newuser2', 'email'   => 'newuser2@test.com', 'realname' => '新用户2']
];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤2：边界值

$dataList = [
    (object)['account' => 'atlassian1', 'email' => 'user@connect.atlassian.com', 'realname' => 'Atlassian用户1'],
    (object)['account' => 'newuser3', 'email' => 'newuser3@test.com', 'realname' => '新用户3']
];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤3：异常输入

$dataList = [
    (object)['account' => 'admin', 'email' => 'admin@newdomain.com', 'realname' => '管理员账号'],
    (object)['account' => 'existing2', 'email' => 'existing@test.com', 'realname' => '重复本地用户']
];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤4：权限验证

r($convertTest->importJiraUserTest([])) && p() && e('1'); // 步骤5：业务规则

$dataList = [
    (object)['account' => 'minimaluser1'],
    (object)['account' => 'minimaluser2', 'email' => 'minimal2@test.com'],
    (object)['account' => 'minimaluser3', 'realname' => '最小用户3']
];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤6：数据完整性

 $dataList = [
    (object)['account' => 'specialuser_@#$', 'email' => 'special.user+test@long-domain-name.com', 'realname' => '特殊字符用户!@#$%^&*()_+-=[]{}|;:,.<>?', 'join' => '2023-12-31 23:59:59'],
    (object)['account' => 'verylongaccountnamewithabcdefghijklmnopqrstuvwxyz1234567890', 'email' => 'verylonguser@verylongdomainnamewithmanysegments.example.com', 'realname' => '这是一个非常长的真实姓名用来测试系统对长字符串的处理能力和边界情况验证']
];
r($convertTest->importJiraUserTest($dataList)) && p() && e('1'); // 步骤7：边界条件

$dataList = [
    (object)['account' => 'emailuser', 'email' => 'emailmode@test.com', 'realname' => 'Email模式用户']
];
r($convertTest->importJiraUserTest($dataList, 'email')) && p() && e('1'); // 步骤8：模式切换
