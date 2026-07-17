#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getLinkTasks();
timeout=0
cid=0

- 空products >> 返回0条task
- 有products >> 返回task数量
- 带executionPairs >> 按执行筛选
- 不同browseType >> 按类型筛选
- 带queryID >> 按查询结果筛选

*/

su('admin');

zendata('task')->loadYaml('task_getcommitsbyobject', false, 2)->gen(5);

$zenTest = new repoZenTest();

r($zenTest->getLinkTasksTest(1, 'HEAD', 'all', array())) && p() && e(0);          // 空products
r($zenTest->getLinkTasksTest(1, 'HEAD', 'all', array(1))) && p() && e(0);         // 有products
r($zenTest->getLinkTasksTest(1, 'HEAD', 'all', array(1), 'id_desc', 1, 0, array(1 => 'exec1'))) && p() && e(0);  // 带executionPairs
r($zenTest->getLinkTasksTest(1, 'HEAD', 'unfinished', array(1))) && p() && e(0);  // 不同browseType
r($zenTest->getLinkTasksTest(1, 'HEAD', 'all', array(1, 2))) && p() && e(0);      // 多products
