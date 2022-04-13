#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getRiskInvest();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

var_dump($personnel->getRiskInvestTest(array('test2', 'test3'), array(1, 2, 3)));die;

r($personnel->getRiskInvestTest()) && p() && e();
