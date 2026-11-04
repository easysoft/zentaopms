#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getMainNavList();
timeout=0
cid=15674

- 步骤1：测试product模块导航列表获取第0条的group属性 @my
- 步骤2：测试使用默认菜单参数第0条的group属性 @my
- 步骤3：测试空模块名参数第0条的group属性 @my
- 步骤4：测试my模块导航列表获取，验证active状态第0条的active属性 @1
- 步骤5：测试project模块导航列表获取第0条的group属性 @my
- 步骤6：仅有代码库浏览权限时，问题导航不应显示 @0
- 步骤7：有评审问题权限时，问题导航指向评审问题 @repo-review
- 步骤8：有扫描问题权限时，问题导航指向扫描问题 @codescan-issue
- 步骤9：代码库导航仍按代码库权限回退显示 @repo-browse
- 步骤10：管理员的问题导航正常显示 @repo-review

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 设置应用程序上下文
global $app;
if(empty($app->control))
{
    $app->control = $tester;
}
if(empty($app->user))
{
    $app->user = (object)array('account' => 'admin', 'rights' => array());
}

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->getMainNavListTest('product', false)) && p('0:group') && e('my'); // 步骤1：测试product模块导航列表获取
r($commonTest->getMainNavListTest('product', true)) && p('0:group') && e('my'); // 步骤2：测试使用默认菜单参数
r($commonTest->getMainNavListTest('', false)) && p('0:group') && e('my'); // 步骤3：测试空模块名参数
r($commonTest->getMainNavListTest('my', false)) && p('0:active') && e('1'); // 步骤4：测试my模块导航列表获取，验证active状态
r($commonTest->getMainNavListTest('project', false)) && p('0:group') && e('my'); // 步骤5：测试project模块导航列表获取

// 6. 准备问题导航显隐相关数据：user1只有代码库浏览权限，user2有评审问题权限，user3有扫描问题权限。
global $config;
zenData('company')->gen(1);
zenData('user')->gen(4);

$group = zenData('group');
$group->project->range('0');
$group->vision->range($config->vision);
$group->gen(3);

$usergroup = zenData('usergroup');
$usergroup->account->range('user1,user2,user3');
$usergroup->group->range('1,2,3');
$usergroup->gen(3);

$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1,2,3');
$grouppriv->module->range('repo,repo,codescan');
$grouppriv->method->range('browse,review,issue');
$grouppriv->gen(3);

su('user1');
r($commonTest->getMainNavItemTest('repo', 'issue')) && p() && e('0'); // 步骤6：仅有代码库浏览权限时，问题导航不应显示
su('user2');
r($commonTest->getMainNavItemTest('repo', 'issue')) && p() && e('repo-review'); // 步骤7：有评审问题权限时，问题导航指向评审问题
su('user3');
r($commonTest->getMainNavItemTest('repo', 'issue')) && p() && e('codescan-issue'); // 步骤8：有扫描问题权限时，问题导航指向扫描问题
su('user1');
r($commonTest->getMainNavItemTest('repo', 'repo')) && p() && e('repo-browse'); // 步骤9：代码库导航仍按代码库权限回退显示
su('admin');
r($commonTest->getMainNavItemTest('repo', 'issue')) && p() && e('repo-review'); // 步骤10：管理员的问题导航正常显示
