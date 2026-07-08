#!/usr/bin/env php
<?php

/**

title=测试 editorModel::save();
timeout=0
cid=16243

- 执行$result === true && file_exists($testPath) @1
- 执行$result === true ? 1 : 0 @1
- 执行$result) && str_contains($result, '只能修改禅道文件' @1
- 执行$result) && !empty($result) && $result !== true @1
- 执行$savedContent, 'eval' @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$editor = new editorModelTest();
$extensionRoot = $editor->instance->app->getExtensionRoot();
$testPath = $extensionRoot . 'custom' . DS . 'test' . DS . 'ext' . DS . 'model' . DS . 'test.php';
$_POST['fileContent'] = "<?php\n// test content";
$result = $editor->instance->save($testPath);
r((int)($result === true && file_exists($testPath))) && p() && e('1');

if(!is_dir(dirname($testPath))) mkdir(dirname($testPath), 0777, true);
$_POST['fileContent'] = "<?php\nclass testModel extends model\n{\n    public function test() { return true; }\n}";
$result = $editor->instance->save($testPath);
r($result === true ? 1 : 0) && p() && e('1');

$invalidPath = '/tmp/outside_zentao.php';
$_POST['fileContent'] = "<?php\n// test content";
$result = $editor->instance->save($invalidPath);
r((int)(is_string($result) && str_contains($result, '只能修改禅道文件'))) && p() && e('1');

$badPath = '/home/nonexistent/test.php';
$_POST['fileContent'] = "<?php\n// test content";
$result = $editor->instance->save($badPath);
r((int)(is_string($result) && !empty($result) && $result !== true)) && p() && e('1');

$filterPath = $extensionRoot . 'custom' . DS . 'test' . DS . 'ext' . DS . 'model' . DS . 'filter.php';
if(!is_dir(dirname($filterPath))) mkdir(dirname($filterPath), 0777, true);
$_POST['fileContent'] = "<?php\n// Test e v a l filtering\nfunction test() { e v a l('test'); }";
$editor->instance->save($filterPath);
$savedContent = file_exists($filterPath) ? file_get_contents($filterPath) : '';
r((int)str_contains($savedContent, 'eval')) && p() && e('1');
