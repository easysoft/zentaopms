#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->suspend();
cid=1
pid=1

挂起id为56状态是doing的项目 >> suspended
挂起id为73状态是suspended的项目 >> suspendedDate
挂起id为74状态是closed的项目 >> suspended

*/

global $tester;
$tester->loadModel('project');

$changes1 = $tester->project->suspend(56);
$changes2 = $tester->project->suspend(73);
$changes3 = $tester->project->suspend(74);

r($changes1[0]) && p('new')   && e('suspended');     // 挂起id为56状态是doing的项目
r($changes2[0]) && p('field') && e('suspendedDate'); // 挂起id为73状态是suspended的项目
r($changes3[0]) && p('new')   && e('suspended');     // 挂起id为74状态是closed的项目
