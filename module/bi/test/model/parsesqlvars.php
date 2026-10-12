#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=biModel->parseSqlVars();
timeout=0
cid=15193

- 测试正常过滤器替换变量 @SELECT * FROM zt_user WHERE account = 'admin' AND status = 'active'
- 测试空过滤器数组处理 @SELECT * FROM zt_task WHERE '' AND ''
- 测试条件变量处理 @SELECT * FROM zt_project WHERE id='1'
- 测试多选类型变量处理 @SELECT * FROM zt_story WHERE type IN ('story', 'requirement')
- 测试变量位于引号内时不做替换 @SELECT * FROM zt_story WHERE type IN ('$storyType')
- 测试过滤值中的引号被转义，注入载荷无法逃逸 @SELECT * FROM zt_user WHERE status = 'active\' or sleep(3)-- '
- 测试条件变量形态中的注入载荷被转义 @SELECT * FROM zt_project WHERE id='1\' or sleep(3)-- '
- 测试嵌套变量不会逃逸出字符串字面量 @SELECT * FROM zt_user WHERE status = 'x$statusx'
- 测试字段名不合法时拒绝请求 @过滤条件的字段名不合法，请求已被拒绝。
- 测试关联字段名不合法时拒绝请求 @过滤条件的字段名不合法，请求已被拒绝。
- 测试过滤值非标量时拒绝请求 @过滤条件的值不合法，只允许数字或引号包裹的字符串，请求已被拒绝。

- 测试复杂变量占位符清理 @SELECT * FROM zt_bug WHERE ''Condition AND status = '' AND ''Condition

*/

$app->loadLang('bi');

$bi = new biModelTest();

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
$sql4 = "SELECT * FROM zt_story WHERE type IN (\$storyType)";
$filters4 = array(
    array(
        'field' => 'storyType',
        'from' => 'query',
        'type' => 'multipleselect',
        'default' => array('story', 'requirement')
    )
);
r($bi->parseSqlVarsTest($sql4, $filters4)) && p() && e("SELECT * FROM zt_story WHERE type IN ('story', 'requirement')"); // 测试多选类型变量处理

// 测试步骤4-1：变量写在引号内时不做替换，避免插入结果被再次扫描
$sql41 = "SELECT * FROM zt_story WHERE type IN ('\$storyType')";
r($bi->parseSqlVarsTest($sql41, $filters4)) && p() && e("SELECT * FROM zt_story WHERE type IN ('\$storyType')"); // 测试变量位于引号内时不做替换

// 测试步骤4-2：注入载荷被转义为字面量，不能闭合引号
$sql42 = "SELECT * FROM zt_user WHERE status = \$status";
$filters42 = array(array('field' => 'status', 'from' => 'query', 'type' => 'select', 'default' => "active' or sleep(3)-- "));
r($bi->parseSqlVarsTest($sql42, $filters42)) && p() && e("SELECT * FROM zt_user WHERE status = 'active\\' or sleep(3)-- '"); // 测试过滤值中的引号被转义，注入载荷无法逃逸

// 测试步骤4-3：Condition 形态同样转义注入载荷
$sql43 = "SELECT * FROM zt_project WHERE \$projectCondition";
$filters43 = array(array('field' => 'project', 'from' => 'query', 'type' => 'select', 'default' => "1' or sleep(3)-- ", 'relatedField' => 'id'));
r($bi->parseSqlVarsTest($sql43, $filters43)) && p() && e("SELECT * FROM zt_project WHERE id='1\\' or sleep(3)-- '"); // 测试条件变量形态中的注入载荷被转义

// 测试步骤4-4：值中携带的嵌套变量不会在字面量内被替换
$sql44 = "SELECT * FROM zt_user WHERE status = 'x\$statusx'";
r($bi->parseSqlVarsTest($sql44, $filters42)) && p() && e("SELECT * FROM zt_user WHERE status = 'x\$statusx'"); // 测试嵌套变量不会逃逸出字符串字面量

// 测试步骤4-5：字段名不合法时拒绝请求
$filters45 = array(array('field' => 'status-bad', 'from' => 'query', 'type' => 'select', 'default' => '1'));
r($bi->parseSqlVarsTest("SELECT * FROM zt_user WHERE \$status-bad", $filters45)) && p() && e($lang->bi->errorIllegalFilter['field']); // 测试字段名不合法时拒绝请求

// 测试步骤4-6：关联字段名不合法时拒绝请求
$filters46 = array(array('field' => 'project', 'from' => 'query', 'type' => 'select', 'default' => '1', 'relatedField' => '1id'));
r($bi->parseSqlVarsTest($sql43, $filters46)) && p() && e($lang->bi->errorIllegalFilter['field']); // 测试关联字段名不合法时拒绝请求

// 测试步骤4-7：过滤值非标量时拒绝请求
$filters47 = array(array('field' => 'status', 'from' => 'query', 'type' => 'select', 'default' => new stdClass()));
r($bi->parseSqlVarsTest($sql42, $filters47)) && p() && e($lang->bi->errorIllegalFilter['value']); // 测试过滤值非标量时拒绝请求

// 测试步骤5：测试复杂变量占位符清理
$sql5 = "SELECT * FROM zt_bug WHERE \$Variable_1Condition AND status = \$status_2 AND \$Variable_3Condition";
$filters5 = array();
r($bi->parseSqlVarsTest($sql5, $filters5)) && p() && e("SELECT * FROM zt_bug WHERE ''Condition AND status = '' AND ''Condition"); // 测试复杂变量占位符清理
