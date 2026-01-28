#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('1');
zenData('file')->loadYaml('resultfile')->gen('20');

su('admin');

/**

title=测试 testtaskModel->getResultsFiles();
timeout=0
cid=19188

- 测试获取结果 1 2 3 的文件
 - 属性result @1: 1 3: 3
 - 属性step @2: 2
- 测试获取结果 4 5 6 的文件
 - 属性result @5: 5
 - 属性step @4: 4 6: 6
- 测试获取结果 7 8 9 的文件
 - 属性result @9: 9
 - 属性step @8: 8
- 测试获取结果 10 11 12 的文件
 - 属性result @11: 11
 - 属性step @10: 10 12: 12
- 测试获取结果 13 14 15 的文件
 - 属性result @13: 13 15: 15
 - 属性step @~~
- 测试获取结果 16 17 18 的文件
 - 属性result @17: 17
 - 属性step @16: 16 18: 18
- 测试获取结果 19 20 的文件
 - 属性result @19: 19
 - 属性step @20: 20
- 测试获取不存在的结果 21 22 的文件
 - 属性result @~~
 - 属性step @~~

*/

$resultIdList = array();
$resultIdList[] = array (1, 2, 3);
$resultIdList[] = array (4, 5, 6);
$resultIdList[] = array (7, 8, 9);
$resultIdList[] = array (10, 11, 12);
$resultIdList[] = array (13, 14, 15);
$resultIdList[] = array (16, 17, 18);
$resultIdList[] = array (19, 20);
$resultIdList[] = array (21, 22);

$testtask = new testtaskModelTest();

r($testtask->getResultsFilesTest($resultIdList[0])) && p('result,step') && e('1: 1 3: 3,2: 2');       // 测试获取结果 1 2 3 的文件
r($testtask->getResultsFilesTest($resultIdList[1])) && p('result,step') && e('5: 5,4: 4 6: 6');       // 测试获取结果 4 5 6 的文件
r($testtask->getResultsFilesTest($resultIdList[2])) && p('result,step') && e('9: 9,8: 8');            // 测试获取结果 7 8 9 的文件
r($testtask->getResultsFilesTest($resultIdList[3])) && p('result,step') && e('11: 11,10: 10 12: 12'); // 测试获取结果 10 11 12 的文件
r($testtask->getResultsFilesTest($resultIdList[4])) && p('result,step') && e('13: 13 15: 15,~~');     // 测试获取结果 13 14 15 的文件
r($testtask->getResultsFilesTest($resultIdList[5])) && p('result,step') && e('17: 17,16: 16 18: 18'); // 测试获取结果 16 17 18 的文件
r($testtask->getResultsFilesTest($resultIdList[6])) && p('result,step') && e('19: 19,20: 20');        // 测试获取结果 19 20 的文件
r($testtask->getResultsFilesTest($resultIdList[7])) && p('result,step') && e('~~,~~');                // 测试获取不存在的结果 21 22 的文件
