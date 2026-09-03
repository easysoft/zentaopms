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

title=测试 codescanModel->getRuleset();
timeout=0
cid=0

- 获取第一个规则集详情
- 获取第二个规则集详情
- 获取 0 号规则集详情失败 @0
- 获取不存在的规则集详情失败 @0
- 获取另一个不存在的规则集详情失败 @0

*/

$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$nameA = "codescan-get-ruleset-{$runID}-a";
$nameB = "codescan-get-ruleset-{$runID}-b";
$rulesetAID = $test->createRulesetTest((object)array('name' => $nameA, 'isCustom' => true));
$rulesetBID = $test->createRulesetTest((object)array('name' => $nameB, 'isCustom' => true));

r(is_object($result = $test->getRulesetTest($rulesetAID)) && $result->name === $nameA && $result->status === 'enabled') && p() && e('1');
r(is_object($result = $test->getRulesetTest($rulesetBID)) && $result->name === $nameB && $result->status === 'enabled') && p() && e('1');
r($test->getRulesetTest(0)) && p() && e('0');
r($test->getRulesetTest(999999999)) && p() && e('0');
r($test->getRulesetTest(1000000000)) && p() && e('0');

dao::$errors = array();
$test->deleteRulesetTest($rulesetAID);
dao::$errors = array();
$test->deleteRulesetTest($rulesetBID);
