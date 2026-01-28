#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoListByUrl();
timeout=0
cid=18077

- 执行repoTest模块的getRepoListByUrlTest方法，参数是'' 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是'http://invalid-server.example.com/repo.git' 属性message @No matched gitlab.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是'http://unknown-server.com/project/repo.git' 属性message @No matched gitlab.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是$nullUrl 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是'http://test.com/项目/仓库.git' 属性message @No matched gitlab.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('pipeline')->loadYaml('pipeline_getrepolistbyurl')->gen(3);
zenData('repo')->loadYaml('repo_getrepolistbyurl')->gen(8);

// 登录管理员用户
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：空URL输入测试
r($repoTest->getRepoListByUrlTest('')) && p('message') && e('Url is empty.');

// 测试步骤2：格式正确但不匹配的URL测试
r($repoTest->getRepoListByUrlTest('http://invalid-server.example.com/repo.git')) && p('message') && e('No matched gitlab.');

// 测试步骤3：有效URL但无匹配gitlab服务器测试
r($repoTest->getRepoListByUrlTest('http://unknown-server.com/project/repo.git')) && p('message') && e('No matched gitlab.');

// 测试步骤4：NULL类型URL处理（转换为空字符串）
$nullUrl = null;
r($repoTest->getRepoListByUrlTest($nullUrl)) && p('message') && e('Url is empty.');

// 测试步骤5：特殊字符URL处理测试
r($repoTest->getRepoListByUrlTest('http://test.com/项目/仓库.git')) && p('message') && e('No matched gitlab.');