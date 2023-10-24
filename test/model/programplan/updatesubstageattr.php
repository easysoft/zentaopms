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
$execution->attribute->range(" ,mix,request,request,review");
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试programplanModel->updateSubStageAttr();
cid=1
pid=1

测试更改id为3的阶段为综合 >> request
测试更改id为2的阶段为设计 >> design

*/

$plan = new programplanTest();

r($plan->updateSubStageAttrTest(3, 'mix', 4))    && p('') && e('request');  // 测试更改id为3的阶段为综合
r($plan->updateSubStageAttrTest(2, 'design', 3)) && p('') && e('design');   // 测试更改id为2的阶段为设计
