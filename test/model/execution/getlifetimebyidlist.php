#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getLifetimeByIdList();
cid=1
pid=1

查询执行103和161 lifetime >> emptyLifetime
查询空执行 lifetime >> empty

*/

$executionIDList = array('103', '161');

$execution = new executionTest();
r($execution->getLifetimeByIdListTest($executionIDList)) && p('103') && e('emptyLifetime'); // 查询执行103和161 lifetime
r($execution->getLifetimeByIdListTest(array('0')))       && p('')    && e('empty');         // 查询空执行 lifetime
