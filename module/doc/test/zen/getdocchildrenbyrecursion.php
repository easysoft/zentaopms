#!/usr/bin/env php
<?php

/**

title=测试 docZen::getDocChildrenByRecursion();
timeout=0
cid=0

- 步骤1:level为0时返回空数组 @0
- 步骤2:level为负数时返回空数组 @0
- 步骤3:获取docID为1且level为1的子文档 @9
- 步骤4:获取docID为1且level为2的子文档第2条的id属性 @2
- 步骤5:不存在的docID返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$doc = zenData('doc');
$doc->id->range('1-20');
$doc->lib->range('1-5');
$doc->parent->range('0,1{3},2{3},1{3},4{3},1{3},5{3}');
$doc->title->range('文档1,子文档1-1,子文档1-2,子文档1-3,子文档2-1,子文档2-2,子文档2-3,子文档3-1,子文档3-2,子文档3-3,子文档4-1,子文档4-2,子文档4-3,子文档5-1,子文档5-2,子文档5-3,子文档6-1,子文档6-2,子文档6-3,子文档7-1');
$doc->acl->range('open');
$doc->deleted->range('0');
$doc->gen(20);

su('admin');

$docTest = new docZenTest();

r($docTest->getDocChildrenByRecursionTest(1, 0)) && p() && e('0'); // 步骤1:level为0时返回空数组
r($docTest->getDocChildrenByRecursionTest(1, -1)) && p() && e('0'); // 步骤2:level为负数时返回空数组
r(count($docTest->getDocChildrenByRecursionTest(1, 1))) && p() && e('9'); // 步骤3:获取docID为1且level为1的子文档
r($docTest->getDocChildrenByRecursionTest(1, 2)) && p('2:id') && e('2'); // 步骤4:获取docID为1且level为2的子文档
r($docTest->getDocChildrenByRecursionTest(999, 1)) && p() && e('0'); // 步骤5:不存在的docID返回空数组