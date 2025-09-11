#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraChangeItem();
timeout=0
cid=0

- 步骤2：导入空数据数组 @true

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTest();

// 4. 测试步骤 - 必须包含至少5个测试步骤
r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 10,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'In Progress'
    ),
    (object)array(
        'id' => 11,
        'groupid' => 2,
        'field' => 'assignee',
        'oldstring' => 'user1',
        'newstring' => 'user2'
    )
))) && p() && e('true'); // 步骤1：正常导入changeitem数据

r($convertTest->importJiraChangeItemTest(array())) && p() && e('true'); // 步骤2：导入空数据数组

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 1,
        'groupid' => 1,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 步骤3：导入已存在关联的changeitem数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 12,
        'groupid' => 999,
        'field' => 'status',
        'oldstring' => 'Open',
        'newstring' => 'Closed'
    )
))) && p() && e('true'); // 步骤4：导入无效groupid的changeitem数据

r($convertTest->importJiraChangeItemTest(array(
    (object)array(
        'id' => 13,
        'groupid' => 3,
        'field' => 'priority',
        'oldstring' => 'High',
        'newstring' => 'Low'
    )
))) && p() && e('true'); // 步骤5：导入issue不存在的changeitem数据