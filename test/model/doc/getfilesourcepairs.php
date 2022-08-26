#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getFileSourcePairs();
cid=1
pid=1

测试获取 产品 1 的文件类型分组 >> testcase:1;story:1;bug:1;
测试获取 产品 2 的文件类型分组 >> 0
测试获取 项目 11 的文件类型分组 >> task:2;bug:1;
测试获取 项目 12 的文件类型分组 >> 0
测试获取 执行 101 的文件类型分组 >> testcase:1;bug:1;task:1;
测试获取 执行 102 的文件类型分组 >> 0

*/

$type     = array('product', 'project', 'execution');
$objectID = array(1, 2, 11, 12, 101, 102);

$doc = new docTest();

r($doc->getFileSourcePairsTest($type[0], $objectID[0])) && p() && e('testcase:1;story:1;bug:1;'); // 测试获取 产品 1 的文件类型分组
r($doc->getFileSourcePairsTest($type[0], $objectID[1])) && p() && e('0');                         // 测试获取 产品 2 的文件类型分组
r($doc->getFileSourcePairsTest($type[1], $objectID[2])) && p() && e('task:2;bug:1;');             // 测试获取 项目 11 的文件类型分组
r($doc->getFileSourcePairsTest($type[1], $objectID[3])) && p() && e('0');                         // 测试获取 项目 12 的文件类型分组
r($doc->getFileSourcePairsTest($type[2], $objectID[4])) && p() && e('testcase:1;bug:1;task:1;');  // 测试获取 执行 101 的文件类型分组
r($doc->getFileSourcePairsTest($type[2], $objectID[5])) && p() && e('0');                         // 测试获取 执行 102 的文件类型分组