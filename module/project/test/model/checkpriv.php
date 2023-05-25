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

- 执行project模块的checkPriv方法，参数是1 @1

- 执行project模块的checkPriv方法，参数是2 @0

- 执行project模块的checkPriv方法，参数是2 @1

*/

global $tester;
$tester->loadModel('project');
r($tester->project->checkPriv(1)) && p() && e('1');
r($tester->project->checkPriv(2)) && p() && e('0');

su('admin');
r($tester->project->checkPriv(2)) && p() && e('1');