#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->create();
cid=1
pid=1

项目集添加干系人 >> 1
项目添加干系人 >> dev17
执行添加干系人 >> outside
输入错误的from >> test
输入不存在的用户名 >> autotest
不输入用户 >> 用户不能为空！
输入错误的key值 >> 『key』不符合格式，应当为:『/0|1/』。
创建相同的干系人 >> 『用户』已经有『po22』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/
$objectIDList = array('1', '11', '101');

$froms = array('team', 'company', 'outside', 'test');
$key   = array('0', '1');
$user  = array('po22', 'dev17', 'outside1', 'autotest', 'dev27');

$teamStakeholder    = array('from' => $froms[0], 'key' => $key[0], 'user' => $user[0], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$companyStakeholder = array('from' => $froms[1], 'key' => $key[1], 'user' => $user[1], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$outsideStakeholder = array('from' => $froms[2], 'key' => $key[0], 'user' => $user[2], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$errorFrom          = array('from' => $froms[3], 'key' => $key[0], 'user' => $user[0], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$errorUser          = array('from' => $froms[0], 'key' => $key[0], 'user' => $user[3], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$noUser             = array('from' => $froms[0], 'key' => $key[0], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');
$noKey              = array('from' => $froms[0], 'user' => $user[3], 'nature' => '性格特征', 'analysis' => '影响分析', 'strategy' => '应对策略');

$stakeholder = new stakeholderTest();
r($stakeholder->createTest($objectIDList[0],$teamStakeholder))    && p('objectID') && e('1');                                    //项目集添加干系人
r($stakeholder->createTest($objectIDList[1],$companyStakeholder)) && p('user')     && e('dev17');                                //项目添加干系人
r($stakeholder->createTest($objectIDList[2],$outsideStakeholder)) && p('type')     && e('outside');                              //执行添加干系人
r($stakeholder->createTest($objectIDList[2],$errorFrom))          && p('from')     && e('test');                                 //输入错误的from
r($stakeholder->createTest($objectIDList[0],$errorUser))          && p('user')     && e('autotest');                             //输入不存在的用户名
r($stakeholder->createTest($objectIDList[0],$noUser)[0])          && p()           && e('用户不能为空！');                       //不输入用户
r($stakeholder->createTest($objectIDList[2],$noKey))              && p('key:0')    && e('『key』不符合格式，应当为:『/0|1/』。');//输入错误的key值
r($stakeholder->createTest($objectIDList[0],$teamStakeholder))    && p('user:0')   && e('『用户』已经有『po22』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');//创建相同的干系人

