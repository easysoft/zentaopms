#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillSQL();
timeout=0
cid=17382

- 执行pivotModel模块的getDrillSQL方法，参数是'task', 'WHERE status="active"', array  @SELECT t1.* FROM (SELECT t1.*  FROM zt_task AS t1 WHERE status="active") AS t1 WHERE 1=1
- 执行pivotModel模块的getDrillSQL方法，参数是'user', '', array  @SELECT t1.* FROM (SELECT t1.*  FROM zt_user AS t1 ) AS t1 WHERE 1=1
- 执行pivotModel模块的getDrillSQL方法，参数是'', '', array  @SELECT t1.* FROM (SELECT t1.*  FROM zt_ AS t1 ) AS t1 WHERE 1=1
- 执行pivotModel模块的getDrillSQL方法，参数是'bug', 'WHERE deleted="0"', array  @SELECT t1.* FROM (SELECT t1.*  FROM zt_bug AS t1 WHERE deleted="0") AS t1 WHERE 1=1 AND (t1.status="open") AND (t1.pri>1)
- 执行pivotModel模块的getDrillSQL方法，参数是'story', '', array  @SELECT t1.* FROM (SELECT t1.* ,m1.module AS m1module FROM zt_story AS t1 ) AS t1 WHERE 1=1 AND (t1.m1module=1)


*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$pivotModel = $tester->loadModel('pivot');

r($pivotModel->getDrillSQL('task', 'WHERE status="active"', array())) && p() && e('SELECT t1.* FROM (SELECT t1.*  FROM zt_task AS t1 WHERE status="active") AS t1 WHERE 1=1');
r($pivotModel->getDrillSQL('user', '', array())) && p() && e('SELECT t1.* FROM (SELECT t1.*  FROM zt_user AS t1 ) AS t1 WHERE 1=1');
r($pivotModel->getDrillSQL('', '', array())) && p() && e('SELECT t1.* FROM (SELECT t1.*  FROM zt_ AS t1 ) AS t1 WHERE 1=1');
r($pivotModel->getDrillSQL('bug', 'WHERE deleted="0"', array(array('drillField' => 'status', 'drillAlias' => 't1', 'value' => '="open"'), array('drillField' => 'pri', 'drillAlias' => 't1', 'value' => '>1')))) && p() && e('SELECT t1.* FROM (SELECT t1.*  FROM zt_bug AS t1 WHERE deleted="0") AS t1 WHERE 1=1 AND (t1.status="open") AND (t1.pri>1)');
r($pivotModel->getDrillSQL('story', '', array(array('drillField' => 'module', 'drillAlias' => 'm1', 'value' => '=1')))) && p() && e('SELECT t1.* FROM (SELECT t1.* ,m1.module AS m1module FROM zt_story AS t1 ) AS t1 WHERE 1=1 AND (t1.m1module=1)');