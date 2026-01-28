#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getReferSQL();
timeout=0
cid=17398

- 执行pivotTest模块的getReferSQLTest方法，参数是'user'  @SELECT t1.*  FROM zt_user AS t1 
- 执行pivotTest模块的getReferSQLTest方法，参数是'task', 'WHERE t1.status = "active"'  @SELECT t1.*  FROM zt_task AS t1 WHERE t1.status = "active"
- 执行pivotTest模块的getReferSQLTest方法，参数是'project', '', array  @SELECT t1.* ,t2.name,t2.status FROM zt_project AS t1 

- 执行pivotTest模块的getReferSQLTest方法，参数是'bug', 'WHERE t1.deleted = "0"', array  @SELECT t1.* ,t3.title,t3.severity FROM zt_bug AS t1 WHERE t1.deleted = "0"

- 执行pivotTest模块的getReferSQLTest方法，参数是'story', '', array  @SELECT t1.*  FROM zt_story AS t1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

r($pivotTest->getReferSQLTest('user')) && p() && e('SELECT t1.*  FROM zt_user AS t1 ');
r($pivotTest->getReferSQLTest('task', 'WHERE t1.status = "active"')) && p() && e('SELECT t1.*  FROM zt_task AS t1 WHERE t1.status = "active"');
r($pivotTest->getReferSQLTest('project', '', array('t2.name', 't2.status'))) && p() && e('SELECT t1.* ,t2.name,t2.status FROM zt_project AS t1 ');
r($pivotTest->getReferSQLTest('bug', 'WHERE t1.deleted = "0"', array('t3.title', 't3.severity'))) && p() && e('SELECT t1.* ,t3.title,t3.severity FROM zt_bug AS t1 WHERE t1.deleted = "0"');
r($pivotTest->getReferSQLTest('story', '', array())) && p() && e('SELECT t1.*  FROM zt_story AS t1 ');