#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

/**

title=测试 searchModel->saveIndex();
timeout=0
cid=1

- 创建ID为10的bug的索引
 - 属性objecttype @bug
 - 属性objectid @10
- 创建ID为10的任务的索引
 - 属性objecttype @task
 - 属性objectid @10

*/

$search = new searchTest();

r($search->saveIndexTest('bug', 10))  && p('objecttype,objectid') && e('bug,10');  //创建ID为10的bug的索引
r($search->saveIndexTest('task', 10)) && p('objecttype,objectid') && e('task,10'); //创建ID为10的任务的索引