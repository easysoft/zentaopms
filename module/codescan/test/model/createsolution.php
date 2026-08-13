#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->createSolution();
timeout=0
cid=0

- 创建绑定一个规则集的启用方案 @1
- 创建绑定两个规则集的禁用方案 @1
- 空规则集创建方案失败 @0
- 空对象创建方案失败 @0
- 绑定不存在规则集的方案失败 @0

*/

su('admin');
$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();

dao::$errors = array();
$rulesetA = $test->createRulesetTest((object)array('name' => "codescan-unit-ruleset-{$runID}-a", 'isCustom' => true));
dao::$errors = array();
$rulesetB = $test->createRulesetTest((object)array('name' => "codescan-unit-ruleset-{$runID}-b", 'isCustom' => true));

$rulesetAID = is_int($rulesetA) ? $rulesetA : 0;
$rulesetBID = is_int($rulesetB) ? $rulesetB : 0;

$solutionA = (object)array('name' => "codescan-unit-solution-{$runID}-a", 'rulesets' => array($rulesetAID),        'status' => 'enabled',  'desc' => 'link one ruleset', 'createdBy' => 'admin');
$solutionB = (object)array('name' => "codescan-unit-solution-{$runID}-b", 'rulesets' => array($rulesetAID, $rulesetBID), 'status' => 'disabled', 'desc' => 'disabled solution', 'createdBy' => 'admin');
$solutionC = (object)array('name' => '', 'rulesets' => array(), 'status' => 'enabled', 'desc' => '');
$solutionD = (object)array('name' => '', 'rulesets' => array(), 'status' => 'enabled', 'desc' => '');
$solutionE = (object)array('name' => '', 'rulesets' => array(999999), 'status' => 'enabled', 'desc' => 'missing ruleset');

r(is_int($solutionAIDResult = $test->createSolutionTest($solutionA)) && $solutionAIDResult > 0) && p() && e('1');
r(is_int($solutionBIDResult = $test->createSolutionTest($solutionB)) && $solutionBIDResult > 0) && p() && e('1');
r($test->createSolutionTest($solutionC)) && p() && e('0');
r($test->createSolutionTest($solutionD)) && p() && e('0');
r($test->createSolutionTest($solutionE)) && p() && e('0');

foreach(array($solutionAIDResult, $solutionBIDResult) as $solutionID)
{
    if(!is_int($solutionID) || $solutionID <= 0) continue;
    dao::$errors = array();
    $test->deleteSolutionTest($solutionID);
}

foreach(array($rulesetAID, $rulesetBID) as $rulesetID)
{
    if($rulesetID <= 0) continue;
    dao::$errors = array();
    $test->deleteRulesetTest($rulesetID);
}
