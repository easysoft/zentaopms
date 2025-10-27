#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleStoryBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleStoryBlockTest方法，参数是$block1 
 - 属性hasValidation @1
 - 属性type @assignedTo
- 执行blockTest模块的printSingleStoryBlockTest方法，参数是$block2 
 - 属性hasValidation @1
 - 属性type @openedBy
- 执行blockTest模块的printSingleStoryBlockTest方法，参数是$block1 属性count @10
- 执行blockTest模块的printSingleStoryBlockTest方法，参数是$block2 属性orderBy @id_asc
- 执行blockTest模块的printSingleStoryBlockTest方法，参数是$block5 属性hasValidation @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('story')->gen(20);
zenData('user')->gen(10);

su('admin');

$blockTest = new blockTest();

$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->type = 'assignedTo';
$block1->params->count = 10;
$block1->params->orderBy = 'id_desc';

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->type = 'openedBy';
$block2->params->count = 15;
$block2->params->orderBy = 'id_asc';

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->type = 'closedBy';
$block3->params->count = 5;
$block3->params->orderBy = 'status_desc';

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->type = 'reviewBy';
$block4->params->count = 20;
$block4->params->orderBy = 'openedDate_asc';

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->type = 'invalid@type';
$block5->params->count = 10;
$block5->params->orderBy = 'id_asc';

r($blockTest->printSingleStoryBlockTest($block1)) && p('hasValidation,type') && e('1,assignedTo');
r($blockTest->printSingleStoryBlockTest($block2)) && p('hasValidation,type') && e('1,openedBy');
r($blockTest->printSingleStoryBlockTest($block1)) && p('count') && e('10');
r($blockTest->printSingleStoryBlockTest($block2)) && p('orderBy') && e('id_asc');
r($blockTest->printSingleStoryBlockTest($block5)) && p('hasValidation') && e('~~');