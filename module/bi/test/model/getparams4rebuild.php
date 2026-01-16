#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::getParams4Rebuild();
timeout=0
cid=15178

- 测试简单SELECT语句，检查返回数组包含4个元素 @4
- 测试带别名的SELECT语句，验证字段映射处理第2条的user_id属性 @user_id
- 测试空字段列表参数，确保方法能正常处理 @4
- 测试复杂SELECT语句，验证多字段处理第2条的description属性 @description
- 测试聚合函数SQL，验证计算字段处理第2条的total属性 @total

*/

$bi = new biModelTest();

// 测试用例1：简单SELECT语句
$sql1 = "SELECT 1 as id, 'test' as name";
$statement1 = $bi->instance->sql2Statement($sql1);
$columnFields1 = array('id' => 'id', 'name' => 'name');

// 测试用例2：带别名的SELECT语句
$sql2 = "SELECT 1 as user_id, 'admin' as user_name";
$statement2 = $bi->instance->sql2Statement($sql2);
$columnFields2 = array('user_id' => 'user_id', 'user_name' => 'user_name');

// 测试用例3：空字段列表
$sql3 = "SELECT 'test' as value";
$statement3 = $bi->instance->sql2Statement($sql3);
$columnFields3 = array();

// 测试用例4：多字段
$sql4 = "SELECT 1 as id, 'name' as title, 'desc' as description";
$statement4 = $bi->instance->sql2Statement($sql4);
$columnFields4 = array('id' => 'id', 'title' => 'title', 'description' => 'description');

// 测试用例5：聚合函数
$sql5 = "SELECT COUNT(1) as total, 'admin' as role";
$statement5 = $bi->instance->sql2Statement($sql5);
$columnFields5 = array('total' => 'total', 'role' => 'role');

r(count($bi->getParams4RebuildTest($sql1, $statement1, $columnFields1))) && p() && e(4);                    // 测试简单SELECT语句，检查返回数组包含4个元素
r($bi->getParams4RebuildTest($sql2, $statement2, $columnFields2)) && p('2:user_id') && e('user_id');     // 测试带别名的SELECT语句，验证字段映射处理
r(count($bi->getParams4RebuildTest($sql3, $statement3, $columnFields3))) && p() && e(4);                 // 测试空字段列表参数，确保方法能正常处理
r($bi->getParams4RebuildTest($sql4, $statement4, $columnFields4)) && p('2:description') && e('description'); // 测试复杂SELECT语句，验证多字段处理
r($bi->getParams4RebuildTest($sql5, $statement5, $columnFields5)) && p('2:total') && e('total');           // 测试聚合函数SQL，验证计算字段处理
