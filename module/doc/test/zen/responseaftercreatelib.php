#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterCreateLib();
timeout=0
cid=0

- 步骤1:测试产品文档库创建返回
 - 属性result @success
 - 属性id @1
- 步骤2:测试项目文档库创建返回
 - 属性result @success
 - 属性id @2
- 步骤3:测试执行文档库创建返回
 - 属性result @success
 - 属性id @3
- 步骤4:测试自定义文档库创建返回
 - 属性result @success
 - 属性id @4
- 步骤5:测试我的空间文档库创建返回
 - 属性result @success
 - 属性id @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->responseAfterCreateLibTest('product', 1, 1, '产品文档库', 'id_asc')) && p('result,id') && e('success,1'); // 步骤1:测试产品文档库创建返回
r($docTest->responseAfterCreateLibTest('project', 11, 2, '项目文档库', 'id_desc')) && p('result,id') && e('success,2'); // 步骤2:测试项目文档库创建返回
r($docTest->responseAfterCreateLibTest('execution', 101, 3, '执行文档库', 'order_asc')) && p('result,id') && e('success,3'); // 步骤3:测试执行文档库创建返回
r($docTest->responseAfterCreateLibTest('custom', 0, 4, '自定义文档库', 'id_asc')) && p('result,id') && e('success,4'); // 步骤4:测试自定义文档库创建返回
r($docTest->responseAfterCreateLibTest('mine', 0, 5, '我的文档库', 'id_asc')) && p('result,id') && e('success,5'); // 步骤5:测试我的空间文档库创建返回