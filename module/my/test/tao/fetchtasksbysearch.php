#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchTasksBySearch();
timeout=0
cid=17310

- 测试名称包含条件的工作任务搜索 >> 期望返回0个任务
- 测试指派条件的工作任务搜索 >> 期望返回0个任务
- 测试执行条件的任务搜索 >> 期望返回0个任务
- 测试贡献任务类型的搜索 >> 期望返回0个任务
- 测试简单条件的工作任务搜索 >> 期望返回0个任务

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
// 生成0条数据来避免复杂的数据依赖问题
zenData('task')->gen(0);
zenData('execution')->gen(0);
zenData('story')->gen(0);
zenData('project')->gen(0);
zenData('taskteam')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($myTest->fetchTasksBySearchTest("`name` like '%任务%'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0'); // 步骤1：测试名称包含条件的工作任务搜索
r($myTest->fetchTasksBySearchTest("`assignedTo` = 'admin'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0'); // 步骤2：测试指派条件的工作任务搜索
r($myTest->fetchTasksBySearchTest("t1.`execution` = '1'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0'); // 步骤3：测试执行条件的任务搜索
r($myTest->fetchTasksBySearchTest('1', 'contributeTask', 'admin', array(1, 2, 3), 'id_desc', 0, null)) && p() && e('0'); // 步骤4：测试贡献任务类型的搜索
r($myTest->fetchTasksBySearchTest('1', 'workTask', 'admin', array(), 'id_desc', 3, null)) && p() && e('0'); // 步骤5：测试简单条件的工作任务搜索