#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->setConcept();
cid=1
pid=1

测试sprintConcept值为0，编辑为产品 - 项目 >> 45
测试sprintConcept值为1，编辑为产品 - 迭代 >> 45
测试sprintConcept值为2，编辑为产品 - 冲刺 >> 45

*/
$sprintConcept = array('0', '1', '2');

$custom = new customTest();

r($custom->setConceptTest($sprintConcept[0])) && p('id') && e('45');  //测试sprintConcept值为0，编辑为产品 - 项目
r($custom->setConceptTest($sprintConcept[1])) && p('id') && e('45');  //测试sprintConcept值为1，编辑为产品 - 迭代
r($custom->setConceptTest($sprintConcept[2])) && p('id') && e('45');  //测试sprintConcept值为2，编辑为产品 - 冲刺
