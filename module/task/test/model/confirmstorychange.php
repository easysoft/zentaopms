#!/usr/bin/env php
<?php
/**

title=taskModel->confirmStoryChange();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('project')->loadYaml('project')->gen(3);
zenData('task')->loadYaml('task')->gen(5);
zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('story')->loadYaml('story')->gen(5);
zenData('user')->gen(5);
su('admin');
