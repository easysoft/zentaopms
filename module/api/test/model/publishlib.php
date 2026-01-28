#!/usr/bin/env php
<?php

/**

title=测试 apiModel::publishLib();
timeout=0
cid=15120

- 执行apiTest模块的publishLibTest方法，参数是$formData1  @1
- 执行apiTest模块的publishLibTest方法，参数是$formData2 第version条的0属性 @『版本号』不能为空。
- 执行apiTest模块的publishLibTest方法，参数是$formData3 第lib条的0属性 @『lib』应当是数字。
- 执行apiTest模块的publishLibTest方法，参数是$formData4  @1
- 执行apiTest模块的publishLibTest方法，参数是$formData5  @1
- 执行apiTest模块的publishLibTest方法，参数是$formData6  @1
- 执行apiTest模块的publishLibTest方法，参数是$formData7  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doclib')->loadYaml('doclib')->gen(5);
zenData('module')->loadYaml('module')->gen(10);
zenData('api')->gen(15);
zenData('apistruct')->gen(8);
zenData('api_lib_release')->gen(0);

su('admin');

$apiTest = new apiModelTest();

$formData1 = new stdclass();
$formData1->lib     = 1;
$formData1->version = 'v1.0.0';
$formData1->desc    = '正常发布测试';

$formData2 = new stdclass();
$formData2->lib     = 1;
$formData2->version = '';
$formData2->desc    = '版本号为空测试';

$formData3 = new stdclass();
$formData3->lib     = '';
$formData3->version = 'v1.0.1';
$formData3->desc    = '文档库ID为空测试';

$formData4 = new stdclass();
$formData4->lib     = 999;
$formData4->version = 'v1.0.2';
$formData4->desc    = '不存在的文档库ID测试';

$formData5 = new stdclass();
$formData5->lib     = 1;
$formData5->version = 'v1.0.3';
$formData5->desc    = str_repeat('这是一个很长的描述', 20);

$formData6 = new stdclass();
$formData6->lib     = 1;
$formData6->version = 'v2.0.0-special@#$';
$formData6->desc    = '特殊字符版本号测试';

$formData7 = new stdclass();
$formData7->lib     = 1;
$formData7->version = '1.0.0';
$formData7->desc    = '';

r($apiTest->publishLibTest($formData1)) && p() && e(1);
r($apiTest->publishLibTest($formData2)) && p('version:0') && e('『版本号』不能为空。');
r($apiTest->publishLibTest($formData3)) && p('lib:0') && e('『lib』应当是数字。');
r($apiTest->publishLibTest($formData4)) && p() && e(1);
r($apiTest->publishLibTest($formData5)) && p() && e(1);
r($apiTest->publishLibTest($formData6)) && p() && e(1);
r($apiTest->publishLibTest($formData7)) && p() && e(1);