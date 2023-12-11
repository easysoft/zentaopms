#!/usr/bin/env php
<?php

/**

title=测试 docModel->getExecutionModulePairs();
cid=1

- 测试系统里有执行文档库数据和模块数据的情况属性1 @这是一个模块1
- 测试系统里没有执行库文档数据的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(50);
zdTable('user')->gen(5);

$moduleTable = zdTable('module');
$moduleTable->root->range('20-25');
$moduleTable->type->range('doc,task,bug,story');
$moduleTable->gen(30);

$docTester = new docTest();
r($docTester->getExecutionModulePairsTest('normal')) && p('1') && e('这是一个模块1'); // 测试系统里有执行文档库数据和模块数据的情况
r($docTester->getExecutionModulePairsTest('noData')) && p()    && e('0');             // 测试系统里没有执行库文档数据的情况
