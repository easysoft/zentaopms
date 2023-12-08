#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getByID();
timeout=0
cid=1

- 测试获取ID为0的图表 @0
- 测试获取一个不存在的ID 的图表 @0
- 测试获取宏观数据-一级项目集个数图表
 - 属性id @1018
 - 属性name @宏观数据-一级项目集个数
 - 属性stage @published

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(27);
zdTable('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');
r($chart->getByID(0))     && p()                && e('0');                                      //测试获取ID为0的图表
r($chart->getByID(10086)) && p()                && e('0');                                      //测试获取一个不存在的ID 的图表
r($chart->getByID(1018))  && p('id,name,stage') && e('1018,宏观数据-一级项目集个数,published'); //测试获取宏观数据-一级项目集个数图表
