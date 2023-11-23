#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('user');
su('admin');

/**

title=测试 customModel->setConcept();
timeout=0
cid=1

*/

$sprintConcept = array('0', '1');

$customTester = new customTest();
r($customTester->setConceptTest($sprintConcept[0])) && p() && e('0'); //测试sprintConcept值为0，编辑为项目 - 产品 - 迭代
r($customTester->setConceptTest($sprintConcept[1])) && p() && e('1'); //测试sprintConcept值为1，编辑为项目 - 产品 - 冲刺
