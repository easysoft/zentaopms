#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 weeklyModel->getSV();
cid=19731
pid=1

测试ev值为0，pv值为0 >> 0
测试ev值为0，pv值为10 >> -100.00
测试ev值为0，pv值为100 >> -100.00
测试ev值为10，pv值为0 >> 0
测试ev值为10，pv值为10 >> 0.00
测试ev值为10，pv值为100 >> -90.00
测试ev值为100，pv值为0 >> 0
测试ev值为100，pv值为10 >> 900.00
测试ev值为100，pv值为100 >> 0.00

*/
$evList = array(0, 10, 100);
$pvList = array(0, 10, 100);

$weekly = new weeklyModelTest();

r($weekly->getSVTest($evList[0], $pvList[0])) && p() && e('0');       //测试ev值为0，pv值为0
r($weekly->getSVTest($evList[0], $pvList[1])) && p() && e('-100.00'); //测试ev值为0，pv值为10
r($weekly->getSVTest($evList[0], $pvList[2])) && p() && e('-100.00'); //测试ev值为0，pv值为100
r($weekly->getSVTest($evList[1], $pvList[0])) && p() && e('0');       //测试ev值为10，pv值为0
r($weekly->getSVTest($evList[1], $pvList[1])) && p() && e('0.00');    //测试ev值为10，pv值为10
r($weekly->getSVTest($evList[1], $pvList[2])) && p() && e('-90.00');  //测试ev值为10，pv值为100
r($weekly->getSVTest($evList[2], $pvList[0])) && p() && e('0');       //测试ev值为100，pv值为0
r($weekly->getSVTest($evList[2], $pvList[1])) && p() && e('900.00');  //测试ev值为100，pv值为10
r($weekly->getSVTest($evList[2], $pvList[2])) && p() && e('0.00');    //测试ev值为100，pv值为100