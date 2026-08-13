#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
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
