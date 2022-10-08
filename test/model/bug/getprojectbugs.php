#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProjectBugs();
cid=1
pid=1

测试获取项目ID为11的bug >> 测试单转Bug1;BUG3;BUG2
测试获取项目ID为12的bug >> SonarQube_Bug2;BUG6;BUG5  
测试获取项目ID为13的bug >> 测试单转Bug3;BUG9;bug8
测试获取项目ID为14的bug >> SonarQube_Bug4;BUG12;BUG11
测试获取项目ID为15的bug >> 测试单转Bug5;缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15;BUG14
测试获取项目ID为16的bug >> SonarQube_Bug6;BUG18;BUG17
测试获取不存在的项目的bug >> 0

*/

$projectIDList = array('11', '12', '13', '14', '15', '16', '1000001');

$bug=new bugTest();
r($bug->getProjectBugsTest($projectIDList[0])) && p('0:title;1:title;2:title') && e('测试单转Bug1;BUG3;BUG2');     // 测试获取项目ID为11的bug
r($bug->getProjectBugsTest($projectIDList[1])) && p('0:title;1:title;2:title') && e('SonarQube_Bug2;BUG6;BUG5  '); // 测试获取项目ID为12的bug
r($bug->getProjectBugsTest($projectIDList[2])) && p('0:title;1:title;2:title') && e('测试单转Bug3;BUG9;bug8');     // 测试获取项目ID为13的bug
r($bug->getProjectBugsTest($projectIDList[3])) && p('0:title;1:title;2:title') && e('SonarQube_Bug4;BUG12;BUG11'); // 测试获取项目ID为14的bug
r($bug->getProjectBugsTest($projectIDList[4])) && p('0:title;1:title;2:title') && e('测试单转Bug5;缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15;BUG14'); // 测试获取项目ID为15的bug
r($bug->getProjectBugsTest($projectIDList[5])) && p('0:title;1:title;2:title') && e('SonarQube_Bug6;BUG18;BUG17'); // 测试获取项目ID为16的bug
r($bug->getProjectBugsTest($projectIDList[6])) && p('0:title;1:title;2:title') && e('0');                          // 测试获取不存在的项目的bug