#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getFinishClosedTotal();
timeout=0
cid=18636

- 步骤1：获取story类型的已完成关闭需求数量(4+3+3=10) @10
- 步骤2：获取requirement类型的已完成关闭需求数量(1+1=2) @2
- 步骤3：获取epic类型的已完成关闭需求数量 @0
- 步骤4：测试无效类型参数 @0
- 步骤5：测试默认参数(默认story类型) @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$table = zenData('story');
$table->id->range('1-15');
$table->product->range('1-3');
$table->title->range('需求{$id}');
$table->type->range('story{10},requirement{3},epic{2}');
$table->status->range('closed');
$table->closedReason->range('done{12},cancel{3}');
$table->deleted->range('0');
$table->gen(15);

su('admin');

$storyTest = new storyTaoTest();

r($storyTest->getFinishClosedTotalTest('story')) && p() && e('10'); // 步骤1：获取story类型的已完成关闭需求数量(4+3+3=10)
r($storyTest->getFinishClosedTotalTest('requirement')) && p() && e('2'); // 步骤2：获取requirement类型的已完成关闭需求数量(1+1=2)
r($storyTest->getFinishClosedTotalTest('epic')) && p() && e('0'); // 步骤3：获取epic类型的已完成关闭需求数量
r($storyTest->getFinishClosedTotalTest('invalid')) && p() && e('0'); // 步骤4：测试无效类型参数
r($storyTest->getFinishClosedTotalTest()) && p() && e('10'); // 步骤5：测试默认参数(默认story类型)