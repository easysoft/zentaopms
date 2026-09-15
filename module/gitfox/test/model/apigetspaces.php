#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSpaces();
timeout=0
cid=0

- 步骤 1：正常输入：查询空间列表不产生 dao 错误 @0
- 步骤 2：正常输入：返回值类型符合 object|bool 声明 @valid
- 步骤 3：边界值输入：默认页码为 1 @1
- 步骤 4：边界值输入：自定义分页大小时 pageSize 为 5 @5
- 步骤 5：业务规则验证：自定义分页时返回值类型仍符合声明 @valid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
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

su('admin');

$gitfoxTest = new gitfoxModelTest();
$customPager = (object)array('pageID' => 1, 'recPerPage' => 5);

r($gitfoxTest->apiGetSpacesErrorTest(array())) && p() && e('0'); // 步骤 1：正常输入：查询空间列表不产生 dao 错误
r($gitfoxTest->apiGetSpacesReturnTypeTest(array())) && p() && e('valid'); // 步骤 2：正常输入：返回值类型符合 object|bool 声明
r($gitfoxTest->apiGetSpacesPagerTest(array(), null, 'page', 1)) && p() && e('1'); // 步骤 3：边界值输入：默认页码为 1
r($gitfoxTest->apiGetSpacesPagerTest(array(), $customPager, 'pageSize', 5)) && p() && e('5'); // 步骤 4：边界值输入：自定义分页大小时 pageSize 为 5
r($gitfoxTest->apiGetSpacesReturnTypeTest(array(), $customPager)) && p() && e('valid'); // 步骤 5：业务规则验证：自定义分页时返回值类型仍符合声明
