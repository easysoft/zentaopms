#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedExecutions();
cid=16175

- 获取系统中已排序的执行第0条的102属性 @敏捷项目1 / 迭代6
- 获取系统中已排序的执行第1条的101属性 @敏捷项目1 / 迭代5
- 获取系统中包括ID=101已排序的执行第0条的102属性 @敏捷项目1 / 迭代6
- 获取系统中包括ID=101已排序的执行第1条的101属性 @敏捷项目1 / 迭代5
- 获取系统中已排序的执行数量 @3
- 获取系统中包括ID=101已排序的执行数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('execution')->gen(20);
zenData('user')->gen(5);
su('admin');

$appends = array(0, 101);

$docTester = new docTaoTest();
$orderedExecutions       = $docTester->getOrderedExecutionsTest($appends[0]);
$appendOrderedExecutions = $docTester->getOrderedExecutionsTest($appends[1]);
r($orderedExecutions)       && p('0:102') && e('敏捷项目1 / 迭代6'); // 获取系统中已排序的执行
r($orderedExecutions)       && p('1:101') && e('敏捷项目1 / 迭代5'); // 获取系统中已排序的执行
r($appendOrderedExecutions) && p('0:102') && e('敏捷项目1 / 迭代6'); // 获取系统中包括ID=101已排序的执行
r($appendOrderedExecutions) && p('1:101') && e('敏捷项目1 / 迭代5'); // 获取系统中包括ID=101已排序的执行

r(count($orderedExecutions))       && p() && e('3'); // 获取系统中已排序的执行数量
r(count($appendOrderedExecutions)) && p() && e('3'); // 获取系统中包括ID=101已排序的执行数量
