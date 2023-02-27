#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
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
$execution->gen(5);

/**

title=测试programplanModel->getSelfAndChildrenList();
cid=1
pid=1

测试id为2时获取自己的状态 >> doing
测试id为2时获取自己某一个后代的状态 >> 2

*/

$plan         = new programplanTest();
$topPlan      = $plan->getSelfAndChildrenListTest(2);
$topPlanCount = count($topPlan[2]);

r($topPlan[2][2])     && p('status') && e('doing'); // 测试id为2时获取自己的状态
r($topPlanCount - 1)  && p('')       && e(2);       // 测试id为2时获取自己后代的个数
