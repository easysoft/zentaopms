#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchCaseTypeChange();
cid=1
pid=1

测试批量修改case 1 2 type为 feature >> feature;feature
测试批量修改case 3 4 type为 performance >> performance;performance 
测试批量修改case 5 6 type为 config >> config;config
测试批量修改case 7 8 type为 install >> install;install
测试批量修改case 9 10 type为 security >> security;security
测试批量修改case 11 12 type为 interface >> interface;interface
测试批量修改case 13 14 15 type为 other >> other;other;other

*/
$caseIDList = array(array(1, 2), array(3, 4), array(5, 6), array(7, 8), array(9, 10), array(11, 12), array(13, 14, 15));
$typeList   = array('feature', 'performance', 'config', 'install', 'security', 'interface', 'other');

$testcase = new testcaseTest();

r($testcase->batchCaseTypeChangeTest($caseIDList[0], $typeList[0])) && p('1:type;2:type')           && e('feature;feature');          // 测试批量修改case 1 2 type为 feature
r($testcase->batchCaseTypeChangeTest($caseIDList[1], $typeList[1])) && p('3:type;4:type')           && e('performance;performance '); // 测试批量修改case 3 4 type为 performance
r($testcase->batchCaseTypeChangeTest($caseIDList[2], $typeList[2])) && p('5:type;6:type')           && e('config;config');            // 测试批量修改case 5 6 type为 config
r($testcase->batchCaseTypeChangeTest($caseIDList[3], $typeList[3])) && p('7:type;8:type')           && e('install;install');          // 测试批量修改case 7 8 type为 install
r($testcase->batchCaseTypeChangeTest($caseIDList[4], $typeList[4])) && p('9:type;10:type')          && e('security;security');        // 测试批量修改case 9 10 type为 security
r($testcase->batchCaseTypeChangeTest($caseIDList[5], $typeList[5])) && p('11:type;12:type')         && e('interface;interface');      // 测试批量修改case 11 12 type为 interface
r($testcase->batchCaseTypeChangeTest($caseIDList[6], $typeList[6])) && p('13:type;14:type;15:type') && e('other;other;other');        // 测试批量修改case 13 14 15 type为 other
