#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('user1');

zdTable('project')->config('project')->gen(8);
zdTable('user')->config('user')->gen(2);

/**

title=测试 projectModel::checkPriv;
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
r($tester->project->checkPriv(1)) && p() && e('1');
r($tester->project->checkPriv(2)) && p() && e('0');

su('admin');
r($tester->project->checkPriv(2)) && p() && e('1');
