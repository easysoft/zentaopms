#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getProjectStats();
cid=1
pid=1

测试是否能拿到数据 >> scrum
测试是否能拿到数据 >> 0
测试是否能拿到数据 >> admin

*/

$tutorial = new tutorialTest();

r($tutorial->getProjectStatsTest()) && p('2:model')            && e('scrum'); //测试是否能拿到数据
r($tutorial->getProjectStatsTest()) && p('2[hours]:totalLeft') && e('0');     //测试是否能拿到数据
r($tutorial->getProjectStatsTest()) && p('2[teamMembers]:0')   && e('admin'); //测试是否能拿到数据