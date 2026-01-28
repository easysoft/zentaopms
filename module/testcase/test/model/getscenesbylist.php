#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('scene')->gen('4')->fixPath();

/**

title=测试 testcaseModel->getScenesByList();
timeout=0
cid=18999

- 测试获取编号为空数组的场景条数。 @0
- 测试获取编号为 1、2 的场景条数。 @2
- 测试获取编号为 1、2、3、4 的场景条数。 @4
- 测试获取编号为 1、2 的场景信息。
 - 第1条的id属性 @1
 - 第1条的deleted属性 @0
 - 第2条的id属性 @2
 - 第2条的deleted属性 @0
- 测试获取编号为 1、2、3、4 的场景信息。
 - 第1条的id属性 @1
 - 第1条的deleted属性 @0
 - 第2条的id属性 @2
 - 第2条的deleted属性 @0
 - 第3条的id属性 @3
 - 第3条的deleted属性 @0
 - 第4条的id属性 @4
 - 第4条的deleted属性 @0
- 测试获取编号为 1、2 并且分支为 0 的场景信息。
 - 第1条的id属性 @1
 - 第1条的deleted属性 @0
 - 第2条的id属性 @2
 - 第2条的deleted属性 @0
- 测试获取编号为 1、2 并且模块为 0 的场景信息。
 - 第1条的id属性 @1
 - 第1条的deleted属性 @0
 - 第2条的id属性 @2
 - 第2条的deleted属性 @0
- 测试获取编号为 1、2 并且分支为 0 的场景条数。 @2
- 测试获取编号为 1、2 并且模块为 0 的场景条数。 @2
- 测试获取编号为 1、2 并且分支不为 0 的场景条数。 @0
- 测试获取编号为 1、2 并且模块不为 0 的场景条数。 @0
- 测试删除编号为 1、2 的场景后获取编号为 1、2 的场景条数。 @0
- 测试删除编号为 1、2 的场景后获取编号为 1、2、3、4 的场景条数。 @2
- 测试删除编号为 1、2 的场景后获取编号为 1、2、3、4 的场景信息。
 - 第3条的id属性 @3
 - 第3条的deleted属性 @0
 - 第4条的id属性 @4
 - 第4条的deleted属性 @0

*/

$testcase    = new testcaseModelTest();
$sceneIdList = array(array(1, 2), array(1, 2, 3, 4));

r(count($testcase->getScenesByListTest(array())))         && p() && e('0'); // 测试获取编号为空数组的场景条数。
r(count($testcase->getScenesByListTest($sceneIdList[0]))) && p() && e('2'); // 测试获取编号为 1、2 的场景条数。
r(count($testcase->getScenesByListTest($sceneIdList[1]))) && p() && e('4'); // 测试获取编号为 1、2、3、4 的场景条数。

r($testcase->getScenesByListTest($sceneIdList[0])) && p('1:id,deleted;2:id,deleted') && e('1,0,2,0');                                   // 测试获取编号为 1、2 的场景信息。
r($testcase->getScenesByListTest($sceneIdList[1])) && p('1:id,deleted;2:id,deleted;3:id,deleted;4:id,deleted') && e('1,0,2,0,3,0,4,0'); // 测试获取编号为 1、2、3、4 的场景信息。

r($testcase->getScenesByListTest($sceneIdList[0], "branch = '0'")) && p('1:id,deleted;2:id,deleted') && e('1,0,2,0'); // 测试获取编号为 1、2 并且分支为 0 的场景信息。
r($testcase->getScenesByListTest($sceneIdList[0], "module = '0'")) && p('1:id,deleted;2:id,deleted') && e('1,0,2,0'); // 测试获取编号为 1、2 并且模块为 0 的场景信息。

r(count($testcase->getScenesByListTest($sceneIdList[0], "branch = '0'"))) && p() && e('2'); // 测试获取编号为 1、2 并且分支为 0 的场景条数。
r(count($testcase->getScenesByListTest($sceneIdList[0], "module = '0'"))) && p() && e('2'); // 测试获取编号为 1、2 并且模块为 0 的场景条数。

r(count($testcase->getScenesByListTest($sceneIdList[0], "branch != '0'"))) && p() && e('0'); // 测试获取编号为 1、2 并且分支不为 0 的场景条数。
r(count($testcase->getScenesByListTest($sceneIdList[0], "module != '0'"))) && p() && e('0'); // 测试获取编号为 1、2 并且模块不为 0 的场景条数。

$testcase->objectModel->batchDelete(array(), $sceneIdList[0]);              // 删除编号为 1、2 的场景。

r(count($testcase->getScenesByListTest($sceneIdList[0]))) && p() && e('0'); // 测试删除编号为 1、2 的场景后获取编号为 1、2 的场景条数。
r(count($testcase->getScenesByListTest($sceneIdList[1]))) && p() && e('2'); // 测试删除编号为 1、2 的场景后获取编号为 1、2、3、4 的场景条数。

r($testcase->getScenesByListTest($sceneIdList[1])) && p('3:id,deleted;4:id,deleted') && e('3,0,4,0'); // 测试删除编号为 1、2 的场景后获取编号为 1、2、3、4 的场景信息。
