#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildEditForm();
timeout=0
cid=0

- 步骤1：标题属性title @代码库-编辑
- 步骤2：代码库ID属性repoID @2
- 步骤3：代码库属性id @1
- 步骤4：服务属性1 @GitLab服务器
- 步骤5：空间属性1 @space1
*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('repo')->gen(5);
zendata('pipeline')->gen(5);
zendata('ops_space')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoZenTest = new repoZenTest();

// 5. 测试步骤
r($repoZenTest->buildEditFormTest(1, 1))               && p('title')  && e('代码库-编辑'); // 步骤1：标题
r($repoZenTest->buildEditFormTest(2, 1))               && p('repoID') && e('2'); // 步骤2：代码库ID
r($repoZenTest->buildEditFormTest(1, 1)->repo)         && p('id')     && e('1'); // 步骤3：代码库
r($repoZenTest->buildEditFormTest(1, 1)->serviceHosts) && p('1')      && e('GitLab服务器'); // 步骤4：服务
r($repoZenTest->buildEditFormTest(1, 1)->spaces)       && p('1')      && e('space1'); // 步骤5：空间
