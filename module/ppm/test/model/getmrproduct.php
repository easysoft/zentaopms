#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getMRProduct();
timeout=0
cid=0

- 执行ppmModel模块的getMRProductTest方法 属性id @1
- 执行ppmModel模块的getMRProductTest方法 属性id @2
- 执行ppmModel模块的getMRProductTest方法  @0
- 执行ppmModel模块的getMRProductTest方法  @0
- 执行ppmModel模块的getMRProductTest方法 属性id @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(2);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6101-6102');
$repo->product->range('1,2');
$repo->name->range('ppm-repo-6101,ppm-repo-6102');
$repo->gen(2);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->getMRProductTest((object)array('repoID' => 6101))) && p('id') && e('1');
r($ppmModel->getMRProductTest((object)array('repoID' => 6102))) && p('id') && e('2');
r($ppmModel->getMRProductTest((object)array('repoID' => 9999))) && p() && e('0');
r($ppmModel->getMRProductTest((object)array('repoID' => 0))) && p() && e('0');
r($ppmModel->getMRProductTest((object)array('repoID' => '6101'))) && p('id') && e('1');