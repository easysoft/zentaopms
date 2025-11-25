#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';
su('admin');

/**

title=测试 customModel->setURAndSR();
timeout=0
cid=15927

- 测试SRName值为空 @1
- 测试SRName值有一个 @1
- 测试SRName值有多个 @1
- 测试SRName值有多个 @1
- 测试SRName值有多个 @1

*/

$SRName = array(
    '0' => array('SRName' => array(''), 'URName' => array('用户需求'), 'ERName' => array('业务需求')),
    '1' => array('SRName' => array('测试需求'), 'URName' => array('用户需求'), 'ERName' => array('业务需求')),
    '2' => array('SRName' => array('测试需求', '用户需求'), 'URName' => array('用户需求'), 'ERName' => array('业务需求')),
    '3' => array('SRName' => array('测试需求', '用户需求', '业务需求'), 'URName' => array('用户需求'), 'ERName' => array('业务需求')),
);

$custom = new customTest();
$custom->objectModel->lang->URName = '用户需求';
$custom->objectModel->lang->SRName = '软件需求';

$customTester = new customTest();
r($customTester->setURAndSRTest($SRName[0])) && p() && e('1');  //测试SRName值为空
r($customTester->setURAndSRTest($SRName[1])) && p() && e('1');  //测试SRName值有一个
r($customTester->setURAndSRTest($SRName[2])) && p() && e('1');  //测试SRName值有多个
r($customTester->setURAndSRTest($SRName[2])) && p() && e('1');  //测试SRName值有多个
r($customTester->setURAndSRTest($SRName[3])) && p() && e('1');  //测试SRName值有多个