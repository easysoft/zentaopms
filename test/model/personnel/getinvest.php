#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getInvest();
cid=1
pid=1



*/

$personnel = new personnelTest('admin');

$programID = array();
$programID[0] = 1;
$programID[1] = 2;

$invest1 = count($personnel->getInvestTest($programID[0]));
$invest2 = count($personnel->getInvestTest($programID[1]));

r($invest1) && p($invest1) && e(''); //获得项目id=1的授权用户数量
r($invest2) && p($invest2) && e(''); //获得项目id=2的授权用户数量