#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildMembersForManageMembers();
timeout=0
cid=0

- 执行method模块的invoke方法，参数是$executionZen, $execution 
 - 第0条的root属性 @1
 - 第1条的root属性 @1
- 执行method模块的invoke方法，参数是$executionZen, $execution  @alse
- 执行method模块的invoke方法，参数是$executionZen, $execution  @alse
- 执行method模块的invoke方法，参数是$executionZen, $execution  @rray()
- 执行method模块的invoke方法，参数是$executionZen, $execution 
 - 第0条的account属性 @user1
 - 第1条的account属性 @user2
 - 第2条的account属性 @user3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('team')->gen(0);
zenData('user')->gen(10);
zenData('project')->gen(10);

su('admin');

// 创建执行对象
$execution = new stdClass();
$execution->id = 1;
$execution->days = 20;

global $tester;
$executionZen = $tester->loadZen('execution');

// 使用反射调用protected方法
$reflection = new ReflectionClass($executionZen);
$method = $reflection->getMethod('buildMembersForManageMembers');
$method->setAccessible(true);

// 测试步骤1：正常成员数据处理
$_POST = array(
    'account' => array('user1', 'user2'),
    'days' => array(5, 10),
    'hours' => array(8, 6)
);
r($method->invoke($executionZen, $execution)) && p('0:root;1:root') && e('1;1');

// 测试步骤2：成员工作天数大于执行天数  
$_POST = array(
    'account' => array('user1'),
    'days' => array(25),
    'hours' => array(8)
);
r($method->invoke($executionZen, $execution)) && p() && e(false);

// 测试步骤3：成员每日工作小时数超过24小时
$_POST = array(
    'account' => array('user1'),
    'days' => array(5),
    'hours' => array(25)
);
r($method->invoke($executionZen, $execution)) && p() && e(false);

// 测试步骤4：空成员数据处理
$_POST = array();
r($method->invoke($executionZen, $execution)) && p() && e(array());

// 测试步骤5：多个成员混合验证情况
$_POST = array(
    'account' => array('user1', 'user2', 'user3'),
    'days' => array(5, 8, 12),
    'hours' => array(8, 7, 6)
);
r($method->invoke($executionZen, $execution)) && p('0:account;1:account;2:account') && e('user1;user2;user3');