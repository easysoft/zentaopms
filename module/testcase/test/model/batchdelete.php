#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen(6);
zdTable('scene')->gen(6);

/**

title=测试 testcaseModel->batchReview();
timeout=0
cid=1

*/

$testcase    = new testcaseTest();
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

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(12)->fetchAll();
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
