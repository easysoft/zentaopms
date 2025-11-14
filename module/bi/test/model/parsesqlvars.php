#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->parseSqlVars();
timeout=0
cid=15193

- 测试正常过滤器替换变量 @SELECT * FROM zt_user WHERE account = 'admin' AND status = 'active'
- 测试空过滤器数组处理 @SELECT * FROM zt_task WHERE '' AND ''
- 测试条件变量处理 @SELECT * FROM zt_project WHERE id='1'
- 测试多选类型变量处理 @SELECT * FROM zt_story WHERE type IN (''story','requirement'')

- 测试复杂变量占位符清理 @SELECT * FROM zt_bug WHERE ''Condition AND status = '' AND ''Condition

*/

$bi = new biTest();

// 测试步骤1：测试正常过滤器替换变量
$sql1 = "SELECT * FROM zt_user WHERE account = \$username AND status = \$status";
$filters1 = array(
    array(
        'field' => 'username',
        'from' => 'query',
        'type' => 'input',
        'default' => 'admin'
    ),
    array(
        'field' => 'status',
        'from' => 'query',
        'type' => 'select',
        'default' => 'active'
    )
);
r($bi->parseSqlVarsTest($sql1, $filters1)) && p() && e("SELECT * FROM zt_user WHERE account = 'admin' AND status = 'active'"); // 测试正常过滤器替换变量

// 测试步骤2：测试空过滤器数组处理
$sql2 = "SELECT * FROM zt_task WHERE \$Variable_1 AND \$Variable_2";
$filters2 = array();
r($bi->parseSqlVarsTest($sql2, $filters2)) && p() && e("SELECT * FROM zt_task WHERE '' AND ''"); // 测试空过滤器数组处理

// 测试步骤3：测试条件变量处理
$sql3 = "SELECT * FROM zt_project WHERE \$projectCondition";
$filters3 = array(
    array(
        'field' => 'project',
        'from' => 'query',
        'type' => 'select',
        'default' => '1',
        'relatedField' => 'id'
    )
);
r($bi->parseSqlVarsTest($sql3, $filters3)) && p() && e("SELECT * FROM zt_project WHERE id='1'"); // 测试条件变量处理

// 测试步骤4：测试多选类型变量处理
$sql4 = "SELECT * FROM zt_story WHERE type IN ('\$storyType')";
$filters4 = array(
    array(
        'field' => 'storyType',
        'from' => 'query',
        'type' => 'multipleselect',
        'default' => array('story', 'requirement')
    )
);
r($bi->parseSqlVarsTest($sql4, $filters4)) && p() && e("SELECT * FROM zt_story WHERE type IN (''story','requirement'')"); // 测试多选类型变量处理

// 测试步骤5：测试复杂变量占位符清理
$sql5 = "SELECT * FROM zt_bug WHERE \$Variable_1Condition AND status = \$status_2 AND \$Variable_3Condition";
$filters5 = array();
r($bi->parseSqlVarsTest($sql5, $filters5)) && p() && e("SELECT * FROM zt_bug WHERE ''Condition AND status = '' AND ''Condition"); // 测试复杂变量占位符清理