#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getCV();
cid=19720
pid=1

测试ev值为0，ac值为0 >> 0
测试ev值为0，ac值为10 >> -100.00
测试ev值为0，ac值为100 >> -100.00
测试ev值为10，ac值为0 >> 0
测试ev值为10，ac值为10 >> 0.00
测试ev值为10，ac值为100 >> -90.00
测试ev值为100，ac值为0 >> 0
测试ev值为100，ac值为10 >> 900.00
测试ev值为100，ac值为100 >> 0.00

*/

$evList = array(0, 10, 100);
$acList = array(0, 10, 100);

$weekly = new weeklyTest();

r($weekly->getCVTest($evList[0], $acList[0])) && p() && e('0');       //测试ev值为0，ac值为0
r($weekly->getCVTest($evList[0], $acList[1])) && p() && e('-100.00'); //测试ev值为0，ac值为10
r($weekly->getCVTest($evList[0], $acList[2])) && p() && e('-100.00'); //测试ev值为0，ac值为100
r($weekly->getCVTest($evList[1], $acList[0])) && p() && e('0');       //测试ev值为10，ac值为0
r($weekly->getCVTest($evList[1], $acList[1])) && p() && e('0.00');    //测试ev值为10，ac值为10
r($weekly->getCVTest($evList[1], $acList[2])) && p() && e('-90.00');  //测试ev值为10，ac值为100
r($weekly->getCVTest($evList[2], $acList[0])) && p() && e('0');       //测试ev值为100，ac值为0
r($weekly->getCVTest($evList[2], $acList[1])) && p() && e('900.00');  //测试ev值为100，ac值为10
r($weekly->getCVTest($evList[2], $acList[2])) && p() && e('0.00');    //测试ev值为100，ac值为100