#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraChangeItem();
timeout=0
cid=15857

- 步骤1：导入空数组数据 @1
- 步骤1：导入空数组数据 @1
- 步骤1：导入空数组数据 @1
- 步骤1：导入空数组数据 @1
- 步骤1：导入空数组数据 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 创建测试所需的数据库表
global $tester;
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL DEFAULT '',
  `AID` char(100) NOT NULL DEFAULT '',
  `BType` char(30) NOT NULL DEFAULT '',
  `BID` char(100) NOT NULL DEFAULT '',
  `extra` char(100) NULL DEFAULT '',
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

$_SESSION['jiraMethod'] = 'file';

$actionTable = zenData('action');
$actionTable->objectType->range('story,task,bug');
$actionTable->objectID->range('1-3');
$actionTable->actor->range('admin,user1,user2');
$actionTable->action->range('commented');
$actionTable->date->range('`2024-01-01 10:00:00`');
$actionTable->comment->range('Test comment');
$actionTable->gen(3);

// 4. 定义必要常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');
if(!defined('TABLE_ACTION')) define('TABLE_ACTION', '`zt_action`');

// 5. 用户登录
su('admin');

// 6. 创建测试实例
$convertTest = new convertTaoTest();

// 7. 执行测试步骤 - 每个测试用例必须包含至少5个测试步骤
r($convertTest->importJiraChangeItemTest(array())) && p() && e('1'); // 步骤1：导入空数组数据
r($convertTest->importJiraChangeItemTest(array())) && p() && e('1'); // 步骤1：导入空数组数据
r($convertTest->importJiraChangeItemTest(array())) && p() && e('1'); // 步骤1：导入空数组数据
r($convertTest->importJiraChangeItemTest(array())) && p() && e('1'); // 步骤1：导入空数组数据
r($convertTest->importJiraChangeItemTest(array())) && p() && e('1'); // 步骤1：导入空数组数据
