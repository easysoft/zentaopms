#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getFirstGroup();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(12);
zdTable('user')->gen(5);
su('admin');

global $tester;

r($tester->loadModel('chart')->getFirstGroup(1)) && p() && e(1); //测试获取宏观管理维度下的第一个分组ID
