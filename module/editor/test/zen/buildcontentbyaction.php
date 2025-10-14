#!/usr/bin/env php
<?php

/**

title=测试 editorZen::buildContentByAction();
timeout=0
cid=0

- 执行$result1 ===  @1
- 执行$result2) > 0 @1
- 执行$result3) > 0 @1
- 执行$result4) > 0 @1
- 执行$result5) > 0 @1
- 执行$result6, 'getModuleRoot') !== false @1
- 执行editorZenTest模块的buildContentByActionTest方法，参数是'/tmp/nonexistent.php', 'unknown'  @<?php

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editorzen.unittest.class.php';

su('admin');

$editorZenTest = new editorZenTest();

// 测试步骤1：空文件路径情况
$result1 = $editorZenTest->buildContentByActionTest('', 'edit');
r($result1 === '') && p() && e('1');

// 测试步骤2：extendModel操作
$modelPath = dirname(__FILE__, 4) . '/todo/model.php/create';
$result2 = $editorZenTest->buildContentByActionTest($modelPath, 'extendModel');
r(strlen($result2) > 0) && p() && e('1');

// 测试步骤3：newPage操作  
$controlPath = dirname(__FILE__, 4) . '/todo/control.php/create';
$result3 = $editorZenTest->buildContentByActionTest($controlPath, 'newPage');
r(strlen($result3) > 0) && p() && e('1');

// 测试步骤4：extendControl操作并设置扩展标记
$result4 = $editorZenTest->buildContentByActionTest($controlPath, 'extendControl', 'yes');
r(strlen($result4) > 0) && p() && e('1');

// 测试步骤5：创建测试文件用于edit操作
$testFile = '/tmp/test_edit.php';
file_put_contents($testFile, "<?php echo 'test';");
$result5 = $editorZenTest->buildContentByActionTest($testFile, 'edit');
r(strlen($result5) > 0) && p() && e('1');

// 测试步骤6：创建测试文件用于override操作
$testOverrideFile = '/tmp/test_override.php';
file_put_contents($testOverrideFile, "require '../../config.php';");
$result6 = $editorZenTest->buildContentByActionTest($testOverrideFile, 'override');
r(strpos($result6, 'getModuleRoot') !== false) && p() && e('1');

// 测试步骤7：不存在的PHP文件，其他操作
r($editorZenTest->buildContentByActionTest('/tmp/nonexistent.php', 'unknown')) && p() && e("<?php");

// 清理测试文件
if(file_exists($testFile)) unlink($testFile);
if(file_exists($testOverrideFile)) unlink($testOverrideFile);