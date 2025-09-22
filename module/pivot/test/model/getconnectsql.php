#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getConnectSQL();
timeout=0
cid=0

- 空过滤条件输入 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->getConnectSQLTest(array(
    'name' => array('field' => 'name', 'operator' => '=', 'value' => "'test'"),
    'score' => array('field' => 'score', 'operator' => '>', 'value' => '100')
))) && p() && e(' where tt.`name` = \'test\' and tt.`score` > 100');                   // 正常过滤条件生成SQL

r($pivot->getConnectSQLTest(array())) && p() && e('');                                  // 空过滤条件输入

r($pivot->getConnectSQLTest(array(
    0 => array('from' => 'query', 'field' => 'name')
))) && p() && e('');                                                                    // 包含from键的过滤条件

r($pivot->getConnectSQLTest(array(
    'status' => array('field' => 'status', 'operator' => 'IN', 'value' => "('active','done')")
))) && p() && e(' where tt.`status` IN (\'active\',\'done\')');                        // 单个过滤条件测试

r($pivot->getConnectSQLTest(array(
    'id' => array('field' => 'id', 'operator' => '!=', 'value' => '0'),
    'type' => array('field' => 'type', 'operator' => 'LIKE', 'value' => "'%bug%'"),
    'priority' => array('field' => 'priority', 'operator' => 'BETWEEN', 'value' => '1 AND 3')
))) && p() && e(' where tt.`id` != 0 and tt.`type` LIKE \'%bug%\' and tt.`priority` BETWEEN 1 AND 3'); // 多种操作符的过滤条件