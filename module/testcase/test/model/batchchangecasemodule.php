#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen(2);

/**

title=测试 testcaseModel->batchChangeCaseModule();
cid=1
pid=1

*/

$testcase   = new testcaseTest();
$caseIdList = array(array(), array(1, 2), array(3, 4));

r($testcase->batchChangeCaseModuleTest($caseIdList[0], 1))        && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchChangeCaseModuleTest($caseIdList[1], -1))       && p() && e('0'); // 用例参数不为空、所属模块参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->batchChangeCaseModuleTest($caseIdList[1], 16777216)) && p() && e('0'); // 用例参数不为空、所属模块参数大于 mediumint unsigned 类型最大值返回 false。
r($testcase->batchChangeCaseModuleTest($caseIdList[2], 1))        && p() && e('0'); // 用例参数对应的用例不存在，返回 false。

r($testcase->batchChangeCaseModuleTest($caseIdList[1], 1)) && p() && e('1');       // 批量修改用例所属模块为 1 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:module;2:module') && e('1,1'); // 批量修改用例所属模块后模块为 1。
r($testcase->batchChangeCaseModuleTest($caseIdList[1], 2)) && p() && e('1');       // 批量修改用例所属模块为 2 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:module;2:module') && e('2,2'); // 批量修改用例所属模块后模块为 2。
r($testcase->batchChangeCaseModuleTest($caseIdList[1], 3)) && p() && e('1');       // 批量修改用例所属模块为 3 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:module;2:module') && e('3,3'); // 批量修改用例所属模块后模块为 3。

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(4)->fetchAll();
r($actions) && p('0:objectType,objectID,action;1:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属模块后记录日志。
r($actions) && p('2:objectType,objectID,action;3:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属模块后记录日志。

$histories = $testcase->objectModel->dao->select('*')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(4)->fetchAll();
r($histories) && p('0:field,old,new;1:field,old,new') && e('module,2,3,module,2,3'); // 批量修改用例所属模块后记录日志详情，module 字段从 2 变成 3。
r($histories) && p('2:field,old,new;3:field,old,new') && e('module,1,2,module,1,2'); // 批量修改用例所属模块后记录日志详情，module 字段从 1 变成 2。

r($testcase->batchChangeCaseModuleTest($caseIdList[1], 3)) && p() && e('0'); // 批量修改的用例所属模块和所属模块参数一致，返回 false。
r($testcase->batchDeleteTest($caseIdList[1], array()))     && p() && e('1'); // 批量删除用例返回 true。
r($testcase->batchChangeCaseModuleTest($caseIdList[1], 4)) && p() && e('0'); // 批量修改已删除用例所属模块返回 false。
