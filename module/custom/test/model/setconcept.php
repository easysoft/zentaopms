#!/usr/bin/env php
<?php

/**

title=测试 customModel::setConcept();
timeout=0
cid=15924

- 测试sprintConcept值为1，设置为项目产品冲刺模式 @1
- 测试sprintConcept值为0，设置为项目产品迭代模式 @1
- 测试sprintConcept值为2，设置为有效值2 @1
- 测试sprintConcept值为无效字符串 @1
- 测试sprintConcept值为空字符串 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

$customTester = new customTest();
r($customTester->setConceptTest('1')) && p() && e('1');  // 测试sprintConcept值为1，设置为项目产品冲刺模式
r($customTester->setConceptTest('0')) && p() && e('1');  // 测试sprintConcept值为0，设置为项目产品迭代模式
r($customTester->setConceptTest('2')) && p() && e('1');  // 测试sprintConcept值为2，设置为有效值2
r($customTester->setConceptTest('abc')) && p() && e('1');  // 测试sprintConcept值为无效字符串
r($customTester->setConceptTest('')) && p() && e('1');  // 测试sprintConcept值为空字符串