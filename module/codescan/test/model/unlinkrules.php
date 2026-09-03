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

title=测试 codescanModel->unlinkRules();
timeout=0
cid=0

- 规则集 1 解绑三条规则成功 @1
- 规则集 2 解绑空规则列表成功 @1
- 规则集 0 解绑规则失败 @0
- 规则集 1 解绑另一组规则成功 @1
- 规则集 2 解绑两条规则成功 @1

*/

$test = new codescanModelTest();

r($test->unlinkRulesTest(1, array(1, 2, 3))) && p() && e('1');
r($test->unlinkRulesTest(2, array())) && p() && e('1');
r($test->unlinkRulesTest(0, array(1))) && p() && e('0');
r($test->unlinkRulesTest(1, array(4, 5, 6))) && p() && e('1');
r($test->unlinkRulesTest(2, array(7, 8))) && p() && e('1');
