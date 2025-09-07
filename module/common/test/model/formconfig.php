#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
su('admin');

/**

title=测试 commonModel::formConfig();
timeout=0
cid=0

- 执行commonTest模块的formConfigTest方法，参数是'user', 'create'  @0
- 执行commonTest模块的formConfigTest方法，参数是'user', 'create'  @array
- 执行commonTest模块的formConfigTest方法，参数是'task', 'edit'  @array
- 执行commonTest模块的formConfigTest方法，参数是'product', 'view', 1  @array
- 执行commonTest模块的formConfigTest方法，参数是'', ''  @0

*/

$commonTest = new commonTest();

global $config;
$originalEdition = $config->edition;

$config->edition = 'open';
r($commonTest->formConfigTest('user', 'create')) && p() && e('0');

$config->edition = 'biz';
r($commonTest->formConfigTest('user', 'create')) && p() && e('array');

$config->edition = 'max';
r($commonTest->formConfigTest('task', 'edit')) && p() && e('array');

$config->edition = 'ipd';
r($commonTest->formConfigTest('product', 'view', 1)) && p() && e('array');

$config->edition = 'open';
r($commonTest->formConfigTest('', '')) && p() && e('0');

$config->edition = $originalEdition;