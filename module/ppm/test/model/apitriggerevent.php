#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::apiTriggerEvent();
timeout=0
cid=0

- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'create' 属性apiMessage @资源未找到。
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'close' 属性apiMessage @资源未找到。
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'reopen' 属性apiMessage @资源未找到。
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'merge' 属性apiMessage @事件触发类型无效，必须为 create、close、reopen 之一。
- 执行ppmModel模块的apiTriggerEventTest方法，参数是4299, 8199, 'custom' 属性apiMessage @事件触发类型无效，必须为 create、close、reopen 之一。

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

r($ppmModel->apiTriggerEventTest(4299, 8199, 'create')) && p('apiMessage') && e('资源未找到。');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'close')) && p('apiMessage') && e('资源未找到。');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'reopen')) && p('apiMessage') && e('资源未找到。');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'merge')) && p('apiMessage') && e('事件触发类型无效，必须为 create、close、reopen 之一。');
r($ppmModel->apiTriggerEventTest(4299, 8199, 'custom')) && p('apiMessage') && e('事件触发类型无效，必须为 create、close、reopen 之一。');
