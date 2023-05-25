#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=测试bugModel->batchChangeBranch();
timeout=0
cid=1

- 修改分支为主干 未发生变化 @0

- 修改分支为分支11
 - 第0条的field属性 @branch
 - 第0条的old属性 @0
 - 第0条的new属性 @11

- 修改分支为分支12
 - 第0条的field属性 @branch
 - 第0条的old属性 @11
 - 第0条的new属性 @12

- 修改分支为主干 未发生变化 @0

- 修改分支为分支9
 - 第0条的field属性 @branch
 - 第0条的old属性 @0
 - 第0条的new属性 @9

- 修改分支为分支10
 - 第0条的field属性 @branch
 - 第0条的old属性 @9
 - 第0条的new属性 @10

- 修改分支为主干 未发生变化 @0

- 修改分支为分支7
 - 第0条的field属性 @branch
 - 第0条的old属性 @0
 - 第0条的new属性 @7

- 修改分支为分支8
 - 第0条的field属性 @branch
 - 第0条的old属性 @7
 - 第0条的new属性 @8

- 修改分支为主干 未发生变化 @0

- 修改分支为分支37
 - 第0条的field属性 @branch
 - 第0条的old属性 @0
 - 第0条的new属性 @37

- 修改分支为分支38
 - 第0条的field属性 @branch
 - 第0条的old属性 @37
 - 第0条的new属性 @38

*/

zdTable('bug')->gen(200);
zdTable('branch')->gen(50);

$bugIDList1 = array('136', '137', '138');
$bugIDList2 = array('133', '134', '135');
$bugIDList3 = array('130', '131', '132');
$bugIDList4 = array('175', '176', '177');

$branchList1 = array('0', '11', '12');
$branchList2 = array('0', '9',  '10');
$branchList3 = array('0', '7',  '8');
$branchList4 = array('0', '37', '38');

$bug = new bugTest();
r($bug->batchChangeBranchTest($bugIDList1, $branchList1[0], $bugIDList1[0])) && p()                  && e('0');            // 修改分支为主干 未发生变化
r($bug->batchChangeBranchTest($bugIDList1, $branchList1[1], $bugIDList1[1])) && p('0:field,old,new') && e('branch,0,11');  // 修改分支为分支11
r($bug->batchChangeBranchTest($bugIDList1, $branchList1[2], $bugIDList1[2])) && p('0:field,old,new') && e('branch,11,12'); // 修改分支为分支12
r($bug->batchChangeBranchTest($bugIDList2, $branchList2[0], $bugIDList2[0])) && p()                  && e('0');            // 修改分支为主干 未发生变化
r($bug->batchChangeBranchTest($bugIDList2, $branchList2[1], $bugIDList2[1])) && p('0:field,old,new') && e('branch,0,9');   // 修改分支为分支9
r($bug->batchChangeBranchTest($bugIDList2, $branchList2[2], $bugIDList2[2])) && p('0:field,old,new') && e('branch,9,10');  // 修改分支为分支10
r($bug->batchChangeBranchTest($bugIDList3, $branchList3[0], $bugIDList3[0])) && p()                  && e('0');            // 修改分支为主干 未发生变化
r($bug->batchChangeBranchTest($bugIDList3, $branchList3[1], $bugIDList3[1])) && p('0:field,old,new') && e('branch,0,7');   // 修改分支为分支7
r($bug->batchChangeBranchTest($bugIDList3, $branchList3[2], $bugIDList3[2])) && p('0:field,old,new') && e('branch,7,8');   // 修改分支为分支8
r($bug->batchChangeBranchTest($bugIDList4, $branchList4[0], $bugIDList4[0])) && p()                  && e('0');            // 修改分支为主干 未发生变化
r($bug->batchChangeBranchTest($bugIDList4, $branchList4[1], $bugIDList4[1])) && p('0:field,old,new') && e('branch,0,37');  // 修改分支为分支37
r($bug->batchChangeBranchTest($bugIDList4, $branchList4[2], $bugIDList4[2])) && p('0:field,old,new') && e('branch,37,38'); // 修改分支为分支38