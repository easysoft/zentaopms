#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallRiskBlock();
timeout=0
cid=0

- 执行blockTest模块的printWaterfallRiskBlockTest方法，参数是$block1
 - 第count,hasUsers,1条的status属性 @5
 - 第count,hasUsers,1条的1:project属性 @1
- 执行blockTest模块的printWaterfallRiskBlockTest方法，参数是$block2
 - 第count,hasUsers,1条的status属性 @3
 - 第count,hasUsers,1条的1:project属性 @1
- 执行blockTest模块的printWaterfallRiskBlockTest方法，参数是$block3
 - 第count,hasUsers,1条的status属性 @2
 - 第count,hasUsers,1条的1:project属性 @1
- 执行blockTest模块的printWaterfallRiskBlockTest方法，参数是$block4
 - 属性count @5
 - 属性hasUsers @1
- 执行blockTest模块的printWaterfallRiskBlockTest方法，参数是$block5
 - 属性count @0
 - 属性hasUsers @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Project1,Project2,Project3,Project4,Project5,Project6,Project7,Project8,Project9,Project10');
$project->type->range('project');
$project->model->range('waterfall');
$project->status->range('doing');
$project->gen(10);

$risk = zenData('risk');
$risk->project->range('1{5},2{3},3{2}');
$risk->status->range('active{7},closed{3}');
$risk->pri->range('1-4');
$risk->gen(10);

zenData('user')->gen(10);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->projectID = 1;
$block1->params->type = 'active';
$block1->params->count = 20;
$block1->params->orderBy = 'id_desc';

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->projectID = 2;
$block2->params->type = 'active';
$block2->params->count = 20;
$block2->params->orderBy = 'id_desc';

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->projectID = 3;
$block3->params->type = 'active';
$block3->params->count = 20;
$block3->params->orderBy = 'id_desc';

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->projectID = 1;
$block4->params->type = 'all';
$block4->params->count = 20;
$block4->params->orderBy = 'id_desc';

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->projectID = 999;
$block5->params->type = 'active';
$block5->params->count = 20;
$block5->params->orderBy = 'id_desc';

r($blockTest->printWaterfallRiskBlockTest($block1)) && p('count,hasUsers,1:status,1:project') && e('5,1,active,1');
r($blockTest->printWaterfallRiskBlockTest($block2)) && p('count,hasUsers,1:status,1:project') && e('3,1,active,2');
r($blockTest->printWaterfallRiskBlockTest($block3)) && p('count,hasUsers,1:status,1:project') && e('2,1,active,3');
r($blockTest->printWaterfallRiskBlockTest($block4)) && p('count,hasUsers') && e('5,1');
r($blockTest->printWaterfallRiskBlockTest($block5)) && p('count,hasUsers') && e('0,1');