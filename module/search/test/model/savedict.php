#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

/**

title=测试 searchModel->saveDict();
timeout=0
cid=1

- 创建我是标题的搜索索引第0条的value属性 @我
- 创建特殊字符的搜索索引 @0

*/

$search = new searchTest();

r($search->saveDictTest('我是标题'))     && p('0:value') && e('我'); //创建我是标题的搜索索引
r($search->saveDictTest('!@#$%^&*()_+')) && p() && e('0');           //创建特殊字符的搜索索引