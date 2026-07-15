#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apicreatebranchtype();
timeout=0
cid=0

- 步骤 1：apicreatebranchtype 不产生 dao 错误 @0
- 步骤 2：apicreatebranchtype 调用返回非 null @1
- 步骤 3：apicreatebranchtype 再次调用返回非 null @1
- 步骤 4：apicreatebranchtype 返回值类型正确 @1
- 步骤 5：apicreatebranchtype 重复调用不报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000')))) && p() && e('1');
r(!is_null($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000')))) && p() && e('1');
r(is_bool($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000'))) || is_array($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000'))) || is_object($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000')))) && p() && e('1');
r(!is_null($model->apicreatebranchtype(1, array('name'=>'feature/*','color'=>'#FF0000')))) && p() && e('1');
