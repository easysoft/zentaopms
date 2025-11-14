#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printExecutionListBlock();
timeout=0
cid=15261

- 测试count为5时返回5条执行记录属性hasExecutions @true
- 测试count为3时返回成功属性success @1
- 测试type为all时正常返回执行记录属性success @1
- 测试type为wait时返回成功属性success @1
- 测试type为doing时返回成功属性success @1
- 测试type包含非法字符时返回空结果属性hasExecutions @false
- 测试count为0时使用默认值返回执行记录属性hasExecutions @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$blockTest = new blockTest();

$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->count = 5;
$block1->params->type = 'all';

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->count = 3;
$block2->params->type = 'all';

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->count = 4;
$block3->params->type = 'all';

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->count = 4;
$block4->params->type = 'wait';

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->count = 4;
$block5->params->type = 'doing';

$block6 = new stdClass();
$block6->params = new stdClass();
$block6->params->count = 10;
$block6->params->type = 'all; DROP TABLE';

$block7 = new stdClass();
$block7->params = new stdClass();
$block7->params->count = 0;
$block7->params->type = 'all';

r($blockTest->printExecutionListBlockTest($block1)) && p('hasExecutions') && e('true'); // 测试count为5时返回5条执行记录
r($blockTest->printExecutionListBlockTest($block2)) && p('success') && e('1'); // 测试count为3时返回成功
r($blockTest->printExecutionListBlockTest($block3)) && p('success') && e('1'); // 测试type为all时正常返回执行记录
r($blockTest->printExecutionListBlockTest($block4)) && p('success') && e('1'); // 测试type为wait时返回成功
r($blockTest->printExecutionListBlockTest($block5)) && p('success') && e('1'); // 测试type为doing时返回成功
r($blockTest->printExecutionListBlockTest($block6)) && p('hasExecutions') && e('false'); // 测试type包含非法字符时返回空结果
r($blockTest->printExecutionListBlockTest($block7)) && p('hasExecutions') && e('true'); // 测试count为0时使用默认值返回执行记录