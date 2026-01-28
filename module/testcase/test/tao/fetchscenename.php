#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('scene')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testcaseTao->fetchSceneName();
cid=19034
pid=1

*/

$testcase = new testcaseTaoTest();

$sceneIdList = array(1, 2, 3, 4, 100);

r($testcase->fetchSceneNameTest($sceneIdList[0])) && p() && e('这个是测试场景1'); // caseID 参数为 1 返回名称
r($testcase->fetchSceneNameTest($sceneIdList[1])) && p() && e('这个是测试场景2'); // caseID 参数为 2 返回名称
r($testcase->fetchSceneNameTest($sceneIdList[2])) && p() && e('这个是测试场景3'); // caseID 参数为 3 返回名称
r($testcase->fetchSceneNameTest($sceneIdList[3])) && p() && e('这个是测试场景4'); // caseID 参数为 4 返回名称
r($testcase->fetchSceneNameTest($sceneIdList[4])) && p() && e('0');               // caseID 不存在 100 返回 false。
