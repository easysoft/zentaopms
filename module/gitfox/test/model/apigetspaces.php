#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSpaces();
timeout=0
cid=0

- 步骤 1：apiGetSpaces 不产生 dao 错误 @0
- 步骤 2：apiGetSpaces 默认返回值类型为 object @object
- 步骤 3：apiGetSpaces 默认页码为 1 @1
- 步骤 4：自定义分页大小时 pageSize 为 5 @5
- 步骤 5：自定义分页时返回值类型仍为 object @object

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

r($gitfoxTest->apiGetSpacesErrorTest(array())) && p() && e('0');
r($gitfoxTest->apiGetSpacesTypeTest(array())) && p() && e('object');
r($gitfoxTest->apiGetSpacesTest(array())) && p('pager:page') && e('1');
r($gitfoxTest->apiGetSpacesTest(array(), $customPager)) && p('pager:pageSize') && e('5');
r($gitfoxTest->apiGetSpacesTypeTest(array(), $customPager)) && p() && e('object');
