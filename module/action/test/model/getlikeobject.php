#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

su('admin');

zdTable('project')->gen(5);

/**

title=测试 actionModel->getLikeObject();
timeout=0
cid=1

- 执行action模块的getLikeObjectTest方法，参数是$table[0], 'name', 'name', $likeName['name']  @5
- 执行action模块的getLikeObjectTest方法，参数是$table[0], 'code', 'code', $likeName['code']  @5

*/

$table    = array(TABLE_PROJECT, TABLE_BUG);
$likeName = array('name' => '%项目集%', 'code' => '%program%');

$action = new actionTest();

r($action->getLikeObjectTest($table[0], 'name', 'name', $likeName['name'])) && p() &&e('5');

r($action->getLikeObjectTest($table[0], 'code', 'code', $likeName['code'])) && p() &&e('5');