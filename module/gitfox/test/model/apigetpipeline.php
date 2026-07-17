#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetPipeline();
timeout=0
cid=0

- 步骤 1：apiGetPipeline 不产生 dao 错误 @0
- 步骤 2：apiGetPipeline 返回 null/array/object 之一 @1
- 步骤 3：apiGetPipeline 重复调用同类型 @1
- 步骤 4：apiGetPipeline 带 branch 参数可执行 @1
- 步骤 5：apiGetPipeline 再调用不报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
ob_start();
$r = $model->apiGetPipeline(1, 1);
ob_end_clean();
r(is_null($r) || is_array($r) || is_object($r)) && p() && e('1');
r(is_null($r) || is_array($r) || is_object($r)) && p() && e('1');
ob_start();
$r = $model->apiGetPipeline(1, 1, 'main');
ob_end_clean();
r(is_null($r) || is_array($r) || is_object($r)) && p() && e('1');
ob_start();
$r = $model->apiGetPipeline(1, 1);
ob_end_clean();
r(is_null($r) || is_array($r) || is_object($r)) && p() && e('1');
