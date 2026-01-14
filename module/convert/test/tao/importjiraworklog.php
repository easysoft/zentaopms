#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraWorkLog();
timeout=0
cid=15864

- 步骤1：正常情况-空数组 @1
- 步骤2：正常工作日志 @1
- 步骤3：已存在关系 @1
- 步骤4：无对应issue @1
- 步骤5：无效数据 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备 - 不使用数据库，通过mock处理

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTaoTest();

// 5. 执行测试步骤
r($convertTest->importJiraWorkLogTest(array())) && p() && e('1'); // 步骤1：正常情况-空数组

// 准备正常工作日志数据
$normalWorklogData = array(
    (object)array(
        'id' => 1001,
        'issueid' => 1,
        'author' => 'testuser',
        'timeworked' => 7200, // 2小时
        'worklogbody' => '完成了功能开发',
        'created' => '2023-06-01 10:00:00'
    )
);
r($convertTest->importJiraWorkLogTest($normalWorklogData)) && p() && e('1'); // 步骤2：正常工作日志

// 已存在关系的工作日志
$existingWorklogData = array(
    (object)array(
        'id' => 2,
        'issueid' => 1,
        'author' => 'testuser',
        'timeworked' => 3600,
        'worklogbody' => '已存在的工作日志',
        'created' => '2023-06-01 11:00:00'
    )
);
r($convertTest->importJiraWorkLogTest($existingWorklogData)) && p() && e('1'); // 步骤3：已存在关系

// 不存在issue的工作日志
$noIssueWorklogData = array(
    (object)array(
        'id' => 1002,
        'issueid' => 999, // 不存在的issue
        'author' => 'testuser',
        'timeworked' => 3600,
        'worklogbody' => '无对应issue的工作日志',
        'created' => '2023-06-01 12:00:00'
    )
);
r($convertTest->importJiraWorkLogTest($noIssueWorklogData)) && p() && e('1'); // 步骤4：无对应issue

// 包含无效数据的工作日志
$invalidWorklogData = array(
    (object)array(
        'id' => 1003,
        'issueid' => 1,
        'author' => '',  // 空作者
        'timeworked' => 0,  // 零工时
        'worklogbody' => '',  // 空工作内容
        'created' => ''  // 空创建时间
    )
);
r($convertTest->importJiraWorkLogTest($invalidWorklogData)) && p() && e('1'); // 步骤5：无效数据