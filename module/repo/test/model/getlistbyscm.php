#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListBySCM();
timeout=0
cid=18072

- 执行$gitResults第0条的SCM属性 @Git
- 执行$gitlabResults第0条的SCM属性 @Gitlab
- 执行repoTest模块的getListBySCMTest方法，参数是'Git, Gitlab'  @4
- 执行repoTest模块的getListBySCMTest方法，参数是'NotExist'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Subversion'  @2
- 执行repoTest模块的getListBySCMTest方法，参数是'Git, Gitlab, Subversion, Gogs', 'haspriv'  @8
- 执行repoTest模块的getListBySCMTest方法，参数是'Git'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('repo');
$table->id->range('1-12');
$table->product->range('1{3},2{3},3{3},4{3}');
$table->name->range('GitRepo{2},GitlabRepo{2},SvnRepo{2},GogsRepo{2},TestRepo{4}');
$table->path->range('/repo/git{2},/repo/gitlab{2},/repo/svn{2},/repo/gogs{2},/repo/test{4}');
$table->SCM->range('Git{2},Gitlab{2},Subversion{2},Gogs{2},Unknown{4}');
$table->account->range('admin{8},user{4}');
$table->password->range('123456{8},dGVzdDEyMw=={4}');
$table->encrypt->range('plain{8},base64{4}');
$table->acl->range('{"acl":"open","users":[],"groups":[]}{4},{"acl":"private","users":["admin"],"groups":[]}{4},{"acl":"custom","users":["admin","user"],"groups":["1"]}{4}');
$table->synced->range('1{10},0{2}');
$table->deleted->range('0{10},1{2}');
$table->gen(12);

// 使用管理员身份登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：查询Git类型代码库 - 检查第一个记录的SCM类型
$gitResults = $repoTest->getListBySCMTest('Git');
r(array_values($gitResults)) && p('0:SCM') && e('Git');

// 测试步骤2：查询Gitlab类型代码库 - 检查第一个记录的SCM类型
$gitlabResults = $repoTest->getListBySCMTest('Gitlab');
r(array_values($gitlabResults)) && p('0:SCM') && e('Gitlab');

// 测试步骤3：查询多种SCM类型代码库 - 验证返回数组数量
r(count($repoTest->getListBySCMTest('Git,Gitlab'))) && p() && e(4);

// 测试步骤4：查询不存在的有效SCM类型 - 期望返回empty
r($repoTest->getListBySCMTest('NotExist')) && p() && e('empty');

// 测试步骤5：查询Subversion类型代码库 - 验证返回数组数量
r(count($repoTest->getListBySCMTest('Subversion'))) && p() && e(2);

// 测试步骤6：测试权限过滤功能 - 使用haspriv参数验证有权限的记录数量
r(count($repoTest->getListBySCMTest('Git,Gitlab,Subversion,Gogs', 'haspriv'))) && p() && e(8);

// 测试步骤7：检查返回结果是否为数组类型 - 验证方法返回数组格式
r(is_array($repoTest->getListBySCMTest('Git'))) && p() && e('1');