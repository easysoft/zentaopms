#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchChangeBranch();
cid=1
pid=1

测试批量修改case 161 162 的branch为1 >> 1;1
测试批量修改case 163 164 的branch为2 >> 2;2
测试批量修改case 165 166 的branch为3 >> 3;3
测试批量修改case 167 168 的branch为4 >> 4;4
测试批量修改case 169 170 的branch为5 >> 5;5
测试批量修改case 171 172 的branch为6 >> 6;6
测试批量修改case 173 174 的branch为7 >> 7;7

*/

$caseIDList = array(array(161, 162), array(163, 164), array(165, 166), array(167, 168), array(169, 170), array(171, 172), array(173, 174));
$branchList = array(1, 2, 3, 4, 5, 6, 7);

$testcase = new testcaseTest();

r($testcase->batchChangeBranchTest($caseIDList[0], $branchList[0])) && p('161:branch;162:branch') && e('1;1'); // 测试批量修改case 161 162 的branch为1
r($testcase->batchChangeBranchTest($caseIDList[1], $branchList[1])) && p('163:branch;164:branch') && e('2;2'); // 测试批量修改case 163 164 的branch为2
r($testcase->batchChangeBranchTest($caseIDList[2], $branchList[2])) && p('165:branch;166:branch') && e('3;3'); // 测试批量修改case 165 166 的branch为3
r($testcase->batchChangeBranchTest($caseIDList[3], $branchList[3])) && p('167:branch;168:branch') && e('4;4'); // 测试批量修改case 167 168 的branch为4
r($testcase->batchChangeBranchTest($caseIDList[4], $branchList[4])) && p('169:branch;170:branch') && e('5;5'); // 测试批量修改case 169 170 的branch为5
r($testcase->batchChangeBranchTest($caseIDList[5], $branchList[5])) && p('171:branch;172:branch') && e('6;6'); // 测试批量修改case 171 172 的branch为6
r($testcase->batchChangeBranchTest($caseIDList[6], $branchList[6])) && p('173:branch;174:branch') && e('7;7'); // 测试批量修改case 173 174 的branch为7
