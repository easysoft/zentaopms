#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProjectBugs();
cid=1
pid=1

测试获取项目ID为11的bug   >> BUG3;BUG2;BUG1
测试获取项目ID为12的bug   >> BUG6;BUG5;BUG4
测试获取项目ID为13的bug   >> BUG9;bug8;缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
测试获取项目ID为14的bug   >> BUG12;BUG11;BUG10
测试获取项目ID为15的bug   >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15;BUG14;BUG13
测试获取项目ID为16的bug   >> BUG18;BUG17;bug16
测试获取不存在的项目的bug >> 0


*/


$projectIDList = array('11', '12', '13', '14', '15', '16', '1000001');

$bug=new bugTest();
r($bug->getProjectBugsTest($projectIDList[0])) && p('0:title;1:title;2:title') && e('BUG3;BUG2;BUG1');     // 测试获取项目ID为11的bug
r($bug->getProjectBugsTest($projectIDList[1])) && p('0:title;1:title;2:title') && e('BUG6;BUG5;BUG4');     // 测试获取项目ID为12的bug
r($bug->getProjectBugsTest($projectIDList[2])) && p('0:title;1:title;2:title') && e('BUG9;bug8;缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7');    // 测试获取项目ID为13的bug
r($bug->getProjectBugsTest($projectIDList[3])) && p('0:title;1:title;2:title') && e('BUG12;BUG11;BUG10');  // 测试获取项目ID为14的bug
r($bug->getProjectBugsTest($projectIDList[4])) && p('0:title;1:title;2:title') && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15;BUG14;BUG13'); // 测试获取项目ID为15的bug
r($bug->getProjectBugsTest($projectIDList[5])) && p('0:title;1:title;2:title') && e('BUG18;BUG17;bug16');  // 测试获取项目ID为16的bug
r($bug->getProjectBugsTest($projectIDList[6])) && p('0:title;1:title;2:title') && e('0');                  // 测试获取不存在的项目的bug
