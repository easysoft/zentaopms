#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('release')->gen(10);

/**

title=测试 systemModel::getPairs();
timeout=0
cid=17997

- 查询默认键值对属性1 @产品正常的正常的发布1
- 查询默认键值对数量 @10
- 查询id为1的发布属性1 @产品正常的正常的发布1
- 查询id为3的发布属性3 @产品正常的正常的发布3
- 根据id列表查询发布 @2

*/
global $tester;
$release = $tester->loadModel('release');

r($release->getPairs())                       && p('1') && e('产品正常的正常的发布1'); // 查询默认键值对
r(count($release->getPairs()))                && p()    && e('10');                    // 查询默认键值对数量
r($release->getPairs(array('1', '2')))        && p('1') && e('产品正常的正常的发布1'); // 查询id为1的发布
r($release->getPairs(array('3', '4')))        && p('3') && e('产品正常的正常的发布3'); // 查询id为3的发布
r(count($release->getPairs(array('1', '2')))) && p()    && e('2');                     // 根据id列表查询发布
