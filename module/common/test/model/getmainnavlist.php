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

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

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
$commonTest = new commonTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->getMainNavListTest('product', false)) && p('0:group') && e('my'); // 步骤1：测试product模块导航列表获取
r($commonTest->getMainNavListTest('product', true)) && p('0:group') && e('my'); // 步骤2：测试使用默认菜单参数
r($commonTest->getMainNavListTest('', false)) && p('0:group') && e('my'); // 步骤3：测试空模块名参数
r($commonTest->getMainNavListTest('my', false)) && p('0:active') && e('1'); // 步骤4：测试my模块导航列表获取，验证active状态
r($commonTest->getMainNavListTest('project', false)) && p('0:group') && e('my'); // 步骤5：测试project模块导航列表获取