#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getJobSearchQuery();
timeout=0
cid=0

- 执行jobTest模块的getJobSearchQueryTest方法  @ 1 = 1
- 执行jobTest模块的getJobSearchQueryTest方法，参数是1  @t1.`name` like "%test%"
- 执行jobTest模块的getJobSearchQueryTest方法，参数是999  @ 1 = 1
- 执行jobTest模块的getJobSearchQueryTest方法，参数是-1  @ 1 = 1
- 执行jobTest模块的getJobSearchQueryTest方法，参数是2  @t1.`repo` = 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

$table = zenData('userquery');
$table->id->range('1-5');
$table->module->range('job{5}');
$table->title->range('搜索1,搜索2,搜索3,搜索4,搜索5');
$table->sql->range('`name` like "%test%",`repo` = 1,`id` > 0,`status` != "deleted",`product` in (1,2,3)');
$table->form->range('');  // Empty form to avoid unserialize issues
$table->account->range('admin{5}');
$table->shortcut->range('0{5}');
$table->common->range('0{5}');
$table->gen(5);

su('admin');

$jobTest = new jobTest();

r($jobTest->getJobSearchQueryTest(0)) && p() && e(' 1 = 1');
r($jobTest->getJobSearchQueryTest(1)) && p() && e('t1.`name` like "%test%"');
r($jobTest->getJobSearchQueryTest(999)) && p() && e(' 1 = 1');
r($jobTest->getJobSearchQueryTest(-1)) && p() && e(' 1 = 1');
r($jobTest->getJobSearchQueryTest(2)) && p() && e('t1.`repo` = 1');