#!/usr/bin/env php
<?php

/**

title=测试 docZen::initDocContext();
timeout=0
cid=16179

 - 测试产品空间文档 @1
 - 测试项目空间文档 @1
 - 测试传入libID @1
 - 测试传入spaceType和space @1
 - 测试quick类型 @1
 - 测试执行文档传入execution空间映射为项目空间 @project,11
 - 测试执行文档未传空间时推断为项目空间 @project,11
 - 测试传入产品空间保持不变 @product,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zenData('doclib')->loadYaml('doclib', false, 2)->gen(25);
zenData('doccontent')->gen(0);
zenData('doc')->loadYaml('doc', false, 2)->gen(25);

su('admin');

$docTest = new docZenTest();

r($docTest->initDocContextTest(1, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(2, 1, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 2, 'product', '1')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(5, 0, 'quick', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(23, 0, 'execution', '101')) && p('spaceType,space') && e('project,11');
r($docTest->initDocContextTest(23, 0, '', '')) && p('spaceType,space') && e('project,11');
r($docTest->initDocContextTest(3, 2, 'product', '1')) && p('spaceType,space') && e('product,1');
