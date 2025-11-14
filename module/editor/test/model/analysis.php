#!/usr/bin/env php
<?php

/**

title=测试 editorModel::analysis();
timeout=0
cid=16229

- 步骤1：分析control.php文件属性hasCreateMethod @1
- 步骤2：分析model.php文件属性hasCreateMethod @1
- 步骤3：分析不存在的文件 @1
- 步骤4：分析空文件路径 @1
- 步骤5：验证返回数组结构属性hasCorrectStructure @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editorTest = new editorTest();

r($editorTest->analysisControlTest()) && p('hasCreateMethod') && e(1);        // 步骤1：分析control.php文件
r($editorTest->analysisModelTest()) && p('hasCreateMethod') && e(1);          // 步骤2：分析model.php文件
r($editorTest->analysisNonExistentFileTest()) && p() && e(1);                 // 步骤3：分析不存在的文件
r($editorTest->analysisEmptyPathTest()) && p() && e(1);                       // 步骤4：分析空文件路径
r($editorTest->analysisStructureTest()) && p('hasCorrectStructure') && e(1);  // 步骤5：验证返回数组结构