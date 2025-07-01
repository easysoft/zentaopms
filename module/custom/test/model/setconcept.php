#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('user');
su('admin');

/**

title=测试 customModel->setConcept();
timeout=0
cid=1

- 测试sprintConcept值为1，编辑为项目 - 产品 - 冲刺 @1
- 测试sprintConcept值为0，编辑为项目 - 产品 - 迭代 @0
- 测试sprintConcept值为2，编辑为项目 - 产品 - 迭代 @2
- 测试sprintConcept值为3，编辑为项目 - 产品 - 迭代 @0
- 测试sprintConcept值为4，编辑为项目 - 产品 - 迭代 @0

*/

$sprintConcept = array('0', '1', '2', '3', '4');

$customTester = new customTest();
r($customTester->setConceptTest($sprintConcept[1])) && p() && e('1'); //测试sprintConcept值为1，编辑为项目 - 产品 - 冲刺
r($customTester->setConceptTest($sprintConcept[0])) && p() && e('0'); //测试sprintConcept值为0，编辑为项目 - 产品 - 迭代
r($customTester->setConceptTest($sprintConcept[2])) && p() && e('2'); //测试sprintConcept值为2，编辑为项目 - 产品 - 迭代
r($customTester->setConceptTest($sprintConcept[3])) && p() && e('0'); //测试sprintConcept值为3，编辑为项目 - 产品 - 迭代
r($customTester->setConceptTest($sprintConcept[4])) && p() && e('0'); //测试sprintConcept值为4，编辑为项目 - 产品 - 迭代
