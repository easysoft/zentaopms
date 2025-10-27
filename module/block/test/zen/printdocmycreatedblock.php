#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocMyCreatedBlock();
timeout=0
cid=0

- 执行blockTest模块的printDocMyCreatedBlockTest方法 属性success @1
- 执行blockTest模块的printDocMyCreatedBlockTest方法 
 - 属性docCount @6
- 执行blockTest模块的printDocMyCreatedBlockTest方法 
 - 属性docCount @0
- 执行blockTest模块的printDocMyCreatedBlockTest方法 属性libList @array
- 执行blockTest模块的printDocMyCreatedBlockTest方法 属性error @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zendata('doc')->loadYaml('doc_printdocmycreatedblock', false, 2)->gen(10);

su('admin');

$blockTest = new blockTest();

r($blockTest->printDocMyCreatedBlockTest()) && p('success') && e('1');
r($blockTest->printDocMyCreatedBlockTest()) && p('docCount') && e('6,<=');
r($blockTest->printDocMyCreatedBlockTest()) && p('docCount') && e('0,>=');
r($blockTest->printDocMyCreatedBlockTest()) && p('libList') && e('array');
r($blockTest->printDocMyCreatedBlockTest()) && p('error') && e('~~');