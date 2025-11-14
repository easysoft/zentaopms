#!/usr/bin/env php
<?php
/**

title=测试 repoZen::buildCreateRepoForm();
timeout=0
cid=18125

- 步骤1：获取标题属性title @代码库-创建
- 步骤2：获取项目ID属性objectID @1
- 步骤3：获取代码库第0条的text属性 @GitLab Instance
- 步骤4：获取空间属性1 @space1
- 步骤5：获取产品属性10 @正常产品10
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('user');  // 使用默认用户数据，避免重复账号问题
zenData('ops_space')->gen(5);
zenData('repo')->gen(5);
zenData('pipeline')->gen(5);
zenData('product')->gen(10);
zenData('project')->gen(5);
zenData('group')->gen(5);

su('admin');

$repoTest = new repoZenTest();

r($repoTest->buildCreateRepoFormTest(0))             && p('title')    && e('代码库-创建'); // 步骤1：获取标题
r($repoTest->buildCreateRepoFormTest(1))             && p('objectID') && e('1'); // 步骤2：获取项目ID
r($repoTest->buildCreateRepoFormTest(1)->repoGroups) && p('0:text')   && e('GitLab Instance'); // 步骤3：获取代码库
r($repoTest->buildCreateRepoFormTest(1)->spaces)     && p('1')        && e('space1'); // 步骤4：获取空间
r($repoTest->buildCreateRepoFormTest(0)->products)   && p('10')       && e('正常产品10'); // 步骤5：获取产品
