#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processStepForExport();
timeout=0
cid=19105

- 执行testcaseTest模块的processStepForExportTest方法 属性real @1. 实际结果1
- 执行testcaseTest模块的processStepForExportTest方法 属性real @1.
- 执行testcaseTest模块的processStepForExportTest方法 属性stepDesc @1. 步骤3
- 执行testcaseTest模块的processStepForExportTest方法 属性stepDesc @1. 包含""双引号""的步骤
- 执行testcaseTest模块的processStepForExportTest方法 属性stepDesc @1. 步骤描述<br />

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->processStepForExportTest((object)array('id' => 1), array(1 => array('real' => '实际结果1')), array(1 => array((object)array('id' => 1, 'desc' => '步骤1', 'expect' => '期望1', 'parent' => 0))))) && p('real') && e('1. 实际结果1');
r($testcaseTest->processStepForExportTest((object)array('id' => 2), array(), array(2 => array((object)array('id' => 2, 'desc' => '步骤2', 'expect' => '期望2', 'parent' => 0))))) && p('real') && e('1.');
r($testcaseTest->processStepForExportTest((object)array('id' => 3), array(), array(3 => array((object)array('id' => 3, 'desc' => '步骤3', 'expect' => '期望3', 'parent' => 0))))) && p('stepDesc') && e('1. 步骤3');
r($testcaseTest->processStepForExportTest((object)array('id' => 4), array(), array(4 => array((object)array('id' => 4, 'desc' => '包含"双引号"的步骤', 'expect' => '包含"双引号"的期望', 'parent' => 0))), 'csv')) && p('stepDesc') && e('1. 包含""双引号""的步骤');
r($testcaseTest->processStepForExportTest((object)array('id' => 5), array(), array(5 => array((object)array('id' => 5, 'desc' => '步骤描述', 'expect' => '期望结果', 'parent' => 0))), 'html')) && p('stepDesc') && e('1. 步骤描述<br />');