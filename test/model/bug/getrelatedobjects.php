#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getRelatedObjects();
cid=1
pid=1

获取bug关联的 branch 的 id,name >> :,0:
获取bug关联的 task 的 id,name >> :,0:
获取bug关联的 case 的 id,title >> :,0:
获取bug关联的 resolvedBuild 的 id,name >> :,0:,trunk:主干
获取bug关联的 openedBuild 的 id,name >> :,0:,trunk:主干,1:项目版本版本1
获取bug关联的 story 的 id,title >> :,0:,2:软件需求2,6:软件需求6,10:软件需求10,14:软件需求14,18:软件需求18,22:软件需求22,26:软件需求26,30:软件需求30,34:软件需求34

*/

$objects = array('branch', 'task', 'case', 'resolvedBuild', 'openedBuild', 'story');
$pairs   = array('id,name', 'id,title');

$bug=new bugTest();
$_SESSION['bugQueryCondition'] = 'id < 10';

r($bug->getRelatedObjectsTest($objects[0], $pairs[0])) && p() && e(':,0:'); // 获取bug关联的 branch 的 id,name
r($bug->getRelatedObjectsTest($objects[1], $pairs[0])) && p() && e(':,0:'); // 获取bug关联的 task 的 id,name
r($bug->getRelatedObjectsTest($objects[2], $pairs[1])) && p() && e(':,0:'); // 获取bug关联的 case 的 id,title
r($bug->getRelatedObjectsTest($objects[3], $pairs[0])) && p() && e(':,0:,trunk:主干'); // 获取bug关联的 resolvedBuild 的 id,name
r($bug->getRelatedObjectsTest($objects[4], $pairs[0])) && p() && e(':,0:,trunk:主干,1:项目版本版本1'); // 获取bug关联的 openedBuild 的 id,name
r($bug->getRelatedObjectsTest($objects[5], $pairs[1])) && p() && e(':,0:,2:软件需求2,6:软件需求6,10:软件需求10,14:软件需求14,18:软件需求18,22:软件需求22,26:软件需求26,30:软件需求30,34:软件需求34'); // 获取bug关联的 story 的 id,title