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

title=测试 codescanModel->createRuleset();
timeout=0
cid=0

- 创建第一个自定义规则集 @1
- 空对象创建规则集失败 @0
- 只传描述创建规则集失败 @0
- 名称为空创建规则集失败 @0
- 创建第二个自定义规则集 @1

*/

$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$nameA = "codescan-create-ruleset-{$runID}-a";
$nameB = "codescan-create-ruleset-{$runID}-b";
$rulesetA = (object)array('name' => $nameA, 'isCustom' => true);
$rulesetB = (object)array('desc' => 'only desc');
$rulesetC = (object)array('name' => '');
$rulesetD = (object)array('name' => $nameB, 'isCustom' => true);

r(is_int($rulesetAID = $test->createRulesetTest($rulesetA)) && $rulesetAID > 0) && p() && e('1');
r($test->createRulesetTest(new stdclass())) && p() && e('0');
r($test->createRulesetTest($rulesetB)) && p() && e('0');
r($test->createRulesetTest($rulesetC)) && p() && e('0');
r(is_int($rulesetBID = $test->createRulesetTest($rulesetD)) && $rulesetBID > 0) && p() && e('1');

foreach(array($rulesetAID, $rulesetBID) as $rulesetID)
{
    if(!is_int($rulesetID) || $rulesetID <= 0) continue;
    dao::$errors = array();
    $test->deleteRulesetTest($rulesetID);
}
