#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTesttaskBlock();
timeout=0
cid=15300

- 执行blockTest模块的printTesttaskBlockTest方法，参数type=all 属性hasValidation @1
- 执行blockTest模块的printTesttaskBlockTest方法，参数type=wait 属性type @wait
- 执行blockTest模块的printTesttaskBlockTest方法，参数type=doing 属性type @doing
- 执行blockTest模块的printTesttaskBlockTest方法，参数type=done 属性type @done
- 执行blockTest模块的printTesttaskBlockTest方法，参数count=3 属性count @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

// 测试参数 type=all
$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = 'all';
$block1->params->orderBy = 'id_desc';
$block1->params->count = 15;

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
$block5->params->count = 3;

r($blockTest->printTesttaskBlockTest($block1)) && p('hasValidation') && e('1'); // 测试type=all, 验证参数有效性
r($blockTest->printTesttaskBlockTest($block2)) && p('type') && e('wait');       // 测试type=wait
r($blockTest->printTesttaskBlockTest($block3)) && p('type') && e('doing');      // 测试type=doing
r($blockTest->printTesttaskBlockTest($block4)) && p('type') && e('done');       // 测试type=done
r($blockTest->printTesttaskBlockTest($block5)) && p('count') && e('3');         // 测试count限制
