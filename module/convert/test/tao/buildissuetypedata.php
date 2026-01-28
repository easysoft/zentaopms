#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildIssueTypeData();
timeout=0
cid=15817

- 执行convertTest模块的buildIssueTypeDataTest方法，参数是$fullData
 - 属性id @12345
 - 属性pname @Bug Report
 - 属性description @This is a bug report issue type
- 执行convertTest模块的buildIssueTypeDataTest方法，参数是$requiredData
 - 属性id @67890
 - 属性pname @Feature Request
 - 属性description @~~
- 执行convertTest模块的buildIssueTypeDataTest方法，参数是$partialData
 - 属性id @11111
 - 属性pname @Task
 - 属性pstyle @primary
 - 属性iconurl @~~
- 执行convertTest模块的buildIssueTypeDataTest方法，参数是$emptyData
 - 属性id @0
 - 属性pname @~~
 - 属性avatar @~~
- 执行convertTest模块的buildIssueTypeDataTest方法，参数是$specialData
 - 属性id @special-123
 - 属性pname @Issue with "quotes" & <html> tags

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整数据输入测试
$fullData = array(
    'id' => '12345',
    'name' => 'Bug Report',
    'description' => 'This is a bug report issue type',
    'style' => 'danger',
    'iconurl' => 'https://example.com/bug-icon.png',
    'avatar' => 'avatar_bug.png'
);
r($convertTest->buildIssueTypeDataTest($fullData)) && p('id,pname,description') && e('12345,Bug Report,This is a bug report issue type');

// 步骤2：必填字段测试
$requiredData = array(
    'id' => '67890',
    'name' => 'Feature Request'
);
r($convertTest->buildIssueTypeDataTest($requiredData)) && p('id,pname,description') && e('67890,Feature Request,~~');

// 步骤3：部分字段缺失测试
$partialData = array(
    'id' => '11111',
    'name' => 'Task',
    'description' => 'Task type description',
    'style' => 'primary'
);
r($convertTest->buildIssueTypeDataTest($partialData)) && p('id,pname,pstyle,iconurl') && e('11111,Task,primary,~~');

// 步骤4：空数据测试
$emptyData = array(
    'id' => '0',
    'name' => ''
);
r($convertTest->buildIssueTypeDataTest($emptyData)) && p('id,pname,avatar') && e('0,~~,~~');

// 步骤5：特殊字符和边界值测试
$specialData = array(
    'id' => 'special-123',
    'name' => 'Issue with "quotes" & <html> tags',
    'description' => "Multi-line\ndescription\nwith\ttabs",
    'style' => '',
    'iconurl' => 'javascript:alert("xss")',
    'avatar' => 'special@avatar#.png'
);
r($convertTest->buildIssueTypeDataTest($specialData)) && p('id,pname') && e('special-123,Issue with "quotes" & <html> tags');