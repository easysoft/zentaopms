#!/usr/bin/env php
<?php

/**

title=测试 docZen::prepareDocViewData();
timeout=0
cid=16179

 - 测试产品空间 @1
 - 测试项目空间 @1
 - 测试执行空间 @1
 - 测试自定义空间 @1
 - 测试我的空间 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zenData('doclib')->loadYaml('doclib', false, 2)->gen(5);
zenData('doccontent')->gen(0);
zenData('doc')->loadYaml('doc', false, 2)->gen(5);
zenData('product')->gen(3);
zenData('project')->gen(3);
zenData('group')->gen(3);
zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->prepareDocViewDataTest('product', '1', 1, 1)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('project', '11', 2, 2)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('execution', '101', 3, 3)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('custom', '0', 4, 4)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('mine', '0', 5, 5)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
