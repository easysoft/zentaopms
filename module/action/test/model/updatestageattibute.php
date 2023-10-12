#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('project')->gen(5);

/**

title=测试 actionModel->read();
cid=1
pid=1

测试attributeList为0,executionID为0          >> 0
测试attributeList为1,executionID为1          >> 1
测试attributeList为attribute,executionID为2  >> 3,attribute;4,attribute

*/

$attributeList = array(0, 1, 'attribute');
$executionID   = array(array(1), array(2), array(3, 4));

$action = new actionTest();

r($action->updateStageAttributeTest($attributeList[0], $executionID[0])) && p('0:id,attribute') && e('1,0');                                    //测试attributeList为0,executionID为0
r($action->updateStageAttributeTest($attributeList[1], $executionID[1])) && p('0:id,attribute') && e('2,1');                                    //测试attributeList为1,executionID为1
r($action->updateStageAttributeTest($attributeList[2], $executionID[2])) && p('0:id,attribute;1:id,attribute') && e('3,attribute;4,attribute');  //测试attributeList为attribute,executionID为2
