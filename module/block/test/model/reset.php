#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

/**

title=测试 block 模块的update 方法
timeout=0
cid=39

- 检查初始后返回结果 @1

*/

global $tester;
$tester->loadModel('block');
r($tester->block->reset('my')) && p('') && e('1'); // 检查初始后返回结果
