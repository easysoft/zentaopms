#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::printTree();
cid=0

- 检查文件树的title字段。 @待办
- 检查文件树items字段第一项的title字段 @model
- 检查文件数items字段第一项的items字段的name字段 @create

*/

$editor = new editorTest();
$tree   = $editor->printTreeTest();
r($tree[0]->text) && p() && e('待办');                       //检查文件树的title字段。
r($tree[0]->items[0]->text) && p() && e('model');            //检查文件树items字段第一项的title字段
r($tree[0]->items[0]->items[0]->name) && p() && e('create'); //检查文件数items字段第一项的items字段的name字段
