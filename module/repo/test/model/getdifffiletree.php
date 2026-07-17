#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getDiffFileTree();
timeout=0
cid=0

- 空数组 @0
- 单个diff文件 @1
- 多个diff文件 @1
- 嵌套路径diff文件 @1
- 扁平文件列表 @1

*/

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getDiffFileTreeTest(array())) && p() && e('0');    // 空数组

$singleDiff = array((object)array('fileName' => 'test.php'));
r($repoTest->getDiffFileTreeTest($singleDiff)) && p() && e('1'); // 单个diff文件

$multiDiffs = array(
    (object)array('fileName' => 'src/main.php'),
    (object)array('fileName' => 'src/utils.php'),
    (object)array('fileName' => 'README.md'),
);
r($repoTest->getDiffFileTreeTest($multiDiffs)) && p() && e('1'); // 多个diff文件

$nestedDiffs = array(
    (object)array('fileName' => 'a/b/c.php'),
    (object)array('fileName' => 'a/b/d.php'),
);
r($repoTest->getDiffFileTreeTest($nestedDiffs)) && p() && e('1'); // 嵌套路径diff文件

$flatDiffs = array(
    (object)array('fileName' => 'root.php'),
);
r($repoTest->getDiffFileTreeTest($flatDiffs)) && p() && e('1'); // 扁平文件列表