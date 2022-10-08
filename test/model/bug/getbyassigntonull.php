#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByAssigntonull();
cid=1
pid=1

查询产品1 581 58 不存在的产品10001下由我创建的bug >> BUG174,BUG173,BUG172,BUG93,BUG92,BUG91
查询产品1 58下由我创建的bug >> BUG174,BUG173,BUG172
查询不存在的产品10001下由我创建的bug >> 0

*/

$productIDList = array('1,31,58,1000001', '1,58', '1000001');

$bug=new bugTest();

r($bug->getByAssigntonullTest($productIDList[0])) && p('title') && e('BUG174,BUG173,BUG172,BUG93,BUG92,BUG91'); // 查询产品1 581 58 不存在的产品10001下由我创建的bug
r($bug->getByAssigntonullTest($productIDList[1])) && p('title') && e('BUG174,BUG173,BUG172');                   // 查询产品1 58下由我创建的bug
r($bug->getByAssigntonullTest($productIDList[2])) && p('title') && e('0');                                      // 查询不存在的产品10001下由我创建的bug