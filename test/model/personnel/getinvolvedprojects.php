#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getInvolvedProjects();
cid=1
pid=1

计数project11，12的团队人员个数 >> 4
取出其中一个角色 >> 1
传入不存在的ID >> 0

*/

$personnel = new personnelTest('admin');

$projectID = array();
$projectID[0] = array(11, 12);
$projectID[1] = array(1111);

$result1 = count($personnel->getInvolvedProjectsTest($projectID[0]));
$result2 = $personnel->getInvolvedProjectsTest($projectID[0]);
$result3 = count($personnel->getInvolvedProjectsTest($projectID[1]));

r($result1) && p()        && e('4'); //计数project11，12的团队人员个数
r($result2) && p('admin') && e('1'); //取出其中一个角色
r($result3) && p()        && e('0'); //传入不存在的ID