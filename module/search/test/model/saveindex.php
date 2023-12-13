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
 - 属性objectType @bug
 - 属性objectID @10
- 创建ID为10的任务的索引
 - 属性objectType @task
 - 属性objectID @10

*/

$search = new searchTest();

r($search->saveIndexTest('bug', 10))  && p('objectType,objectID') && e('bug,10');  //创建ID为10的bug的索引
r($search->saveIndexTest('task', 10)) && p('objectType,objectID') && e('task,10'); //创建ID为10的任务的索引