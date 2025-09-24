#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraChangeItem();
timeout=0
cid=0

- 步骤1：导入空数组数据 @true

*/

// 1. 导入依赖
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
$tester->dao->exec($sql);

// 2. 定义常量（如果未定义）
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');
if(!defined('TABLE_ACTION')) define('TABLE_ACTION', '`zt_action`');

// 3. 简化数据准备（使用mock方式，避免数据库依赖）

// 4. 用户登录
su('admin');

// 5. 创建测试实例
$convertTest = new convertTest();

// 6. 测试步骤 - 必须包含至少5个测试步骤
r($convertTest->importJiraChangeItemTest(array())) && p() && e('true'); // 步骤1：导入空数组数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 20,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'In Progress'
    )
))) && p() && e('true'); // 步骤2：导入正常的单个changeitem数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 1,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 步骤3：导入已存在关联关系的数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 21,
        'groupid' => 999,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 步骤4：导入不存在changeGroup的数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 24,
        'groupid' => 1,
        'field' => 'priority',
        'oldstring' => 'High',
        'newstring' => 'Critical'
    ),
    (object)array(
        'id' => 25,
        'groupid' => 2,
        'field' => 'resolution',
        'oldstring' => '',
        'newstring' => 'Fixed'
    ),
    (object)array(
        'id' => 1,
        'groupid' => 999,
        'field' => 'labels',
        'oldstring' => 'bug',
        'newstring' => 'resolved'
    )
))) && p() && e('true'); // 步骤5：导入多个混合数据验证批量处理