#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraChangeItem();
timeout=0
cid=15857

- 步骤1：导入空数组数据 @true
- 步骤2：导入有效的ChangeItem数据 @true
- 步骤3：导入已存在关联关系的数据 @true
- 步骤4：导入无效ChangeGroup数据 @true
- 步骤5：导入不存在Issue的数据 @true
- 步骤6：导入包含HTML和特殊字符的数据 @true
- 步骤7：导入边界值数据（null值处理） @true
- 步骤8：导入批量混合状态数据（包含邮箱格式） @true
- 步骤9：导入多语言数据测试 @true

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

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
r($convertTest->importJiraChangeItemTest(array())) && p() && e('true'); // 步骤1：导入空数组数据

$testData1 = array(
    (object)array(
        'id' => 100,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'In Progress'
    )
);
r($convertTest->importJiraChangeItemTest($testData1)) && p() && e('true'); // 步骤2：导入有效的ChangeItem数据

$testData2 = array(
    (object)array(
        'id' => 1,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
);
r($convertTest->importJiraChangeItemTest($testData2)) && p() && e('true'); // 步骤3：导入已存在关联关系的数据

$testData3 = array(
    (object)array(
        'id' => 101,
        'groupid' => 999,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
);
r($convertTest->importJiraChangeItemTest($testData3)) && p() && e('true'); // 步骤4：导入无效ChangeGroup数据

$testData4 = array(
    (object)array(
        'id' => 102,
        'groupid' => 3,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
);
r($convertTest->importJiraChangeItemTest($testData4)) && p() && e('true'); // 步骤5：导入不存在Issue的数据

$testData5 = array(
    (object)array(
        'id' => 103,
        'groupid' => 1,
        'field' => 'description',
        'oldstring' => 'Bug with <script>alert("XSS")</script>',
        'newstring' => 'Fixed: Bug with "quotes" and \'apostrophes\' & special chars < >'
    )
);
r($convertTest->importJiraChangeItemTest($testData5)) && p() && e('true'); // 步骤6：导入包含HTML和特殊字符的数据

$testData6 = array(
    (object)array(
        'id' => 104,
        'groupid' => 1,
        'field' => 'components',
        'oldstring' => null,
        'newstring' => 'UI,Backend,Database'
    ),
    (object)array(
        'id' => 105,
        'groupid' => 2,
        'field' => 'fixVersion',
        'oldstring' => '1.0.0',
        'newstring' => null
    )
);
r($convertTest->importJiraChangeItemTest($testData6)) && p() && e('true'); // 步骤7：导入边界值数据（null值处理）

$testData7 = array(
    (object)array(
        'id' => 106,
        'groupid' => 1,
        'field' => 'priority',
        'oldstring' => 'High',
        'newstring' => 'Critical'
    ),
    (object)array(
        'id' => 107,
        'groupid' => 2,
        'field' => 'resolution',
        'oldstring' => '',
        'newstring' => 'Fixed'
    ),
    (object)array(
        'id' => 108,
        'groupid' => 1,
        'field' => 'assignee',
        'oldstring' => 'user1@company.com',
        'newstring' => 'user2@company.com'
    )
);
r($convertTest->importJiraChangeItemTest($testData7)) && p() && e('true'); // 步骤8：导入批量混合状态数据（包含邮箱格式）

$testData8 = array(
    (object)array(
        'id' => 109,
        'groupid' => 1,
        'field' => 'summary',
        'oldstring' => '这是一个中文标题',
        'newstring' => 'This is an English title'
    )
);
r($convertTest->importJiraChangeItemTest($testData8)) && p() && e('true'); // 步骤9：导入多语言数据测试