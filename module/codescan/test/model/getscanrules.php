#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
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

/**

title=测试 codescanModel->getScanRules();
timeout=0
cid=0

- 空参数查询规则失败 @0
- 按 ID 查询规则列表返回空分页 @0,1,30
- 按页码查询规则列表返回空分页 @0,1,30
- 按 limit 查询规则列表返回空分页 @0,1,30
- 按排序查询规则列表返回空分页 @0,1,30

*/

$test = new codescanModelTest();

r($test->getscanrulesTest(array())) && p() && e('0');
r($test->getScanRulesTest(array('id' => 1))) && p('pager:total,page,pageSize') && e('0,1,30');
r($test->getScanRulesTest(array('page' => 1))) && p('pager:total,page,pageSize') && e('0,1,30');
r($test->getScanRulesTest(array('limit' => 10))) && p('pager:total,page,pageSize') && e('0,1,30');
r($test->getScanRulesTest(array('sort' => 'id'))) && p('pager:total,page,pageSize') && e('0,1,30');
