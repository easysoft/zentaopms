#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getConnectSQL();
timeout=0
cid=17377

- 步骤1：空数组输入测试 @0
- 步骤2：包含from键的过滤条件测试 @0
- 步骤3：单个等号操作符过滤条件测试 @ where tt.`status` = 'active'
- 步骤4：多个过滤条件AND组合测试 @ where tt.`name` = 'test' and tt.`score` > 100
- 步骤5：各种SQL操作符组合测试 @ where tt.`id` != 0 and tt.`type` LIKE '%bug%' and tt.`priority` IN ('1','2','3')

- 步骤6：特殊字符和转义测试 @ where tt.`description` LIKE '%can\'t%'
- 步骤7：数值型条件组合测试 @ where tt.`count` >= 50 and tt.`level` < 10
- 步骤8：小写操作符测试 @ where tt.`status` like '%test%'
- 步骤9：空字符串值过滤测试 @ where tt.`title` != ''

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getConnectSQLTest(array())) && p() && e('0');                                                                 // 步骤1：空数组输入测试
r($pivotTest->getConnectSQLTest(array(0 => array('from' => 'query', 'field' => 'name')))) && p() && e('0');              // 步骤2：包含from键的过滤条件测试
r($pivotTest->getConnectSQLTest(array('status' => array('field' => 'status', 'operator' => '=', 'value' => "'active'")))) && p() && e(" where tt.`status` = 'active'");    // 步骤3：单个等号操作符过滤条件测试
r($pivotTest->getConnectSQLTest(array('name' => array('field' => 'name', 'operator' => '=', 'value' => "'test'"), 'score' => array('field' => 'score', 'operator' => '>', 'value' => '100')))) && p() && e(" where tt.`name` = 'test' and tt.`score` > 100");    // 步骤4：多个过滤条件AND组合测试
r($pivotTest->getConnectSQLTest(array('id' => array('field' => 'id', 'operator' => '!=', 'value' => '0'), 'type' => array('field' => 'type', 'operator' => 'LIKE', 'value' => "'%bug%'"), 'priority' => array('field' => 'priority', 'operator' => 'IN', 'value' => "('1','2','3')")))) && p() && e(" where tt.`id` != 0 and tt.`type` LIKE '%bug%' and tt.`priority` IN ('1','2','3')");    // 步骤5：各种SQL操作符组合测试
r($pivotTest->getConnectSQLTest(array('description' => array('field' => 'description', 'operator' => 'LIKE', 'value' => "'%can\\'t%'")))) && p() && e(" where tt.`description` LIKE '%can\'t%'");    // 步骤6：特殊字符和转义测试
r($pivotTest->getConnectSQLTest(array('count' => array('field' => 'count', 'operator' => '>=', 'value' => '50'), 'level' => array('field' => 'level', 'operator' => '<', 'value' => '10')))) && p() && e(" where tt.`count` >= 50 and tt.`level` < 10");    // 步骤7：数值型条件组合测试
r($pivotTest->getConnectSQLTest(array('status' => array('field' => 'status', 'operator' => 'like', 'value' => "'%test%'")))) && p() && e(" where tt.`status` like '%test%'");    // 步骤8：小写操作符测试
r($pivotTest->getConnectSQLTest(array('title' => array('field' => 'title', 'operator' => '!=', 'value' => "''")))) && p() && e(" where tt.`title` != ''");    // 步骤9：空字符串值过滤测试