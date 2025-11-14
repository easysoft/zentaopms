#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getByID();
timeout=0
cid=15570

- 测试获取ID为0的图表 @0
- 测试获取一个不存在的ID 的图表 @0
- 测试获取宏观数据-一级项目集个数图表
 - 属性id @33
 - 属性name @图表33
 - 属性stage @published

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('chart')->loadYaml('chart')->gen(50);
zenData('module')->loadYaml('module')->gen(27)->fixPath();
zenData('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');
r($chart->getByID(0))     && p()                && e('0');                   //测试获取ID为0的图表
r($chart->getByID(10086)) && p()                && e('0');                   //测试获取一个不存在的ID 的图表
r($chart->getByID(33))    && p('id,name,stage') && e('33,图表33,published'); //测试获取宏观数据-一级项目集个数图表
