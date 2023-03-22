#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->setURAndSR();
cid=1
pid=1

测试SRName值为空 >> 1
测试SRName值有一个 >> 1
测试SRName值有多个 >> 1

*/
$SRName = array(
    '0' => array('SRName' => array('')),
    '1' => array('SRName' => array('测试需求')),
    '2' => array('SRName' => array('测试需求', '用户需求'))
);

$custom = new customTest();

r($custom->setURAndSRTest($SRName[0])) && p() && e('1');  //测试SRName值为空
r($custom->setURAndSRTest($SRName[1])) && p() && e('1');  //测试SRName值有一个
r($custom->setURAndSRTest($SRName[2])) && p() && e('1');  //测试SRName值有多个
