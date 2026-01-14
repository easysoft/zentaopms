#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDynamic();
cid=16089

- 获取每页5条，第1页动态数据 @5
- 获取每页5条，第2页动态数据 @5
- 获取每页10条，第1页动态数据 @10
- 获取每页10条，第2页动态数据 @10
- 获取每页20条，第1页动态数据 @20
- 获取每页20条，第2页动态数据 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('api')->gen(0);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('action')->loadYaml('action')->gen(50);
zenData('user')->gen(5);

$recPerPages = array(5, 10, 20);
$pagerIds    = array(1, 2);

$docTester = new docModelTest();
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[0])) && p() && e('5');  // 获取每页5条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[1])) && p() && e('5');  // 获取每页5条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[0])) && p() && e('10'); // 获取每页10条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[1])) && p() && e('10'); // 获取每页10条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[0])) && p() && e('20'); // 获取每页20条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[1])) && p() && e('20'); // 获取每页20条，第2页动态数据
