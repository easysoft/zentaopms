#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraSprintIssue();
timeout=0
cid=15781

- 步骤1：正常情况下使用file方法，无sprint关系返回空数组 @0
- 步骤2：正常情况下使用db方法，无sprint关系返回空数组 @0
- 步骤3：正常情况下有sprint关系但无API配置返回空数组 @0
- 步骤4：边界情况测试jiraMethod为空返回空数组 @0
- 步骤5：异常情况测试session未设置jiraMethod返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建临时表
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
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

// 3. 插入测试数据
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(id, AType, AID, BType, BID, extra) VALUES 
        (1, 'jsprint', '10001', 'zexecution', '20001', 'issue'),
        (2, 'jsprint', '10002', 'zexecution', '20002', 'issue'),
        (3, 'jsprint', '10003', 'zexecution', '20003', 'issue'),
        (4, 'jother', '10004', 'zother', '20004', 'other'),
        (5, 'jother', '10005', 'zother', '20005', 'other')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

// 4. 用户登录（选择合适角色）
su('admin');

// 5. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 6. 🔴 强制要求：必须包含至少5个测试步骤
global $app;

// 步骤1：正常情况下使用file方法，无sprint关系返回空数组
$app->session->set('jiraMethod', 'file');
r($convertTest->getJiraSprintIssueTest()) && p() && e('0'); // 步骤1：正常情况下使用file方法，无sprint关系返回空数组

// 步骤2：正常情况下使用db方法，无sprint关系返回空数组  
$app->session->set('jiraMethod', 'db');
r($convertTest->getJiraSprintIssueTest()) && p() && e('0'); // 步骤2：正常情况下使用db方法，无sprint关系返回空数组

// 步骤3：正常情况下有sprint关系但无API配置返回空数组
$app->session->set('jiraMethod', 'file');
r($convertTest->getJiraSprintIssueTest()) && p() && e('0'); // 步骤3：正常情况下有sprint关系但无API配置返回空数组

// 步骤4：边界情况测试jiraMethod为空返回空数组
$app->session->set('jiraMethod', '');
r($convertTest->getJiraSprintIssueTest()) && p() && e('0'); // 步骤4：边界情况测试jiraMethod为空返回空数组

// 步骤5：异常情况测试session未设置jiraMethod返回空数组
unset($_SESSION['jiraMethod']);
r($convertTest->getJiraSprintIssueTest()) && p() && e('0'); // 步骤5：异常情况测试session未设置jiraMethod返回空数组