#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('scene')->gen(3);

/**

title=测试 testcaseModel->batchChangeSceneModule();
cid=1
pid=1

*/

$testcase    = new testcaseTest();
$sceneIdList = array(array(), array(1, 2, 3));

r($testcase->batchChangeSceneModuleTest($sceneIdList[0], 1))        && p() && e('0'); // 场景参数为空返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 0))        && p() && e('0'); // 场景参数不为空、所属分支参数为 0 返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], -1))       && p() && e('0'); // 场景参数不为空、所属分支参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 16777216)) && p() && e('0'); // 场景参数不为空、所属分支参数大于 mediumint unsigned 类型最大值返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 1))        && p() && e('1'); // 场景参数不为空、所属分支参数为 1 返回 true。

r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module;3:module') && e('1,1,1'); // 批量修改场景所属分支之前分支为 1。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 2)) && p() && e('1');                       // 批量修改场景所属分支为 2 成功，返回 true。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module;3:module') && e('2,2,2'); // 批量修改场景所属分支之后分支为 2。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 3)) && p() && e('1');                       // 批量修改场景所属分支为 3 成功，返回 true。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module;3:module') && e('3,3,3'); // 批量修改场景所属分支之后分支为 3。

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(3)->fetchAll();
r($actions) && p('0:objectType,objectID,action') && e('scene,3,edited'); // 批量修改编号为 3 的场景所属分支之后记录日志。
r($actions) && p('1:objectType,objectID,action') && e('scene,2,edited'); // 批量修改编号为 2 的场景所属分支之后记录日志。
r($actions) && p('2:objectType,objectID,action') && e('scene,1,edited'); // 批量修改编号为 1 的场景所属分支之后记录日志。

$histories = $testcase->objectModel->dao->select('*')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(3)->fetchAll();
r($histories) && p('0:field,old,new') && e('module,2,3'); // 批量修改编号为 3 的场景所属分支之后记录日志详情。
r($histories) && p('1:field,old,new') && e('module,2,3'); // 批量修改编号为 2 的场景所属分支之后记录日志详情。
r($histories) && p('2:field,old,new') && e('module,2,3'); // 批量修改编号为 1 的场景所属分支之后记录日志详情。

r($testcase->batchDeleteTest(array(), $sceneIdList[1]))     && p() && e('1'); // 批量删除场景返回 true。
r($testcase->batchChangeCaseModuleTest($sceneIdList[1], 4)) && p() && e('0'); // 批量修改已删除场景所属分支返回 false。
