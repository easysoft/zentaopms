#!/usr/bin/env php
<?php

/**

title=测试 jobZen::buildSearchForm();
timeout=0
cid=0

- 执行jobZenTest模块的buildSearchFormTest方法，参数是array  @0
- 执行jobZenTest模块的buildSearchFormTest方法，参数是array  @0
- 执行jobZenTest模块的buildSearchFormTest方法，参数是array  @0
- 执行jobZenTest模块的buildSearchFormTest方法，参数是array  @0
- 执行jobZenTest模块的buildSearchFormTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$repo = zenData('repo');
$repo->id->range('1-5');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->path->range('http://test1.com,http://test2.com,http://test3.com,http://test4.com,http://test5.com');
$repo->SCM->range('Git,Gitlab,Subversion,Git,Gitlab');
$repo->deleted->range('0');
$repo->product->range('1-5');
$repo->gen(5);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('code1-code10');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

su('admin');

$jobZenTest = new jobZenTest();

r($jobZenTest->buildSearchFormTest(array('module' => 'job', 'fields' => array(), 'params' => array('repo' => array())), 1, 'browse')) && p() && e('0');
r($jobZenTest->buildSearchFormTest(array('module' => 'job', 'fields' => array(), 'params' => array('product' => array())), 2, 'search')) && p() && e('0');
r($jobZenTest->buildSearchFormTest(array('module' => 'job', 'fields' => array()), 0, '')) && p() && e('0');
r($jobZenTest->buildSearchFormTest(array('module' => 'job', 'fields' => array(), 'params' => array('repo' => array())), 'test', 'test-url')) && p() && e('0');
r($jobZenTest->buildSearchFormTest(array('module' => 'job', 'fields' => array(), 'params' => array('repo' => array())), -1, 'browse?type=all&param=1')) && p() && e('0');