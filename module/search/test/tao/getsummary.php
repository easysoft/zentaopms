#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

/**

title=测试 searchModel->getSummary();
cid=1
pid=1

测试搜索的内容中不带关键字的结果展示 >> 【步骤】【结果】【期望】
测试搜索的内容中带关键字的结果展示 >> 【步<span class='text-danger'>骤</span> 】【结果】【期望】

*/

$search = new searchTest();

r($search->getSummaryTest(1, 0))     && p() && e('【步骤】【结果】【期望】');                                   //测试搜索的内容中不带关键字的结果展示
r($search->getSummaryTest(1, 39588)) && p() && e("【步<span class='text-danger'>骤</span> 】【结果】【期望】"); //测试搜索的内容中带关键字的结果展示