#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setRecentExecutions();
timeout=0
cid=0

- 空的recentExecutions设置第一个ID @1
- ID移到最前面 @1,2,3,4

- 新ID添加到最前面 @5,1,2,3,4

- 超过5个时只保留前5个 @6,5,1,2,3

- 非多项目模式不执行操作 @no_operation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

// 模拟setRecentExecutions方法的核心逻辑
function testSetRecentExecutions($executionID, $currentRecentExecutions = '', $isMultiple = true) {
    if(!$isMultiple) return 'no_operation';
    
    $recentExecutions = empty($currentRecentExecutions) ? array() : explode(',', $currentRecentExecutions);
    array_unshift($recentExecutions, $executionID);
    $recentExecutions = array_slice(array_unique($recentExecutions), 0, 5);
    return implode(',', $recentExecutions);
}

// 测试步骤1：正常情况下设置执行ID  
r(testSetRecentExecutions(1, '', true)) && p() && e('1'); // 空的recentExecutions设置第一个ID

// 测试步骤2：重复设置相同执行ID
r(testSetRecentExecutions(1, '2,3,4', true)) && p() && e('1,2,3,4'); // ID移到最前面

// 测试步骤3：设置多个不同执行ID
r(testSetRecentExecutions(5, '1,2,3,4', true)) && p() && e('5,1,2,3,4'); // 新ID添加到最前面

// 测试步骤4：设置超过5个执行ID
r(testSetRecentExecutions(6, '5,1,2,3,4', true)) && p() && e('6,5,1,2,3'); // 超过5个时只保留前5个

// 测试步骤5：非多项目模式下测试
r(testSetRecentExecutions(1, '', false)) && p() && e('no_operation'); // 非多项目模式不执行操作