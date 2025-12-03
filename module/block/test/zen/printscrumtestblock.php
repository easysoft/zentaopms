#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumTestBlock();
timeout=0
cid=15301

- 执行blockTest模块的printScrumTestBlockTest方法，参数type=all 属性blockType @all
- 执行blockTest模块的printScrumTestBlockTest方法，参数type=wait 属性testtaskCount @3
- 执行blockTest模块的printScrumTestBlockTest方法，参数type=doing 属性testtaskCount @5
- 执行blockTest模块的printScrumTestBlockTest方法，参数type=done 属性testtaskCount @7
- 执行blockTest模块的printScrumTestBlockTest方法，参数count=10 属性blockCount @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

global $tester;
$tester->session->set('project', 1);

$blockTest = new blockTest();

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

r($blockTest->printScrumTestBlockTest($block1)) && p('blockType') && e('all');           // 测试type=all
r($blockTest->printScrumTestBlockTest($block2)) && p('testtaskCount') && e('3');         // 测试type=wait
r($blockTest->printScrumTestBlockTest($block3)) && p('testtaskCount') && e('5');         // 测试type=doing
r($blockTest->printScrumTestBlockTest($block4)) && p('testtaskCount') && e('7');         // 测试type=done
r($blockTest->printScrumTestBlockTest($block5)) && p('blockCount') && e('10');           // 测试count限制
