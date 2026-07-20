#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::apiTriggerEvent();
timeout=0
cid=0

- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'create'  @0
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'close'  @0
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'reopen'  @0
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'merge'  @0
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'custom'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('4299');
$repo->product->range('1');
$repo->name->range('ppm-repo-4299');
$repo->gen(1);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->apiTriggerEventTest(4299, 8199, 'create')) && p() && e('0');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'close')) && p() && e('0');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'reopen')) && p() && e('0');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'merge')) && p() && e('0');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'custom')) && p() && e('0');