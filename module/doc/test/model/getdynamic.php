#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDynamic();
cid=1

- 获取每页5条，第1页动态数据 @31;30;29;28;27;
- 获取每页5条，第2页动态数据 @26;25;24;23;22;
- 获取每页10条，第1页动态数据 @31;30;29;28;27;26;25;24;23;22;
- 获取每页10条，第2页动态数据 @21;20;19;50;18;49;17;48;16;47;
- 获取每页20条，第1页动态数据 @31;30;29;28;27;26;25;24;23;22;21;20;19;50;18;49;17;48;16;47;
- 获取每页20条，第2页动态数据 @15;46;14;45;13;44;12;43;11;42;10;41;9;40;8;39;7;38;6;37;

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('api')->gen(0);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('action')->loadYaml('action')->gen(50);
zenData('user')->gen(5);

$recPerPages = array(5, 10, 20);
$pagerIds    = array(1, 2);

$docTester = new docTest();
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[0])) && p() && e('27;28;29;30;31');                                              // 获取每页5条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[1])) && p() && e('22;23;24;25;26');                                              // 获取每页5条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[0])) && p() && e('22;23;24;25;26;27;28;29;30;31');                               // 获取每页10条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[1])) && p() && e('16;17;18;19;20;21;47;48;49;50');                               // 获取每页10条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[0])) && p() && e('16;17;18;19;20;21;22;23;24;25;26;27;28;29;30;31;47;48;49;50'); // 获取每页20条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[1])) && p() && e('6;7;8;9;10;11;12;13;14;15;37;38;39;40;41;42;43;44;45;46');     // 获取每页20条，第2页动态数据
