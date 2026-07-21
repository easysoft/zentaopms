#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::parseCreateCheckMsg();
timeout=0
cid=0

- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是false, $mergeRuleResult, 'feature/demo', 'release/main') === '' ? 1 : 0  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是$mergeCheck, $mergeRuleResult, 'feature/demo', 'release/main'), '检测到代码冲突') !== false ? 1 : 0  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是$mergeCheck, array  @1
- 执行parseCreateCheckMsgTest($mergeCheck, $bothFail, 'feature/demo', 'hotfix/main'), '代码冲突') !== false && strpos($ppmZen模块的parseCreateCheckMsgTest方法，参数是$mergeCheck, $bothFail, 'feature/demo', 'hotfix/main'), '分支类型') !== false ? 1 : 0  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是$mergeCheck, $allPass, 'feature/demo', 'release/main'), '检测到代码冲突') !== false ? 1 : 0  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是true, $mergeRuleResult, 'feature/demo', 'release/main'), '分支类型') !== false ? 1 : 0  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法  @1
- 执行ppmZen模块的parseCreateCheckMsgTest方法，参数是false, $mergeRuleResult, 'nobranch', 'release/main') === '' ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$ppmZen = new ppmZenTest();

$mergeRuleResult = array(
    'feature/demo' => array('result' => false, 'branchType' => array('Release')),
    'release/main' => array('result' => true,  'branchType' => array()),
    'hotfix/main'  => array('result' => false, 'branchType' => array('Feature')),
);
$mergeCheck = (object)array('message' => '存在差异', 'conflictFiles' => array('a.php'));

r($ppmZen->parseCreateCheckMsgTest(false, $mergeRuleResult, 'feature/demo', 'release/main') === '' ? 1 : 0) && p() && e('1');
r(strpos($ppmZen->parseCreateCheckMsgTest($mergeCheck, $mergeRuleResult, 'feature/demo', 'release/main'), '检测到代码冲突') !== false ? 1 : 0) && p() && e('1');
r(strpos($ppmZen->parseCreateCheckMsgTest((object)array('message' => ''), $mergeRuleResult, 'release/main', 'hotfix/main'), '目标分支允许合并的源分支类型') !== false ? 1 : 0) && p() && e('1');
r(strpos($ppmZen->parseCreateCheckMsgTest((object)array('message' => ''), $mergeRuleResult, 'feature/demo', 'release/main'), '源分支允许合并到的目标分支类型') !== false ? 1 : 0) && p() && e('1');
r($ppmZen->parseCreateCheckMsgTest($mergeCheck, array(), 'feature/demo', 'release/main') === '' ? 1 : 0) && p() && e('1');
$bothFail = array('feature/demo' => array('result' => false, 'branchType' => array('Release')), 'hotfix/main' => array('result' => false, 'branchType' => array('Feature')));
r(strpos($ppmZen->parseCreateCheckMsgTest($mergeCheck, $bothFail, 'feature/demo', 'hotfix/main'), '代码冲突') !== false && strpos($ppmZen->parseCreateCheckMsgTest($mergeCheck, $bothFail, 'feature/demo', 'hotfix/main'), '分支类型') !== false ? 1 : 0) && p() && e('1');
$allPass = array('feature/demo' => array('result' => true, 'branchType' => array()), 'release/main' => array('result' => true, 'branchType' => array()));
r(strpos($ppmZen->parseCreateCheckMsgTest($mergeCheck, $allPass, 'feature/demo', 'release/main'), '检测到代码冲突') !== false ? 1 : 0) && p() && e('1');
r(strpos($ppmZen->parseCreateCheckMsgTest(true, $mergeRuleResult, 'feature/demo', 'release/main'), '分支类型') !== false ? 1 : 0) && p() && e('1');
r(strpos($ppmZen->parseCreateCheckMsgTest((object)array('message' => '', 'conflictFiles' => array()), $mergeRuleResult, 'feature/demo', 'release/main'), '分支类型') !== false ? 1 : 0) && p() && e('1');
r($ppmZen->parseCreateCheckMsgTest(false, $mergeRuleResult, 'nobranch', 'release/main') === '' ? 1 : 0) && p() && e('1');