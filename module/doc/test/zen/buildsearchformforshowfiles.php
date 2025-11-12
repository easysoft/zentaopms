#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildSearchFormForShowFiles();
timeout=0
cid=0

- 步骤1:测试product类型的搜索表单配置
 - 属性module @productDocFile
 - 属性queryID @0
- 步骤2:测试project类型的搜索表单配置
 - 属性module @projectDocFile
 - 属性queryID @0
- 步骤3:测试execution类型的搜索表单配置
 - 属性module @executionDocFile
 - 属性queryID @0
- 步骤4:测试onMenuBar配置属性onMenuBar @no
- 步骤5:测试带有param参数的搜索表单配置属性queryID @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->buildSearchFormForShowFilesTest('product', 1, '', 0)) && p('module,queryID') && e('productDocFile,0'); // 步骤1:测试product类型的搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('project', 2, '', 0)) && p('module,queryID') && e('projectDocFile,0'); // 步骤2:测试project类型的搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('execution', 3, '', 0)) && p('module,queryID') && e('executionDocFile,0'); // 步骤3:测试execution类型的搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('product', 10, 'table', 0)) && p('onMenuBar') && e('no'); // 步骤4:测试onMenuBar配置
r($docTest->buildSearchFormForShowFilesTest('project', 5, '', 100)) && p('queryID') && e('100'); // 步骤5:测试带有param参数的搜索表单配置