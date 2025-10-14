#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeBatchEdit();
timeout=0
cid=0

- 执行todoTest模块的beforeBatchEditTest方法，参数是array  @0
- 执行todoTest模块的beforeBatchEditTest方法，参数是$todos
 - 第1条的objectID属性 @10
 - 第1条的name属性 @
- 执行todoTest模块的beforeBatchEditTest方法，参数是$customTodos  @0
- 执行todoTest模块的beforeBatchEditTest方法，参数是$invalidTimeTodos  @0
- 执行todoTest模块的beforeBatchEditTest方法，参数是$switchTimeTodos
 - 第1条的begin属性 @2400
 - 第1条的end属性 @2400

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('user')->gen(5);
zenData('config')->gen(5);

su('admin');

$todoTest = new todoTest();

// 步骤1：测试空数组输入
r($todoTest->beforeBatchEditTest(array())) && p() && e('0');

// 步骤2：测试正常模块类型待办处理
$todos = array(
    1 => (object)array(
        'type' => 'task',
        'task' => '10',
        'name' => 'test task',
        'begin' => '0900',
        'end' => '1800',
        'pri' => '2',
        'status' => 'wait'
    )
);
r($todoTest->beforeBatchEditTest($todos)) && p('1:objectID,name') && e('10,');

// 步骤3：测试自定义类型待办名称验证
$customTodos = array(
    1 => (object)array(
        'type' => 'custom',
        'name' => '',
        'begin' => '0900',
        'end' => '1800'
    )
);
r($todoTest->beforeBatchEditTest($customTodos)) && p() && e('0');

// 步骤4：测试时间范围验证
$invalidTimeTodos = array(
    1 => (object)array(
        'type' => 'custom',
        'name' => 'test todo',
        'begin' => '1800',
        'end' => '0900'
    )
);
r($todoTest->beforeBatchEditTest($invalidTimeTodos)) && p() && e('0');

// 步骤5：测试时间切换功能
$switchTimeTodos = array(
    1 => (object)array(
        'type' => 'custom',
        'name' => 'test todo',
        'begin' => '0900',
        'end' => '1800',
        'switchTime' => '1'
    )
);
r($todoTest->beforeBatchEditTest($switchTimeTodos)) && p('1:begin,end') && e('2400,2400');