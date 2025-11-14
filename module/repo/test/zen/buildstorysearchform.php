#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildStorySearchForm();
timeout=0
cid=18128

- 执行repoZenTest模块的buildStorySearchFormTest方法，参数是1, 'abc123', 'all', 1, array 属性result @success
- 执行repoZenTest模块的buildStorySearchFormTest方法，参数是2, 'def456', 'bySearch', 2, array 属性result @success
- 执行repoZenTest模块的buildStorySearchFormTest方法，参数是3, 'ghi789', 'all', 0, array 属性result @success
- 执行repoZenTest模块的buildStorySearchFormTest方法，参数是4, '', 'all', 5, array 属性result @success
- 执行repoZenTest模块的buildStorySearchFormTest方法，参数是5, 'test123', 'bySearch', 10, array 属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('product')->loadYaml('product', false, 1)->gen(5);
zendata('productplan')->loadYaml('productplan', false, 1)->gen(10);
zendata('branch')->loadYaml('branch', false, 1)->gen(8);

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->buildStorySearchFormTest(1, 'abc123', 'all', 1, array(1 => (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal')), array(1 => '模块1'))) && p('result') && e('success');
r($repoZenTest->buildStorySearchFormTest(2, 'def456', 'bySearch', 2, array(2 => (object)array('id' => 2, 'name' => '产品2', 'type' => 'branch')), array(2 => '模块2'))) && p('result') && e('success');
r($repoZenTest->buildStorySearchFormTest(3, 'ghi789', 'all', 0, array(3 => (object)array('id' => 3, 'name' => '产品3', 'type' => 'normal')), array())) && p('result') && e('success');
r($repoZenTest->buildStorySearchFormTest(4, '', 'all', 5, array(), array())) && p('result') && e('success');
r($repoZenTest->buildStorySearchFormTest(5, 'test123', 'bySearch', 10, array(5 => (object)array('id' => 5, 'name' => '产品5', 'type' => 'platform')), array(5 => '模块5'))) && p('result') && e('success');