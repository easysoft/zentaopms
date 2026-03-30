#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualData();
timeout=0
cid=18192

- 测试公司年度数据时用户数属性users @10
- 测试公司年度数据时包含状态统计属性hasStatusStat @yes
- 测试部门无成员时动作数属性actions @0
- 测试部门无成员时待办统计属性todos @0,0,0
- 测试指定用户时登录次数属性logins @0
- 测试指定用户时不返回用户数属性users @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(20);
zenData('dept')->gen(10);
zenData('action')->loadYaml('action_assignannualbasedata')->gen(100);
zenData('todo')->gen(20);
zenData('effort')->gen(20);
zenData('story')->gen(20);
zenData('task')->gen(20);
zenData('bug')->gen(20);
zenData('case')->gen(20);
zenData('product')->gen(10);
zenData('project')->gen(10);

su('admin');

$year       = date('Y');
$reportTest = new reportZenTest();
$accounts   = array('admin');

r($reportTest->assignAnnualDataTest($year, '0', '', $accounts, 10)) && p('users')         && e('10');    // 测试公司年度数据时用户数
r($reportTest->assignAnnualDataTest($year, '0', '', $accounts, 10)) && p('hasStatusStat') && e('yes');   // 测试公司年度数据时包含状态统计
r($reportTest->assignAnnualDataTest($year, '1', '', array(), 10))   && p('actions')       && e('0');     // 测试部门无成员时动作数
r($reportTest->assignAnnualDataTest($year, '1', '', array(), 10))   && p('todos', '|')    && e('0,0,0'); // 测试部门无成员时待办统计
r($reportTest->assignAnnualDataTest($year, '1', 'admin', $accounts, 10)) && p('logins')   && e('0');     // 测试指定用户时登录次数
r($reportTest->assignAnnualDataTest($year, '1', 'admin', $accounts, 10)) && p('users')    && e('~~');    // 测试指定用户时不返回用户数