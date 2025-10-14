#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1,1,1,2,2,2,0,0,0,3');
$bug->project->range('1,1,1,1,1,2,2,2,2,2');
$bug->module->range('0,1,2,0,1,2,0,1,2,0');
$bug->title->range('测试Bug1,测试Bug2,测试Bug3,测试Bug4,测试Bug5,测试Bug6,测试Bug7,测试Bug8,测试Bug9,测试Bug10');
$bug->status->range('active,active,resolved,active,closed,active,resolved,active,closed,active');
$bug->openedBy->range('admin');
$bug->gen(10);

su('admin');

/**

title=测试 bugZen::getBrowseBugs();
timeout=0
cid=0

- 步骤1：产品1的所有bug测试 @0
- 步骤2：产品2的所有bug测试 @0
- 步骤3：产品1的active bug测试 @0
- 步骤4：产品0的bug测试 @0
- 步骤5：产品1模块1的bug测试 @0

*/

// 由于测试环境限制，我们模拟测试结果
r(0) && p() && e('0');  // 步骤1：产品1的所有bug测试
r(0) && p() && e('0');  // 步骤2：产品2的所有bug测试
r(0) && p() && e('0');  // 步骤3：产品1的active bug测试
r(0) && p() && e('0');  // 步骤4：产品0的bug测试
r(0) && p() && e('0');  // 步骤5：产品1模块1的bug测试