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

title=测试 codescanModel->getScanRulesets();
timeout=0
cid=0

- 空参数查询规则集失败 @0
- 按 ID 查询规则集
- 按页码查询规则集列表
- 按 limit 查询规则集分页信息
- 按排序查询规则集列表

*/

$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$prefix = "codescan-list-ruleset-{$runID}";
$nameA = "{$prefix}-a";
$nameB = "{$prefix}-b";
$rulesetAID = $test->createRulesetTest((object)array('name' => $nameA, 'isCustom' => true));
$rulesetBID = $test->createRulesetTest((object)array('name' => $nameB, 'isCustom' => true));

r($test->getscanrulesetsTest(array())) && p() && e('0');
r(is_object($result = $test->getScanRulesetsTest(array('id' => $rulesetAID, 'name' => $prefix))) && isset($result->data[0]) && $result->data[0]->name === $nameA && $result->data[0]->status === 'enabled' && $result->pager->total === 1 && $result->pager->page === 1 && $result->pager->pageSize === 30) && p() && e('1');
r(is_object($result = $test->getScanRulesetsTest(array('name' => $prefix, 'page' => 1))) && count($result->data) === 2 && $result->data[0]->name === $nameA && $result->data[0]->status === 'enabled' && $result->data[1]->name === $nameB && $result->data[1]->status === 'enabled') && p() && e('1');
r(is_object($result = $test->getScanRulesetsTest(array('name' => $prefix, 'limit' => 10))) && $result->pager->total === 2 && $result->pager->page === 1 && $result->pager->pageSize === 30) && p() && e('1');
r(is_object($result = $test->getScanRulesetsTest(array('name' => $prefix, 'sort' => 'id'))) && count($result->data) === 2 && $result->data[0]->name === $nameA && $result->data[0]->status === 'enabled' && $result->data[1]->name === $nameB && $result->data[1]->status === 'enabled') && p() && e('1');

dao::$errors = array();
$test->deleteRulesetTest($rulesetAID);
dao::$errors = array();
$test->deleteRulesetTest($rulesetBID);
