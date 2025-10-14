#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildRepoTree();
timeout=0
cid=0

步骤1：正常情况 >> project
步骤2：边界值 >> single
步骤3：异常输入 >> a-first
步骤4：权限验证 >> 1
步骤5：业务规则 >> 3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildRepoTreeTest(array(
    'project/repo1' => array('value' => 1, 'parent' => 'project', 'path' => 'project/repo1', 'text' => 'repo1'),
    'project' => array('value' => 2, 'parent' => '0', 'path' => 'project', 'text' => 'project'),
    'project/repo2' => array('value' => 3, 'parent' => 'project', 'path' => 'project/repo2', 'text' => 'repo2')
), '0')) && p('0:text') && e('project'); // 步骤1：正常情况

r($repoTest->buildRepoTreeTest(array(
    'single' => array('value' => 1, 'parent' => '0', 'path' => 'single', 'text' => 'single')
), '0')) && p('0:text') && e('single'); // 步骤2：边界值

r($repoTest->buildRepoTreeTest(array(
    'z-last' => array('value' => 1, 'parent' => '0', 'path' => 'z-last', 'text' => 'z-last'),
    'a-first' => array('value' => 2, 'parent' => '0', 'path' => 'a-first', 'text' => 'a-first'),
    'm-middle' => array('value' => 3, 'parent' => '0', 'path' => 'm-middle', 'text' => 'm-middle')
), '0')) && p('0:text') && e('a-first'); // 步骤3：异常输入

r($repoTest->buildRepoTreeTest(array(
    'project/repo1' => array('value' => 1, 'parent' => 'project', 'path' => 'project/repo1', 'text' => 'repo1'),
    'project' => array('value' => 2, 'parent' => '0', 'path' => 'project', 'text' => 'project')
), '0')) && p('0:disabled') && e('1'); // 步骤4：权限验证

r($repoTest->buildRepoTreeTest(array(
    'a' => array('value' => 1, 'parent' => '0', 'path' => 'a', 'text' => 'a'),
    'b' => array('value' => 2, 'parent' => '0', 'path' => 'b', 'text' => 'b'),
    'c' => array('value' => 3, 'parent' => '0', 'path' => 'c', 'text' => 'c')
), '0')) && p('count') && e('3'); // 步骤5：业务规则