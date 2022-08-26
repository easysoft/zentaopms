#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getUserPairs();
cid=1
pid=1

测试查询权限分组 3 的用户id和name >> 高层管理2
测试查询权限分组 3 的用户id和name >> 用户20
测试查询权限分组 3 的用户id和name >> 开发1
测试查询权限分组 6 的用户id和name >> 产品主管68
测试查询权限分组 6 的用户id和name >> 产品主管69
测试查询权限分组 5 的用户id和name >> 产品主管59

*/
$groupList = array(3,6,5);

$group = new groupTest();

r($group->getUserPairsTest($groupList[0])) && p('top2')  && e('高层管理2');  // 测试查询权限分组 3 的用户id和name
r($group->getUserPairsTest($groupList[0])) && p('top20') && e('用户20');     // 测试查询权限分组 3 的用户id和name
r($group->getUserPairsTest($groupList[0])) && p('dev1')  && e('开发1');      // 测试查询权限分组 3 的用户id和name
r($group->getUserPairsTest($groupList[1])) && p('td68')  && e('产品主管68'); // 测试查询权限分组 6 的用户id和name
r($group->getUserPairsTest($groupList[1])) && p('td69')  && e('产品主管69'); // 测试查询权限分组 6 的用户id和name
r($group->getUserPairsTest($groupList[2])) && p('td59')  && e('产品主管59'); // 测试查询权限分组 5 的用户id和name
r($group->getUserPairsTest($groupList[2])) && p('td80')  && e('');           // 测试查询权限分组 5 的用户id和name