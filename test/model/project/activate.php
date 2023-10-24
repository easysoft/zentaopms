#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->activate();
cid=1
pid=1

激活id为66状态是closed的项目 >> status,closed,doing
激活id为73状态是suspended的项目 >> status,suspended,doing

*/

global $tester;
$tester->loadModel('project');

$changes1 = $tester->project->activate(66);
$changes2 = $tester->project->activate(73);

r($changes1) && p('1:field,old,new') && e('status,closed,doing');    // 激活id为66状态是closed的项目
r($changes2) && p('1:field,old,new') && e('status,suspended,doing'); // 激活id为73状态是suspended的项目
