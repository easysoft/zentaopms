#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallGanttBlock();
timeout=0
cid=15309

- 执行blockTest模块的printWaterfallGanttBlockTest方法，参数是$block1  @1
- 执行blockTest模块的printWaterfallGanttBlockTest方法，参数是$block2, array  @1
- 执行blockTest模块的printWaterfallGanttBlockTest方法，参数是$block3, array  @1
- 执行blockTest模块的printWaterfallGanttBlockTest方法，参数是$block4, array  @1
- 执行blockTest模块的printWaterfallGanttBlockTest方法，参数是$block5, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->gen(5);
zendata('project')->gen(10);
zendata('productplan')->gen(20);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdclass();
$block1->id = 1;
$block1->code = 'waterfallgantt';

$block2 = new stdclass();
$block2->id = 2;
$block2->code = 'waterfallgantt';

$block3 = new stdclass();
$block3->id = 3;
$block3->code = 'waterfallgantt';

$block4 = new stdclass();
$block4->id = 4;
$block4->code = 'waterfallgantt';

$block5 = new stdclass();
$block5->id = 5;
$block5->code = 'waterfallgantt';

r($blockTest->printWaterfallGanttBlockTest($block1)) && p() && e('1');
r($blockTest->printWaterfallGanttBlockTest($block2, array('productID' => 1))) && p() && e('1');
r($blockTest->printWaterfallGanttBlockTest($block3, array('productID' => 999))) && p() && e('1');
r($blockTest->printWaterfallGanttBlockTest($block4, array())) && p() && e('1');
r($blockTest->printWaterfallGanttBlockTest($block5, array('productID' => 0))) && p() && e('1');