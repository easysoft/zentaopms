#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('瀑布项目1,阶段a,阶段a子1,阶段a子1子1,阶段b');
$execution->type->range('project,stage{4}');
$execution->project->range('0,1{4}');
$execution->parent->range('0,1,2,3,1');
$execution->path->range("`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,3,4,`,`,1,5,`");
$execution->status->range('doing,doing,doing,closed,suspended');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->deleted->range('0,1{4}');
$execution->gen(5);

$action = zdTable('action');
$action->id->range('1-4');
$action->objectType->range('execution');
$action->objectID->range('2,3,4,5');
$action->project->range('1');
$action->actor->range('admin');
$action->action->range('deleted');
$action->extra->range('1');
$action->gen(4);


/**

title=测试 actionModel->restoreStages();
cid=1
pid=1

测试还原id为2和3的阶段 >> 0

*/

$action = new actionTest();

$hasDeleted = $action->restoreStagesTest(array(2 => 2, 3 => 3), array(1, 2));

r($hasDeleted[2]) && p('extra') && e('0');  // 测试还原id为2和3的阶段
