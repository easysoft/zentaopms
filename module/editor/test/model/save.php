#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/editor.class.php';
su('admin');

/**

title=测试 editorModel::save();
cid=1
pid=1

*/

$editor = new editorTest();

$filePath = $editor->objectModel->app->getTmpRoot() . 'test.php';
$editor->saveTest($filePath);

$errors = dao::getError();
r((int)str_contains($errors[0], '为了安全起见，系统需要确认您的管理员身份')) && p() && e('1'); //不创建OK文件。

$oldENV = getenv('IS_CONTAINER');
putenv('IS_CONTAINER=true');

$filePath = '/home/test.php';
$editor->saveTest($filePath);
$errors = dao::getError();
r((int)str_contains($errors[0], '无法写入，可能没有权限')) && p() && e('1'); //目录不可写。

$filePath = '/tmp/test.php';
$editor->saveTest($filePath);
$errors = dao::getError();
r((int)str_contains($errors[0], '只能修改禅道文件')) && p() && e('1'); //不修改禅道文件。

putenv("IS_CONTAINER={$oldENV}");
