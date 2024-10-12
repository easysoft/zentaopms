#!/usr/bin/env php
<?php

/**

title=taskModel->getChildTasksByList();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->loadYaml('task')->gen(10);

$taskIdList = array(array(1), array(2), array(1, 2), array(3), array(11));

$task = new taskTest();
