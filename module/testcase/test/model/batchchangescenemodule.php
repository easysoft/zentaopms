#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

zenData('scene')->gen(2);

/**

title=测试 testcaseModel->batchChangeSceneModule();
timeout=0
cid=1

- 场景参数为空返回 false。 @0
- 场景参数不为空、所属模块参数小于 mediumint unsigned 类型最小值返回 false。 @0
- 场景参数不为空、所属模块参数大于 mediumint unsigned 类型最大值返回 false。 @0
- 场景参数对应的场景不存在，返回 false。 @0
- 批量修改场景所属模块为 1 成功，返回 true。 @1
- 批量修改场景所属模块后模块为 1。
 - 第1条的module属性 @1
 - 第2条的module属性 @1
- 批量修改场景所属模块为 2 成功，返回 true。 @1
- 批量修改场景所属模块后模块为 2。
 - 第1条的module属性 @2
 - 第2条的module属性 @2
- 批量修改场景所属模块为 3 成功，返回 true。 @1
- 批量修改场景所属模块后模块为 3。
 - 第1条的module属性 @3
 - 第2条的module属性 @3
- 批量修改场景所属模块后记录日志。
 - 第0条的objectType属性 @scene
 - 第0条的objectID属性 @2
 - 第0条的action属性 @edited
 - 第1条的objectType属性 @scene
 - 第1条的objectID属性 @1
 - 第1条的action属性 @edited
- 批量修改场景所属模块后记录日志。
 - 第2条的objectType属性 @scene
 - 第2条的objectID属性 @2
 - 第2条的action属性 @edited
 - 第3条的objectType属性 @scene
 - 第3条的objectID属性 @1
 - 第3条的action属性 @edited
- 批量修改场景所属模块后记录日志详情，module 字段从 2 变成 3。
 - 第0条的field属性 @module
 - 第0条的old属性 @2
 - 第0条的new属性 @3
 - 第1条的field属性 @module
 - 第1条的old属性 @2
 - 第1条的new属性 @3
- 批量修改场景所属模块后记录日志详情，module 字段从 1 变成 2。
 - 第2条的field属性 @module
 - 第2条的old属性 @1
 - 第2条的new属性 @2
 - 第3条的field属性 @module
 - 第3条的old属性 @1
 - 第3条的new属性 @2
- 批量修改的场景所属模块和所属模块参数一致，返回 false。 @0
- 批量删除场景返回 true。 @1
- 批量修改已删除场景所属模块返回 false。 @0

*/

$testcase    = new testcaseTest();
$sceneIdList = array(array(), array(1, 2), array(3, 4));

r($testcase->batchChangeSceneModuleTest($sceneIdList[0], 1))        && p() && e('0'); // 场景参数为空返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], -1))       && p() && e('0'); // 场景参数不为空、所属模块参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 16777216)) && p() && e('0'); // 场景参数不为空、所属模块参数大于 mediumint unsigned 类型最大值返回 false。
r($testcase->batchChangeSceneModuleTest($sceneIdList[2], 1))        && p() && e('0'); // 场景参数对应的场景不存在，返回 false。

r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 1)) && p() && e('1');            // 批量修改场景所属模块为 1 成功，返回 true。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module') && e('1,1'); // 批量修改场景所属模块后模块为 1。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 2)) && p() && e('1');            // 批量修改场景所属模块为 2 成功，返回 true。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module') && e('2,2'); // 批量修改场景所属模块后模块为 2。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 3)) && p() && e('1');            // 批量修改场景所属模块为 3 成功，返回 true。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:module;2:module') && e('3,3'); // 批量修改场景所属模块后模块为 3。

$actions = $testcase->objectModel->dao->select('objectType,objectID,action')->from(TABLE_ACTION)->orderBy('id_desc')->limit(4)->fetchAll();
r($actions) && p('0:objectType,objectID,action;1:objectType,objectID,action') && e('scene,2,edited,scene,1,edited'); // 批量修改场景所属模块后记录日志。
r($actions) && p('2:objectType,objectID,action;3:objectType,objectID,action') && e('scene,2,edited,scene,1,edited'); // 批量修改场景所属模块后记录日志。

$histories = $testcase->objectModel->dao->select('field,old,new')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(4)->fetchAll();
r($histories) && p('0:field,old,new;1:field,old,new') && e('module,2,3,module,2,3'); // 批量修改场景所属模块后记录日志详情，module 字段从 2 变成 3。
r($histories) && p('2:field,old,new;3:field,old,new') && e('module,1,2,module,1,2'); // 批量修改场景所属模块后记录日志详情，module 字段从 1 变成 2。

r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 3)) && p() && e('0'); // 批量修改的场景所属模块和所属模块参数一致，返回 false。
r($testcase->batchDeleteTest(array(), $sceneIdList[1]))      && p() && e('1'); // 批量删除场景返回 true。
r($testcase->batchChangeSceneModuleTest($sceneIdList[1], 4)) && p() && e('0'); // 批量修改已删除场景所属模块返回 false。