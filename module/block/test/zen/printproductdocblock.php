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

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zendata('product')->loadYaml('product_printproductdocblock', false, 2)->gen(10);
zendata('doc')->loadYaml('doc_printproductdocblock', false, 2)->gen(50);
zendata('user')->loadYaml('user_printproductdocblock', false, 2)->gen(10);

su('admin');

$blockTest = new blockTest();

$result1 = $blockTest->printProductDocBlockTest();
r($result1->success) && p() && e('1');
r($result1->type) && p() && e('involved');

$result2 = $blockTest->printProductDocBlockTest((object)array('params' => (object)array('count' => 5)));
r($result2->success) && p() && e('1');

$result3 = $blockTest->printProductDocBlockTest(null, array('type' => 'all'));
r($result3->type) && p() && e('all');