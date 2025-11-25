#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeCreate();
cid=19291

- 测试正常表单数据处理 >> 期望返回包含所有默认字段的对象
- 测试具有模块类型对象的表单数据 >> 期望正确设置objectID
- 测试空日期或切换日期的处理 >> 期望设置为FUTURE_TIME
- 测试空时间或切换时间的处理 >> 期望设置为2400
- 测试私有属性开关处理 >> 期望private字段设置为1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zendata('todo')->loadYaml('todo_beforecreate', false, 2)->gen(10);
zendata('user')->loadYaml('user', false, 2)->gen(5);

su('admin');

$todoTest = new todoTest();

// 测试1：正常表单数据处理 - 验证默认字段设置
$normalData = array(
    'type' => 'custom',
    'name' => '测试待办',
    'pri' => '2',
    'date' => '2023-12-01',
    'begin' => '0830',
    'end' => '1730'
);

r($todoTest->beforeCreateTest($normalData)) && p('account,vision,assignedTo,assignedBy') && e('admin,rnd,admin,admin');

// 测试2：具有模块类型对象的表单数据 - 验证objectID设置
$moduleData = array(
    'type' => 'task',
    'objectID' => 5,
    'name' => '任务待办',
    'pri' => '1',
    'date' => '2023-12-01',
    'begin' => '0900',
    'end' => '1800'
);

r($todoTest->beforeCreateTest($moduleData)) && p('objectID') && e(5);

// 测试3：空日期处理 - 验证FUTURE_TIME设置
$emptyDateData = array(
    'type' => 'custom',
    'name' => '空日期待办',
    'pri' => '3',
    'date' => '',
    'begin' => '1000',
    'end' => '1200'
);

r($todoTest->beforeCreateTest($emptyDateData)) && p('date') && e('2030-01-01');

// 测试4：空时间处理 - 验证时间默认值2400
$emptyTimeData = array(
    'type' => 'custom',
    'name' => '空时间待办',
    'pri' => '2',
    'date' => '2023-12-02',
    'begin' => '',
    'end' => ''
);

r($todoTest->beforeCreateTest($emptyTimeData)) && p('begin,end') && e('2400,2400');

// 测试5：私有属性处理 - 验证private字段设置
$privateData = array(
    'type' => 'custom',
    'name' => '私有待办',
    'pri' => '1',
    'date' => '2023-12-03',
    'begin' => '1400',
    'end' => '1600',
    'private' => 'on'
);

r($todoTest->beforeCreateTest($privateData)) && p('private') && e(1);