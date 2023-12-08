#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getFirstGroup();
timeout=0
cid=1

- 测试获取宏观管理维度下的第一个分组ID @32
- 测试获取一个不存在的维度的分组ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(27);
zdTable('user')->gen(5);
su('admin');

global $tester;

r($tester->loadModel('chart')->getFirstGroup(1))   && p() && e(32); //测试获取宏观管理维度下的第一个分组ID
r($tester->loadModel('chart')->getFirstGroup(111)) && p() && e(0);  //测试获取一个不存在的维度的分组ID
