#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkExecutions();
timeout=0
cid=0

- 执行repoZenTest模块的getLinkExecutionsTest方法，参数是array  @0
- 执行repoZenTest模块的getLinkExecutionsTest方法，参数是array  @0
- 执行repoZenTest模块的getLinkExecutionsTest方法，参数是array  @0
- 执行repoZenTest模块的getLinkExecutionsTest方法，参数是array  @0
- 执行repoZenTest模块的getLinkExecutionsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('product')->loadYaml('product_getlinkexecutions', false, 2)->gen(10);
zenData('project')->loadYaml('project_getlinkexecutions', false, 2)->gen(20);
zenData('projectproduct')->loadYaml('projectproduct_getlinkexecutions', false, 2)->gen(30);

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->getLinkExecutionsTest(array())) && p() && e('0');
r($repoZenTest->getLinkExecutionsTest(array((object)array('id' => 1, 'name' => 'Product1', 'type' => 'normal')))) && p() && e('0');
r($repoZenTest->getLinkExecutionsTest(array((object)array('id' => 1, 'name' => 'Product1'), (object)array('id' => 2, 'name' => 'Product2')))) && p() && e('0');
r($repoZenTest->getLinkExecutionsTest(array((object)array('id' => 1, 'name' => 'Product1'), 'invalid', (object)array('id' => 2, 'name' => 'Product2')))) && p() && e('0');
r($repoZenTest->getLinkExecutionsTest(array((object)array('id' => 999, 'name' => 'NonExistProduct')))) && p() && e('0');