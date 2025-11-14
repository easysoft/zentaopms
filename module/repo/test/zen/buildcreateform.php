#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildCreateForm();
timeout=0
cid=18124

- 步骤1：正常项目ID属性title @代码库-创建
- 步骤2：项目ID属性objectID @2
- 步骤3：服务器列表属性1 @GitLab服务器
- 步骤4：空间列表属性1 @space1
- 步骤5：不存在的项目ID属性title @代码库-创建
*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('user')->gen(5);
zenData('ops_space')->gen(5);
zenData('repo')->gen(5);
zenData('pipeline')->gen(5);
zenData('product')->gen(10);
zenData('project')->gen(5);
zenData('group')->gen(5);

su('admin');

$repoTest = new repoZenTest();
r($repoTest->buildCreateFormTest(1))               && p('title')    && e('代码库-创建');  // 步骤1：正常项目ID
r($repoTest->buildCreateFormTest(2))               && p('objectID') && e('2');            // 步骤2：项目ID
r($repoTest->buildCreateFormTest(3)->serviceHosts) && p('1')        && e('GitLab服务器'); // 步骤3：服务器列表
r($repoTest->buildCreateFormTest(1)->spaces)       && p('1')        && e('space1');       // 步骤4：空间列表
r($repoTest->buildCreateFormTest(999))             && p('title')    && e('代码库-创建');  // 步骤5：不存在的项目ID
