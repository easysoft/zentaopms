#!/usr/bin/env php
<?php
/**

title=测试buildModel->getBuildBlockData();
cid=1
pid=1


*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';
su('admin');

zdTable('project')->config('project')->gen(20);
zdTable('build')->config('build')->gen(20);
zdTable('userview')->config('userview')->gen(2);

$build = new buildTest();

r(count($build->getBuildBlockDataTest(2)))                       && p('')       && e('2');          // 管理员获取项目ID为2的版本总数
r(count($build->getBuildBlockDataTest(2, 'id_desc', 10, false))) && p('')       && e('0');          // 非管理员获取项目ID为2的版本总数
r(count($build->getBuildBlockDataTest(10, 'id_desc', 10)))       && p('')       && e('1');          // 管理员获取项目ID为10且不包括已删除版本的版本总数
r(count($build->getBuildBlockDataTest(0, 'id_desc', 15)))        && p('')       && e('15');         // 管理员获取前15条记录的总数
r($build->getBuildBlockDataTest(8, 'id_asc'))                    && p('0:name') && e('项目8版本8'); // 管理员获取项目为8的版本列表，按照id升序
