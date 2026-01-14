#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('scene')->loadYaml('treescene')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getAllChildId();
cid=18973

- 测试获取场景 1 所有的子场景 id @1,2

- 测试获取场景 11 所有的子场景 id @11,12,13,14,15

- 测试获取场景 12 所有的子场景 id @12,13,14

- 测试获取场景 13 所有的子场景 id @13,14

- 测试获取场景 18 所有的子场景 id @18,19,20

- 测试获取场景 19 所有的子场景 id @19,20

- 测试获取场景 20 所有的子场景 id @20

*/

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();

$sceneIdList = array(1, 11, 12, 13, 18, 19, 20);

$testcase = new testcaseModelTest();

r($testcase->getAllChildIdTest($sceneIdList[0])) && p() && e('1,2');            // 测试获取场景 1 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[1])) && p() && e('11,12,13,14,15'); // 测试获取场景 11 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[2])) && p() && e('12,13,14');       // 测试获取场景 12 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[3])) && p() && e('13,14');          // 测试获取场景 13 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[4])) && p() && e('18,19,20');       // 测试获取场景 18 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[5])) && p() && e('19,20');          // 测试获取场景 19 所有的子场景 id
r($testcase->getAllChildIdTest($sceneIdList[6])) && p() && e('20');             // 测试获取场景 20 所有的子场景 id
