#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->importFromLib();
cid=1
pid=1

测试导入用例库用例 401 402 >> 用例库用例1;用例库用例2
测试导入用例库用例 403 404 >> 用例库用例3;用例库用例4
测试导入用例库用例 405 406 >> 用例库用例5;用例库用例6
测试导入用例库用例 407 408 >> 用例库用例7;用例库用例8
测试导入用例库用例 409 410 >> 用例库用例9;用例库用例10

*/

$productID  = 1;
$caseIdList = array(array('401' => '401', '402' => '402'), array('403' => '403', '404' => '404'), array('405' => '405', '406' => '406'), array('407' => '407', '408' => '408'), array('409' => '409', '410' => '410'));

$testcase = new testcaseTest();

r($testcase->importFromLibTest($productID, $caseIdList[0])) && p('0:title;1:title') && e('用例库用例1;用例库用例2'); // 测试导入用例库用例 401 402
r($testcase->importFromLibTest($productID, $caseIdList[1])) && p('0:title;1:title') && e('用例库用例3;用例库用例4'); // 测试导入用例库用例 403 404
r($testcase->importFromLibTest($productID, $caseIdList[2])) && p('0:title;1:title') && e('用例库用例5;用例库用例6'); // 测试导入用例库用例 405 406
r($testcase->importFromLibTest($productID, $caseIdList[3])) && p('0:title;1:title') && e('用例库用例7;用例库用例8'); // 测试导入用例库用例 407 408
r($testcase->importFromLibTest($productID, $caseIdList[4])) && p('0:title;1:title') && e('用例库用例9;用例库用例10'); // 测试导入用例库用例 409 410
