#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildIssueData();
timeout=0
cid=15814

- 执行convertTest模块的buildIssueDataTest方法，参数是$fullData
 - 属性id @12345
 - 属性summary @Test Issue Summary
 - 属性priority @High
- 执行convertTest模块的buildIssueDataTest方法，参数是$partialData
 - 属性id @67890
 - 属性summary @Partial Issue
 - 属性project @0
- 执行convertTest模块的buildIssueDataTest方法，参数是$emptyData
 - 属性id @0
 - 属性project @0
- 执行convertTest模块的buildIssueDataTest方法，参数是$minimalData
 - 属性id @999
 - 属性project @0
- 执行convertTest模块的buildIssueDataTest方法，参数是$specialData 属性id @special-123

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整issue数据构建
$fullData = array(
    'id' => '12345',
    'summary' => 'Test Issue Summary',
    'priority' => 'High',
    'project' => '1001',
    'status' => 'Open',
    'created' => '2024-01-15 10:30:00',
    'creator' => 'testuser',
    'type' => 'Bug',
    'assignee' => 'developer1',
    'resolution' => 'Fixed',
    'timeoriginalestimate' => '8h',
    'timeestimate' => '6h',
    'timespent' => '4h',
    'number' => 'ISSUE-123',
    'description' => 'This is a test issue description',
    'duedate' => '2024-02-15'
);
r($convertTest->buildIssueDataTest($fullData)) && p('id,summary,priority') && e('12345,Test Issue Summary,High');

// 步骤2：部分字段缺失数据构建
$partialData = array(
    'id' => '67890',
    'summary' => 'Partial Issue',
    'priority' => 'Medium',
    'created' => '2024-01-20 14:15:00',
    'type' => 'Task'
);
r($convertTest->buildIssueDataTest($partialData)) && p('id,summary,project') && e('67890,Partial Issue,0');

// 步骤3：空数组输入测试
$emptyData = array('id' => '0');
r($convertTest->buildIssueDataTest($emptyData)) && p('id,project') && e('0,0');

// 步骤4：只有id字段的最小数据
$minimalData = array('id' => '999');
r($convertTest->buildIssueDataTest($minimalData)) && p('id,project') && e('999,0');

// 步骤5：特殊字符和边界值测试
$specialData = array(
    'id' => 'special-123',
    'summary' => 'Issue with "quotes" & <html> tags',
    'priority' => '',
    'description' => "Multi-line\ndescription\nwith\ttabs",
    'creator' => 'user@domain.com'
);
r($convertTest->buildIssueDataTest($specialData)) && p('id') && e('special-123');