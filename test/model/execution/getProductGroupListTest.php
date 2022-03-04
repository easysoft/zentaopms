#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getProductGroupListTest();
cid=1
pid=1

查询当前用户访问的产品相关项目集 >> 项目集10
查询当前用户访问的产品相关数量 >> 101

*/

$count = array('0','1');

$execution = new executionTest();
r($execution->getProductGroupListTest($count[0])) && p('9:name')  && e('项目集10'); // 查询当前用户访问的产品相关项目集
r($execution->getProductGroupListTest($count[1])) && p()          && e('101');      // 查询当前用户访问的产品相关数量