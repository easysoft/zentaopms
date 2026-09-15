#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processIssueFileTree();
timeout=0
cid=0

- 执行test模块的processIssueFileTreeTest方法，参数是array  @1
- 执行test模块的processIssueFileTreeTest方法，参数是$fileTree, 'url/%s', array  @1
- 执行test模块的processIssueFileTreeTest方法，参数是array  @1
- 执行test模块的processIssueFileTreeTest方法，参数是array  @1
- 执行test模块的processIssueFileTreeTest方法，参数是array  @1
- 执行$branchMatch[1] @feature

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->processIssueFileTreeTest(array(), '', array()))) && p() && e('1');
$fileTree = array((object)array('name' => 'src', 'path' => 'src/main', 'children' => array()));
r(is_array($test->processIssueFileTreeTest($fileTree, 'url/%s', array()))) && p() && e('1');
$file2 = (object)array('name' => 'app', 'path' => 'app/index', 'children' => array());
r(is_array($test->processIssueFileTreeTest(array($file2), 'url', array('repoID' => 1)))) && p() && e('1');
r(is_array($test->processIssueFileTreeTest(array(), 'url', array()))) && p() && e('1');
r(is_array($test->processIssueFileTreeTest(array(), '', array('taskID' => 5)))) && p() && e('1');
$leaf   = (object)array('name' => 'index.php', 'path' => 'index.php', 'ref' => 'ai-test/index.php');
$dir    = (object)array('name' => 'src', 'path' => 'src', 'children' => array($leaf));
$branch = (object)array('name' => 'feature', 'path' => 'feature', 'children' => array($dir));
$root   = (object)array('name' => 'root', 'path' => 'root', 'children' => array($branch));
$tree   = $test->processIssueFileTreeTest(array($root), 'url/%s', array());
$leafLink = $tree[0]->children[0]->children[0]->children[0]->link;
preg_match('/branch64=([^,&]+)/', $leafLink, $branchMatch);
r(helper::safe64Decode($branchMatch[1])) && p() && e('feature');