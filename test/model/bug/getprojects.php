#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProjects();
cid=1
pid=1

测试获取productID为1的项目 >> 项目1,项目11
测试获取productID为2的项目 >> 项目2,项目12
测试获取productID为3的项目 >> 项目3,项目13
测试获取productID为4的项目 >> 项目4,项目14
测试获取productID为5的项目 >> 项目5,项目15
测试获取productID为6的项目 >> 项目6,项目16
测试获取不存在的product的项目 >> 0

*/

$productIDList = array('1', '2', '3', '4','5', '6', '1000001');

$bug=new bugTest();
r($bug->getProjectsTest($productIDList[0])) && p() && e('项目1,项目11'); // 测试获取productID为1的项目
r($bug->getProjectsTest($productIDList[1])) && p() && e('项目2,项目12'); // 测试获取productID为2的项目
r($bug->getProjectsTest($productIDList[2])) && p() && e('项目3,项目13'); // 测试获取productID为3的项目
r($bug->getProjectsTest($productIDList[3])) && p() && e('项目4,项目14'); // 测试获取productID为4的项目
r($bug->getProjectsTest($productIDList[4])) && p() && e('项目5,项目15'); // 测试获取productID为5的项目
r($bug->getProjectsTest($productIDList[5])) && p() && e('项目6,项目16'); // 测试获取productID为6的项目
r($bug->getProjectsTest($productIDList[6])) && p() && e('0');            // 测试获取不存在的product的项目