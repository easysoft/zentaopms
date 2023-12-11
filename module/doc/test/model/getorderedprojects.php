#!/usr/bin/env php
<?php

/**

title=测试 docModel->getOrderedProjects();
cid=1

- 获取系统中已排序的项目第1条的11属性 @项目11
- 获取系统中包括ID=11已排序的项目第1条的11属性 @项目11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->gen(20);
zdTable('user')->gen(5);
su('admin');

$appends = array(0, 11);

$docTester = new docTest();
r($docTester->getOrderedProjectsTest($appends[0])) && p('1:11') && e('项目11'); // 获取系统中已排序的项目
r($docTester->getOrderedProjectsTest($appends[1])) && p('1:11') && e('项目11'); // 获取系统中包括ID=11已排序的项目
