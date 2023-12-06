#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('team')->gen(5);
zdTable('project')->gen(20);

/**

title=测试 personnelModel->getInvolvedProjects();
cid=1
pid=1

计数project11，12的团队人员个数 >> 2
取出其中一个角色                >> 1
传入不存在的ID                  >> 0

*/

$personnel = new personnelTest('admin');

$projectID = array(array(11, 12), array(10000));

$result1 = count($personnel->getInvolvedProjectsTest($projectID[0]));
$result2 = $personnel->getInvolvedProjectsTest($projectID[0]);
$result3 = count($personnel->getInvolvedProjectsTest($projectID[1]));

r($result1) && p()        && e('2'); //计数project11，12的团队人员个数
r($result2) && p('admin') && e('1'); //取出其中一个角色
r($result3) && p()        && e('0'); //传入不存在的ID
