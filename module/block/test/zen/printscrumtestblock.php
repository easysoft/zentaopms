#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumTestBlock();
timeout=0
cid=15301

- 测试type=all属性count @0
- 测试type=wait属性count @1
- 测试type=doing属性count @0
- 测试type=done属性count @0
- 测试count限制属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->name->range('测试单`1-5`');
$testtask->status->range('wait{3},doing{2}');
$testtask->deleted->range('0');
$testtask->gen(5);

zenData('product')->loadYaml('product')->gen(3);
zenData('project')->loadYaml('project')->gen(5);
zenData('user')->loadYaml('user')->gen(5);

su('admin');

global $tester, $app;
$tester->session->set('project', 11);

$blockTest = new blockZenTest();

// 测试参数 type=all
$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = 'all';
$block1->params->orderBy = 'id_desc';
$block1->params->count = 10;

// 测试参数 type=wait
$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->type = 'wait';
$block2->params->orderBy = 'id_desc';
$block2->params->count = 15;

// 测试参数 type=doing
$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->type = 'doing';
$block3->params->orderBy = 'id_desc';
$block3->params->count = 15;

// 测试参数 type=done
$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->type = 'done';
$block4->params->orderBy = 'id_desc';
$block4->params->count = 15;

// 测试参数 count限制
$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->type = 'all';
$block5->params->orderBy = 'id_desc';
$block5->params->count = 10;

$app->rawModule = 'block';
$app->rawMethod = 'dashboard';

r($blockTest->printScrumTestBlockTest($block1)) && p('count') && e('0');   // 测试type=all
r($blockTest->printScrumTestBlockTest($block2)) && p('count') && e('1');   // 测试type=wait
r($blockTest->printScrumTestBlockTest($block3)) && p('count') && e('0');   // 测试type=doing
r($blockTest->printScrumTestBlockTest($block4)) && p('count') && e('0');   // 测试type=done
r($blockTest->printScrumTestBlockTest($block5)) && p('count') && e('0');   // 测试count限制
