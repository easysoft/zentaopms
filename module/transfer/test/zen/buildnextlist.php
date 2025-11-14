#!/usr/bin/env php
<?php

/**

title=测试 transferZen::buildNextList();
timeout=0
cid=0

- 步骤1：空列表 @0
- 步骤2：正常列表生成HTML @1
- 步骤3：lastID过滤 @1
- 步骤4：懒加载限制 @1
- 步骤5：边界情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferZenTest();

// 4. 准备测试数据
$emptyList = array();

$normalList = array();
for($i = 1; $i <= 5; $i++)
{
    $obj = new stdClass();
    $obj->id = $i;
    $obj->name = 'Task ' . $i;
    $normalList[$i] = $obj;
}

$largeList = array();
for($i = 1; $i <= 30; $i++)
{
    $obj = new stdClass();
    $obj->id = $i;
    $obj->name = 'Task ' . $i;
    $largeList[$i] = $obj;
}

$fields = array();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->buildNextListTest($emptyList, 0, $fields, 1, 'task')) && p() && e('0'); // 步骤1：空列表
r(strlen($transferTest->buildNextListTest($normalList, 0, $fields, 1, 'task')) > 0) && p() && e('1'); // 步骤2：正常列表生成HTML
r(strpos($transferTest->buildNextListTest($normalList, 2, $fields, 1, 'task'), 'id') !== false) && p() && e('1'); // 步骤3：lastID过滤
r(substr_count($transferTest->buildNextListTest($largeList, 0, $fields, 1, 'task'), '<tr') <= 11) && p() && e('1'); // 步骤4：懒加载限制
r($transferTest->buildNextListTest($normalList, 5, $fields, 1, 'task')) && p() && e('0'); // 步骤5：边界情况