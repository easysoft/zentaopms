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

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zenData('doclib')->loadYaml('doclib', false, 2)->gen(5);
zenData('doccontent')->gen(0);
zenData('doc')->loadYaml('doc', false, 2)->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->initDocContextTest(1, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(2, 1, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 2, 'product', '1')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(5, 0, 'quick', '')) && p('hasDoc') && e('1');
