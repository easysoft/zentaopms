#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getPrivApis();
timeout=0
cid=15111

- 执行apiTest模块的getPrivApisTest方法  @15
- 执行apiTest模块的getPrivApisTest方法，参数是'all'  @20
- 执行apiTest模块的getPrivApisTest方法，参数是'' 第1条的id属性 @1
- 执行apiTest模块的getPrivApisTest方法，参数是'invalid'  @15
- 执行apiTest模块的getPrivApisTest方法，参数是'all' 第20条的id属性 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->type->range('api{5},custom{3},project{2}');
$doclib->vision->range('rnd{8},lite{2}');
$doclib->name->range('API文档库1,API文档库2,API文档库3,API文档库4,API文档库5,自定义文档库1,自定义文档库2,自定义文档库3,项目文档库1,项目文档库2');
$doclib->acl->range('open{3},private{4},custom{3}');
$doclib->gen(10, true, false);

$api = zenData('api');
$api->id->range('1-20');
$api->lib->range('1-5{4}');
$api->title->range('用户登录API,获取用户信息API,创建用户API,删除用户API');
$api->deleted->range('0{15},1{5}');
$api->gen(20, true, false);

su('admin');

$apiTest = new apiModelTest();

r(count($apiTest->getPrivApisTest())) && p() && e('15');
r(count($apiTest->getPrivApisTest('all'))) && p() && e('20');
r($apiTest->getPrivApisTest('')) && p('1:id') && e('1');
r(count($apiTest->getPrivApisTest('invalid'))) && p() && e('15');
r($apiTest->getPrivApisTest('all')) && p('20:id') && e('20');
