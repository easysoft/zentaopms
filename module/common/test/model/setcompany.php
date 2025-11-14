#!/usr/bin/env php
<?php

/**

title=测试 commonModel::setCompany();
timeout=0
cid=15710

- 步骤1：测试设置的公司名称 @易软天创网络科技有限公司
- 步骤2：测试设置的公司ID @1
- 步骤3：测试设置的公司电话 @15666933065
- 步骤4：测试设置的guest属性 @0
- 步骤5：测试设置的网站属性 @www.zentao.net
- 步骤6：测试设置的邮政编码 @100000
- 步骤7：测试设置的管理员列表 @,admin,

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';

// 2. zendata数据准备
zenData('company')->gen(1);

// 3. 用户登录
su('admin');

// 4. 执行setCompany方法
global $tester, $app;
$tester->loadModel('common')->setCompany();

// 5. 测试步骤：7个测试步骤，提升覆盖率
r($app->company->name) && p('') && e('易软天创网络科技有限公司'); // 步骤1：测试设置的公司名称
r($app->company->id) && p('') && e('1'); // 步骤2：测试设置的公司ID
r($app->company->phone) && p('') && e('15666933065'); // 步骤3：测试设置的公司电话
r($app->company->guest) && p('') && e('0'); // 步骤4：测试设置的guest属性
r($app->company->website) && p('') && e('www.zentao.net'); // 步骤5：测试设置的网站属性
r($app->company->zipcode) && p('') && e('100000'); // 步骤6：测试设置的邮政编码
r($app->company->admins) && p('') && e(',admin,'); // 步骤7：测试设置的管理员列表