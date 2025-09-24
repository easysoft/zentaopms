#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getConnectSQL();
timeout=0
cid=0

- 步骤1：空数组过滤条件输入 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getConnectSQLTest(array())) && p() && e('0');                                                        // 步骤1：空数组过滤条件输入
r($pivotTest->getConnectSQLTest(array(
    0 => array('from' => 'query', 'field' => 'name')
))) && p() && e('0');                                                                                               // 步骤2：包含from键的过滤条件
r($pivotTest->getConnectSQLTest(array(
    'status' => array('field' => 'status', 'operator' => '=', 'value' => "'active'")
))) && p() && e(' where tt.`status` = \'active\'');                                                               // 步骤3：单个等号操作符过滤条件
r($pivotTest->getConnectSQLTest(array(
    'name' => array('field' => 'name', 'operator' => '=', 'value' => "'test'"),
    'score' => array('field' => 'score', 'operator' => '>', 'value' => '100')
))) && p() && e(' where tt.`name` = \'test\' and tt.`score` > 100');                                              // 步骤4：多个过滤条件组合
r($pivotTest->getConnectSQLTest(array(
    'id' => array('field' => 'id', 'operator' => '!=', 'value' => '0'),
    'type' => array('field' => 'type', 'operator' => 'LIKE', 'value' => "'%bug%'"),
    'priority' => array('field' => 'priority', 'operator' => 'BETWEEN', 'value' => '1 AND 3')
))) && p() && e(' where tt.`id` != 0 and tt.`type` LIKE \'%bug%\' and tt.`priority` BETWEEN 1 AND 3');          // 步骤5：各种操作符的过滤条件测试
r($pivotTest->getConnectSQLTest(array(
    'title' => array('field' => 'title', 'operator' => '=', 'value' => "'test\'s data'"),
    'count' => array('field' => 'count', 'operator' => '>=', 'value' => '0')
))) && p() && e(' where tt.`title` = \'test\'s data\' and tt.`count` >= 0');                                     // 步骤6：特殊字符处理测试
r($pivotTest->getConnectSQLTest(array(
    'category' => array('field' => 'category', 'operator' => 'IN', 'value' => "('bug','task','story')"),
    'deleted' => array('field' => 'deleted', 'operator' => '=', 'value' => "'0'"),
    'created' => array('field' => 'created', 'operator' => '>', 'value' => "'2024-01-01'")
))) && p() && e(' where tt.`category` IN (\'bug\',\'task\',\'story\') and tt.`deleted` = \'0\' and tt.`created` > \'2024-01-01\''); // 步骤7：复杂数据类型过滤条件
r($pivotTest->getConnectSQLTest(array(
    'min_value' => array('field' => 'min_value', 'operator' => '>=', 'value' => '0'),
    'max_value' => array('field' => 'max_value', 'operator' => '<=', 'value' => '999999')
))) && p() && e(' where tt.`min_value` >= 0 and tt.`max_value` <= 999999');                                      // 步骤8：边界值测试
r($pivotTest->getConnectSQLTest(array(
    'nullable_field' => array('field' => 'nullable_field', 'operator' => 'IS NULL', 'value' => ''),
    'empty_field' => array('field' => 'empty_field', 'operator' => '=', 'value' => "''")
))) && p() && e(' where tt.`nullable_field` IS NULL  and tt.`empty_field` = \'\'');                             // 步骤9：NULL值和空字符串测试