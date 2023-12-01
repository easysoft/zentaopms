#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/editor.class.php';
su('admin');

/**

title=测试 editorModel::printTree();
cid=1
pid=1

*/

$editor = new editorTest();
$tree   = $editor->printTreeTest();
r($tree[0]->title) && p() && e('todo');                      //检查文件树的title字段。
r($tree[0]->items[0]->title) && p() && e('model.php');       //检查文件树items字段第一项的title字段
r($tree[0]->items[0]->items[0]->name) && p() && e('create'); //检查文件数items字段第一项的items字段的name字段
