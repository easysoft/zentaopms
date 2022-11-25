#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLibFiles();
cid=1
pid=1

获取 产品 1 下文件库id倒序排列的文件 >> 这是一个文件名称4.dot,这是一个文件名称3.docx,这是一个文件名称2.doc
获取 产品 2 下文件库id倒序排列的文件 >> 0
获取 项目 11 下文件库id倒序排列的文件 >> 这是一个文件名称91.docx,这是一个文件名称2.doc,这是一个文件名称1.txt
获取 项目 12 下文件库id倒序排列的文件 >> 0
获取 执行 101 下文件库id倒序排列的文件 >> 这是一个文件名称4.dot,这是一个文件名称2.doc,这是一个文件名称1.txt
获取 执行 102 下文件库id倒序排列的文件 >> 0
获取 产品 1 下文件库id正序排列的文件 >> 这是一个文件名称2.doc,这是一个文件名称3.docx,这是一个文件名称4.dot
获取 产品 2 下文件库id正序排列的文件 >> 0
获取 项目 11 下文件库id正序排列的文件 >> 这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称91.docx
获取 项目 12 下文件库id正序排列的文件 >> 0
获取 执行 101 下文件库id正序排列的文件 >> 这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称4.dot
获取 执行 102 下文件库id正序排列的文件 >> 0

*/

$type     = array('product', 'project', 'execution');
$objectID = array(1, 2, 11, 12, 101, 102);
$orderBy  = array('t1.id_desc', 't1.id_asc');

$doc = new docTest();

r($doc->getLibFilesTest($type[0], $objectID[0], $orderBy[0])) && p() && e('这是一个文件名称4.dot,这是一个文件名称3.docx,这是一个文件名称2.doc');  // 获取 产品 1 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[0], $objectID[1], $orderBy[0])) && p() && e('0');                                                                   // 获取 产品 2 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[1], $objectID[2], $orderBy[0])) && p() && e('这是一个文件名称91.docx,这是一个文件名称2.doc,这是一个文件名称1.txt'); // 获取 项目 11 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[1], $objectID[3], $orderBy[0])) && p() && e('0');                                                                   // 获取 项目 12 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[2], $objectID[4], $orderBy[0])) && p() && e('这是一个文件名称4.dot,这是一个文件名称2.doc,这是一个文件名称1.txt');   // 获取 执行 101 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[2], $objectID[5], $orderBy[0])) && p() && e('0');                                                                   // 获取 执行 102 下文件库id倒序排列的文件
r($doc->getLibFilesTest($type[0], $objectID[0], $orderBy[1])) && p() && e('这是一个文件名称2.doc,这是一个文件名称3.docx,这是一个文件名称4.dot');  // 获取 产品 1 下文件库id正序排列的文件
r($doc->getLibFilesTest($type[0], $objectID[1], $orderBy[1])) && p() && e('0');                                                                   // 获取 产品 2 下文件库id正序排列的文件
r($doc->getLibFilesTest($type[1], $objectID[2], $orderBy[1])) && p() && e('这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称91.docx'); // 获取 项目 11 下文件库id正序排列的文件
r($doc->getLibFilesTest($type[1], $objectID[3], $orderBy[1])) && p() && e('0');                                                                   // 获取 项目 12 下文件库id正序排列的文件
r($doc->getLibFilesTest($type[2], $objectID[4], $orderBy[1])) && p() && e('这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称4.dot');   // 获取 执行 101 下文件库id正序排列的文件
r($doc->getLibFilesTest($type[2], $objectID[5], $orderBy[1])) && p() && e('0');                                                                   // 获取 执行 102 下文件库id正序排列的文件