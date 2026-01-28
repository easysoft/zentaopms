#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('scene')->loadYaml('treescene')->gen(10);
zenData('user')->gen(1);

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();

su('admin');

/**

title=测试 testcaseTao->fixScenePath();
timeout=0
cid=19036

- 测试将场景 2 移动到场景 1 下面 @id:2, parent:1, path:,1,2,, grade:2

- 测试将场景 2 移动到场景 7 下面 @id:2, parent:7, path:,6,7,2,, grade:3

- 测试将场景 3 移动到场景 1 下面 @id:3, parent:1, path:,1,3,, grade:2

- 测试将场景 3 移动到场景 7 下面 @id:3, parent:7, path:,6,7,3,, grade:3

- 测试将场景 4 移动到场景 1 下面 @id:4, parent:1, path:,1,4,, grade:2

- 测试将场景 4 移动到场景 7 下面 @id:4, parent:7, path:,6,7,4,, grade:3

*/

$testcase = new testcaseTaoTest();

$sceneIdList  = array(2, 3, 4);
$pSceneIdList = array(1, 7);

r($testcase->fixScenePathTest($sceneIdList[0], $pSceneIdList[0])) && p() && e('id:2, parent:1, path:,1,2,, grade:2'); // 测试将场景 2 移动到场景 1 下面
r($testcase->fixScenePathTest($sceneIdList[0], $pSceneIdList[1])) && p() && e('id:2, parent:7, path:,6,7,2,, grade:3'); // 测试将场景 2 移动到场景 7 下面
r($testcase->fixScenePathTest($sceneIdList[1], $pSceneIdList[0])) && p() && e('id:3, parent:1, path:,1,3,, grade:2'); // 测试将场景 3 移动到场景 1 下面
r($testcase->fixScenePathTest($sceneIdList[1], $pSceneIdList[1])) && p() && e('id:3, parent:7, path:,6,7,3,, grade:3'); // 测试将场景 3 移动到场景 7 下面
r($testcase->fixScenePathTest($sceneIdList[2], $pSceneIdList[0])) && p() && e('id:4, parent:1, path:,1,4,, grade:2'); // 测试将场景 4 移动到场景 1 下面
r($testcase->fixScenePathTest($sceneIdList[2], $pSceneIdList[1])) && p() && e('id:4, parent:7, path:,6,7,4,, grade:3'); // 测试将场景 4 移动到场景 7 下面