#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen(2);

/**

title=测试 testcaseModel->batchChangeCaseBranch();
cid=1
pid=1

*/

$testcase   = new testcaseTest();
$caseIdList = array(array(), array(1, 2));

r($testcase->batchChangeCaseBranchTest($caseIdList[0], 1))        && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchChangeCaseBranchTest($caseIdList[1], -1))       && p() && e('0'); // 用例参数不为空、所属分支参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->batchChangeCaseBranchTest($caseIdList[1], 16777216)) && p() && e('0'); // 用例参数不为空、所属分支参数大于 mediumint unsigned 类型最大值返回 false。

r($testcase->batchChangeCaseBranchTest($caseIdList[1], 1)) && p() && e('1');       // 批量修改用例所属分支为 1 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:branch;2:branch') && e('1,1'); // 批量修改用例所属分支后分支为 1。
r($testcase->batchChangeCaseBranchTest($caseIdList[1], 2)) && p() && e('1');       // 批量修改用例所属分支为 2 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:branch;2:branch') && e('2,2'); // 批量修改用例所属分支后分支为 2。
r($testcase->batchChangeCaseBranchTest($caseIdList[1], 3)) && p() && e('1');       // 批量修改用例所属分支为 3 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:branch;2:branch') && e('3,3'); // 批量修改用例所属分支后分支为 3。

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(4)->fetchAll();
r($actions) && p('0:objectType,objectID,action;1:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属分支后记录日志。
r($actions) && p('2:objectType,objectID,action;3:objectType,objectID,action') && e('case,2,edited,case,1,edited'); // 批量修改用例所属分支后记录日志。

$histories = $testcase->objectModel->dao->select('*')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(4)->fetchAll();
r($histories) && p('0:field,old,new;1:field,old,new') && e('branch,2,3,branch,2,3'); // 批量修改用例所属分支后记录日志详情，branch 字段从 2 变成 3。
r($histories) && p('2:field,old,new;3:field,old,new') && e('branch,1,2,branch,1,2'); // 批量修改用例所属分支后记录日志详情，branch 字段从 1 变成 2。

r($testcase->batchChangeCaseBranchTest($caseIdList[1], 3)) && p() && e('0'); // 批量修改的用例所属分支和所属分支参数一致，返回 false。
r($testcase->batchDeleteTest($caseIdList[1], array()))     && p() && e('1'); // 批量删除用例返回 true。
r($testcase->batchChangeCaseBranchTest($caseIdList[1], 4)) && p() && e('0'); // 批量修改已删除用例所属分支返回 false。
