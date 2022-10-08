#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getAllProjectIds();
cid=1
pid=1

测试projectId为11的项目 >> 11
测试projectId为12的项目 >> 12
测试projectId为13的项目 >> 13
测试projectId为41的项目 >> 41
测试projectId为51的项目 >> 51
测试projectId为91的项目 >> 91

*/

$projectIDList = array('11', '12', '13', '41', '51', '91');

$bug=new bugTest();
r($bug->getAllProjectIdsTest()) && p("$projectIDList[0]") && e('11'); // 测试projectId为11的项目
r($bug->getAllProjectIdsTest()) && p("$projectIDList[1]") && e('12'); // 测试projectId为12的项目
r($bug->getAllProjectIdsTest()) && p("$projectIDList[2]") && e('13'); // 测试projectId为13的项目
r($bug->getAllProjectIdsTest()) && p("$projectIDList[3]") && e('41'); // 测试projectId为41的项目
r($bug->getAllProjectIdsTest()) && p("$projectIDList[4]") && e('51'); // 测试projectId为51的项目
r($bug->getAllProjectIdsTest()) && p("$projectIDList[5]") && e('91'); // 测试projectId为91的项目