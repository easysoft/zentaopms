#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeBatchCreate();
timeout=0
cid=19290

- 执行$result1 @2
- 执行$result2 @0
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('user')->gen(1);

su('admin');

$todoTest = new todoTest();

// 步骤1：正常批量创建待办数据
$normalForm = new stdClass();
$normalForm->data = array(
    '1' => (object)array(
        'name' => '测试待办1',
        'type' => 'custom',
        'pri' => 2,
        'begin' => '0900',
        'end' => '1800',
        'desc' => '测试描述',
        'status' => 'wait'
    ),
    '2' => (object)array(
        'name' => '测试待办2',
        'type' => 'custom',
        'pri' => 1,
        'begin' => '1000',
        'end' => '1700',
        'desc' => '测试描述2',
        'status' => 'wait'
    )
);
$result1 = $todoTest->beforeBatchCreateTest($normalForm);
r(count($result1)) && p() && e(2);

// 步骤2：时间范围错误的待办数据
$invalidTimeForm = new stdClass();
$invalidTimeForm->data = array(
    '1' => (object)array(
        'name' => '时间错误待办',
        'type' => 'custom',
        'pri' => 2,
        'begin' => '1800',
        'end' => '0900',
        'desc' => '时间范围错误',
        'status' => 'wait'
    )
);
$result2 = $todoTest->beforeBatchCreateTest($invalidTimeForm);
r($result2) && p() && e('0');

// 步骤3：包含自定义类型和模块类型的混合数据
$mixedForm = new stdClass();
$mixedForm->data = array(
    '1' => (object)array(
        'name' => '自定义待办',
        'type' => 'custom',
        'pri' => 2,
        'begin' => '0900',
        'end' => '1800',
        'status' => 'wait'
    ),
    '2' => (object)array(
        'name' => '1',
        'type' => 'task',
        'pri' => 1,
        'begin' => '1000',
        'end' => '1700',
        'status' => 'wait'
    )
);
$result3 = $todoTest->beforeBatchCreateTest($mixedForm);
r(count($result3)) && p() && e(1);

// 步骤4：空时间数据的处理
$emptyTimeForm = new stdClass();
$emptyTimeForm->data = array(
    '1' => (object)array(
        'name' => '空时间待办',
        'type' => 'custom',
        'pri' => 2,
        'begin' => '',
        'end' => '',
        'desc' => '空时间测试',
        'status' => 'wait',
        'switchTime' => 1
    )
);
$result4 = $todoTest->beforeBatchCreateTest($emptyTimeForm);
r(count($result4)) && p() && e(1);

// 步骤5：不同类型待办名称获取
$moduleTypeForm = new stdClass();
$moduleTypeForm->data = array(
    '1' => (object)array(
        'name' => '1',
        'type' => 'story',
        'pri' => 2,
        'begin' => '0900',
        'end' => '1800',
        'status' => 'wait'
    )
);
$result5 = $todoTest->beforeBatchCreateTest($moduleTypeForm);
r(count($result5)) && p() && e(1);