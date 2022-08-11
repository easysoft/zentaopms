#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->saveDict();
cid=1
pid=1

创建我是标题的搜索索引 >> 4
创建特殊字符的搜索索引 >> 0

*/

$search = new searchTest();

r($search->saveDictTest('我是标题'))     && p() && e('4'); //创建我是标题的搜索索引
r($search->saveDictTest('!@#$%^&*()_+')) && p() && e('0'); //创建特殊字符的搜索索引