#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProductDocBlock();
timeout=0
cid=0

- 执行$result1->success @1
- 执行$result1->type @involved
- 执行$result2->success @1
- 执行$result3->type @all
- 执行$result4->success @1
- 执行$result4->type @involved
- 执行$result5->productsCount @10
- 执行$result5->totalDocsCount @10
- 执行$result6->success @1
- 执行$result7->success @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zendata('product')->loadYaml('product_printproductdocblock', false, 2)->gen(10);
zendata('doc')->loadYaml('doc_printproductdocblock', false, 2)->gen(50);
zendata('user')->loadYaml('user_printproductdocblock', false, 2)->gen(10);

su('admin');

$blockTest = new blockTest();

// 测试步骤1：测试默认参数下获取产品文档区块数据
$result1 = $blockTest->printProductDocBlockTest();
r($result1->success) && p() && e('1');
r($result1->type) && p() && e('involved');

// 测试步骤2：测试指定count参数限制文档数量
$result2 = $blockTest->printProductDocBlockTest((object)array('params' => (object)array('count' => 5)));
r($result2->success) && p() && e('1');

// 测试步骤3：测试type参数为all时获取所有产品文档
$result3 = $blockTest->printProductDocBlockTest(null, array('type' => 'all'));
r($result3->type) && p() && e('all');

// 测试步骤4：测试空block参数和空params参数的处理
$result4 = $blockTest->printProductDocBlockTest(null, array());
r($result4->success) && p() && e('1');
r($result4->type) && p() && e('involved');

// 测试步骤5：测试产品文档分组和数量统计功能
$result5 = $blockTest->printProductDocBlockTest();
r($result5->productsCount) && p() && e('10');
r($result5->totalDocsCount) && p() && e('10');

// 测试步骤6：测试边界值count为0的情况
$result6 = $blockTest->printProductDocBlockTest((object)array('params' => (object)array('count' => 0)));
r($result6->success) && p() && e('1');

// 测试步骤7：测试极大count值的处理
$result7 = $blockTest->printProductDocBlockTest((object)array('params' => (object)array('count' => 999)));
r($result7->success) && p() && e('1');