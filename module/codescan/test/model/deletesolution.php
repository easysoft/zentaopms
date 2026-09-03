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

/**

title=测试 codescanModel->deleteSolution();
timeout=0
cid=0

- 删除第一个方案成功 @1
- 删除第二个方案成功 @1
- 删除不存在的方案失败 @0
- 删除另一个不存在的方案失败 @0
- 删除 0 号方案失败 @0

*/

su('admin');
$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$rulesetID = $test->createRulesetTest((object)array('name' => "codescan-delete-solution-ruleset-{$runID}", 'isCustom' => true));
$solutionAID = $test->createSolutionTest((object)array('name' => "codescan-delete-solution-{$runID}-a", 'rulesets' => array($rulesetID), 'status' => 'enabled'));
$solutionBID = $test->createSolutionTest((object)array('name' => "codescan-delete-solution-{$runID}-b", 'rulesets' => array($rulesetID), 'status' => 'disabled'));
$missingID = 999999999;

r($test->deleteSolutionTest($solutionAID)) && p() && e('1');
r($test->deleteSolutionTest($solutionBID)) && p() && e('1');
r($test->deleteSolutionTest($missingID)) && p() && e('0');
r($test->deleteSolutionTest($missingID + 1)) && p() && e('0');
r($test->deleteSolutionTest(0)) && p() && e('0');

dao::$errors = array();
$test->deleteRulesetTest($rulesetID);
