#!/usr/bin/env php
<?php

/**

title=测试 commonModel::formConfig();
timeout=0
cid=15671

- 步骤1:正常模块和方法 @0
- 步骤2:带objectID参数 @0
- 步骤3:空模块名 @0
- 步骤4:空方法名 @0
- 步骤5:较大的objectID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->formConfigTest('user', 'create')) && p() && e('0');      // 步骤1:正常模块和方法
r($commonTest->formConfigTest('task', 'edit', 1)) && p() && e('0');     // 步骤2:带objectID参数
r($commonTest->formConfigTest('', 'create')) && p() && e('0');          // 步骤3:空模块名
r($commonTest->formConfigTest('user', '')) && p() && e('0');            // 步骤4:空方法名
r($commonTest->formConfigTest('product', 'view', 100)) && p() && e('0'); // 步骤5:较大的objectID