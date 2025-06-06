#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::addLink4File();
cid=1
pid=1

- 开源版添加todo模块的链接 @1,1,1,1,1,1,1,1,1,1,1
- 收费版添加todo模块的链接 @1,1,1,1,1,1,1,1,1,1,1

*/

global $config;

$editor = new editorTest();

$config->edition = 'open';
r($editor->addLink4FileTest()) && p() && e('1,1,1,1,1,1,1,1,1,1,1'); //开源版添加todo模块的链接

$config->edition = 'max';
r($editor->addLink4FileTest()) && p() && e('1,1,1,1,1,1,1,1,1,1,1'); //收费版添加todo模块的链接
