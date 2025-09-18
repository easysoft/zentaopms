#!/usr/bin/env php
<?php

/**

title=测试 repoZen::setBackSession();
timeout=0
cid=0

- 步骤1：默认参数属性repoList @repo-browse-1.html
- 步骤2：指定type属性repoView @repo-browse-1.html
- 步骤3：withOtherModule
 - 属性bugList @repo-browse-1.html
 - 属性taskList @repo-browse-1.html
- 步骤4：清除repoView属性repoView @~~
- 步骤5：PATH_INFO模式属性repoList @repo-browse-1.html?param=test

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_setbacksession.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->setBackSessionTest('list', false)) && p('repoList') && e('repo-browse-1.html'); // 步骤1：默认参数
r($repoTest->setBackSessionTest('view', false)) && p('repoView') && e('repo-browse-1.html'); // 步骤2：指定type
r($repoTest->setBackSessionTest('list', true)) && p('bugList,taskList') && e('repo-browse-1.html,repo-browse-1.html'); // 步骤3：withOtherModule
r($repoTest->setBackSessionTest('list', false, true)) && p('repoView') && e('~~'); // 步骤4：清除repoView
r($repoTest->setBackSessionTest('list', false, false, 'PATH_INFO')) && p('repoList') && e('repo-browse-1.html?param=test'); // 步骤5：PATH_INFO模式