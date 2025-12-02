#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('scene')->gen('5');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getSceneByID();
cid=18994
pid=1

*/

$sceneIdList = array(1, 2, 3, 4, 5, 1001);

$testcase = new testcaseTest();

r($testcase->getSceneByIDTest($sceneIdList[0])) && p('title,parent,grade') && e('这个是测试场景1,0,0'); // 测试获取scene 1 的信息
r($testcase->getSceneByIDTest($sceneIdList[1])) && p('title,parent,grade') && e('这个是测试场景2,0,0'); // 测试获取scene 2 的信息
r($testcase->getSceneByIDTest($sceneIdList[2])) && p('title,parent,grade') && e('这个是测试场景3,0,0'); // 测试获取scene 3 的信息
r($testcase->getSceneByIDTest($sceneIdList[3])) && p('title,parent,grade') && e('这个是测试场景4,1,0'); // 测试获取scene 4 的信息
r($testcase->getSceneByIDTest($sceneIdList[4])) && p('title,parent,grade') && e('这个是测试场景5,1,0'); // 测试获取scene 5 的信息
r($testcase->getSceneByIDTest($sceneIdList[5])) && p()                     && e('0');                                   // 测试获取不存在的 scene 的信息
