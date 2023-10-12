#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

su('admin');

zdTable('project')->gen(5);

/**

title=测试 actionModel->getLikeObject();
cid=1
pid=1

获取id为1,2,3的stage >> 1,stage,1;2,stage,1;3,stage,1
获取id为4,5,6的stage >> 4,stage,1;5,stage,1;6,stage,1
获取id为7,8,9的stage >> 7,stage,1;8,stage,1;9,stage,1

*/

$table    = array(TABLE_PROJECT, TABLE_BUG);
$likeName = array('name' => '%项目集%', 'code' => '%program%');

$action = new actionTest();

r($action->getLikeObjectTest($table[0], 'name', 'name', $likeName['name'])) && p() &&e('5');

r($action->getLikeObjectTest($table[0], 'code', 'code', $likeName['code'])) && p() &&e('5');
