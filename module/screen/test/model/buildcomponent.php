#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildComponent();
timeout=0
cid=18207

- 测试步骤1：传入空对象时设置默认属性 @1
- 测试步骤2：传入非组模式组件设置默认属性 @1
- 测试步骤3：传入组模式组件处理 @1
- 测试步骤4：传入具有特殊属性的普通组件 @1
- 测试步骤5：传入sourceID为0的组件 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('screen')->gen(0);
zenData('chart')->gen(0);
zenData('product')->gen(0);
zenData('story')->gen(0);
zenData('action')->gen(0);

su('admin');

$screenTest = new screenTest();

// 测试步骤1：传入空对象时设置默认属性
$emptyComponent = new stdClass();
$screenTest->buildComponentTest($emptyComponent);
r(isset($emptyComponent->styles) && isset($emptyComponent->status) && isset($emptyComponent->request)) && p() && e('1');    // 测试步骤1：传入空对象时设置默认属性

// 测试步骤2：传入非组模式组件设置默认属性
$normalComponent = new stdClass();
$normalComponent->isGroup = false;
$normalComponent->name = 'testComponent';
$screenTest->buildComponentTest($normalComponent);
r(isset($normalComponent->styles) && isset($normalComponent->status) && isset($normalComponent->request)) && p() && e('1');    // 测试步骤2：传入非组模式组件设置默认属性

// 测试步骤3：传入组模式组件处理
$groupComponent = new stdClass();
$groupComponent->isGroup = true;
$groupComponent->groupList = array();
$screenTest->buildComponentTest($groupComponent);
r(isset($groupComponent->styles) && isset($groupComponent->status) && isset($groupComponent->request)) && p() && e('1');    // 测试步骤3：传入组模式组件处理

// 测试步骤4：传入具有特殊属性的普通组件
$specialComponent = new stdClass();
$specialComponent->name = 'specialTest';
$specialComponent->customProperty = 'test';
$screenTest->buildComponentTest($specialComponent);
r(isset($specialComponent->styles) && isset($specialComponent->status) && isset($specialComponent->request)) && p() && e('1');    // 测试步骤4：传入具有特殊属性的普通组件

// 测试步骤5：传入sourceID为0的组件
$zeroSourceComponent = new stdClass();
$zeroSourceComponent->sourceID = 0;
$zeroSourceComponent->name = 'zeroSource';
$screenTest->buildComponentTest($zeroSourceComponent);
r(isset($zeroSourceComponent->styles) && isset($zeroSourceComponent->status) && isset($zeroSourceComponent->request)) && p() && e('1');    // 测试步骤5：传入sourceID为0的组件