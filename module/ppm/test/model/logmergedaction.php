#!/usr/bin/env php
<?php

/**

title=测试 mrModel::logMergedAction();
timeout=0
cid=17258

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
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
zenData('action')->gen(0);
zenData('pipeline')->gen(5);
zenData('product')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('relation')->loadYaml('relation')->gen(5);
zenData('mr')->loadYaml('mr')->gen(5);

$mrModel = new mrModelTest();

r($mrModel->logMergedActionTester(2)) && p('id,objectType,objectID') && e('1,mr,2');   // 没有关联的对象
r($mrModel->logMergedActionTester(1)) && p('id,objectType,objectID') && e('7,task,2'); // 有关联的对象