#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildRepoTree();
timeout=0
cid=18127

- 步骤1:空路径列表 @0
- 步骤2:单个根路径第0条的text属性 @repo1
- 步骤3:单层路径结构
 - 第0条的text属性 @repo1
 - 第1条的text属性 @repo2
- 步骤4:两层嵌套路径第0条的text属性 @group
- 步骤5:三层嵌套路径第0条的text属性 @company
- 步骤6:多个并列分支
 - 第0条的text属性 @backend
 - 第1条的text属性 @frontend
- 步骤7:路径按字母排序
 - 第0条的text属性 @alpha
 - 第1条的text属性 @beta
 - 第2条的text属性 @zebra
- 步骤8:父节点禁用状态第0条的disabled属性 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$repoTest = new repoZenTest();

// 4. 测试步骤1: 空路径列表
r($repoTest->buildRepoTreeTest(array(), '0')) && p() && e('0'); // 步骤1:空路径列表

// 5. 测试步骤2: 单个根路径
$pathList1 = array(
    'repo1' => array('value' => 1, 'parent' => '0', 'path' => 'repo1', 'text' => 'repo1')
);
r($repoTest->buildRepoTreeTest($pathList1, '0')) && p('0:text') && e('repo1'); // 步骤2:单个根路径

// 6. 测试步骤3: 单层路径结构(两个并列仓库)
$pathList2 = array(
    'repo1' => array('value' => 1, 'parent' => '0', 'path' => 'repo1', 'text' => 'repo1'),
    'repo2' => array('value' => 2, 'parent' => '0', 'path' => 'repo2', 'text' => 'repo2')
);
r($repoTest->buildRepoTreeTest($pathList2, '0')) && p('0:text;1:text') && e('repo1,repo2'); // 步骤3:单层路径结构

// 7. 测试步骤4: 两层嵌套路径结构
$pathList3 = array(
    'group' => array('value' => 1, 'parent' => '0', 'path' => 'group', 'text' => 'group'),
    'group/repo1' => array('value' => 2, 'parent' => 'group', 'path' => 'group/repo1', 'text' => 'repo1')
);
r($repoTest->buildRepoTreeTest($pathList3, '0')) && p('0:text') && e('group'); // 步骤4:两层嵌套路径

// 8. 测试步骤5: 三层嵌套路径结构
$pathList4 = array(
    'company' => array('value' => 1, 'parent' => '0', 'path' => 'company', 'text' => 'company'),
    'company/team' => array('value' => 2, 'parent' => 'company', 'path' => 'company/team', 'text' => 'team'),
    'company/team/repo1' => array('value' => 3, 'parent' => 'company/team', 'path' => 'company/team/repo1', 'text' => 'repo1')
);
r($repoTest->buildRepoTreeTest($pathList4, '0')) && p('0:text') && e('company'); // 步骤5:三层嵌套路径

// 9. 测试步骤6: 多个并列分支的复杂路径
$pathList5 = array(
    'frontend' => array('value' => 1, 'parent' => '0', 'path' => 'frontend', 'text' => 'frontend'),
    'frontend/web' => array('value' => 2, 'parent' => 'frontend', 'path' => 'frontend/web', 'text' => 'web'),
    'backend' => array('value' => 3, 'parent' => '0', 'path' => 'backend', 'text' => 'backend'),
    'backend/api' => array('value' => 4, 'parent' => 'backend', 'path' => 'backend/api', 'text' => 'api')
);
r($repoTest->buildRepoTreeTest($pathList5, '0')) && p('0:text;1:text') && e('backend,frontend'); // 步骤6:多个并列分支

// 10. 测试步骤7: 路径按字母顺序排序
$pathList6 = array(
    'zebra' => array('value' => 1, 'parent' => '0', 'path' => 'zebra', 'text' => 'zebra'),
    'alpha' => array('value' => 2, 'parent' => '0', 'path' => 'alpha', 'text' => 'alpha'),
    'beta' => array('value' => 3, 'parent' => '0', 'path' => 'beta', 'text' => 'beta')
);
r($repoTest->buildRepoTreeTest($pathList6, '0')) && p('0:text;1:text;2:text') && e('alpha,beta,zebra'); // 步骤7:路径按字母排序

// 11. 测试步骤8: 父节点禁用状态
$pathList7 = array(
    'parent' => array('value' => 1, 'parent' => '0', 'path' => 'parent', 'text' => 'parent'),
    'parent/child' => array('value' => 2, 'parent' => 'parent', 'path' => 'parent/child', 'text' => 'child')
);
r($repoTest->buildRepoTreeTest($pathList7, '0')) && p('0:disabled') && e('1'); // 步骤8:父节点禁用状态