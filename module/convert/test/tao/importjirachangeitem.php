#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraChangeItem();
timeout=0
cid=0

- 测试步骤1：导入空数组数据 @true

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 创建测试所需的数据库表
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

// 3. 准备测试数据
$jiraRelationTable = zenData('jiratmprelation');
$jiraRelationTable->AType->range('jissue{3},jchangeitem{1}');
$jiraRelationTable->AID->range('1,2,3,1');
$jiraRelationTable->BType->range('zstory,ztask,zbug,zaction');
$jiraRelationTable->BID->range('1,2,3,101');
$jiraRelationTable->extra->range('issue,issue,issue,action');
$jiraRelationTable->gen(4);

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
$convertTest = new convertTest();

// 7. 执行测试步骤 - 每个测试用例必须包含至少5个测试步骤
r($convertTest->importJiraChangeItemTest(array())) && p() && e('true'); // 测试步骤1：导入空数组数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 10,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'In Progress'
    )
))) && p() && e('true'); // 测试步骤2：导入正常的change item数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 1,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 测试步骤3：导入已存在关联关系的数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 11,
        'groupid' => 999,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 测试步骤4：导入不存在change group的数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 12,
        'groupid' => 1,
        'field' => 'priority',
        'oldstring' => 'High',
        'newstring' => 'Critical'
    ),
    (object)array(
        'id' => 13,
        'groupid' => 2,
        'field' => 'resolution',
        'oldstring' => '',
        'newstring' => 'Fixed'
    )
))) && p() && e('true'); // 测试步骤5：导入批量混合状态数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 16,
        'groupid' => 1,
        'field' => 'description',
        'oldstring' => 'Bug with <script>alert("test")</script>',
        'newstring' => 'Fixed: Bug with "quotes" and \'apostrophes\' & special chars'
    )
))) && p() && e('true'); // 测试步骤6：导入包含特殊字符的数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 18,
        'groupid' => 1,
        'field' => 'components',
        'oldstring' => null,
        'newstring' => 'UI,Backend'
    ),
    (object)array(
        'id' => 19,
        'groupid' => 2,
        'field' => 'fixVersion',
        'oldstring' => '1.0.0',
        'newstring' => null
    )
))) && p() && e('true'); // 测试步骤7：导入边界值数据