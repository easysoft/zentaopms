#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getSearchBugs();
cid=1
pid=1

查询产品1bug        >> 测试单转Bug1
查询项目13bug优先级 >> 3

*/

$productIDList   = array(1 => '正常产品1');
$executionIDList = array('103');
$sql             = '1=1';

$execution = new executionTest();
r($execution->getSearchBugsTest($productIDList, 0, $sql))            && p('301:title') && e('测试单转Bug1'); // 查询产品1bug
r($execution->getSearchBugsTest(array(), $executionIDList[0], $sql)) && p('303:pri')   && e(3);              // 查询项目13bug优先级
