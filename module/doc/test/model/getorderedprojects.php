#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedProjects();
cid=16120

- 获取系统中已排序的项目第1条的11属性 @项目11
- 获取系统中包括ID=11已排序的项目第2条的16属性 @项目16
- 获取系统中包括ID=11已排序的项目第1条的11属性 @项目11
- 获取系统中已排序的项目数量 @3
- 获取系统中包括ID=11已排序的项目数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(20);
zenData('user')->gen(5);
su('admin');

$appends = array(0, 11);

$docTester      = new docModelTest();
$allProjects    = $docTester->getOrderedProjectsTest($appends[0]);
$appendProjects = $docTester->getOrderedProjectsTest($appends[1]);

r($allProjects)    && p('1:11') && e('项目11'); // 获取系统中已排序的项目
r($appendProjects) && p('2:16') && e('项目16'); // 获取系统中包括ID=11已排序的项目
r($appendProjects) && p('1:11') && e('项目11'); // 获取系统中包括ID=11已排序的项目

r(count($allProjects))    && p() && e('3'); // 获取系统中已排序的项目数量
r(count($appendProjects)) && p() && e('3'); // 获取系统中包括ID=11已排序的项目数量
