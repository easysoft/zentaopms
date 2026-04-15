#!/usr/bin/env php
<?php

/**

title=测试 docZen::prepareDocFormData();
timeout=0
cid=16179

 - 测试产品空间 @default
 - 测试项目空间 @default
 - 测试执行空间 @default
 - 测试自定义空间 @default
 - 测试空参数 @default

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->prepareDocFormDataTest('product', '1')) && p('acl') && e('default');
r($docTest->prepareDocFormDataTest('project', '11')) && p('acl') && e('default');
r($docTest->prepareDocFormDataTest('execution', '101')) && p('acl') && e('default');
r($docTest->prepareDocFormDataTest('custom', '0')) && p('acl') && e('default');
r($docTest->prepareDocFormDataTest('', '')) && p('acl') && e('default');
