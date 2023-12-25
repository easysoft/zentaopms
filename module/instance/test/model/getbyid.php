#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);
zdTable('space')->gen(5);
zdTable('solution')->gen(5);

/**

title=instanceModel->getByID();
timeout=0
cid=1


*/

global $tester;
$tester->loadModel('instance');
