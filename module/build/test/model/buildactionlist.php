#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';
su('admin');

zdTable('build')->config('build')->gen(7);

$execution = zdTable('project')->config('execution');
$execution->deleted->range('0,1');
$execution->gen(30);

/**

title=测试 buildModel->buildActionList();
timeout=0
cid=1

*/

$buildIdList = range(2, 7);
$executions  = array('normal' => 11, 'deleted' => 60, 'kanban' => 124);

$buildTester = new buildTest();
r($buildTester->buildActionListObject($buildIdList[0], $executions['normal']))  && p() && e('linkStory|createTest|viewBug|buildEdit|delete');   // 正常的执行
r($buildTester->buildActionListObject($buildIdList[1], $executions['deleted'])) && p() && e('linkStory|-createTest|viewBug|buildEdit|delete');  // 已删除的执行
r($buildTester->buildActionListObject($buildIdList[1], $executions['kanban']))  && p() && e('linkStory|-createTest|bugList|buildEdit|delete');  // 看板执行
