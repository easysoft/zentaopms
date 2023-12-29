#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGroupDataForGantt()
cid=0

- 检查构建分组Gantt数据。
 - 属性id @1
 - 属性type @group
 - 属性text @开发

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zdTable('user')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$users = $tester->programplan->loadModel('user')->getPairs();

$groupID = 1;
$group   = '开发';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $users)) && p('id,type,text') && e("1,group,开发"); //检查构建分组Gantt数据。
