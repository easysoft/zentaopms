#!/usr/bin/env php
<?php

/**

title=测试 mrModel::logMergedAction();
timeout=0
cid=0

- 没有关联的对象
 - 属性id @1
 - 属性objectType @mr
 - 属性objectID @2
- 有关联的对象
 - 属性id @7
 - 属性objectType @task
 - 属性objectID @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

su('admin');
zdTable('action')->gen(0);
zdTable('pipeline')->gen(5);
zdTable('product')->gen(5);
zdTable('repo')->config('repo')->gen(5);
zdTable('relation')->config('relation')->gen(5);
zdTable('mr')->config('mr')->gen(5);

$mrModel = new mrTest();

r($mrModel->logMergedActionTester(2)) && p('id,objectType,objectID') && e('1,mr,2');   // 没有关联的对象
r($mrModel->logMergedActionTester(1)) && p('id,objectType,objectID') && e('7,task,2'); // 有关联的对象