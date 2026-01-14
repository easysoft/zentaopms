#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(2);

/**

title=测试 testcaseModel->batchChangeScene();
timeout=0
cid=18958

- 用例参数为空返回 false。 @0
- 用例参数不为空、所属场景参数小于 mediumint unsigned 类型最小值返回 false。 @0
- 用例参数不为空、所属场景参数大于 mediumint unsigned 类型最大值返回 false。 @0
- 用例参数对应的用例不存在，返回 false。 @0
- 批量修改用例所属场景为 1 成功，返回 true。 @1
- 批量修改用例所属场景后场景为 1。
 - 第1条的scene属性 @1
 - 第2条的scene属性 @1
- 批量修改用例所属场景为 2 成功，返回 true。 @1
- 批量修改用例所属场景后场景为 2。
 - 第1条的scene属性 @2
 - 第2条的scene属性 @2
- 批量修改用例所属场景为 3 成功，返回 true。 @1
- 批量修改用例所属场景后场景为 3。
 - 第1条的scene属性 @3
 - 第2条的scene属性 @3
- 批量修改用例所属场景后记录日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @2
 - 第0条的action属性 @edited
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @1
 - 第1条的action属性 @edited
- 批量修改用例所属场景后记录日志。
 - 第2条的objectType属性 @case
 - 第2条的objectID属性 @2
 - 第2条的action属性 @edited
 - 第3条的objectType属性 @case
 - 第3条的objectID属性 @1
 - 第3条的action属性 @edited
- 批量修改用例所属场景后记录日志详情，scene 字段从 2 变成 3。
 - 第0条的field属性 @scene
 - 第0条的old属性 @2
 - 第0条的new属性 @3
 - 第1条的field属性 @scene
 - 第1条的old属性 @2
 - 第1条的new属性 @3
- 批量修改用例所属场景后记录日志详情，scene 字段从 1 变成 2。
 - 第2条的field属性 @scene
 - 第2条的old属性 @1
 - 第2条的new属性 @2
 - 第3条的field属性 @scene
 - 第3条的old属性 @1
 - 第3条的new属性 @2
- 批量修改的用例所属场景和所属场景参数一致，返回 false。 @0
- 批量删除用例返回 true。 @1
- 批量修改已删除用例所属场景返回 false。 @0

*/

$testcase   = new testcaseModelTest();
$caseIdList = array(array(), array(1, 2), array(3, 4));

r($testcase->batchChangeSceneTest($caseIdList[0], 1))        && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchChangeSceneTest($caseIdList[1], -1))       && p() && e('0'); // 用例参数不为空、所属场景参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->batchChangeSceneTest($caseIdList[1], 16777216)) && p() && e('0'); // 用例参数不为空、所属场景参数大于 mediumint unsigned 类型最大值返回 false。
r($testcase->batchChangeSceneTest($caseIdList[2], 1))        && p() && e('0'); // 用例参数对应的用例不存在，返回 false。

r($testcase->batchChangeSceneTest($caseIdList[1], 1)) && p() && e('1');          // 批量修改用例所属场景为 1 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:scene;2:scene') && e('1,1'); // 批量修改用例所属场景后场景为 1。
r($testcase->batchChangeSceneTest($caseIdList[1], 2)) && p() && e('1');          // 批量修改用例所属场景为 2 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:scene;2:scene') && e('2,2'); // 批量修改用例所属场景后场景为 2。
r($testcase->batchChangeSceneTest($caseIdList[1], 3)) && p() && e('1');          // 批量修改用例所属场景为 3 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:scene;2:scene') && e('3,3'); // 批量修改用例所属场景后场景为 3。

$actions = $testcase->objectModel->dao->select('objectType,objectID,action')->from(TABLE_ACTION)->orderBy('id_desc')->limit(4)->fetchAll();
r($actions) && p('0:objectType,objectID,action;1:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属场景后记录日志。
r($actions) && p('2:objectType,objectID,action;3:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属场景后记录日志。

$histories = $testcase->objectModel->dao->select('field,old,new')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(4)->fetchAll();
r($histories) && p('0:field,old,new;1:field,old,new') && e('scene,2,3,scene,2,3'); // 批量修改用例所属场景后记录日志详情，scene 字段从 2 变成 3。
r($histories) && p('2:field,old,new;3:field,old,new') && e('scene,1,2,scene,1,2'); // 批量修改用例所属场景后记录日志详情，scene 字段从 1 变成 2。

r($testcase->batchChangeSceneTest($caseIdList[1], 3))  && p() && e('0'); // 批量修改的用例所属场景和所属场景参数一致，返回 false。
r($testcase->batchDeleteTest($caseIdList[1], array())) && p() && e('1'); // 批量删除用例返回 true。
r($testcase->batchChangeSceneTest($caseIdList[1], 4))  && p() && e('0'); // 批量修改已删除用例所属场景返回 false。