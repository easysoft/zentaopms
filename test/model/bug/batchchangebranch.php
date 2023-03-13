#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->batchChangeBranch();
cid=1
pid=1

修改分支为主干 未发生变化 >> 0
修改分支为分支11 >> branch,0,11
修改分支为分支12 >> branch,11,12
修改分支为主干 未发生变化 >> 0
修改分支为分支9 >> branch,0,9
修改分支为分支10 >> branch,9,10
修改分支为主干 未发生变化 >> 0
修改分支为分支7 >> branch,0,7
修改分支为分支8 >> branch,7,8
修改分支为主干 未发生变化 >> 0
修改分支为分支37 >> branch,0,37
修改分支为分支38 >> branch,37,38

*/

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
