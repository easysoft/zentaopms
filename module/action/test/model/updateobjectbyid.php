#!/usr/bin/env php
<?php

/**

title=测试 actionModel::updateObjectByID();
timeout=0
cid=14936

- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 1, array 属性name @更新后的项目名称
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 2, array
 - 属性name @多字段更新项目
 - 属性status @doing
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 3, array 属性name @项目名称不变
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 4, array 属性name @特殊字符测试ABC
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 5, array 属性status @closed
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 6, array
 - 属性name @数值类型测试
 - 属性type @kanban
- 执行action模块的updateObjectByIDTest方法，参数是TABLE_PROJECT, 7, array 属性project @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('project')->gen(10);

su('admin');

$action = new actionTest();

r($action->updateObjectByIDTest(TABLE_PROJECT, 1, array('name' => '更新后的项目名称'))) && p('name') && e('更新后的项目名称');
r($action->updateObjectByIDTest(TABLE_PROJECT, 2, array('name' => '多字段更新项目', 'status' => 'doing'))) && p('name,status') && e('多字段更新项目,doing');
r($action->updateObjectByIDTest(TABLE_PROJECT, 3, array('name' => '项目名称不变'))) && p('name') && e('项目名称不变');
r($action->updateObjectByIDTest(TABLE_PROJECT, 4, array('name' => '特殊字符测试ABC'))) && p('name') && e('特殊字符测试ABC');
r($action->updateObjectByIDTest(TABLE_PROJECT, 5, array('status' => 'closed'))) && p('status') && e('closed');
r($action->updateObjectByIDTest(TABLE_PROJECT, 6, array('name' => '数值类型测试', 'type' => 'kanban'))) && p('name,type') && e('数值类型测试,kanban');
r($action->updateObjectByIDTest(TABLE_PROJECT, 7, array('project' => 1))) && p('project') && e('1');