#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuite.unittest.class.php';
su('admin');

zenData('testsuite')->gen(2);
zenData('case')->gen(3);
/**

title=测试 testsuiteModel->linkCase();
cid=19149
pid=1

测试存在的suiteID值为1,cases值为空 >> 0
测试存在的suiteID值为1,cases值为一个 >> 1,1
测试存在的suiteID值为1,cases值为多个 >> 1,2;1,3
测试不存在的suiteID值为1000,cases值为空 >> 0
测试不存在的suiteID值为1000,cases值为一个 >> 1000,1
测试不存在的suiteID值为1000,cases值为多个 >> 1000,2;1000,3
测试不存在的suiteID值为0,cases值为空 >> 0
测试不存在的suiteID值为0,cases值为一个 >> 0,1
测试不存在的suiteID值为0,cases值为多个 >> 0,2;0,3

*/
$suiteID  = array(1, 1000, 0);
$cases    = array(array(), array(1), array(2, 3));
$versions = array(1 => 1, 2 => 1, 3=> 1);

$testsuite = new testsuiteTest();

r($testsuite->linkCaseTest($suiteID[0], $cases[0], $versions)) && p() && e('0');                                        //测试存在的suiteID值为1,cases值为空
r($testsuite->linkCaseTest($suiteID[0], $cases[1], $versions)) && p('0:suite,case') && e('1,1');                        //测试存在的suiteID值为1,cases值为一个
r($testsuite->linkCaseTest($suiteID[0], $cases[2], $versions)) && p('0:suite,case;1:suite,case') && e('1,2;1,3');       //测试存在的suiteID值为1,cases值为多个
r($testsuite->linkCaseTest($suiteID[1], $cases[0], $versions)) && p() && e('0');                                        //测试不存在的suiteID值为1000,cases值为空
r($testsuite->linkCaseTest($suiteID[1], $cases[1], $versions)) && p('0:suite,case') && e('1000,1');                     //测试不存在的suiteID值为1000,cases值为一个
r($testsuite->linkCaseTest($suiteID[1], $cases[2], $versions)) && p('0:suite,case;1:suite,case') && e('1000,2;1000,3'); //测试不存在的suiteID值为1000,cases值为多个
r($testsuite->linkCaseTest($suiteID[2], $cases[0], $versions)) && p() && e('0');                                        //测试不存在的suiteID值为0,cases值为空
r($testsuite->linkCaseTest($suiteID[2], $cases[1], $versions)) && p('0:suite,case') && e('0,1');                        //测试不存在的suiteID值为0,cases值为一个
r($testsuite->linkCaseTest($suiteID[2], $cases[2], $versions)) && p('0:suite,case;1:suite,case') && e('0,2;0,3');       //测试不存在的suiteID值为0,cases值为多个
