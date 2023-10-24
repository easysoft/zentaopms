#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->linkBug();
cid=1
pid=1

正常任务关联解决Bug >> 1,,311,312
正常任务关联遗留Bug >> 1,,311,312,,311,312
停止维护任务关联解决Bug >> 6,,311,312
停止维护任务关联遗留Bug >> 6,,311,312,,311,312

*/
$releaseID = array('1', '6');
$bugs      = array('311', '312');
$type      = array('bug', 'leftBug');

$release   = new releaseTest();

r($release->linkBugTest($releaseID[0], $type[0], $bugs)) && p('id,bugs,leftBugs') && e('1,,311,312'); //正常任务关联解决Bug
r($release->linkBugTest($releaseID[0], $type[1], $bugs)) && p('id,bugs,leftBugs') && e('1,,311,312,,311,312'); //正常任务关联遗留Bug
r($release->linkBugTest($releaseID[1], $type[0], $bugs)) && p('id,bugs,leftBugs') && e('6,,311,312'); //停止维护任务关联解决Bug
r($release->linkBugTest($releaseID[1], $type[1], $bugs)) && p('id,bugs,leftBugs') && e('6,,311,312,,311,312'); //停止维护任务关联遗留Bug

