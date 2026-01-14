#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::getReviewRelated();
timeout=0
cid=14954

- 步骤1：正常review查询project 1，返回第一个和第二个product以及project ID
 - 第0条的1属性 @1
 - 第0条的2属性 @2
 - 属性1 @1
- 步骤2：不存在的review对象，返回默认值
 - 第0条的0属性 @0
 - 属性1 @0
- 步骤3：review存在但project为0，返回默认值
 - 第0条的0属性 @0
 - 属性1 @0
- 步骤4：review关联project 4，返回products和project ID
 - 第0条的1属性 @1
 - 第0条的2属性 @2
 - 属性1 @4
- 步骤5：边界值测试ID为0，返回默认值
 - 第0条的0属性 @0
 - 属性1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$reviewTable = zenData('review');
$reviewTable->id->range('1-20');
$reviewTable->title->range('评审1,评审2,评审3{5},评审4{10}');
$reviewTable->project->range('1,2,3{3},4{5},0{9}');
$reviewTable->status->range('wait,doing,done{18}');
$reviewTable->gen(20);

$projectproductTable = zenData('projectproduct');
$projectproductTable->project->range('1{3},2{2},3{4},4{3}');
$projectproductTable->product->range('1,2,3,1,2,1,2,3,4,1,2,3');
$projectproductTable->gen(12);

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->getReviewRelatedTest('review', 1)) && p('0:1;0:2;1') && e('1;2;1');  // 步骤1：正常review查询project 1，返回第一个和第二个product以及project ID
r($actionTest->getReviewRelatedTest('review', 999)) && p('0:0;1') && e('0;0');            // 步骤2：不存在的review对象，返回默认值
r($actionTest->getReviewRelatedTest('review', 16)) && p('0:0;1') && e('0;0');             // 步骤3：review存在但project为0，返回默认值
r($actionTest->getReviewRelatedTest('review', 6)) && p('0:1;0:2;1') && e('1;2;4');       // 步骤4：review关联project 4，返回products和project ID
r($actionTest->getReviewRelatedTest('review', 0)) && p('0:0;1') && e('0;0');              // 步骤5：边界值测试ID为0，返回默认值