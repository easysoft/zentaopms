#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(6);
zenData('scene')->gen(6);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testcaseModel->batchDelete();
timeout=0
cid=18963

- 用例和场景都为空返回 false。 @0
- 用例不为空，场景为空，返回 true。 @1
- 用例为空，场景不为空，返回 true。 @1
- 用例和场景都不为空返回 true。 @1
- 批量删除用例后 deleted 字段都为 1。
 - 第1条的deleted属性 @1
 - 第2条的deleted属性 @1
 - 第3条的deleted属性 @1
 - 第4条的deleted属性 @1
 - 第5条的deleted属性 @1
 - 第6条的deleted属性 @1
- 批量删除场景后 deleted 字段都为 1。
 - 第1条的deleted属性 @1
 - 第2条的deleted属性 @1
 - 第3条的deleted属性 @1
 - 第4条的deleted属性 @1
 - 第5条的deleted属性 @1
 - 第6条的deleted属性 @1
- 批量删除编号为 6 的场景后记日志。
 - 第0条的objectType属性 @scene
 - 第0条的objectID属性 @6
 - 第0条的action属性 @deleted
 - 第0条的extra属性 @1
- 批量删除编号为 5 的场景后记日志。
 - 第1条的objectType属性 @scene
 - 第1条的objectID属性 @5
 - 第1条的action属性 @deleted
 - 第1条的extra属性 @1
- 批量删除编号为 4 的场景后记日志。
 - 第2条的objectType属性 @scene
 - 第2条的objectID属性 @4
 - 第2条的action属性 @deleted
 - 第2条的extra属性 @1
- 批量删除编号为 6 的用例后记日志。
 - 第3条的objectType属性 @case
 - 第3条的objectID属性 @6
 - 第3条的action属性 @deleted
 - 第3条的extra属性 @1
- 批量删除编号为 5 的用例后记日志。
 - 第4条的objectType属性 @case
 - 第4条的objectID属性 @5
 - 第4条的action属性 @deleted
 - 第4条的extra属性 @1
- 批量删除编号为 4 的用例后记日志。
 - 第5条的objectType属性 @case
 - 第5条的objectID属性 @4
 - 第5条的action属性 @deleted
 - 第5条的extra属性 @1
- 批量删除编号为 3 的场景后记日志。
 - 第6条的objectType属性 @scene
 - 第6条的objectID属性 @3
 - 第6条的action属性 @deleted
 - 第6条的extra属性 @1
- 批量删除编号为 2 的场景后记日志。
 - 第7条的objectType属性 @scene
 - 第7条的objectID属性 @2
 - 第7条的action属性 @deleted
 - 第7条的extra属性 @1
- 批量删除编号为 1 的场景后记日志。
 - 第8条的objectType属性 @scene
 - 第8条的objectID属性 @1
 - 第8条的action属性 @deleted
 - 第8条的extra属性 @1
- 批量删除编号为 3 的用例后记日志。
 - 第9条的objectType属性 @case
 - 第9条的objectID属性 @3
 - 第9条的action属性 @deleted
 - 第9条的extra属性 @1
- 批量删除编号为 2 的用例后记日志。
 - 第10条的objectType属性 @case
 - 第10条的objectID属性 @2
 - 第10条的action属性 @deleted
 - 第10条的extra属性 @1
- 批量删除编号为 1 的用例后记日志。
 - 第11条的objectType属性 @case
 - 第11条的objectID属性 @1
 - 第11条的action属性 @deleted
 - 第11条的extra属性 @1

*/

$testcase    = new testcaseModelTest();
$caseIdList  = array(array(1, 2, 3), array(4, 5, 6));
$sceneIdList = array(array(1, 2, 3), array(4, 5, 6));

r($testcase->batchDeleteTest(array(), array()))                && p() && e('0'); // 用例和场景都为空返回 false。
r($testcase->batchDeleteTest($caseIdList[0], array()))         && p() && e('1'); // 用例不为空，场景为空，返回 true。
r($testcase->batchDeleteTest(array(), $sceneIdList[0]))        && p() && e('1'); // 用例为空，场景不为空，返回 true。
r($testcase->batchDeleteTest($caseIdList[1], $sceneIdList[1])) && p() && e('1'); // 用例和场景都不为空返回 true。

$cases = $testcase->objectModel->dao->select('*')->from(TABLE_CASE)->fetchAll('id');
r($cases) && p('1:deleted;2:deleted;3:deleted;4:deleted;5:deleted;6:deleted') && e('1,1,1,1,1,1'); // 批量删除用例后 deleted 字段都为 1。

$scenes = $testcase->objectModel->dao->select('*')->from(TABLE_SCENE)->fetchAll('id');
r($scenes) && p('1:deleted;2:deleted;3:deleted;4:deleted;5:deleted;6:deleted') && e('1,1,1,1,1,1'); // 批量删除场景后 deleted 字段都为 1。

$actions = $testcase->objectModel->dao->select('id,objectType,objectID,action,extra')->from(TABLE_ACTION)->orderBy('id_desc')->limit(12)->fetchAll();
r($actions) && p('0:objectType,objectID,action,extra')  && e('scene,6,deleted,1'); // 批量删除编号为 6 的场景后记日志。
r($actions) && p('1:objectType,objectID,action,extra')  && e('scene,5,deleted,1'); // 批量删除编号为 5 的场景后记日志。
r($actions) && p('2:objectType,objectID,action,extra')  && e('scene,4,deleted,1'); // 批量删除编号为 4 的场景后记日志。
r($actions) && p('3:objectType,objectID,action,extra')  && e('case,6,deleted,1');  // 批量删除编号为 6 的用例后记日志。
r($actions) && p('4:objectType,objectID,action,extra')  && e('case,5,deleted,1');  // 批量删除编号为 5 的用例后记日志。
r($actions) && p('5:objectType,objectID,action,extra')  && e('case,4,deleted,1');  // 批量删除编号为 4 的用例后记日志。
r($actions) && p('6:objectType,objectID,action,extra')  && e('scene,3,deleted,1'); // 批量删除编号为 3 的场景后记日志。
r($actions) && p('7:objectType,objectID,action,extra')  && e('scene,2,deleted,1'); // 批量删除编号为 2 的场景后记日志。
r($actions) && p('8:objectType,objectID,action,extra')  && e('scene,1,deleted,1'); // 批量删除编号为 1 的场景后记日志。
r($actions) && p('9:objectType,objectID,action,extra')  && e('case,3,deleted,1');  // 批量删除编号为 3 的用例后记日志。
r($actions) && p('10:objectType,objectID,action,extra') && e('case,2,deleted,1');  // 批量删除编号为 2 的用例后记日志。
r($actions) && p('11:objectType,objectID,action,extra') && e('case,1,deleted,1');  // 批量删除编号为 1 的用例后记日志。
